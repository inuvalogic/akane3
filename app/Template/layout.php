<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Akane Framework</title>
<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
</head>
<body class="bg-gray">
<?= $this->render('header'); ?>
<?php echo $content; ?>
<?= $this->render('footer'); ?>
</body>
</html>