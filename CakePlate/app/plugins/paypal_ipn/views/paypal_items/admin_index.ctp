<div class="paypalItems index">
<h1><?php __('PaypalItems');?></h1>
<p>
<?php
echo $paginator->counter(array(
'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
));
?></p>
<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $paginator->sort('item_name');?></th>
	<th><?php echo $paginator->sort('item_number');?></th>
	<th><?php echo $paginator->sort('quantity');?></th>
	<th><?php echo $paginator->sort('mc_gross');?></th>
	<th><?php echo $paginator->sort('created');?></th>
	<th class="actions"><?php __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($paypalItems as $paypalItem):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $paypalItem['PaypalItem']['item_name']; ?>
		</td>
		<td>
			<?php echo $paypalItem['PaypalItem']['item_number']; ?>
		</td>
		<td>
			<?php echo $paypalItem['PaypalItem']['quantity']; ?>
		</td>
		<td>
			<?php echo $number->currency($paypalItem['PaypalItem']['mc_gross']); ?>
		</td>
		<td>
			<?php echo $time->niceShort($paypalItem['PaypalItem']['created']); ?>
		</td>
		<td class="actions">
			<?php echo $html->link(__('View', true), array('action' => 'view', $paypalItem['PaypalItem']['id'])); ?>
			<?php echo $html->link(__('Edit', true), array('action' => 'edit', $paypalItem['PaypalItem']['id'])); ?>
			<?php echo $html->link(__('Delete', true), array('action' => 'delete', $paypalItem['PaypalItem']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $paypalItem['PaypalItem']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>
</div>
<div class="paging">
	<?php echo $paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));?>
 | 	<?php echo $paginator->numbers();?>
	<?php echo $paginator->next(__('next', true).' >>', array(), null, array('class' => 'disabled'));?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('New PaypalItem', true), array('action' => 'add')); ?></li>
	</ul>
</div>
