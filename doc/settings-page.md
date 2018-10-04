# Settings page

### เพิ่ม Settings section ไปยัง settings page เดิม ๆ ของ WordPress 

#### Variables่

```php

// Our variables
$groupId = 'settings_group_1';
$groupTitle = 'Settings group #1';
$groupContentCallback = function () { echo 'Group content.'; };

$field1Id = 'settings_field_1';
$field1Title = 'Settings Field #1';

$field2Id = 'settings_field_2';
$field2Title = 'Settings Field #2';

$fieldContentCallback = function () { echo '<input type="text" />'; };


$pageId = 'reading'; // WordPress's reading settings page.

```

#### Using WordPress API.

```php

<?php

// WordPress API

add_action('admin_init', function () {
	add_settings_section(
		$groupId,	
		$groupTItle,	
		$groupContentCallback,	
		$pageId	
	);

	add_settings_field(
		$field1Id,	
		$field1Title,	
		$fieldContentCallback,	
		$pageId,	
		$groupId	
	);

	add_settings_field(
		$field2Id,	
		$field2Title,	
		$fieldContentCallback,	
		$pageId,	
		$groupId	
	);

	register_setting($pageId, $field1Id);
	register_setting($pageId, $field2Id);
});

```

#### Using WPFacade API

```php

<?php

use WPFacade\WPEventCollector;
use WPFacade\AdminSettingsSection;
use WPFacade\AdminSettingsField;

$wpEventCollector = new WPEventCollector();
$group = new AdminSettingsGroup($groupId);
$field1 = new AdminSettingsField($field1Id);
$field2 = new AdminSettingsField($field2Id); 

$field1->title($field1Title)
	->addRenderer($fieldContentCallback);
$field2->title($field2Title)
	->addRenderer($fieldContentCallback);

$group->title($groupTitle)
	->addRenderer($groupContentCallback)
	->addFields($field1, $field2)
	->displayOn($pageId)
	->addEventCollector($wpEventCollector)
	->register();

```

หรือถ้าจำได้ว่า Settings group กับ Settings field ต้องการ arguments อะไรบ้าง เราก็สามารถใส่ arguments เหล่านั้นไปตอน instantiate class ได้เลยครับ

```php

<?php

use WPFacade\WPEventCollector;
use WPFacade\AdminSettingsSection;
use WPFacade\AdminSettingsField;

$wpEventCollector = new WPEventCollector();

$field1 = new AdminSettingsField($field1Id, $field1Title, $fieldContentCallback);
$field2 = new AdminSettingsField($field2Id, $field2Title, $fieldContentCallback); 

$group = new AdminSettingsGroup($groupId, $groupTitle, [$field1Id, $field2Id], $groupContentCallback);

$group->displayOn($pageId)
	->addEventCollector($wpEventCollector)
	->register();

```

## เพิ่ม Settings section ไปยัง custom page ที่ยังไม่มี HTML Form

เราต้องเพิ่ม admin page ใหม่ก่อน แล้วค่อย inject form section เข้าไปในเพจ

### ใช้ WordPress API

```php

<?php



```

### ใช้ WPFacade API

```php

<?php

use WPFacade\WPEventCollector;
use WPFacade\AdminMenu;
use WPFacade\AdminPage;
use WPFacade\AdminSettingsGroup;
use WPFacade\AdminSettingsField;
use WPFacade\AdminSettingsForm;

$wpEventCollector = new WPEventCollector();
$menu = new AdminMenu($menuId);
$page = new AdminPage($pageId);
$group = new AdminSettingsGroup();
$field = new AdminSettingsField();
$form = new AdminSettingsForm();

$field->title($field1Title)
	->addRenderer($fieldContentCallback);

$group->title($groupTitle)
	->addRenderer($groupContentCallback)
	->addField($field1)
	->displayOn($page)
	->addEventCollector($wpEventCollector)
	->register();

$page->title($pageTitle)
	->addRenderer($pageContentCallback)
	->addRenderer($form);

$form->for($page)
	->method('POST')
	->url('option.php')
	->addGroup($group)
	->plainHtml(function () { echo '<form />'; });

$menu->title($menuTitle)
	->linkTo($page)
	->addEventCollector($wpEventCollector)
	->register();

```





