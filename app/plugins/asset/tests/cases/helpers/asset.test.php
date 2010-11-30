<?php
App::import('Helper', array('Asset.Asset', 'Javascript', 'Html'));
App::import('Core', array('Folder', 'View'));

class AssetTestCase extends CakeTestCase {
  var $Asset = null;
  var $Folder = null;
  var $View = null;
  
  var $testAppRoot = null;
  var $wwwRoot = null;
  var $jsRoot = null;
  var $jsCache = null;
  var $cssCache = null;
  
  function startCase() {
    $this->testAppRoot = ROOT . DS . 'app' . DS . 'plugins' . DS . 'asset' . DS . 'tests' . DS . 'test_app' . DS;
    $this->wwwRoot =  $this->testAppRoot . 'webroot' . DS;
    $this->jsCache = $this->wwwRoot . 'cjs' . DS;
    $this->cssCache = $this->wwwRoot . 'ccss' . DS;

    $controller = null;
    $this->View = new View($controller);
    
    $this->Asset = new AssetHelper(array(), array('wwwRoot' => $this->wwwRoot, 'js' => $this->wwwRoot . 'js' . DS, 'css' => $this->wwwRoot . 'css' . DS));
    $this->Asset->Javascript = new JavascriptHelper();
    $this->Asset->Html = new HtmlHelper();
    
    $this->Folder = new Folder();

    $this->jsRoot =  $this->wwwRoot . 'js' . DS;
    Configure::write('localePaths', array($this->testAppRoot . 'locale'));
    Configure::write('Js.paths', array('wwwRoot' => $this->wwwRoot,
                                       'js' => $this->jsRoot,
                                       'source' => $this->jsRoot . 'source' . DS));
    
    Configure::write('debug', 0);
  }
  
  function endCase() {
    $this->Folder->delete($this->jsCache);
    $this->Folder->delete($this->cssCache);
    
    Configure::write('debug', 2);
  }
  
  function startTest() {
    $this->Asset->viewScriptCount = 0;
    $this->Asset->initialized = false;
    $this->Asset->js = array();
    $this->Asset->css = array();
  }

  function testInstances() {
    $this->assertTrue(is_a($this->Asset, 'AssetHelper'));
    $this->assertTrue(is_a($this->Asset->Javascript, 'JavascriptHelper'));
    $this->assertTrue(is_a($this->Asset->Html, 'HtmlHelper'));
    $this->assertTrue(is_a($this->View, 'View'));
    $this->assertTrue(is_a(ClassRegistry::getObject('view'), 'View'));
  }
  
  function testVendors() {
    if(PHP5) {
      App::import('Vendor', 'jsmin/jsmin');
      $this->assertTrue(class_exists('JSMin'));
    }
    
    App::import('Vendor', 'csstidy', array('file' => 'class.csstidy.php'));
    $this->assertTrue(class_exists('csstidy'));
  }
  
  function testAfterRender() {
    $this->View->__scripts = array('script1', 'script2', 'script3');
    $this->Asset->afterRender();
    $this->assertEqual(3, $this->Asset->viewScriptCount);
  }
  
  function testGenerateFileName() {
    $files = array('script1', 'script2', 'script3');
    $name = $this->Asset->__generateFileName($files);
    $this->assertEqual('script1_script2_script3', $name);
  }
  
  function testGenerateFileNameMd5() {
    $this->Asset->options['md5FileName'] = true;
    $files = array('script1', 'script2', 'script3');
    $name = $this->Asset->__generateFileName($files);
    $this->assertEqual('4991a54c1356544e1188bf6c8b9e7ae9', $name);
    $this->Asset->options['md5FileName'] = false;
  }
  
  function testFindFileDupeName() {
    $asset = array('plugin' => '', 'script' => 'asset1');
    $path1 = $this->Asset->__findFile($asset, 'js');
    $path2 = $this->Asset->__findFile($asset, 'css');
    
    $this->AssertNotEqual($path1, $path2);
  }
  
  function testGetFileContents() {
    $asset = array('plugin' => '', 'script' => 'script1');
    $contents = $this->Asset->__getFileContents($asset, 'js');
    $expected = <<<END
var str = "I'm a string";
alert(str);
END;
    $this->assertEqual($expected, $contents);
  }
  
  function testGetFileContentsPlugin() {
    $asset = array('plugin' => 'asset', 'script' => 'script3');
    $contents = $this->Asset->__getFileContents($asset, 'js');
    $expected = <<<END
$(function(){
  $("#nav").show();
});
END;
    $this->assertEqual($expected, $contents);
  }
  
  function testGetFileContentsExtraPath() {
    $this->Asset->options['searchPaths'] = array($this->wwwRoot . 'js' . DS);
    $asset = array('plugin' => '', 'script' => 'open_source_with_js_and_css/style');
    $contents = $this->Asset->__getFileContents($asset, 'css');
    $expected = <<<END
#sub {
  float: left;
}
END;
    $this->assertEqual($expected, $contents);
    $this->Asset->options['searchPaths'] = array();
  }
  
  function testProcessJsNew() {
    $this->assertFalse(is_dir($this->jsCache));
    
    $js = array(array('plugin' => '', 'script' => 'script1'),
                array('plugin' => '', 'script' => 'script2'),
                array('plugin' => 'asset', 'script' => 'script3'));
    
    $fileName = $this->Asset->__process('js', $js);
    $expected = <<<END
/* script1.js (91%) */
var str="I'm a string";alert(str);

/* script2.js (73%) */
var sum=0;for(i=0;i<100;i++){sum+=i;}
alert(i);

/* script3.js (89%) */
\$(function(){\$("#nav").show();});
END;
    $contents = file_get_contents($this->jsCache . $fileName);
    $this->assertEqual($expected, $contents);
  }
  
  function testProcessJsExistingNoChanges() {
    $this->Folder->cd($this->jsCache);
    $files = $this->Folder->find('script1_script2_script3_([0-9]{10}).js');
    
    $this->assertTrue(!empty($files[0]));
    $origFileName = $files[0];

    $js = array(array('plugin' => '', 'script' => 'script1'),
                array('plugin' => '', 'script' => 'script2'),
                array('plugin' => 'asset', 'script' => 'script3'));
    
    $this->Asset->checkTs = true;
    $fileName = $this->Asset->__process('js', $js);
    $this->assertEqual($origFileName, $fileName);
  }
  
  function testProcessJsExistingWithChanges() {
    $this->Folder->cd($this->jsCache);
    $files = $this->Folder->find('script1_script2_script3_([0-9]{10}).js');
    
    $this->assertTrue(!empty($files[0]));
    $origFileName = $files[0];

    sleep(1);
    $touched = touch($this->wwwRoot . 'js' . DS . 'script1.js');
    $this->assertTrue($touched);
    
    $js = array(array('plugin' => '', 'script' => 'script1'),
                array('plugin' => '', 'script' => 'script2'),
                array('plugin' => 'asset', 'script' => 'script3'));
    
    $this->Asset->options['checkTs'] = true;
    $fileName = $this->Asset->__process('js', $js);
    $this->assertNotEqual($origFileName, $fileName);
    $this->Asset->options['checkTs'] = false;
  }
  
  function testProcessCssNew() {
    $this->assertFalse(is_dir($this->cssCache));
    
    $css = array(array('plugin' => '', 'script' => 'style1'),
                array('plugin' => '', 'script' => 'style2'),
                array('plugin' => 'asset', 'script' => 'style3'));
    
    $fileName = $this->Asset->__process('css', $css);
    $expected = <<<END
/* style1.css (78%) */
*{margin:0;padding:0;}

/* style2.css (89%) */
body{background:#003d4c;color:#fff;font-family:'lucida grande',verdana,helvetica,arial,sans-serif;font-size:90%;margin:0;}

/* style3.css (72%) */
h1,h2,h3,h4{font-weight:400;}
END;
    $contents = file_get_contents($this->cssCache . $fileName  . '.css');
    $this->assertEqual($expected, $contents);
  }
  
  function testInit() {
    $this->View->__scripts = array ('<link rel="stylesheet" type="text/css" href="/css/style1.css" />',
                      '<link rel="stylesheet" type="text/css" href="/css/style2.css" />',
                      '<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>',
                      '<script type="text/javascript" src="/js/script1.js"></script>',
                      '<script type="text/javascript" src="/js/script2.js"></script>',
                      '<script type="text/javascript" src="/asset/js/script3.js"></script>'
    );
    
    
    $this->Asset->__init();
    $this->assertEqual($this->Asset->assets, array(
																									 array('type' => 'css', 'assets' => array(array('script' => 'style1', 'plugin' => ''),
																																														array('script' => 'style2', 'plugin' => ''))),
																									 array('type' => 'codeblock', 'assets' => array('script' => '<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>')),
																									 array ('type' => 'js', 'assets' => array(array('script' => 'script1', 'plugin' => ''),
																																														array('script' => 'script2', 'plugin' => ''),
																																														array('script' => 'script3', 'plugin' => 'asset'))
																													)
																									 )
											 );
  }
  
  function testScriptsForLayout() {
    $this->View->__scripts = array ('<link rel="stylesheet" type="text/css" href="/css/style1.css" />',
                      '<link rel="stylesheet" type="text/css" href="/css/style2.css" />',
                      '<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>',
                      '<script type="text/javascript" src="/js/script1.js"></script>',
                      '<script type="text/javascript" src="/js/script2.js"></script>',
                      '<script type="text/javascript" src="/asset/js/script3.js"></script>'
    );
    
    $scripts = $this->Asset->scripts_for_layout();
    $expected = '/<link rel="stylesheet" type="text\/css" href="\/ccss\/style1_style2_[0-9]{10}.css" \/>' . "\n\t" .
								'<script type="text\/javascript" src="http:\/\/ajax.googleapis.com\/ajax\/libs\/jquery\/1.3.2\/jquery.min.js"><\/script>' . "\n\t" .
                '<script type="text\/javascript" src="\/cjs\/script1_script2_script3_[0-9]{10}.js"><\/script>/';
    $this->assertPattern($expected, $scripts);
  }

  function testScriptsForLayoutCssInJs() {
    $this->View->__scripts = array ('<link rel="stylesheet" type="text/css" href="/js/open_source_with_js_and_css/lib.css" />',
                      '<script type="text/javascript" src="/js/open_source_with_js_and_css/lib.js"></script>'
    );
    
    $scripts = $this->Asset->scripts_for_layout();
    $expected = '/<link rel="stylesheet" type="text\/css" href="\/ccss\/open_source_with_js_and_css-lib_[0-9]{10}.css" \/>' . "\n\t" .
                '<script type="text\/javascript" src="\/cjs\/open_source_with_js_and_css-lib_[0-9]{10}.js"><\/script>/';
                
    $this->assertPattern($expected, $scripts);
  }
  
  function testScriptsForLayoutJs() {
    $this->View->__scripts = array ('<link rel="stylesheet" type="text/css" href="/css/style1.css" />',
                      '<link rel="stylesheet" type="text/css" href="/css/style2.css" />',
                      '<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>',
                      '<script type="text/javascript" src="/js/script1.js"></script>',
                      '<script type="text/javascript" src="/js/script2.js"></script>',
                      '<script type="text/javascript" src="/js/sublevel/sub.js"></script>',
                      '<script type="text/javascript" src="/asset/js/script3.js"></script>'
    );
    
    $scripts = $this->Asset->scripts_for_layout(array('js'));
    $expected = '/<script type="text\/javascript" src="\/cjs\/script1_script2_sublevel-sub_script3_[0-9]{10}.js"><\/script>/';
                
    $this->assertPattern($expected, $scripts);
  }

  function testScriptsForLayoutJsString() {
    $this->View->__scripts = array ('<link rel="stylesheet" type="text/css" href="/css/style1.css" />',
                      '<link rel="stylesheet" type="text/css" href="/css/style2.css" />',
                      '<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>',
                      '<script type="text/javascript" src="/js/script1.js"></script>',
                      '<script type="text/javascript" src="/js/script2.js"></script>',
                      '<script type="text/javascript" src="/asset/js/script3.js"></script>'
    );
    
    $scripts = $this->Asset->scripts_for_layout('js');
    $expected = '/<script type="text\/javascript" src="\/cjs\/script1_script2_script3_[0-9]{10}.js"><\/script>/';
                
    $this->assertPattern($expected, $scripts);
  }
  
  function testScriptsForLayoutCss() {
    $this->View->__scripts = array ('<link rel="stylesheet" type="text/css" href="/css/style1.css" />',
                      '<link rel="stylesheet" type="text/css" href="/css/style2.css" />',
                      '<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>',
                      '<script type="text/javascript" src="/js/script1.js"></script>',
                      '<script type="text/javascript" src="/js/script2.js"></script>',
                      '<script type="text/javascript" src="/asset/js/script3.js"></script>'
    );
    
    $scripts = $this->Asset->scripts_for_layout(array('css'));
    $expected = '/<link rel="stylesheet" type="text\/css" href="\/ccss\/style1_style2_[0-9]{10}.css" \/>/';
                
    $this->assertPattern($expected, $scripts);
  }

  function testScriptsForLayoutCssString() {
    $this->View->__scripts = array ('<link rel="stylesheet" type="text/css" href="/css/style1.css" />',
                      '<link rel="stylesheet" type="text/css" href="/css/style2.css" />',
                      '<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>',
                      '<script type="text/javascript" src="/js/script1.js"></script>',
                      '<script type="text/javascript" src="/js/script2.js"></script>',
                      '<script type="text/javascript" src="/asset/js/script3.js"></script>'
    );
    
    $scripts = $this->Asset->scripts_for_layout('css');
    $expected = '/<link rel="stylesheet" type="text\/css" href="\/ccss\/style1_style2_[0-9]{10}.css" \/>/';
                
    $this->assertPattern($expected, $scripts);
  }

  function testScriptsForLayoutSplit() {
    $this->View->__scripts = array ('<link rel="stylesheet" type="text/css" href="/css/style1.css" />',
                      '<link rel="stylesheet" type="text/css" href="/css/style2.css" />',
                      '<script type="text/javascript" src="/js/script1.js"></script>',
                      '<script type="text/javascript" src="/js/script2.js"></script>',
                      '<script type="text/javascript" src="/asset/js/script3.js"></script>'
    );
    
    $scripts = $this->Asset->scripts_for_layout('css');
    $expected = '/<link rel="stylesheet" type="text\/css" href="\/ccss\/style1_style2_[0-9]{10}.css" \/>/';
                
    $this->assertPattern($expected, $scripts);
    
    $scripts = $this->Asset->scripts_for_layout('js');
    $expected = '/<script type="text\/javascript" src="\/cjs\/script1_script2_script3_[0-9]{10}.js"><\/script>/';
                
    $this->assertPattern($expected, $scripts);
  }
  
  function testWithCodeBlock() {
    $this->View->__scripts = array ('<script type="text/javascript" src="/js/script1.js"></script>',
                                    '<script type="text/javascript">//<![CDATA[alert("test");//]]></script>',
                                    '<script type="text/javascript" src="/js/script2.js"></script>'
    );
    $scripts = $this->Asset->scripts_for_layout();
    $expected = '/<script type="text\/javascript" src="\/cjs\/script1_[0-9]{10}.js"><\/script>' . "\n\t" .
								'<script type="text\/javascript">\/\/<!\[CDATA\[alert\("test"\);\/\/]]><\/script>' . "\n\t" .
								'<script type="text\/javascript" src="\/cjs\/script2_[0-9]{10}.js">/';
    $this->assertPattern($expected, $scripts);
  }
  
  function testWithScriptsInLayout() {
    $this->View->__scripts = array ('<script type="text/javascript" src="/js/script1.js"></script>',
                                    '<script type="text/javascript" src="/js/layout.js"></script>');
    $this->Asset->viewScriptCount = 1;
    $scripts = $this->Asset->scripts_for_layout();
    $expected = '/<script type="text\/javascript" src="\/cjs\/layout_script1_[0-9]{10}.js"><\/script>/';
    $this->assertPattern($expected, $scripts);
  }

  function testLangFindFile() {
		if ($this->skipIf(!App::import('Model', 'Js.JsLang'), '%s JS localize plugin not installed')) {
			return;
		}
    
    $asset = array('plugin' => '', 'script' => 'en/test');
    $path = $this->Asset->__findFile($asset, 'js');
    $this->assertEqual($this->jsRoot . 'source' . DS . 'test.js', $path);
  }
  
  function testLangGetFileContents() {
		if ($this->skipIf(!App::import('Model', 'Js.JsLang'), '%s JS localize plugin not installed')) {
			return;
		}
    
    $asset = array('plugin' => '', 'script' => 'en/test');
    $content = $this->Asset->__getFileContents($asset, 'js');
    $this->assertEqual('alert("Hello World");', $content);
  }
	
	function testDebugMode() {
		Configure::write('debug', 2);

    $this->View->__scripts = array ('<script type="text/javascript" src="/js/view.js"></script>',
                                    '<script type="text/javascript" src="/js/layout.js"></script>'
    );
		
		$this->Asset->viewScriptCount = 1;
		$scripts = $this->Asset->scripts_for_layout();
		$expected = '<script type="text/javascript" src="/js/layout.js"></script>' . "\n\t" . '<script type="text/javascript" src="/js/view.js"></script>';
		$this->assertEqual($scripts, $expected);
		
		Configure::write('debug', 0);
	}

	function testCssImagePathNormalization() {
		$result = $this->Asset->__normalizeImageUrl('css/blueprint/background.jpg');
		$this->assertEqual($result, '/css/blueprint/background.jpg');

		$result = $this->Asset->__normalizeImageUrl('css/../images/background.png');
		$this->assertEqual($result, '/images/background.png');

		$result = $this->Asset->__normalizeImageUrl('css/drink/../../images/background.png');
		$this->assertEqual($result, '/images/background.png');

		$result = $this->Asset->__normalizeImageUrl('css/./images/background.png');
		$this->assertEqual($result, '/css/images/background.png');
	}

	function testPreprocessCss() {
		$input = array(
			'script' => 'image_paths.css'
		);

		$result = $this->Asset->__preprocessCss($input, file_get_contents($this->Asset->paths['css'].DS.'image_paths.css'));

		$this->assertPattern('#\(\/css\/images\/background\.jpg\)#', $result);
		$this->assertPattern('#\(\/images\/bg1\.jpg\)#', $result);
		$this->assertPattern('#\(\/css\/imgs\/bg2\.png\)#', $result);
		$this->assertPattern('#\(\/css\/images\/arse\.gif\)#', $result);

		$input = array(
			'script' => 'some_subfolder/image_paths.css'
		);

		$result = $this->Asset->__preprocessCss($input, file_get_contents($this->Asset->paths['css'].DS.'image_paths.css'));

		$this->assertPattern('#\(\/css\/some_subfolder\/images\/background\.jpg\)#', $result);
		$this->assertPattern('#\(\/css\/images\/bg1\.jpg\)#', $result);
		$this->assertPattern('#\(\/css\/some_subfolder\/imgs\/bg2\.png\)#', $result);
		$this->assertPattern('#\(\/css\/images\/arse\.gif\)#', $result);
	}
}
