<?php
class ExamplesController extends AppController {
   public $name = 'Examples';
   public $uses = array();

   function index(){   		
	   	$this->set('title_for_layout', 'Example');	
	   	$this->Session->setFlash('There was an error saving the data. please contact support (support@leaguelogix.com)','flash_error',array(),'error');
	   	$this->Session->setFlash('Logo successufully added','flash_success',array(),'success');
   }
}