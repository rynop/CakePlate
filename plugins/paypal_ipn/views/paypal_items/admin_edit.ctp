<div class="paypalItems form">
<h1><?php __('Add/Edit PaypalItem');?></h1>
<?php echo $form->create('PaypalItem');?>
	<fieldset>
	<?php
		echo $form->input('id');
		echo $form->input('instant_payment_notification_id');
		echo $form->input('item_name');
		echo $form->input('item_number');
		echo $form->input('quantity');
		echo $form->input('mc_gross');
		echo $form->input('mc_shipping');
		echo $form->input('mc_handling');
		echo $form->input('tax');
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('List PaypalItems', true), array('action' => 'index'));?></li>
	</ul>
</div>
