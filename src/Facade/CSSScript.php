<?php

namespace NWP\Facade;

class CSSScript extends FrontendScript
{
	protected $scriptType = 'css';

	public function __construct(string $id, string $src)
	{
		parent::__construct($id, $src);

		$this->info['lastArgument'] = null;
	}

	public function media(string $type)
	{
		$this->info['lastArgument'] = $type;

		return $this;
	}
}
