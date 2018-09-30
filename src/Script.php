<?php

namespace NWP;

use NWP\BaseFrontendTag;
use \Exception;
use \InvalidArgumentException;

class Script extends BaseFrontendTag
{
	// const WP_FUNCTIONS_WP_ENQUEUE_SCRIPT = 'wp_enqueue_script';

	// private $info = [
	// 	'id' => '',
	// 	'src' => '',
	// 	'dependencies' => [],
	// 	'version' => '',
	// 	'isInFooter' => true,
	// 	'priority' => null,
	// 	'conditions' => []
	// ];

	public function __construct(string $id, string $src)
	{
		parent::__construct($id, $src, 'js');

		// $this->info['id'] = $id;
		// $this->info['src'] = $src;
	}

	/**
	 * Get Sidebar information.
	 *
	 * @return string Sidebar information. 
	 */
	// public function __get(string $name)
	// {
	// 	return $this->info[$name];
	// }
	
	/**
	 * WP's wp_enqueue_script wrapper
	 */
	// protected function wpWpEnqueueScript(...$args)
	// {
	// 	$wpFunction = self::$wpFunctions[self::WP_FUNCTIONS_WP_ENQUEUE_SCRIPT];
	// 
	// 	if (empty($wpFunction)) {
	// 		throw new Exception("WordPress function for Script::wpWpEnqueueScript() has not been assigned.");
	// 	}

	// 	return call_user_func_array($wpFunction, $args);
	// }

	/**
	 * Call WP's add_action
	 */
	// public function register()
	// {
	// 	$this->wpAddAction(
	// 		self::WP_EVENTS_WP_ENQUEUE_SCRIPTS, 
	// 		function() { $this->enqueueScript(); }, // [$this, 'enqueueScript'], 
	// 		$this->priority 
	// 	);
	// }

	/**
	 * Enqueue callback for WP's add_action
	 *
	 * @see https://developer.wordpress.org/reference/functions/wp_enqueue_script/
	 */
	// private function enqueueScript()
	// {
	// 	if ($this->shouldBeEnqueued()) {
	// 		$this->wpWpEnqueueScript(
	// 			$this->id, 
	// 			$this->src,
	// 			$this->dependencies,
	// 			$this->version,
	// 			$this->isInFooter
	// 		);
	// 	}
	// }

	/**
	 * Determines if the script should be enqueued based on given condition
	 *
	 * @see Script::when()
	 *
	 * @return bool
	 */
	// private function shouldBeEnqueued()
	// {
	// 	if (empty($this->conditions)) {
	// 		return true;	
	// 	} elseif ($this->isAllConditionsAreTruee()) {
	// 		return true;	
	// 	}

	// 	return false;
	// }

	/**
	 * Put the script in the bottom of HTML <body> tag. This is default behaviour.
	 *
	 * @return $this
	 */
	public function inFooter()
	{
		$this->info['isInFooter'] = true;

		return $this;
	}

	/**
	 * Put the script in HTML <head> tag.
	 *
	 * @return $this
	 */
	public function inHead()
	{
		echo self::WP_EVENTS_WP_ENQUEUE_SCRIPTS;
		$this->info['isInFooter'] = false;

		return $this;
	}

	/**
	 * Specify version of the script.
	 *
	 * @param string|int $version Version of the script.
	 *
	 * @return $this
	 */
	// public function version($version)
	// {
	// 	$this->info['version'] = $version;

	// 	return $this;
	// }

	/**
	 * Add script dependencies.
	 *
	 * @param string|Script $script An instance of NWP\Script or name string of registered-script.
	 *
	 * @return $this
	 */
	// public function dependsOn($script)
	// {
	// 	$isScriptInstance = $script instanceof Script;

	// 	if (empty($script) || !$isScriptInstance && !is_string($script)) {
	// 		throw new InvalidArgumentException(
	// 			sprintf(
	// 				'Expected a script argument supplied to Scripts::dependsOn() to be a string or an instance of NWP\Script, %s given',
	// 				gettype($script)
	// 			)
	// 		);
	// 	}

	// 	$scriptName = $script instanceof Script ? $script->id : $script;
	// 	$this->info['dependencies'][] = $scriptName;

	// 	return $this;
	// }

	/**
	 * Add condition to be checked before actually enqueue script
	 *
	 * @param callable $callback Callback which Boolean return
	 *
	 * @return $this
	 */
	// public function when(callable $callback)
	// {
	// 	$this->info['conditions'][] = $callback;

	// 	return $this;
	// }

	/**
	 * Specify script priority
	 *
	 * @param int $number Number of priority
	 *
	 * @return $this
	 */
	// public function priority(int $number)
	// {
	// 	$this->info['priority'] = $number;

	// 	return $this;
	// }
	
	/**
	 * Do what Array.prototype.every() in JavaScript done
	 *
	 * @return bool
	 */
	// private function isAllConditionsAreTruee()
	// {
	// 	foreach ($this->conditions as $condition) {
	// 		$result = call_user_func($condition);

	// 		if (!$result) {
	// 			return false;
	// 		}
	// 	}

	// 	return true;
	// }
}
