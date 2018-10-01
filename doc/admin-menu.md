# Admin menu

## ตัวอย่างการใช้งาน

```php
<?php

use NWP\Facade\AdminPage;
use NWP\Facade\AdminMenu;
use NWP\Facade\AdminSubMenu;

$menuPage = new AdminPage('Menu Page Title');
$submenuPage = new AdminPage('SubMenu Page Title');

$menu = new AdminMenu('menu_id', 'Menu Title');
$submenu = new AdminSubMenu('submenu_id', 'SubMenu Title');

$menuPage
    ->allow('administrator') // อนุญาตให้ user ระดับ admin ขึ้นไปเท่านั้นที่จะเข้าถึึงหน้านี้ได้
    ->addContentRenderer(function() { echo '<h1>Hello, world!</h1>'; }); // ใส่ function สำหรับ render HTML page
$submenuPage
    ->allow('administrator')
    ->addContentRenderer(function() { echo "<h1>Hi! I'm subpage!</h1>"; });

$menu
    ->iconUrl('dashicons-admin-users') // ใส่ icon รูปคนให้เมนู
    ->position(1) // แสดงเมนูนี้เป็นลำดับแรก
    ->linkTo($menuPage) // บอกว่า menu ลิงก์ไปยัง menuPage นะ
    ->register(); // เพิ่มเมนูไปยัง menu panel
$submenu
    ->parent($menu) // ระบุ parent ของ submenu นี้
    ->linkTo($submenuPage)
    ->register();

```

## Class ที่เกี่ยวข้อง

- `NWP\Facade\AdminPage`
- `NWP\Facade\AdminMenu`
- `NWP\Facade\AdminSubMenu`
