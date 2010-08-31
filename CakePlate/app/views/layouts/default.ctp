<!doctype html>
<html lang="en" class="no-js">
<head>
  <meta charset="utf-8">
  
  <!-- www.phpied.com/conditional-comments-block-downloads/ -->
  <!--[if IE]><![endif]-->

  <!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame 
       Remove this if you use the .htaccess -->
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  
  <title><?php echo $title_for_layout;?></title>
  <meta name="description" content="">
  <meta name="author" content="">

  <!--  Mobile viewport optimized: j.mp/bplateviewport -->
  <meta name="viewport" content="width=device-width; initial-scale=1.0">

  <!-- Replace favicon.ico & apple-touch-icon.png in the root of your domain and delete these references -->
  <link rel="shortcut icon" href="/favicon.ico">
  <link rel="apple-touch-icon" href="/apple-touch-icon.png">

<!--  optional: <link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/themes/ui-lightness/jquery-ui.css">-->
                
  <?php   	  	
	$html->css(array('style','cake.generic','custom'),NULL,array('inline'=>false));	
	
	//Thanks to http://github.com/mcurry/asset
	//There is a bug currently in the asset plugin where it will not just output css or just js
	//This is only a problem if debug is > 0, and only causes problems if your developing on an old browser (which ur not)
	echo $asset->scripts_for_layout('css');
	
	//Don't include handheld in asset because it needs media="handheld"
	echo $html->css(array('handheld'),null,array('media'=>'handheld'));	
	
	//Example of how to use google webfonts - see webroot/css/custom.css
	echo $html->css('http://fonts.googleapis.com/css?family=Lobster',NULL,array('inline'=>true));
  ?>
  
</head>

<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->

<!--[if lt IE 7 ]> <body class="ie6"> <![endif]-->
<!--[if IE 7 ]>    <body class="ie7"> <![endif]-->
<!--[if IE 8 ]>    <body class="ie8"> <![endif]-->
<!--[if IE 9 ]>    <body class="ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <body> <!--<![endif]-->

  <div id="container">
    <header>
	<?php echo $this->element('header');?>
    </header>
    
    <div id="main">
	<?php echo $content_for_layout ?>
    </div>
    
    <footer>
	<?php echo $this->element('footer');?>
    </footer>
  </div> <!--! end of #container -->


  <!-- Javascript at the bottom for fast page loading -->

  <!-- Grab Google CDN's jQuery. fall back to local if necessary -->
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
  <script>!window.jQuery && document.write('<script src="/js/jquery-1.4.2.min.js"><\/script>')</script>  
  
  <!-- Optional: <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/jquery-ui.min.js"></script>-->  
  <!-- If you add jquery above, include this<script>!jQuery.ui && document.write('<script src="/js/jquery-ui-1.8.4.custom.min"><\/script>');</script> -->
  <!-- Optional: <script src="http://cdn.jquerytools.org/1.2.4/all/jquery.tools.min.js"></script>-->
  
  <?php 
  	$html->script(array('plugins','script'),array('inline'=>false));
  	echo $asset->scripts_for_layout('js');
  ?>

  <!--[if lt IE 7 ]>
	<?php echo $html->script('dd_belatedpng')?>
  <![endif]-->


  <!-- yui profiler and profileviewer - remove for production -->
  <?php echo $html->script('profiling/yahoo-profiling.min')?>
  <?php echo $html->script('profiling/config')?>  
  <!-- end profiling code -->


  <!-- asynchronous google analytics: mathiasbynens.be/notes/async-analytics-snippet 
       change the UA-XXXXX-X to be your site's ID -->
  <script>
   var _gaq = [['_setAccount', 'UA-XXXXX-X'], ['_trackPageview']];
   (function(d, t) {
    var g = d.createElement(t),
        s = d.getElementsByTagName(t)[0];
    g.async = true;
    g.src = '//www.google-analytics.com/ga.js';
    s.parentNode.insertBefore(g, s);
   })(document, 'script');
  </script>
  
<?php echo $this->element('sql_dump'); ?>  
</body>
</html>