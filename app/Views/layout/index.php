<!--
author: W3layouts
author URL: http://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->
<!DOCTYPE html>
<html>
<head>
<title>Safe Drone Delivery</title>
<!-- for-mobile-apps -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Safe drone delivery" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false);
		function hideURLbar(){ window.scrollTo(0,1); } </script>
<!-- //for-mobile-apps -->
<link href="<?= site_url('assets/css/bootstrap.css') ?>" rel="stylesheet" type="text/css" media="all" />
<link href="<?php echo site_url('assets/addons/fontawesome/css/all.min.css') ?>" rel="stylesheet">
<link href="<?= site_url('assets/css/style.css') ?>" rel="stylesheet" type="text/css" media="all" />

<!-- js -->
<script src="<?= site_url('assets/js/jquery-1.11.1.min.js') ?>"></script>
<!-- //js -->
<!-- <link href='//fonts.googleapis.com/css?family=Ubuntu:400,300,300italic,400italic,500,500italic,700,700italic' rel='stylesheet' type='text/css'>
<link href='//fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic,800,800italic' rel='stylesheet' type='text/css'> -->
<!-- start-smoth-scrolling -->
<script type="text/javascript" src="<?= site_url('assets/js/move-top.js') ?>"></script>
<script type="text/javascript" src="<?= site_url('assets/js/easing.js') ?>"></script>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$(".scroll").click(function(event){		
			event.preventDefault();
			$('html,body').animate({scrollTop:$(this.hash).offset().top},1000);
		});
	});
</script>
<!-- start-smoth-scrolling -->
</head>
	
<body>

	<?= $this->include('layout/header') ?>
	<?= $this->renderSection('content') ?>
	<?= $this->include('layout/footer') ?>

</body>
</html>
