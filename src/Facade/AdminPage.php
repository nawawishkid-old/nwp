<?php

namespace NWP\Facade;

use NWP\AbstractEventCollector;
use NWP\RenderableInterface;
use \Exception;

use \NWP\RendererTrait;

class AdminPage extends AbstractEventCollector implements RenderableInterface
{
	use RendererTrait;

	private $info = [
		'id' => null,
		'title' => null,
		'capability' => null
	];

	public function __construct(string $id, string $title)
	{
		$this->info['id'] = $id;
		$this->info['title'] = $title;
	}

	/**
	 * Getter
	 */
	public function __get(string $name)
	{
		return $this->info[$name];
	}

	public function register() : void
	{

	}

	/**
	 * Authorization, set capability
	 *
	 * @param string $capability User's capability to access this page.
	 * 
	 * @return $this
	 */
	public function allow(string $capability)
	{
		$this->info['capability'] = $capability;

		return $this;
	}
}
