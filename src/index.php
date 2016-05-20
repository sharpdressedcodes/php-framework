<?php

$basePath = __DIR__ . DIRECTORY_SEPARATOR;
$frameworkPath =  $basePath . 'framework' . DIRECTORY_SEPARATOR;

require_once($frameworkPath . 'autoload.php');

$controller = \WebsiteConnect\Framework\Controller\Controller::loadController(array(
	'basePath' => $basePath,
	'frameworkPath' => $frameworkPath,
));
$controller->run();
