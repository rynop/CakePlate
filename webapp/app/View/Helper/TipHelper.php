<?php 
App::uses('AppHelper', 'View/Helper');

/**
 * $this->Tip->show('Some Help text here')
 * 
 * @author rynop
 *
 */
class TipHelper extends AppHelper{

    function show($helpText) {
    	return '<img class="help helpImg" title="'.$helpText.'" rel="twipsy" src="/img/help.png" />';    	
    }
	
}
?>