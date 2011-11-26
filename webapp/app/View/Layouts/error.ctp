<!doctype html>
<html lang="en" class="no-js">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  
  <title><?php echo Configure::read('Company.sitename');?></title>
  <meta name="Author" content="leaguelogix.com" />
  <meta name="Keywords" content="automated sports league management software <?php echo Configure::read('Company.sitename').' '.Configure::read('Company.sitename_short'); ?>" />
  <meta name="description" content="automated sports league management software" />
  <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;">
  
  <link rel="shortcut icon" type="image/x-icon" href="/img/icons/favicon.ico">

  <link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/themes/ui-lightness/jquery-ui.css">
        
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
  <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/jquery-ui.min.js"></script>  
  <script>!window.jQuery && document.write('<script src="/js/jquery-1.4.2.min.js"><\/script>')</script>  
  <script>!jQuery.ui && document.write('<script src="/js/jquery-ui-1.8.4.custom.min"><\/script>');</script>
        
  <?php   	  	
	$this->Html->css(array('style','common','icons','buttons'),NULL,array('inline'=>false));
	$this->Html->script(array('common','jquery/common','jquery/jquery.bgiframe.min'),array('inline'=>false));	
	echo $asset->scripts_for_layout();
  ?>
  <!--[if lte IE 6]>
	<?php echo $this->Html->css('commonie6');?>
	<?php echo $this->Html->script('jquery/supersleight.plugin')?>
	<script>$(document).ready(function(){$('#theLogo').supersleight();});</script>
  <![endif]-->
  <?php 
	//Dont include this in asset because it removes duplicates. We are duplicating on purpose.
	echo $this->Html->css('/custom/css/site');
  ?>
</head>
<body class="main" onunload="$(this).restoreSubmit();">
<div id="content">
	<div id="left">
		<a href="/"><img id="theLogo" src="/custom/img/logo.png"/></a>
		<div id="navcontainer"><?php echo $this->element('nav'); ?></div>
	</div>
	<div id="middle">
		<div id="middleBody">
			<h1>Oops there was an error</h1>
			<?php echo $content_for_layout ?>
		</div>
	</div>
</div>
<div id="footer"><?php echo $this->element('footer');?></div>
	
<script type="text/javascript">
	  var _gaq = [['_setAccount', 'UA-1305853-8'], ['_setDomainName', '.leaguelogix.com'], ['_trackPageview']];
	  (function(d, t) {
	    var g = d.createElement(t),
	        s = d.getElementsByTagName(t)[0];
	    g.async = true;
	    g.src = '//www.google-analytics.com/ga.js';
	    s.parentNode.insertBefore(g, s);
	  })(document, 'script');     
</script>    
<script type="text/javascript">
var uservoiceOptions = {
  /* required */
  key: 'leaguelogix',
  host: 'leaguelogix.uservoice.com', 
  forum: '40755',
  showTab: true,  
  /* optional */
  alignment: 'right',
  background_color:'#f00', 
  text_color: 'white',
  hover_color: '#06C',
  lang: 'en'
};

function _loadUserVoice() {
  var s = document.createElement('script');
  s.setAttribute('type', 'text/javascript');
  s.setAttribute('src', ("https:" == document.location.protocol ? "https://" : "http://") + "cdn.uservoice.com/javascripts/widgets/tab.js");
  document.getElementsByTagName('head')[0].appendChild(s);
}
_loadSuper = window.onload;
window.onload = (typeof window.onload != 'function') ? _loadUserVoice : function() { _loadSuper(); _loadUserVoice(); };
</script>
<?php echo $this->element('sql_dump'); ?>
</body>
</html>