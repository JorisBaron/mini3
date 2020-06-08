<?php


namespace Mini\Model\Renderer;


use Mini\Model\Application;

interface RendererInterface {
	public function render(Application $app);
}