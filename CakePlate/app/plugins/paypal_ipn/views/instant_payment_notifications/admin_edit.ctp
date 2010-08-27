<div class="instantPaymentNotifications form">
<h1>Add/Edit Instant Payment Notification</h1>
<?php echo $form->create('InstantPaymentNotification');?>
	<fieldset>
	<?php
		echo $form->input('id');
		echo $form->input('notify_version');
		echo $form->input('verify_sign');
		echo $form->input('test_ipn');
		echo $form->input('address_city');
		echo $form->input('address_country');
		echo $form->input('address_country_code');
		echo $form->input('address_name');
		echo $form->input('address_state');
		echo $form->input('address_status');
		echo $form->input('address_street');
		echo $form->input('address_zip');
		echo $form->input('first_name');
		echo $form->input('last_name');
		echo $form->input('payer_business_name');
		echo $form->input('payer_email');
		echo $form->input('payer_id');
		echo $form->input('payer_status');
		echo $form->input('contact_phone');
		echo $form->input('residence_country');
		echo $form->input('business');
		echo $form->input('item_name');
		echo $form->input('item_number');
		echo $form->input('quantity');
		echo $form->input('receiver_email');
		echo $form->input('receiver_id');
		echo $form->input('custom');
		echo $form->input('invoice');
		echo $form->input('memo');
		echo $form->input('option_name_1');
		echo $form->input('option_name_2');
		echo $form->input('option_selection1');
		echo $form->input('option_selection2');
		echo $form->input('tax');
		echo $form->input('auth_id');
		echo $form->input('auth_exp');
		echo $form->input('auth_amount');
		echo $form->input('auth_status');
		echo $form->input('num_cart_items');
		echo $form->input('parent_txn_id');
		echo $form->input('payment_date');
		echo $form->input('payment_status');
		echo $form->input('payment_type');
		echo $form->input('pending_reason');
		echo $form->input('reason_code');
		echo $form->input('remaining_settle');
		echo $form->input('shipping_method');
		echo $form->input('shipping');
		echo $form->input('transaction_entity');
		echo $form->input('txn_id');
		echo $form->input('txn_type');
		echo $form->input('exchange_rate');
		echo $form->input('mc_currency');
		echo $form->input('mc_fee');
		echo $form->input('mc_gross');
		echo $form->input('mc_handling');
		echo $form->input('mc_shipping');
		echo $form->input('payment_fee');
		echo $form->input('payment_gross');
		echo $form->input('settle_amount');
		echo $form->input('settle_currency');
		echo $form->input('auction_buyer_id');
		echo $form->input('auction_closing_date');
		echo $form->input('auction_multi_item');
		echo $form->input('for_auction');
		echo $form->input('subscr_date');
		echo $form->input('subscr_effective');
		echo $form->input('period1');
		echo $form->input('period2');
		echo $form->input('period3');
		echo $form->input('amount1');
		echo $form->input('amount2');
		echo $form->input('amount3');
		echo $form->input('mc_amount1');
		echo $form->input('mc_amount2');
		echo $form->input('mc_amount3');
		echo $form->input('recurring');
		echo $form->input('reattempt');
		echo $form->input('retry_at');
		echo $form->input('recur_times');
		echo $form->input('username');
		echo $form->input('password');
		echo $form->input('subscr_id');
		echo $form->input('case_id');
		echo $form->input('case_type');
		echo $form->input('case_creation_date');
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('List InstantPaymentNotifications', true), array('action' => 'index'));?></li>
	</ul>
</div>
