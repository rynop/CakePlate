<?php

/**
 * Component for using your bit.ly API login to generate shortened links
 *
 * 
 * Exmaples:
 * 
 * $short_url = $this->Bitly->getBitlyUrl("http://bakery.cakephp.org/articles/view/twitter-model-plus-plus-for-the-twitter-datasource");
 *  
 * @author		  Zack Garbow
 * @link          TBD
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 *
 */

/**
 * Component to use your bit.ly API login to generate shortened links.
 * 
 * 1) Configure with your bitly login and bitly api key
 * 2) Simply pass the getBitlyUrl() function a URL to shorten and it will return the shortened URL
 */

class BitlyComponent extends Object {

	/**
	 * Convert a URL to a tinyurl
	 * @param string $url
	 * @return string URL
	 */
	function getBitlyUrl($url) {
		
		$bitlylogin = '<your_api_login>';
		$bitlyapikey= '<your_api_key>';
		$bitlyurl = file_get_contents("http://api.bit.ly/shorten?version=2.0.1&longUrl=".$url."&login=".$bitlylogin."&apiKey=".$bitlyapikey);
		$bitlycontent = json_decode($bitlyurl,true);
		
		$bitlyerror = $bitlycontent["errorCode"];
		$bitlyurl = $bitlyerror == 0 ? $bitlycontent["results"][$url]["shortUrl"] : null;
		
		return $bitlyurl;
	}
}