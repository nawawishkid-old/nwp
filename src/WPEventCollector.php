<?php

namespace NWP;

class WPEventCollector implements EventCollectorInterface
{
	private $events = [];

	private $delay = null;

	public function on(string $eventName, $eventHandler) 
	{
		if (!isset($this->events[$eventName])) {
			$this->events[$eventName] = [];
		}

		$this->events[$eventName][] = $eventHandler;

		return $this;
	}

	public function register()
	{
		foreach ($this->events as $eventName => $eventHandlers) {
			add_action($eventName, function (...$args) use ($eventHandlers) {
				foreach ($eventHandlers as $eventHandler) {
					call_user_func_array($eventHandler, $args);
				}
			}, $this->delay);
		}
	}

	public function delay(int $number = null)
	{
		if (!is_null($number)) {
			$this->delay = $number;
		}
		
		return $this;
	}
}
