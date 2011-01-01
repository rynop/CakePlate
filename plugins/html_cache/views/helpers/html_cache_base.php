<?php
/*
 * HtmlCache Plugin
 * Copyright (c) 2009 Matt Curry
 * http://pseudocoder.com
 * http://github.com/mcurry/html_cache
 *
 * @author        mattc <matt@pseudocoder.com>
 * @license       MIT
 *
 */

class HtmlCacheBaseHelper extends Helper {

/**
 * options property
 *
 * @var array
 * @access public
 */
	public $options = array(
		'test_mode' => false,
    	'host' => null,
		'www_root' => null,
		'domain' => false
	);

/**
 * helpers property
 *
 * @var array
 * @access public
 */
	public $helpers = array('Session');

/**
 * path property
 *
 * @var string ''
 * @access public
 */
	public $path = '';

/**
 * isFlash property
 *
 * @var bool false
 * @access public
 */
	public $isFlash = false;

/**
 * beforeRender method
 *
 * @return void
 * @access public
 */
	public function beforeRender() {
		if($this->Session->read('Message')) {
			$this->isFlash = true;
		}
	}

/**
 * afterLayout method
 *
 * @return void
 * @access public
 */
	public function afterLayout() {
		if(!$this->_isCachable()) {
			return;
		}

		$view =& ClassRegistry::getObject('view');

		//handle 404s
		if ($view->name == 'CakeError') {
			$path = $this->params['url']['url'];
		} else {
			$path = $this->here;
		}

		$path = implode(DS, array_filter(explode('/', $path)));
		if($path !== '') {
			$path = DS . ltrim($path, DS);
		}
    
    $host = '';
    if($this->options['domain']) {
      if (!empty($_SERVER['HTTP_HOST'])) {
        $host = DS . $_SERVER['HTTP_HOST'];
      } elseif ($this->options['host']) {
        $host = DS . $this->options['host'];
      }
    }
    
		$path = $this->options['www_root'] . 'cache' . $host . $path . DS . 'index.html';
		$file = new File($path, true);
		$file->write($view->output);
	}

/**
 * isCachable method
 *
 * @return void
 * @access protected
 */
	protected function _isCachable() {
		if (!$this->options['test_mode'] && Configure::read('debug') > 0) {
			return false;
		}

		if($this->isFlash) {
			return false;
		}

		if(!empty($this->data)) {
			return false;
		}

		return true;
	}
}