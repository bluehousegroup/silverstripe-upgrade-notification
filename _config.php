<?php

LeftAndMain::require_css(basename(__DIR__) . '/css/custom.css');
LeftAndMain::require_javascript(basename(__DIR__) . '/javascript/customjs.js');

//Define a constant so we can dynamically set menu title
define('UpgradeMenuTitle', UpgradeNotification::getUpgradeMessage());
define('UpgradeMenuIcon', UpgradeNotification::getIcon());