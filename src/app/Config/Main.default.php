<?php

return array(
	'debug' => false,
	'keys' => array(
		array(
			'user' => 'username goes here',
			'key' => 'key goes here',
		),
	),
	'timeZone' => 'Australia/Sydney',
	'user' => array(
		'maxRows' => 10,
		'maxPages' => 5,
		'searchPlaceHolder' => 'First or Last name...',
		'noResultsMessage' => 'No users found.',
		'allowedFields' => array(
			'id',
			'first name',
			'last name',
			'role',
			'department',
			' ', // options button
		),
	),
	'database' => array(
		'host' => 'localhost',
		'port' => 3306,
		'database' => '',
		'username' => '',
		'password' => '',
		'type' => 'mysql',
		'persistent' => true,
	),
);