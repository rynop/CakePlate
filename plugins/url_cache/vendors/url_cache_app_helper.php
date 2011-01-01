<?php
/*
 * App Helper url caching
 * Copyright (c) 2009 Matt Curry
 * www.PseudoCoder.com
 * http://github.com/mcurry/cakephp/tree/master/snippets/app_helper_url
 * http://www.pseudocoder.com/archives/2009/02/27/how-to-save-half-a-second-on-every-cakephp-requestand-maintain-reverse-routing
 *
 * @author      Matt Curry <matt@pseudocoder.com>
 * @license     MIT
 *
 */

class UrlCacheAppHelper extends Helper {
  var $_cache = array();
  var $_key = '';
  var $_extras = array();
  var $_paramFields = array('controller', 'plugin', 'action', 'prefix');

  function __construct() {
    parent::__construct();

    if (Configure::read('UrlCache.pageFiles')) {
      $view =& ClassRegistry::getObject('view');
      $path = $view->here;
      if ($this->here == '/') {
        $path = 'home';
      }
      $this->_key = '_' . strtolower(Inflector::slug($path));
    }

    if (is_a($this, 'HtmlHelper')) {
    	$this->_key = 'url_map_html' . $this->_key;
    	$this->_cache = Cache::read($this->_key, '_cake_core_');
    }
    elseif (is_a($this, 'FormHelper')){
    	$this->_key = 'url_map_form' . $this->_key;
    	$this->_cache = Cache::read($this->_key, '_cake_core_');
    }        
  }

  function beforeRender() {
    $this->_extras = array_intersect_key($this->params, array_combine($this->_paramFields, $this->_paramFields));
  }

  function afterLayout() {
    if (!empty($this->_cache) && (is_a($this, 'HtmlHelper') || is_a($this, 'FormHelper'))) {
      Cache::write($this->_key, $this->_cache, '_cake_core_');
    }
  }

  function url($url = null, $full = false) {
    $keyUrl = $url;
    if (is_array($keyUrl)) {
      $keyUrl += $this->_extras;
    }

    $key = md5(serialize($keyUrl) . $full);
    $key .= md5_file(CONFIGS . DS . 'routes.php');

    if (!empty($this->_cache[$key])) {
      return $this->_cache[$key];
    }

    $url = parent::url($url, $full);
    $this->_cache[$key] = $url;

    return $url;
  }
}
?>