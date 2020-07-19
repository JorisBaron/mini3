<?php

/**
 * MINI - an extremely simple naked PHP application
 *
 * @package mini
 * @author Panique
 * @link http://www.php-mini.com
 * @link https://github.com/panique/mini/
 * @license http://opensource.org/licenses/MIT MIT License
 */

/**
 * Now MINI work with namespaces + composer's autoloader (PSR-4)
 *
 * @author Joao Vitor Dias <joaodias@noctus.org>
 *
 * For more info about namespaces plase @see http://php.net/manual/en/language.namespaces.importing.php
 */

define('DS',DIRECTORY_SEPARATOR);

// set a constant that holds the project's folder path, like "/var/www/".
define('ROOT', dirname(__DIR__));

// set a constant that holds the project's "application" folder, like "/var/www/application".
define('APP', ROOT . '/application');

// URL
define('URL_PUBLIC_FOLDER', 'public');
define('URL_PROTOCOL', '//');
define('URL_DOMAIN', $_SERVER['HTTP_HOST']);
define('URL_SUB_FOLDER', rtrim(str_replace(URL_PUBLIC_FOLDER, '', dirname($_SERVER['SCRIPT_NAME'])), '/'));
define('URL', URL_PROTOCOL . URL_DOMAIN . URL_SUB_FOLDER);

// This is the auto-loader for Composer-dependencies (to load tools into your project).
require ROOT . '/vendor/autoload.php';

// load application config (error reporting etc.)
define('CONFIG', require APP.DS.'config'.DS.'config.php');

if (CONFIG['env'] == 'development' || CONFIG['env'] == 'dev') {
	error_reporting(E_ALL);
	ini_set("display_errors", 1);
}

//session
session_set_cookie_params(['path'=> URL_SUB_FOLDER, 'httponly'=>true]);
session_start();

// load application class
use Mini\Core\Application;

// start the application
$app = new Application();
