<?php


namespace Mini\Model\Renderer;


use Mini\Model\Application;

class DefaultRenderer implements RendererInterface {

	public function render(Application $app) {
		$view = empty($app->view) ? $app->urlPath : trim(trim($app->view), '/');

		$data = $app->viewData;
		extract($data);

		require APP.'view/_templates/header.phtml';
		if(file_exists(APP.'view/'.$view.".phtml")) {
			require APP.'view/'.$view.".phtml";
		} else {
			echo '<main class="container"><h1 class="display-1 lead">Vue inexistante</h1></main>';
		}
		require APP.'view/_templates/footer.phtml';
	}
}