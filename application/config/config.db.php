<?php

return [
	'type'    => 'mysql',
	'host'    => '127.0.0.1',
	'name' 	  => 'canet990678',
	'user'    => 'root',
	'pass'    => '',
	'charset' => 'utf8mb4',
	'options' => [
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_EMULATE_PREPARES   => false,
	],
];