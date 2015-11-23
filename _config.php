<?php

LeftAndMain::require_css(basename(__DIR__) . '/css/custom.css');
Object::add_extension('SiteConfig','SiteConfigUpgradeNotification');

CMSMenu::remove_menu_item('UpgradeNotificationPage'); 

$upgrade_notification = new UpgradeNotification();
$upgrade_notification_page = new UpgradeNotificationPage();

if(!$upgrade_notification->isCurrentVersion() || ($upgrade_notification->isCurrentVersion() && $upgrade_notification_page->config()->ShowMenuItemWhenCurrent))
	CMSMenu::add_menu_item($upgrade_notification->getCode(), $upgrade_notification->getUpgradeMessage(), 'admin/upgrade/', null, 1000);