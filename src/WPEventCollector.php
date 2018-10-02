<?php

namespace NWP;

class WPEventCollector implements EventCollectorInterface
{
	private $delay = null;

	public function on(string $eventName, $eventHandler) : void
	{
		add_action($eventName, function () use ($eventHandler) {
			call_user_func($eventHandler);
		}, $this->delay);
	}

	public function delay(int $number = null)
	{
		if (!is_null($number)) {
			$this->delay = $number;
		}
		
		return $this;
	}
}
