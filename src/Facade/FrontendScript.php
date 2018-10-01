<?php

namespace NWP\Facade;

use \Exception;
use \InvalidArgumentException;

class FrontendScript
{
	protected $info = [
		'id' => '',
		'src' => '',
		'dependencies' => [],
		'version' => '',
		'priority' => null,
		'conditions' => []
	];

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
	public function register()
	{
		$this->utils->addAction(
			'wp_enqueue_scripts', 
			function() { $this->tryToEnqueue(); }, 
			$this->priority 
		);
	}

	/**
	 * Enqueue callback for WP's add_action
	 *
	 * @see https://developer.wordpress.org/reference/functions/wp_enqueue_script/
	 */
	protected function tryToEnqueue()
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

			return true;
		}

		return false;
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
