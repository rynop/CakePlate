<?php
App::import('Vendor', 'UrlCache.url_cache_app_helper');
App::import('Helper', 'Html');

class UrlCacheTestCase extends CakeTestCase {
  var $HtmlHelper = null;

	function startTest() {
		$this->HtmlHelper = new HtmlHelper();
		$this->HtmlHelper->beforeRender();
	}
	
	function endTest() {
		Cache::delete($this->HtmlHelper->_key, '_cake_core_');
	}

  function testInstances() {
    $this->assertTrue(is_a($this->HtmlHelper, 'HtmlHelper'));
  }
	
	function testUrlRelative() {
		$url = $this->HtmlHelper->url(array('controller' => 'posts'));
		$this->assertEqual($url, '/posts');
		$this->assertEqual(array('c0662a9d1c026334679f7a02e6c0f8e012a55d605aac76775a2f56efac64438a' => '/posts'), $this->HtmlHelper->_cache);
		
		$this->HtmlHelper->afterLayout();
		$cache = Cache::read($this->HtmlHelper->_key, '_cake_core_');
		$this->assertEqual(array('c0662a9d1c026334679f7a02e6c0f8e012a55d605aac76775a2f56efac64438a' => '/posts'), $cache);
	}
	
	function testUrlFull() {
		$url = $this->HtmlHelper->url(array('controller' => 'posts'), true);
		$this->assertPattern('/http:\/\/(.*)\/posts/', $url);
		$this->assertEqual(array('35bc4241bbf31c0d0fe6b12bc9c0bce012a55d605aac76775a2f56efac64438a'), array_keys($this->HtmlHelper->_cache));
		$this->assertPattern('/http:\/\/(.*)\/posts/', $this->HtmlHelper->_cache['35bc4241bbf31c0d0fe6b12bc9c0bce012a55d605aac76775a2f56efac64438a']);

		$this->HtmlHelper->afterLayout();
		$cache = Cache::read($this->HtmlHelper->_key, '_cake_core_');
		$this->assertEqual(array('35bc4241bbf31c0d0fe6b12bc9c0bce012a55d605aac76775a2f56efac64438a'), array_keys($cache));
		$this->assertPattern('/http:\/\/(.*)\/posts/', $cache['35bc4241bbf31c0d0fe6b12bc9c0bce012a55d605aac76775a2f56efac64438a']);
	}
	
	function testUrlRelativeAndFull() {
		$this->HtmlHelper->url(array('controller' => 'posts'));
		$this->HtmlHelper->url(array('controller' => 'posts'), true);
		
		$this->assertEqual(array('c0662a9d1c026334679f7a02e6c0f8e012a55d605aac76775a2f56efac64438a', '35bc4241bbf31c0d0fe6b12bc9c0bce012a55d605aac76775a2f56efac64438a'), array_keys($this->HtmlHelper->_cache));

		$this->HtmlHelper->afterLayout();
		$cache = Cache::read($this->HtmlHelper->_key, '_cake_core_');
		$this->assertEqual(array('c0662a9d1c026334679f7a02e6c0f8e012a55d605aac76775a2f56efac64438a', '35bc4241bbf31c0d0fe6b12bc9c0bce012a55d605aac76775a2f56efac64438a'), array_keys($cache));
	}
}