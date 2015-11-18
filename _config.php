<?php

LeftAndMain::require_css(basename(__DIR__) . '/css/custom.css');
LeftAndMain::require_javascript(basename(__DIR__) . '/javascript/customjs.js');

$menuTitle = UpgradeNotification::getUpgradeMessage();
$code = UpgradeNotification::getCode();

CMSMenu::add_menu_item(
	$code, //'upgrade-notification'
	$menuTitle,
	'#', //$url
	null, //$controllerClass
	1000 //$priority
);