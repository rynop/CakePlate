<?php
/*
 * Asset Packer CakePHP Plugin
 * Copyright (c) 2009 Matt Curry
 * www.PseudoCoder.com
 * http://github.com/mcurry/asset
 *
 * @author      Matt Curry <matt@pseudocoder.com>
 * @license     MIT
 *
 */

App::import('Core', array('File', 'Folder', 'Sanitize'));

class AssetHelper extends Helper {
	var $options = array(
		//Cake debug = 0  packed js/css returned.  $this->options['md5FileName']options['debug'] doesn't do anything.
		//Cake debug > 0, $this->options['md5FileName']options['debug'] = false    essentially turns the helper off.  js/css not packed.  Good for debugging your js/css files.
		//Cake debug > 0, $this->options['md5FileName']options['debug'] = true     packed js/css returned.  Good for debugging this helper.
		'debug' => false,
		
		//there is a *minimal* perfomance hit associated with looking up the filemtimes
		//if you clean out your cached dir (as set below) on builds then you don't need this.		
		'checkTs' => false,
		
		//the packed files are named by stringing together all the individual file names
		//this can generate really long names, so by setting this option to true
		//the long name is md5'd, producing a resonable length file name.
		'md5FileName' => false,

		//Additional paths for searching for css/js
		'searchPaths' => array(),
		
		//Paths for storing the compressed files, relative to your webroot
		'cachePaths' => array('css' => 'ccss', 'js' => 'cjs'),
		
		//options: default, low_compression, high_compression, highest_compression
		//I like high_compression because it still leaves the file readable.		
		'cssCompression' => 'high_compression',
		
		//replace relative img paths in css files with full http://...
		'fixCssImg' => false
	);
	
  //Class for localizing JS files if JS I18N plugin is installed
  //http://github.com/mcurry/js/tree/master
  var $Lang = false;

  var $paths = array('wwwRoot' => WWW_ROOT,
                     'js' => JS,
                     'css' => CSS);

  var $foundFiles = array();

  var $helpers = array('Html', 'Javascript');
  var $viewScriptCount = 0;
  var $initialized = false;
  var $js = array();
	
  var $css = array();
	var $assets = array();

  var $View = null;

  function __construct($options, $paths=array()) {
		$this->options = array_merge($this->options, $options);
    $this->paths = array_merge($this->paths, $paths);

    $this->View =& ClassRegistry::getObject('view');
  }

  //flag so we know the view is done rendering and it's the layouts turn
  function afterRender() {
    if ($this->View) {
      $this->viewScriptCount = count($this->View->__scripts);
    }
  }

  function scripts_for_layout($types=array('js', 'css', 'codeblock')) {
    if (!is_array($types)) {
      $types = array($types);
    }

    if (!$this->initialized) {
      $this->__init();
    }    
    
    if (Configure::read('debug') && $this->options['debug'] == false) {     
//    	return join("\n\t", $this->View->__scripts);
    	
    	$scripts_for_layout = array();
    	foreach ($this->View->__scripts as $resource) {
    		foreach ($types as $type) {
    			if($type=='css' || $type=='js'){
    				if(stristr($resource,'.'.$type)) $scripts_for_layout[] = $resource;
    			}
    		}    		
    	}
//    	htmlspecialchars(debug($scripts_for_layout,true));
    	return join("\n\t", $scripts_for_layout);
    }

    $scripts_for_layout = array();
		foreach($this->assets as $asset) {
			if(!in_array($asset['type'], $types) ) {
				continue;
			}
			
			switch($asset['type']) {
				case 'js':
					$processed = $this->__process($asset['type'], $asset['assets']);
					$scripts_for_layout[] = $this->Javascript->link('/' . $this->options['cachePaths']['js'] . '/' . $processed);
					break;
				case 'css':
					$processed = $this->__process($asset['type'], $asset['assets']);
					$scripts_for_layout[] = $this->Html->css('/' . $this->options['cachePaths']['css'] . '/' . $processed);
					break;				
				default:
					$scripts_for_layout[] = $asset['assets']['script'];
			}
		}

    return implode("\n\t", $scripts_for_layout) . "\n\t";
  }

  function __init() {
    $this->initialized = true;
		$this->assets = array();

    //nothing to do
    if (!$this->View->__scripts) {
      return;
    }

    //move the layout scripts to the front
    $this->View->__scripts = array_merge(
                               array_slice($this->View->__scripts, $this->viewScriptCount),
                               array_slice($this->View->__scripts, 0, $this->viewScriptCount)
                             );
//		if (Configure::read('debug') && $this->options['debug'] == false) {
//			return;
//		}
		
    if (App::import('Model', 'Js.JsLang')) {
      $this->Lang = ClassRegistry::init('Js.JsLang');
      $this->Lang->init();
    }

    //split the scripts into js and css
		$slot = 0;
		$prev = '';
		$holding = array();
    foreach ($this->View->__scripts as $i => $script) {
      if (preg_match('/(src|href)="\/?(.*\/)?(js|css)\/(.*).(js|css)"/', $script, $match)) {
        $temp = array();
        $temp['script'] = $match[4];
        $temp['plugin'] = trim($match[2], '/');
				
				if($prev != $match[5] && !empty($holding)) {
					$this->assets[$slot] = array('type' => $prev, 'assets' => $holding);
					$holding = array();
					$slot ++;
				}
       
				$holding[] = $temp;
				$prev = $match[5];
      } else {
				if(!empty($holding)) {
					$this->assets[$slot] = array('type' => $prev, 'assets' => $holding);
					$holding = array();
					$slot ++;
				}
				
				$this->assets[$slot] = array('type' => 'codeblock' , 'assets' => array('script' => $script));
				$slot ++;
				$prev = 'codeblock';
			}
    }
		
		if(!empty($holding)) {
			$this->assets[$slot] = array('type' => $prev, 'assets' => $holding);
		}
  }

  function __preprocessCss($asset, $buffer) {
	$originalPath = $asset['script'];

	$rootPath = dirname(CSS_URL.$originalPath);

	$matches = array();
	$results = preg_match_all('#url\(\'?([^\'\)]+)\'?\)#i', $buffer, $matches, PREG_SET_ORDER);

	$replacements = array();

	foreach ($matches as $match) {
		if (isset($replacements[$match[1]])) {
			continue;
		}

		if (substr($match[1], 0, 1) == '/'
			|| strpos($match[1], 'http://') !== false
			|| strpos($match[1], 'https://') !== false) {
			continue;
		}

		$normalized = $this->__normalizeImageUrl($rootPath.'/'.$match[1]);
		$replacements[$match[1]] = Router::url($normalized);
	}

	return str_replace(array_keys($replacements), array_values($replacements), $buffer);
  }

  function __normalizeImageUrl($url) {
	$parts = explode('/', $url);
	$newparts = array();
	$newpath = '';

	while (($part = array_shift($parts)) !== NULL) {
		if ($part === '.' || $part === '') {
			continue;
		}
		if ($part === '..') {
			if (!empty($newparts)) {
				array_pop($newparts);
				continue;
			} else {
				return false;
			}
		}
		$newparts[] = $part;
	}

	$newpath .= implode('/', $newparts);
	return '/'.$newpath;
  }

  function __process($type, $assets) {
    $path = $this->__getPath($type);
    $folder = new Folder($this->paths['wwwRoot'] . $this->options['cachePaths'][$type], true);

    //check if the cached file exists
    $scripts = Set::extract('/script', $assets);
    $fileName = $folder->find($this->__generateFileName($scripts) . '_([0-9]{10}).' . $type);
    if ($fileName) {
      //take the first file...really should only be one.
      $fileName = $fileName[0];
    }

    //make sure all the pieces that went into the packed script
    //are OLDER then the packed version
    if ($this->options['checkTs'] && $fileName) {
      $packed_ts = filemtime($this->paths['wwwRoot'] . $this->options['cachePaths'][$type] . DS . $fileName);

      $latest_ts = 0;
      foreach($assets as $asset) {
        $assetFile = $this->__findFile($asset, $type);
        if (!$assetFile) {
          continue;
        }
        $latest_ts = max($latest_ts, filemtime($assetFile));
      }

      //an original file is newer.  need to rebuild
      if ($latest_ts > $packed_ts) {
        unlink($this->paths['wwwRoot'] . $this->options['cachePaths'][$type] . DS . $fileName);
        $fileName = null;
      }
    }

    //file doesn't exist.  create it.
    if (!$fileName) {
      $ts = time();
      switch ($type) {
        case 'js':
          if (PHP5) {
            App::import('Vendor', 'jsmin/jsmin');
          }
          break;
        case 'css':
          App::import('Vendor', 'csstidy', array('file' => 'class.csstidy.php'));
          $tidy = new csstidy();
          $tidy->load_template($this->options['cssCompression']);
          break;
      }

      //merge the script
      $scriptBuffer = '';
      foreach($assets as $asset) {
        $buffer = $this->__getFileContents($asset, $type);
        $origSize = strlen($buffer);

        switch ($type) {
          case 'js':
            //jsmin only works with PHP5
            if (PHP5) {
              $buffer = trim(JSMin::minify($buffer));
            }
            break;

          case 'css':
						if($this->options['fixCssImg']) {
							$buffer = $this->__preprocessCss($asset, $buffer);
						}
						
            $tidy->parse($buffer);
            $buffer = $tidy->print->plain();
            break;
        }

        $delta = 0;
        if ($origSize > 0) {
          $delta = (strlen($buffer) / $origSize) * 100;
        }
        $scriptBuffer .= sprintf("/* %s.%s (%d%%) */\n", $asset['script'], $type, $delta);
        $scriptBuffer .= $buffer . "\n\n";
      }

      //write the file
      $fileName = $this->__generateFileName($scripts) . '_' . $ts . '.' . $type;
      $file = new File($this->paths['wwwRoot'] . $this->options['cachePaths'][$type] . DS . $fileName);
      $file->write(trim($scriptBuffer));
    }

    if ($type == 'css') {
      //$html->css doesn't check if the file already has
      //the .css extension and adds it automatically, so we need to remove it.
      $fileName = str_replace('.css', '', $fileName);
    }

    return $fileName;
  }

  /**
   * Find the source file contents.  Looks in in webroot, vendors and plugins.
   *
   * @param string $filename
   * @return string the full path to the file
   * @access private
  */
  function __getFileContents(&$asset, $type) {
    $assetFile = $this->__findFile($asset, $type);

    if ($assetFile) {
      if($type == 'js' && $this->Lang && strpos($assetFile, $this->Lang->paths['source']) !== false) {
        return $this->Lang->i18n($asset['script'] . '.js');
      } else {
        return trim(file_get_contents($assetFile));
      }
    }

    return '';
  }

  function __findFile(&$asset, $type) {
    $key = md5(serialize($asset) . $type);
    if (!empty($this->foundFiles[$key])) {
      return $this->foundFiles[$key];
    }

    $paths = array($this->__getPath($type));
		$paths = array_merge($paths, $this->options['searchPaths']);
		
    
    if (!empty($asset['plugin']) > 0) {
      $pluginPaths = App::path('plugins');
      $count = count($pluginPaths);
      for ($i = 0; $i < $count; $i++) {
        $paths[] = $pluginPaths[$i] . $asset['plugin'] . DS . 'webroot' . DS;
      }
    }

    $assetFile = '';
    foreach ($paths as $path) {
      $script = sprintf('%s.%s', $asset['script'], $type);
      if (is_file($path . $script) && file_exists($path . $script)) {
        $assetFile = $path . $script;
        break;
      }

      if (is_file($path . $type . DS . $script) && file_exists($path . $type . DS . $script)) {
        $assetFile = $path . $type . DS . $script;
        break;
      }
    }

    if($type == 'js' && !$assetFile && $this->Lang) {
      $script = $this->Lang->parseFile($this->Lang->normalize($asset['script'] . '.js'));
      if (is_file($this->Lang->paths['source'] . $script) && file_exists($this->Lang->paths['source'] . $script)) {
        $assetFile = $this->Lang->paths['source'] . $script;
      }
    }
      
    $this->foundFiles[$key] = $assetFile;
    return $assetFile;
  }

  /**
   * Generate the cached filename.
   *
   * @param array $names an array of the original file names
   * @return string
   * @access private
  */
  function __generateFileName($names) {
    $fileName = Sanitize::paranoid(str_replace('/', '-', implode('_', $names)), array('_', '-'));

    if ($this->options['md5FileName']) {
      $fileName = md5($fileName);
    }

    return $fileName;
  }

  function __getPath($type) {
    switch ($type) {
      case 'js':
        return $this->paths['js'];
      case 'css':
        return $this->paths['css'];
    }

    return false;
  }
}
?>