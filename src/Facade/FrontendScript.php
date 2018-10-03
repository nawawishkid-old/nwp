<?php

namespace NWP\Facade;

use NWP\AbstractEventCollector;
use NWP\EventHandlerInterface;
use \Exception;
use \InvalidArgumentException;

class FrontendScript extends AbstractEventCollector implements EventHandlerInterface 
{
	const EVENT_WP_ENQUEUE_SCRIPTS = 'wp_enqueue_scripts';

	const EVENT_ADMIN_ENQUEUE_SCRIPTS = 'admin_enqueue_scripts';

	protected $info = [
		'id' => '',
		'src' => '',
		'dependencies' => [],
		'version' => '',
		'priority' => null,
		'conditions' => []
	];

	private $isForAdminOnly = false;

	private $isForAdminToo = false;

	public function __construct(string $id, string $src)
	{
		$this->utils = Utils::getInstance();
		$this->info['id'] = $id;
		$this->info['src'] = $src;
	}

	/**
	 * Get Sidebar information.
	 *
	 * @return string Sidebar information. 
	 */
	public function __get(string $name)
	{
		return $this->info[$name];
	}

	/**
	 * Call WP's add_action
	 */
	public function register() : void
	{
		if ($this->isForAdminOnly || $this->isForAdminToo) {
			$this->eventCollector->on(self::EVENT_ADMIN_ENQUEUE_SCRIPTS, [$this, 'eventHandler']);
		}

		if (!$this->isForAdminOnly) {
			$this->eventCollector->on(self::EVENT_WP_ENQUEUE_SCRIPTS, [$this, 'eventHandler']);
		}

		$this->eventCollector->register();
	}

	public function forAdminOnly()
	{
		$this->isForAdminOnly = true;
		$this->isForAdminToo = false;

		return $this;
	}

	public function forAdminToo()
	{
		$this->isForAdminToo = true;
		$this->isForAdminOnly = false;

		return $this;
	}

	/**
	 * Enqueue callback for WP's add_action
	 *
	 * @see https://developer.wordpress.org/reference/functions/wp_enqueue_script/
	 */
	public function eventHandler() : void
	{
		if ($this->shouldBeEnqueued()) {
			$this->utils->enqueueScript(
				$this->scriptType,
				$this->id, 
				$this->src,
				$this->dependencies,
				$this->version,
				$this->lastArgument
			);
		}
	}

	/**
	 * Determines if the script should be enqueued based on given condition
	 *
	 * @see BaseFrontendTag::when()
	 *
	 * @return bool
	 */
	private function shouldBeEnqueued()
	{
		if (empty($this->conditions)) {
			return true;	
		} elseif ($this->isAllConditionsAreTrue()) {
			return true;	
		}

		return false;
	}


	/**
	 * Specify version of the script.
	 *
	 * @param string|int $version Version of the script.
	 *
	 * @return $this
	 */
	public function version($version)
	{
		$this->info['version'] = $version;

		return $this;
	}

	/**
	 * Add script dependencies.
	 *
	 * @param string|BaseFrontendTag $script An instance of NWP\BaseFrontenTag or name string of registered-script.
	 *
	 * @return $this
	 */
	public function dependsOn($script)
	{
		$isBaseFrontenTagInstance = $script instanceof BaseFrontenTag;

		if (empty($script) || !$isBaseFrontenTagInstance && !is_string($script)) {
			throw new InvalidArgumentException(
				sprintf(
					'Expected a script argument supplied to BaseFrontenTag::dependsOn() to be a string or an instance of NWP\BaseFrontenTag, %s given',
					gettype($script)
				)
			);
		}

		$scriptName = $script instanceof BaseFrontenTag ? $script->id : $script;
		$this->info['dependencies'][] = $scriptName;

		return $this;
	}

	/**
	 * Add condition to be checked before actually enqueue script
	 *
	 * @param callable $callback Callback which Boolean return
	 *
	 * @return $this
	 */
	public function when(callable $callback)
	{
		$this->info['conditions'][] = $callback;

		return $this;
	}

	/**
	 * Specify script priority
	 *
	 * @param int $number Number of priority
	 *
	 * @return $this
	 */
	public function priority(int $number)
	{
		$this->info['priority'] = $number;

		return $this;
	}
	
	/**
	 * Do what Array.prototype.every() in JavaScript done
	 *
	 * @return bool
	 */
	private function isAllConditionsAreTrue()
	{
		foreach ($this->conditions as $condition) {
			$result = call_user_func($condition);

			if (!$result) {
				return false;
			}
		}

		return true;
	}
}
