<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta name="keywords" content="medicare, healthcare, sms" />
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>RemindaVax :: Mobile Healthcare Solution</title>
	<?php echo html::style('css/style.css', array('media' => 'screen')); ?>
	<?php echo html::style('css/my.css', array('media' => 'screen')); ?>
	<?php echo html::style('css/date.css', array('media' => 'screen')); ?>
	<?php echo html::style('css/ui-lightness/jquery-ui-1.8.9.custom.css', array('media' => 'screen')); ?>
	<?php echo html::script('js/jquery-1.4.4.min.js'); ?>
	<?php echo html::script('js/jquery-ui-1.8.9.custom.min.js'); ?>
	<?php echo html::script('js/date.js'); ?>
</head>
<body>
<div id="wrapper">
	<div id="header-wrapper">
		<div id="header">
			<div id="logo">
				<h1><?php echo html::anchor('/', '<span>Reminda</span>vax</a>'); ?></h1>
			</div>
			<?php echo View::factory('menu'); ?>
		</div>
	</div>
	<!-- end #header -->
	<div id="page">
		<div id="content">
			<?php echo $content; ?>
		<!-- end #content -->
		</div>
		<div style="clear: both;">&nbsp;</div>
	<!-- end #page -->
	</div>
</div>
<div id="footer">
	<p>Copyright (c) 2010 RemindaVax.com. All rights reserved. </p>
</div>
<!-- end #footer -->
</body>
</html>
