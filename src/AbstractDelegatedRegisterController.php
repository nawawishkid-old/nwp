<?php

namespace NWP;

abstract class AbstractDelegatedRegisterController extends AbstractEventCollector
{
	protected $eventHandler;

	abstract protected function getEventName() : string;
	
	public function register() : void
	{
		$this->eventCollector->on($this->eventName, $this->eventHandler); 
	}
}
