<?php
App::import('Lib', 'LazyModel.LazyModel');
class AppModel extends LazyModel {
	var $actsAs = array('Containable');
}
?>