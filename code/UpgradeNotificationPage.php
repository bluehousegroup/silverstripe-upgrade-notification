<?php

class UpgradeNotificationPage extends LeftAndMain {

	static $url_segment = "upgrade";
	static $menu_title = UpgradeMenuTitle;
	static $menu_icon = UpgradeMenuIcon;
	static $menu_priority = 1000;
	static $allowed_actions = array ('upgrade');

	public function index($request) {
		return $this->renderWith('UpgradeNotificationPage');
	}

	public function init() {
		parent::init();
	}
}