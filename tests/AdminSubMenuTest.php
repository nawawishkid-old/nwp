<?php

use PHPUnit\Framework\TestCase;
use NWP\Facade\AdminMenu;
use NWP\Facade\AdminSubMenu;
use NWP\Facade\AdminPage;

final class AdminSubMenuTest extends TestCase
{
	private $submenuId = 'submenu_id';
	private $submenuTitle = 'Submenu Title';

	public function getInstance()
	{
		return new AdminSubMenu($this->submenuId, $this->submenuTitle);
	}

	public function testCanBeCreatedWithIdAndNameParameter() : void
	{
		$submenu = $this->getInstance();

		$this->assertSame($this->submenuId, $submenu->id);
	}

	public function testCanAddParentMenuUsingStringAsAnArgument() : void
	{
		$submenu = $this->getInstance();
		$parent = 'parent_id';

		$submenu->parent($parent);

		$this->assertSame($parent, $submenu->parentSlug);
	}
	
	public function testCanAddParentMenuUsingAdminMenuInstanceAsAnArgument() : void
	{
		// arrange
		$submenu = $this->getInstance();
		$menuId = 'menu_id';
		$menuTitle = 'Menu Title';
		$parentMenuMock = $this->getMockBuilder(AdminMenu::class)
			->setConstructorArgs([$menuId, $menuTitle])
			->getMock();

		$parentMenuMock->expects($this->once())
			->method('__get')
			->with($this->equalTo('id'))
			->willReturn($menuId);

		// act
		$submenu->parent($parentMenuMock);

		// assert
		$this->assertSame($menuId, $submenu->parentSlug);
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function testThrowsExceptionWhenParentMenuIsInvalidArgument() : void
	{
		$this->expectException(InvalidArgumentException::class);

		$submenu = $this->getInstance();
		$parent = [];

		$submenu->parent($parent);
	}

	public function testCanAddAdminPageUsingAdminPageInstanceAsAnArgument() : void
	{
		// arrange
		$submenu = $this->getInstance();
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
		$submenu->linkTo($adminPageMock);

		// assert
		$this->assertSame($pageTitle, $submenu->pageTitle);
		$this->assertSame($pageCapability, $submenu->capability);
		$this->assertSame($pageContentRenderer, $submenu->pageContentRenderer);
	}
}
