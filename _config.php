<?php

LeftAndMain::require_css(basename(__DIR__) . '/css/custom.css');
CMSMenu::remove_menu_item('UpgradeNotificationPage'); 
$upgrade_notification_page = new UpgradeNotificationPage();
if(!$upgrade_notification_page->isCurrentVersion() || ($upgrade_notification_page->isCurrentVersion() && $upgrade_notification_page->config()->ShowMenuItemWhenCurrent)) {
	CMSMenu::add_menu_item($upgrade_notification_page->getCode(), $upgrade_notification_page->getUpgradeMessage(), 'admin/upgrade/', null, 1000);
}
