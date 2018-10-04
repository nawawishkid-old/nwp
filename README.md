# I DECIDED TO SUSPEND THIS PROJECT FOR A WHILE...

sorry for incompleteness. :(

## ความตั้งใจ

ผมเขียน `WPFacade` ขึ้นมาเพื่อต้องการให้เขียน WordPress API ได้ง่ายขึ้น อ่านง่าย เข้าใจง่าย แบบที่แค่อ่าน code ก็รู้ได้ว่าผู้เขียนกำลังทำอะไร

## ทำไมต้อง Facade?

ออกตัวก่อนว่าผมไม่ได้เชี่ยวชาญเรื่อง design pattern นะครับ แต่ก็พอจะรู้ว่าสิ่งที่ผมเขียน มันไม่ใช่ Facade pattern เพราะ Facade pattern นั้นจะมีลักษณะของการ bundle classes หรือ API หลาย ๆ อย่างที่เกี่ยวข้องกันเข้ามาไว้ด้วยกัน แล้วทำ API ใหม่ เพื่อซ่อนรายละเอียดต่าง ๆ ของ API เดิมที่ไม่จำเป็นในการใช้งานเอาไว้ ทำให้ง่ายต่อการใช้งานครับ

แต่ `WPFacade` นั้น ไม่ได้ bundle อะไรของ WordPress แค่จับ API ของ WordPress ที่เป็น global function มาเปลี่ยนหน้าตาใหม่ให้มีลักษณะเป็น Object โดยอาศัย Object-oriented programming paradigm ที่ PHP supports อยู่แล้ว เพื่อความ intuitive ในการใช้งานครับ ซึ่งผมเลือกใช้ชื่อ Facade ก็เพราะมันมีจุดประสงค์เดียวกันกับ Facade pattern คือช่วยให้เขียน API นึงได้ง่ายขึ้น แม้ว่า `WPFade` อาจจะได้ทำให้เขียน code ได้สั้นลง หรือทำให้ต้องเขียน code มากขึ้น แต่สิ่งสำคัญคือ เข้าใจง่ายขึ้น (increase readability) ครับผม

## Interfaces

### `RenderableInterface`

Class ต้อง render HTML string ได้

### `EventCollectorInterface`

Class ต้องเพิ่ม event listener ไปยัง event emitter เป้าหมายได้ ซึ่งในกรณีนี้คือ ต้อง listens WordPress events ได้ หรือพูดให้เข้าใจง่ายก็คือ ต้อง `add_action` เพื่อให้ WordPress Core สามารถ `do_action` ของเราได้นั่นเอง (เข้าใจง่ายไหมเนี่ย)

### `EventHandlerInterface`

Class ต้องมี event handler เพื่อให้ event collector นำไปใช้เป็น callback สำหรับ WordPress's `add_action` function ได้

## API

### Admin Page

#### Expected usage:

```php
<?php

$page->title($title)
	->addRenderer($contentCallback1)
	->addRenderers($contentCallback2, $contentCallback3, $contentCallbackN)
	->allow($capability)
	->addMenu($menu1)
	->addMenus($menu2, $menu3, $menuN)
	->addEventCollector($eventCollector)
	->register();
```

**NOTE**: `addMenu`, `addEventCollector`, and `register` methods become optional if page is registered via `AdminMenu` or `AdminSubMenu`.

---

### Menu

### 1. Admin Menu

#### Expected usage:

```php
<?php

$menu = new AdminMenu($id);

$menu->title($title)
	->for($page)
	->addRenderer($menuContentCallback)
	->addEventCollector($eventCollector)
	->register();
```

**NOTE**: `for`, `addEventCollector`, and `register` methods become optional if menu is registered via `AdminPage`.

### 2. Admin SubMenu

#### Expected usage:

```php
<?php

$submenu = new AdminSubMenu($id);

$submenu->title($title)
	->belongsTo($menu)
	->addRenderer($submenuContentCallback)
	->addEventCollector($eventCollector)
	->register();
```

**NOTE**: `belongsto`, `addeventcollector`, and `register` methods become optional if submenu is registered via `adminmenu`.

---

### Settings API

### 1. Settings Section

#### Expected usage:

```php
<?php

$section->title($title)
	->displayOn($page1)
	->displayOns($page2, $page3, $pageN)
	->addField($field1)
	->addFields($field2, $field3, $fieldN)
	->addRenderer($sectionContentCallback)
	->addEventCollector($eventCollector)
	->register();
```

### 2. Settings Field

#### Expected usage:

```php
<?php

$field->title($title)
	->belongsTo($section)
	->addRenderer($fieldContentCallback)
	->addEventCollector($eventCollector)
	->register();
```

__NOTE__: `belongsto`, `addeventcollector`, and `register` methods become optional if field is registered via Section.

---

### EventCollector

#### Expected usage:

```php
<?php

$wpEventCollector->on('event_name', function () { /* do something */ })
	->on('event_name_2', function () { /* do something */ })
	->register();

$wpEventCollector->registerAll($eventHandler1, $eventHandler2);
```

---

## Example

### Add settings section to WordPress built-in settings pages.

```php

```



