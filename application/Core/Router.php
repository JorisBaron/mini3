<?php


namespace Mini\Core;

use Exception;

/**
 * Class Router
 * @package Mini\Core
 * @property-read $controllerUrl
 * @property-read $controller
 * @property-read $actionUrl
 * @property-read $action
 * @property-read $params
 */
class Router {

	private $controllerUrl;
	private $actionUrl;
	private $params;

	/**
	 * @param $name
	 * @return mixed
	 * @throws Exception
	 */
	public function __get($name){
		if(property_exists($this,$name))
			return $this->$name;
		elseif($name == 'controller')
			return $this->formatController($this->controllerUrl);
		elseif($name == 'action')
			return $this->formatAction($this->actionUrl);
		else
			throw new Exception("Propriété non définie ou inaccessible");
	}

	/**
	 * Router constructor.
	 * @param $url
	 */
	public function __construct($url){
		if(!empty($url)) {
			$arrUrl = explode('/', filter_var(trim($url, '/'), FILTER_SANITIZE_URL));
		}
		else {
			$arrUrl=[];
		}

		$this->controllerUrl = $arrUrl[0] ?? CONFIG['default_controller'];
		$this->actionUrl     = $arrUrl[1] ?? CONFIG['default_action'];

		unset($arrUrl[0],$arrUrl[1]);
		$this->params = array_values($arrUrl);
	}

	/**
	 * Convertis une string comportant des "-" en string camelCase
	 * @param string $str
	 * @return string|null
	 */
	public function dashToUpper($str){
		return preg_replace_callback('#-([a-z])#', function ($matches){
			return strtoupper($matches[1]);
		},$str);
	}


	private function formatController($controllerName){
		return '\\Mini\\Controller\\'.ucfirst($this->dashToUpper($controllerName)) . 'Controller';
	}

	private function formatAction($actionName){
		return $this->dashToUpper($actionName);
	}
}