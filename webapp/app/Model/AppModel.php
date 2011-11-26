<?php
/**
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 */

App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 */
class AppModel extends Model {
	public $actsAs = array('Containable');
}
