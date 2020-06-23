<?php

/**
 * Configuration
 *
 * For more info about constants please @see http://php.net/manual/en/function.define.php
 */


return [
	'env' => require_once APP.DS.'config'.DS.'config.env.php',
	'db'  => require_once APP.DS.'config'.DS.'config.db.php',
];

