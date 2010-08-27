<?php
/**
 * Twitter DataSource
 *
 * Used for reading and writing to Twitter, through models.
 *
 * PHP Version 5.2.x
 *
 * CakePHP(tm) : Rapid Development Framework (http://www.cakephp.org)
 * Copyright 2005-2009, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright     Copyright 2009, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */
App::import('Core', 'HttpSocket');
class TwitterSource extends DataSource {
	protected $_schema = array(
		'tweets' => array(
			'id' => array(
				'type' => 'integer',
				'null' => true,
				'key' => 'primary',
				'length' => 11,
			),
			'text' => array(
				'type' => 'string',
				'null' => true,
				'key' => 'primary',
				'length' => 140
			),
			'status' => array(
				'type' => 'string',
				'null' => true,
				'key' => 'primary',
				'length' => 140
			),
		)
	);
	public function __construct($config) {
		$auth = "{$config['login']}:{$config['password']}";
		$this->connection = new HttpSocket(
			"http://{$auth}@twitter.com/"
		);
		parent::__construct($config);
	}
	public function listSources() {
		return array('tweets');
	}
	public function read($model, $queryData = array()) {
		if (!isset($queryData['conditions']['username'])) {
			$queryData['conditions']['username'] = $this->config['login'];
		}
		$url = "/statuses/user_timeline/";
		$url .= "{$queryData['conditions']['username']}.json";
 
		$response = json_decode($this->connection->get($url), true);
		$results = array();
 
		foreach ($response as $record) {
			$record = array('Tweet' => $record);
			$record['User'] = $record['Tweet']['user'];
			unset($record['Tweet']['user']);
			$results[] = $record;
		}
		return $results;
	}
	public function create($model, $fields = array(), $values = array()) {
		$data = array_combine($fields, $values);
		$result = $this->connection->post('/statuses/update.json', $data);
		$result = json_decode($result, true);
		if (isset($result['id']) && is_numeric($result['id'])) {
			$model->setInsertId($result['id']);
			return true;
		}
		return false;
	}
	public function describe($model) {
		return $this->_schema['tweets'];
	}
}
?>