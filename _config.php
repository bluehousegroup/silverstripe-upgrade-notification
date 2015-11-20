<?php

LeftAndMain::require_css(basename(__DIR__) . '/css/custom.css');
Object::add_extension('SiteConfig','SiteConfigUpgradeNotification');

define('UPGRADE_NOTE_DIR',basename(dirname(__FILE__)));

// Define a constant so we can dynamically set menu title
define('UpgradeMenuTitle', UpgradeNotification::getUpgradeMessage());
define('UpgradeMenuIcon', UpgradeNotification::getIcon());