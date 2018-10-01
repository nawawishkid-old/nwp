<?php

namespace NWP\Facade;

use \Exception;

class AdminPage
{
	private $info = [
		'title' => null,
		'capability' => null,
		'contentRenderer' => null
	];

	public function __construct(string $title)
	{
		$this->info['title'] = $title;
	}

	/**
	 * Getter
	 */
	public function __get(string $name)
	{
		$info = $this->info[$name];

		if (null === $info) {
			throw new Exception(
				sprintf("'%s' value has not been assigned. Please, assign the value first.", $name)
			);
		}

		return $this->info[$name];
	}

	/**
	 * Authorization, set capability
	 *
	 * @param string $capability User's capability to access this page.
	 * 
	 * @return $this
	 */
	public function auth(string $capability)
	{
		$this->info['capability'] = $capability;

		return $this;
	}

	public function addContentRenderer(callable $callback)
	{
		$this->info['contentRenderer'] = $callback;

		return $this;
	}
}
