# Design
```php
<?php

$wpEventAgent = new WPEventAgent($wordpress);
$wpEventAgent->on('after_setup_theme', function () {});

// Classes that use EventAgent must implement EventHandler interface.

interface EventAgent
{
    public function on(string $eventName, EventHandler $eventHandler) : void
}

interface Registerable
{
    public function addEventAgent(EventAgent $eventAgent) : void

    public function addEventHandler(EventHandler $eventHandler) : void

    public function register() : void // supplies eventHandler to eventAgent as an argument.
}

interface EventHandler
{
    public function eventHandler() : void
}

$registerable = new RegisterableClass();
$eventHandler = new EventHandlerClass();

$registerable->addEventAgent($wpEventAgent)
    ->addEventHandler($eventHandler)
    ->register();

$box0 = new MetaBox();
$box0->addEventAgent($wpEventAgent)
    ->addContentRenderer(function () { echo '<h1>Content!</h1>'; })
    ->register();

$box1 = new MetaBox('metabox_id', 'MetaBox Title');
$admin = AdminFactory::create();
$customPage = new AdminPage(...);
$customMenu = new AdminMenu(...);

$box1->addContentRenderer(function () { echo '<h1>Content!</h1>'; });

$admin->getAdminDefaultPage('dashboard') // return new AdminPage();
    ->addMetaBox($box1)
    ->toRight()
    ->important();

$customPage->addMetaBox($box1);

$customMenu->linkTo($customPage);

$admin->addAdminMenu($customMenu);

```
