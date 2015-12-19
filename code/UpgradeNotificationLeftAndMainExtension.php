<?php

class UpgradeNotificationLeftAndMainExtension extends LeftAndMainExtension
{

    public function init()
    {
        parent::init();
        Requirements::css('silverstripe-upgrade-notification/css/custom.css');

        CMSMenu::remove_menu_item('UpgradeNotificationPage');
        $upgrade_notification_page = new UpgradeNotificationPage();
        if (!$upgrade_notification_page->isCurrentVersion() || ($upgrade_notification_page->isCurrentVersion() && $upgrade_notification_page->config()->ShowMenuItemWhenCurrent)) {
            CMSMenu::add_menu_item($upgrade_notification_page->getCode(), $upgrade_notification_page->getUpgradeMessage(), 'admin/upgrade/', null, 1000);
        }
    }
}
