<?php

LeftAndMain::require_css(basename(__DIR__) . '/css/custom.css');
Object::add_extension('SiteConfig','SiteConfigUpgradeNotification');

CMSMenu::remove_menu_item('UpgradeNotificationPage'); 

$menuTitle = UpgradeNotification::getUpgradeMessage();
$code = UpgradeNotification::getCode();

CMSMenu::add_menu_item($code, $menuTitle, 'admin/upgrade/', null, 1000);