<?php 
$this->AssetCompress->autoInclude = false; 
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?php echo $title_for_layout;?></title>
	<meta name="author" content="" />
	<meta name="Keywords" content="CakePlate http://cakeplate.posterous.com" />
	<meta name="description" content="" />

<?php if (Configure::read('debug') >= 1): ?>
	<link rel="stylesheet" href="/css/cake.debug.css">
<?php endif; ?>

    <!-- HTML5 shim, for IE6-8 support of HTML elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <link rel="stylesheet" href="http://twitter.github.com/bootstrap/1.4.0/bootstrap.min.css">

    <link rel="shortcut icon" href="/img/icons/favicon.ico">
<!--     <link rel="apple-touch-icon" href="images/apple-touch-icon.png"> -->
<!--     <link rel="apple-touch-icon" sizes="72x72" href="images/apple-touch-icon-72x72.png"> -->
<!--     <link rel="apple-touch-icon" sizes="114x114" href="images/apple-touch-icon-114x114.png"> -->

	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>
<!-- 	  <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>   -->

	<?php   	  	
		echo $this->AssetCompress->css('common');
		echo $this->AssetCompress->includeCss();
	?>
	
	<!-- put ur google analytics here  -->
  </head>

  <body>

	<?php echo $this->element('nav')?>
	
    <div class="container">

      <div class="content">
		<?php echo $content_for_layout;?>
      </div>

		<?php echo $this->element('footer');?>

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
