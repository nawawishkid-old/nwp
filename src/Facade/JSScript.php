<?php

namespace NWP\Facade;

class JSScript extends FrontendScript
{
	protected $scriptType = 'js';

	public function __construct(string $id, string $src)
	{
		parent::__construct($id, $src);

		$this->info['lastArgument'] = true;
	}

	/**
	 * Put the script in the bottom of HTML <body> tag. This is default behaviour.
	 *
	 * @return $this
	 */
	public function inFooter()
	{
		$this->info['lastArgument'] = true;

		return $this;
	}

	/**
	 * Put the script in HTML <head> tag.
	 *
	 * @return $this
	 */
	public function inHead()
	{
		$this->info['lastArgument'] = false;

		return $this;
	}
}
