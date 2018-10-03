<?php

namespace NWP;

class RegisterController extends AbstractEventCollector
{
	public function register() : void
	{
		$this->eventCollector->register();
	}
}
