<?php

/**
 * tinyurl.com component. No API key required and httpsocket is fast.
 * 
 * @author		  rynop
 * @link          http://rynop.com, http://tinyurl.com
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 *
 */

class TinyurlComponent extends Object {
	function getTinyUrl($url) {
		App::import('Core', 'HttpSocket');
		$conn = new HttpSocket();
		return $conn->get("http://tinyurl.com/api-create.php","url=$url");
	}
}