<?php

namespace NWP;

abstract class AbstractEventCollector
{
	protected $eventCollector;

	abstract public function register() : void;

	public function addEventCollector(EventCollectorInterface $eventCollector) : self 
	{
		$this->eventCollector = $eventCollector;

		return $this;
	}
}
