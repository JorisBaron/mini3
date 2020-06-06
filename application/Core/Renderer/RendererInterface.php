<?php


namespace Mini\Core\Renderer;


use Mini\Core\Application;

interface RendererInterface {
	public function render(Application $app);
}