<?php


namespace Mini\Controller;

class ErrorController extends AbstractController {
	public function index() {
		$this->app->view = 'error/error404';
		$this->error404();
	}

	public function error404() {
	}
}
