<?php
//Make sure these are always added first to asset (before stuff in your views)
$this->Html->script(array('plugins','commonscript'),array('inline'=>false));
?>
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
                
  <?php   	  	
	$this->Html->css(array('style','cake.generic','custom'),NULL,array('inline'=>false));		
	echo $asset->scripts_for_layout('css');
	
	//Don't include handheld in asset because it needs media="handheld"
	echo $this->Html->css(array('handheld'),null,array('media'=>'handheld'));	
	
	//Example of how to use google webfonts - see webroot/css/custom.css
	echo $this->Html->css('http://fonts.googleapis.com/css?family=Lobster',NULL,array('inline'=>true));
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
    <h1>Oops there was an error</h1>
	<?php echo $content_for_layout ?>
    </div>
    
    <footer>
	<?php echo $this->element('footer');?>
    </footer>
  </div> <!--! end of #container -->


  <!-- Javascript at the bottom for fast page loading -->

  <!-- Grab Google CDN's jQuery. fall back to local if necessary -->
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
 <!-- If you want to fallback, download jquery to your js dir -->
  <script>!window.jQuery && document.write(unescape('%3Cscript src="/js/jquery-1.4.3.min.js"%3E%3C/script%3E'))</script>
  
  <!-- Optional: <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.6/jquery-ui.min.js"></script>-->  
  <!-- Optional: <script src="http://cdn.jquerytools.org/1.2.5/all/jquery.tools.min.js"></script>-->
  
<?php
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
    
</body>
</html>