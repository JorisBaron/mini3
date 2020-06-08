<?php


namespace Mini\Model\Renderer;


use Mini\Model\Application;

class JsonRenderer implements RendererInterface {
	public function render(Application $app) {
		header('Content-Type: application/json');
		echo json_encode($app->viewData);
	}
}