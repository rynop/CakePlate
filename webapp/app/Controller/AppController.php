<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 */

App::uses('Controller', 'Controller');

/**
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       Cake.Controller
 * @link http://book.cakephp.org/view/957/The-App-Controller
 */
class AppController extends Controller {
	public $components = array(
			'Session',
			'DebugKit.Toolbar' => array('panels' => array('history'=>false,'include'=>false))
	);

	public $helpers = array(
			'Html',
			'Form',
			'Session',
			'Js',
			'AssetCompress.AssetCompress',
	);
}
