<?php

namespace NWP;

use NWP\WordPressCouple;
use \Exception;
use \InvalidArgumentException;

class BaseFrontendTag extends WordPressCouple
{
	const TAG_TYPE_STYLE = 'css';

	const TAG_TYPE_SCRIPT = 'js';
	
	const WP_FUNCTIONS_WP_ENQUEUE_STYLE = 'wp_enqueue_style';

	const WP_FUNCTIONS_WP_ENQUEUE_SCRIPT = 'wp_enqueue_script';

	protected $info = [
		'id' => '',
		'src' => '',
		'dependencies' => [],
		'version' => '',
		'priority' => null,
		'conditions' => []
	];

	private $privateInfo = [
		'type' => '',
		'wpFunctionName' => ''
	];

	public function __construct(string $id, string $src, string $type)
	{
		if ($type !== self::TAG_TYPE_SCRIPT && $type !== self::TAG_TYPE_STYLE) {
			throw new InvalidArgumentException(
				sprintf(
					'Expected tag type supplied to NWP\BaseFrontenTag::__construct() to be %s or %s, %s given',
					self::TAG_TYPE_STYLE,
					self::TAG_TYPE_SCRIPT,
					$type
				)
			);
		}	

		$this->info['id'] = $id;
		$this->info['src'] = $src;
		$this->privateInfo['type'] = $type;
		$this->privateInfo['wpFunctionName'] = $type === self::TAG_TYPE_SCRIPT 
			? self::WP_FUNCTIONS_WP_ENQUEUE_SCRIPT 
			: self::WP_FUNCTIONS_WP_ENQUEUE_STYLE;
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
	 * WP's wp_enqueue_script wrapper
	 */
	protected function wpEnqueueTag(string $type, ...$args)
	{
		$wpFunction = self::$wpFunctions[$this->privateInfo['wpFunctionName']];
	
		if (empty($wpFunction)) {
			throw new Exception("WordPress function for BaseFrontendTag::wpEnqueueTag() has not been assigned.");
		}

		return call_user_func_array($wpFunction, $args);
	}

	/**
	 * Call WP's add_action
	 */
	public function register()
	{
		$this->wpAddAction(
			self::WP_EVENTS_WP_ENQUEUE_SCRIPTS, 
			function() { $this->enqueue(); }, 
			$this->priority 
		);
	}

	/**
	 * Enqueue callback for WP's add_action
	 *
	 * @see https://developer.wordpress.org/reference/functions/wp_enqueue_script/
	 */
	private function enqueue()
	{
		if ($this->shouldBeEnqueued()) {
			$this->wpEnqueueTag(
				$this->id, 
				$this->src,
				$this->dependencies,
				$this->version,
				$this->isInFooter
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
