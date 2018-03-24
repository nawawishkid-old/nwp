# WordPress API in OOP way.

## WordPress API
### Settings API
Allows admin pages containing settings forms to be managed semi-automatically. It lets you define settings pages, sections within those pages and fields within the sections. [More](https://codex.wordpress.org/Settings_API)
### Options API
A simple and standardized way of storing data in the database. The API makes it easy to create, access, update, and delete options. All the data is stored in the wp_options table under a given custom name. [More](https://codex.wordpress.org/Options_API)
### Widgets API
Widgets were originally designed to provide a simple and easy-to-use way of giving design and structure control of the WordPress Theme to the user, which is now available on properly "widgetized" WordPress Themes to include the header, footer, and elsewhere in the WordPress design and structure. [More](https://codex.wordpress.org/Widgets_API)

## Expected usage
```php
use NWP\Admin;
use NWP\Page;
use NWP\ContentCallback;
use NWP\Widget;

$admin = new Admin();
$my_widget = new Widget( 'base_id', 'Widget Name', 'Widget description');
$my_page = new Page( 'Title', 'slug', 'page_content' )
				->addSubPage( 'Title', 'slug', 'subpage_content' ) 
				// Optional. Add specific-page style and script
				->addStyle( 'slug', 'path/to/file.css' )
				->addStyles( [
					['slug', 'path/to/file.css'],
					['slug-2', 'path/to/file2.css']
				] )
				->addScript( 'slug', 'path/to/file.js' )

$admin->addStyle( 'my-admin-css', 'path/to/file.css' ) // the path will append to theme directory path
		->addScript( 'my-admin-js', 'path/to/file.js' )
		->addPage( $my_page )
		->hidePages( ['edit.php', 'upload.php', 'themes.php'] )
		->addDashboardWidget( 'Title', 'slug', 'dashboard_widget_content' )
		->addWidget( $my_widget );

$admin->build();
```

### The widget API is not available due to the bug I still haven't found a way to fix it yet. :(