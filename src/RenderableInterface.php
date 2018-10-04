<?php

namespace NWP;

interface RenderableInterface 
{
	public function addRenderer(callable $renderer);

	public function getRenderer();

	public function render();
}
