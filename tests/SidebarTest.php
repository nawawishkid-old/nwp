<?php

use PHPUnit\Framework\TestCase;
use NWP\Facade\Sidebar;

class SidebarTest extends TestCase
{
	private $id = 'sidebar_id';
	private $name = 'Sidebar Name';

	public function getInstance()
	{
		return new Sidebar($this->id, $this->name);
	}

	public function testCanBeInstantiatedWithIdAndNameParemeters() : void
	{
		$sidebar = new Sidebar($this->id, $this->name);

		$this->assertSame($this->id, $sidebar->id);
		$this->assertSame($this->name, $sidebar->name);
	}

	public function testCanBeInstantiatedWithAdditionalOptionsParemeter() : void
	{
		$afterTitle = 'hahaha';
		$options = [
			'after_title' => $afterTitle 
		];
		$sidebar = new Sidebar($this->id, $this->name, $options);

		$this->assertSame($afterTitle, $sidebar->after_title);
	}

	public function testCanSetBeforeWidget() : void
	{
		$sidebar = $this->getInstance();
		$beforeWidget = 'class';

		$sidebar->beforeWidget($beforeWidget);

		$this->assertSame($beforeWidget, $sidebar->before_widget);
	}

	public function testCanSetAfterWidget() : void
	{
		$sidebar = $this->getInstance();
		$afterWidget = 'class';

		$sidebar->afterWidget($afterWidget);

		$this->assertSame($afterWidget, $sidebar->after_widget);
	}

	public function testCanSetBeforeTitle() : void
	{
		$sidebar = $this->getInstance();
		$beforeTitle = 'class';

		$sidebar->beforeTitle($beforeTitle);

		$this->assertSame($beforeTitle, $sidebar->before_title);
	}

	public function testCanSetAfterTitle() : void
	{
		$sidebar = $this->getInstance();
		$afterTitle = 'class';

		$sidebar->afterTitle($afterTitle);

		$this->assertSame($afterTitle, $sidebar->after_title);
	}

	public function testCanSetClassName() : void
	{
		$sidebar = $this->getInstance();
		$className = 'class';

		$sidebar->className($className);

		$this->assertSame($className, $sidebar->class);
	}

	public function testCanSetDescription() : void
	{
		$sidebar = $this->getInstance();
		$description = 'description';

		$sidebar->description($description);

		$this->assertSame($description, $sidebar->description);
	}
}
