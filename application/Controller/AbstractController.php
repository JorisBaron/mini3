<?php


namespace Mini\Controller;


use Mini\Model\Application;

abstract class AbstractController {
	/** @var Application */
	protected $app;

	public function __construct($app) {
		$this->app = $app;
	}
}