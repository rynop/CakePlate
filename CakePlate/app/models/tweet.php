<?php
/**
 * Model for use with Tweet datasource (http://book.cakephp.org/view/1077/An-Example)
 * Requires PHP 5.2+
 *
 * Based on http://book.cakephp.org/view/1077/An-Example 
 * with help from http://snipplr.com/view/36992/improvement-of-url-interpretation-with-regex/
 * 
 * big ups to regexpal.com and tinyurl.com
 * 
 * Exmaples:
 * 
 * $this->Tweet->find('all');
 * 
 * $conditions= array('username' => 'caketest');
 * $otherTweets = $this->find('all', compact('conditions'));
 * 
 * $this->save(array('status' => 'This is an update'));
 * 
 * @author		  rynop
 * @link          http://bakery.cakephp.org/articles/view/twitter-model-plus-plus-for-the-twitter-datasource, http://rynop.com
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 *
 */
class Tweet extends AppModel {
	public $useDbConfig = 'twitter';

	/**
	 * Convert a URL to a tinyurl
	 * @param string $url
	 * @return string URL
	 */
	public function getTinyURL($url){
		App::import('Core', 'HttpSocket');
		$conn = new HttpSocket();
		return $conn->get("http://tinyurl.com/api-create.php","url=$url");
	}
	
	/**
	 * Post something to your twitter account. Member it truncates at 160.
	 * @param string $theTweet
	 * @param boolean $shrinkURLs
	 */
	public function statusUpdate($theTweet,$shrinkURLs=true){
		if(true===$shrinkURLs){			
			$regex = '@((https?://)?([-\w]+\.[-\w\.]+)+\w(:\d+)?(/([-\w/_\.]*(\?\S+)?)?)*)@';
			$theTweet = preg_replace_callback($regex, array(&$this, 'createTinyURLCallback'), $theTweet);
		}
		
		if(Configure::read('debug') == 0) $this->save(array('status' => $theTweet));
		else debug($theTweet);
	}
	
	private function createTinyURLCallback($matches){
		return $this->getTinyURL($matches[0]);	
	}	
}
?>