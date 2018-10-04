<?php

namespace NWP;

use \InvalidArgumentException;

/**
 * Implement Rendereable interface implicitly
 */
trait RendererTrait
{
	protected $renderers = [];

	public function render()
	{
		foreach ($this->renderers as $renderer) {
			call_user_func($renderer);
		}	
	}

	public function getRenderer()
	{
		return $this->renderers;
	}

	/**
	 * Add multiple output callbacks
	 *
	 * @see AdminSettingsGroup::addRenderer()
	 */
	public function addRenderers(...$renderers)
	{
		foreach ($renderers as $renderer) {
			$this->addRenderer($renderer);
		}

		return $this;
	}

	/**
	 * Add output callback
	 *
	 * @param callable $renderer Callback that echo something.
	 * 
	 * @return $this
	 */
	public function addRenderer(callable $renderer)
	{
		if (is_null($renderer) || empty($renderer)) {
			throw new InvalidArgumentException(
				sprintf(
						"Expecting %s, %s given.",
						'callable',
						gettype($renderer)
					)
				);
			}

		$this->renderers[] = $renderer;

		return $this;
	}
}
