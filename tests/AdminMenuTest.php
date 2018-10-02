<?php

use PHPUnit\Framework\TestCase;
use NWP\Facade\AdminMenu;
use NWP\Facade\AdminPage;

final class AdminMenuTest extends TestCase
{
	private $menuId = 'menu_id';
	private $menuTitle = 'Menu title';
	private $menuIconUrl = 'dashicons-admin-users';
	private $menuPosition = 3;

	public function getInstance()
	{
		return new AdminMenu($this->menuId, $this->menuTitle);
	}

	public function testCanBeCreatedWithIdAndNameParameter() : void
	{
		$menu = $this->getInstance();

		$this->assertSame($this->menuId, $menu->id);
	}

	public function testCanSetIconUrl() : void
	{
		$menu = $this->getInstance();

		$menu->iconUrl($this->menuIconUrl);

		$this->assertSame($this->menuIconUrl, $menu->iconUrl);
	}

	public function testCanSetPosition() : void
	{
		$menu = $this->getInstance();

		$menu->position($this->menuPosition);

		$this->assertSame($this->menuPosition, $menu->position);
	}

	public function testCanAddAdminPageUsingAdminPageInstanceAsAnArgument() : void
	{
		// arrange
		$menu = $this->getInstance();
		$pageTitle = 'Page Title';
		$pageCapability = 'adminstrator';
		$pageContentRenderer = function() {};
		$adminPageMock = $this->getMockBuilder(AdminPage::class)
			->setConstructorArgs([$pageTitle])
			->getMock();
		$valueMap = [
			['title', $pageTitle],
			['capability', $pageCapability],
			['contentRenderer', $pageContentRenderer]
		];

		$adminPageMock
			->expects($this->exactly(3))
			->method('__get')
			->will($this->returnValueMap($valueMap));

		// act	
		$menu->linkTo($adminPageMock);

		// assert
		$this->assertSame($pageTitle, $menu->pageTitle);
		$this->assertSame($pageCapability, $menu->capability);
		$this->assertSame($pageContentRenderer, $menu->pageContentRenderer);
	}
}
