<?php

namespace NWP;

abstract class AbstractSelfRegisterController extends AbstractEventCollector implements EventHandlerInterface
{
	abstract protected function action() : void;

	abstract protected function getEventName() : string;

	public function eventHandler() : void
	{
		$this->action();
	}

	public function register() : void
	{
		$this->eventCollector->on($this->getEventName(), [$this, 'eventHandler']); 
	}
}
