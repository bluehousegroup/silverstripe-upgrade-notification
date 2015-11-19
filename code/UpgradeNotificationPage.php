<?php

class UpgradeNotificationPage extends LeftAndMain {

	static $url_segment = "upgrade";
	static $menu_title = UpgradeMenuTitle;
	static $menu_icon = UpgradeMenuIcon;
	static $menu_priority = 1000;
	static $allowed_actions = array ('upgrade');

	public function init() {
		parent::init();
		Requirements::css(UPGRADE_NOTE_DIR . '/css/custom.css');
		Requirements::javascript(UPGRADE_NOTE_DIR . '/javascript/custom.js');
	}

	public function getEditForm($id = null, $fields = null) {

		$fields = new FieldList();
		$fields->push(new LiteralField("Versions",'<div class="ss-upgrade-content"><h2>Installed Version: '. UpgradeMenuTitle .'</h2><h2>Latest Version: '. UpgradeMenuTitle .'</h2></div>'));
		$fields->push(new LiteralField("Message",'<div class="message notice ss-upgrade-content"><p>Your SilverStripe is out of date.</p></div>'));
		$fields->push(new LiteralField("Status",'<div class="ss-upgrade-content"><p>Last checked: November 12, 2015. <button id="ss-version-check" class="ss-ui-button" type="button">Check again</button></p></div>'));
		$fields->push(new LiteralField("Content",'<div class="ss-upgrade-content"><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quaerat voluptas pariatur cumque? Sed recusandae totam fugit reprehenderit vel consequuntur eos dolor nihil. Expedita, impedit, repellat.</p><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quaerat voluptas pariatur cumque? Sed recusandae totam fugit reprehenderit vel consequuntur eos dolor nihil. Expedita, impedit, repellat.</p></div>'));

		$actions = new FieldList();
		$form = new Form($this, "EditForm", $fields, $actions);
		$form->addExtraClass('cms-edit-form cms-panel-padded center ' . $this->BaseCSSClasses());
		$form->loadDataFrom($this->request->getVars());

		$this->extend('updateEditForm', $form);

		return $form;
	}

}