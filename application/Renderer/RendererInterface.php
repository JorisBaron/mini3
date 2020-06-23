<?php


namespace Mini\Renderer;


use Mini\Core\Application;

interface RendererInterface {
	public function render(Application $app);
}