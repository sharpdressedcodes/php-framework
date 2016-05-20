<!DOCTYPE html>
<html lang="en">
<head>

	<?php echo $this->getResourcesForLocation(self::PAGE_HEAD_BEGIN_1); ?>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo $title; ?></title>

	<link href="css/framework-bundle.css" rel="stylesheet" type="text/css">

	<?php echo $this->getResourcesForLocation(self::PAGE_HEAD_BEGIN_2); ?>

	<?php echo $this->getResourcesForLocation(self::PAGE_HEAD_BEGIN_3); ?>

	<?php echo $this->getResourcesForLocation(self::PAGE_HEAD_END_1); ?>

	<!--[if lt IE 9]>
	<script src="vendor/html5shiv/html5shiv-3.7.2.min.js" type="text/javascript"></script>
	<script src="vendor/respond/respond-1.4.2.min.js" type="text/javascript"></script>
	<![endif]-->

	<?php echo $this->getResourcesForLocation(self::PAGE_HEAD_END_2); ?>

</head>
<body>

<?php echo $this->getResourcesForLocation(self::PAGE_BODY_BEGIN); ?>

<?php //echo $navbar; ?>

<div id="main-outer-container">
	<div id="main-inner-container">
		<?php echo $content; ?>
	</div>
</div>

<?php //echo $controls; ?>

<?php echo $this->getResourcesForLocation(self::PAGE_BODY_END_1); ?>

<script type="text/javascript" src="js/framework-bundle.js"></script>

<?php echo $this->getResourcesForLocation(self::PAGE_BODY_END_2); ?>

<?php echo $this->getResourcesForLocation(self::PAGE_BODY_END_3); ?>

<?php echo $this->getResourcesForLocation(self::PAGE_BODY_END_4); ?>

</body>
</html>