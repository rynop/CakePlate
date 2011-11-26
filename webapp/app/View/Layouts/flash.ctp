<?php 
$this->AssetCompress->autoInclude = false; 
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?php echo $title_for_layout.' - '.Configure::read('Company.sitename');?></title>
	<meta name="author" content="leaguelogix.com" />
	<meta name="Keywords" content="automated sports league management software <?php echo Configure::read('Company.sitename').' '.Configure::read('Company.sitename_short'); ?>" />
	<meta name="description" content="automated sports league management software" />

    <!-- HTML5 shim, for IE6-8 support of HTML elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <link rel="stylesheet" href="http://twitter.github.com/bootstrap/1.3.0/bootstrap.min.css">

    <link rel="shortcut icon" href="/img/icons/favicon.ico">
<!--     <link rel="apple-touch-icon" href="images/apple-touch-icon.png"> -->
<!--     <link rel="apple-touch-icon" sizes="72x72" href="images/apple-touch-icon-72x72.png"> -->
<!--     <link rel="apple-touch-icon" sizes="114x114" href="images/apple-touch-icon-114x114.png"> -->

	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
<!-- 	  <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>   -->

	<?php   	  	
		echo $this->AssetCompress->css('common');
		echo $this->AssetCompress->includeCss();
	?>
	  
	<link rel="stylesheet" href="/custom/css/site.css">	
	
	<script type="text/javascript">
	
	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-1305853-8']);
	  _gaq.push(['_setDomainName', '.leaguelogix.com']);
	  _gaq.push(['_trackPageview']);
	
	  (function() {
	    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();
	
	</script>

	
<?php if (Configure::read() == 0):?>
<meta http-equiv="Refresh" content="<?php echo $pause?>;url=<?php echo $url?>"/>
<?php endif; ?>
  </head>

  <body style="padding-top: 40px;">

	<?php echo $this->element('nav')?>
	
    <div class="container">

      <div class="content">
		<div class="page-header"><h2><?php echo $message;?></h2></div>
		
		<p><a href="<?php echo $url?>">Click here if not automatically forwarded in <?php echo $pause?> seconds.</a></p>
      </div>

		<?php
			echo $this->element('footer'); 
		?>

    </div> <!-- /container -->

	<?php 
		echo $this->AssetCompress->script(
			'common'			
		);
		echo $this->AssetCompress->includeJs();
		echo $scripts_for_layout;
		echo $this->Js->writeBuffer(); // Any Buffered Scripts
	?>
  </body>
</html>
