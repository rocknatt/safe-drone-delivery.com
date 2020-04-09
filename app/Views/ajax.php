<!DOCTYPE html>
	<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
		<meta name="theme-color" content="#000000">

		<title>Mzara Tools Plateform</title>
		<!-- Common Plugins -->
	    <link href="<?php echo site_url('assets/addons/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
	    <link href="<?php echo site_url('assets/addons/fontawesome/css/all.min.css') ?>" rel="stylesheet">
	    <link rel="stylesheet" type="text/css" href="<?php echo site_url('assets/css/animate.css') ?>">
	    <link rel="stylesheet" type="text/css" href="<?php echo site_url('assets/css/init-font.css') ?>">

		<link rel="stylesheet" type="text/css" href="<?php echo site_url('assets/css/bundle.css') ?>">
	</head>
<body>
	<noscript>You need to enable JavaScript to run this app.</noscript>
	<div id="root">

    </div>
    <!--
      This HTML file is a template.
      If you open it directly in the browser, you will see an empty page.

      You can add webfonts, meta tags, or analytics to this file.
      The build step will place the bundled scripts into the <body> tag.

      To begin the development, run `npm start` or `yarn start`.
      To create a production bundle, use `npm run build` or `yarn build`.
    -->
    <div id="text-container" style="position: absolute; z-index: -1; opacity: 0;">
      
    </div>
	<?php 
		// $this->config->load('ratchet_client');
		// $ratchet_client_config = $this->config->item('ratchet_client');
	 ?>

	<div id="src"
		data-url="<?php echo site_url(uri_string()) ?>"
		data-base-url="<?php echo site_url('') ?>"
		data-websocket="<?php echo 'ws://'. $_SERVER['SERVER_NAME'] . ':4000'  /*$ratchet_client_config['port']*/; ?>"
		data-websocket-boot="<?php echo site_url('hub/websocket') ?>"
		data-environment="<?php echo ENVIRONMENT ?>"
		></div>

	<script type="text/javascript" src="<?php echo site_url('assets/js/bundle.js') ?>"></script>
</body>
</html>