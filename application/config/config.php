<?php

/**
 * Configuration
 */


return [
	'env' => require_once __DIR__.DS.'config.env.php',
	'db'  => require_once __DIR__.DS.'config.db.php',
	'session' => [
		'timeout' => 900,
	],
	'default_controller' => 'home',
	'default_action' => 'index',
];

