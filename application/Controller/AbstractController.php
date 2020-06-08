<?php


namespace Mini\Controller;


use Mini\Core\Application;

abstract class AbstractController {
	/** @var Application */
	protected $app;

	public function __construct($app) {
		$this->app = $app;
	}
}