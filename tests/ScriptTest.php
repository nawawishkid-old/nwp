<?php

use PHPUnit\Framework\TestCase;
use NWP\Script;

final class ScriptTest extends TestCase
{
	private function getInstance() : Script
	{
		return new Script('id', 'src');
	}

	public function testCanBeCreatedUsingIdAndSrc() : void
	{
		$this->assertInstanceOf(
			Script::class,
			new Script('id', 'abc')
		);
	}

	public function testCanAddVersionWithString() : void
	{
		$script  = $this->getInstance(); 
		$version = '3.7';

		$script->version($version);

		$this->assertSame($version, $script->version);
	}

	public function testCanAddVersionWithInt() : void
	{
		$script  = $this->getInstance(); 
		$version = 3.7;

		$script->version($version);

		$this->assertSame($version, $script->version);
	}

	public function testCanBeSpecifiedToBeInFooter() : void
	{
		$script = $this->getInstance();

		$script->inFooter();

		$this->assertTrue($script->isInFooter);
	}

	public function testCanBeSpecifiedToBeInHead() : void
	{
		$script = $this->getInstance();

		$script->inHead();

		$this->assertFalse($script->isInFooter);
	}

	public function testCanAddDependencyWithString() : void
	{
		$script = $this->getInstance();
		$otherScript = 'other_script';

		$script->dependsOn($otherScript);

		$this->assertSame($otherScript, $script->dependencies[0]);
	}

	public function testCanAddDependencyWithScriptInstance() : void
	{
		$script = $this->getInstance();
		$otherScript = new Script('other_script', 'src');

		$script->dependsOn($otherScript);

		$this->assertSame($otherScript->id, $script->dependencies[0]);
	}

	/**
	 * Not yet fully covered all possibilities.
	 */
	public function testCanAddDependencyOnlyWithStringOrScriptInstance() : void
	{
		$this->expectException(InvalidArgumentException::class);

		$script = $this->getInstance();
		
		$script->dependsOn('');
	}

	public function testCanSetPriority() : void
	{
		$script = $this->getInstance();
		$priority = 10;

		$script->priority($priority);

		$this->assertSame($priority, $script->priority);
	}

	public function testCanAddEnqueueCondition() : void
	{
		$script = $this->getInstance();

		$script->when(function() {});

		$this->assertTrue(is_callable($script->conditions[0]));
	}

	/**
	 * Not complete, can't figure out a way to test this case.
	 */
	// public function testCanBeEnqueuedWhenAllConditionsAreTrue() : void
	// {
	// 	$script = $this->getInstance();

	// 	foreach (range(0, 10) as $range) {
	// 		$script->when(function () { return true; });
	// 	}

	// }

	// public function testCanAddWordPressFunction() : void
	// {
	// 	Script::addWPFunction(function ($x) { return $x; });

	// 	$script = $this->getInstance();

	// }	
}
