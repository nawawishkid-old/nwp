<?php

interface EventCollector
{
    public function on(string $eventName, callable $eventHandler) : void;
}

interface EventHandler
{
    public function eventHandler() : void;
}

abstract class AbstractEventCollector
{
	protected $eventCollector;

	abstract public function register() : void;

	public function addEventCollector(EventCollector $eventCollector) : self 
	{
		$this->eventCollector = $eventCollector;

		return $this;
	}
}

abstract class AbstractDelegatedRegisterController extends AbstractEventCollector
{
	protected $eventHandler;

	abstract protected function getEventName() : string;

	public function register() : void
	{
		echo "  -- AbstractAbstractSelfRegisterController->register()\n";
		$this->eventCollector->on($this->getEventName(), $this->eventHandler); 
	}
}

abstract class AbstractSelfRegisterController extends AbstractEventCollector implements EventHandler
{
	protected $eventName;

	abstract protected function action() : void;

	abstract protected function getEventName() : string;

	public function eventHandler() : void
	{
		$this->action();
	}

	public function register() : void
	{
		echo "  -- AbstractAbstractSelfRegisterController->register()\n";
		$this->eventCollector->on($this->getEventName(), [$this, 'eventHandler']); 
	}
}

class WPEventCollector implements EventCollector
{
	private $delay = null;

	public function on(string $eventName, $eventHandler) : void
	{
		echo "  -- WPEventCollector->on() => add_action()\n";
		// add_action($eventName, function () use ($eventHandler) {
		// 	call_user_func($eventHandler);
		// }, $this->delay);
	}

	public function delay(int $number = null)
	{
		if (!is_null($number)) {
			$this->delay = $number;
		}
		
		return $this;
	}
}

class App extends AbstractDelegatedRegisterController 
{
	protected function getEventName() : string
	{
		return $this->eventName;
	}

	public function addMetaBox(MetaBox $metaBox, int $delay = null)
	{
		$this->eventName = 'add_meta_boxes';
		$this->eventHandler = [$metaBox, 'eventHandler'];
		$this->eventCollector->delay($delay);
		$this->register();
	}
}

class MetaBox extends AbstractSelfRegisterController 
{
	protected function getEventName() : string
	{
		return 'add_meta_boxes';
	}

	protected function action() : void
	{
		// add_meta_box(/*...*/);
		echo "  -- MetaBox->action()\n";
	}
}

/**
 * Executions
 */
// Setup
$eventCollector = new WPEventCollector();
$app = new App();

$app->addEventCollector($eventCollector);

// Arbitrary executions.
$metaBox = new MetaBox();

$app->addMetaBox($metaBox);

// or
$metaBox->addEventCollector($eventCollector)->register();



