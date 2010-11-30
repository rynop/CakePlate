<?php if(!empty($fadeSuccess)){?>
<script type="text/javascript">
$(document).ready(function(){
	$("#successWidget").fadeIn(600).delay(2500).fadeOut(4000);
});
</script>
<?php }?>
<div class="ui-widget" id="successWidget">
	<div class="ui-state-highlight ui-corner-all success">
		<span class="icons tick"></span><span><?php echo $message?></span>
	</div>
</div>