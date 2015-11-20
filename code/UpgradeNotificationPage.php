<?php

class UpgradeNotificationPage extends LeftAndMain {

	static $url_segment = "upgrade";
	static $menu_title = UpgradeMenuTitle;
	static $menu_icon = UpgradeMenuIcon;
	static $menu_priority = 1000;
	static $allowed_actions = array ('upgrade', 'refresh');

	public function init() {
		parent::init();
		Requirements::css(UPGRADE_NOTE_DIR . '/css/custom.css');
		Requirements::javascript(UPGRADE_NOTE_DIR . '/javascript/custom.js');
	}

	public function getEditForm($id = null, $fields = null) {
		$upgrade_notification = new UpgradeNotification();
		$config = SiteConfig::current_site_config(); 

		$fields = new FieldList();
		$fields->push(new LiteralField("Versions",'<div class="ss-upgrade-content"><h2>Installed Version: '. $upgrade_notification->getInstalledVersion() .'</h2><h2>Latest Version: '. $upgrade_notification->getLatestVersion() .'</h2></div>'));
		if($upgrade_notification->isCurrentVersion())
			$fields->push(new LiteralField("Message",'<div class="message good ss-upgrade-content"><p>Your SilverStripe is up to date.</p></div>'));
		else
			$fields->push(new LiteralField("Message",'<div class="message notice ss-upgrade-content"><p>Your SilverStripe is out of date.</p></div>'));
		$fields->push(new LiteralField("Status",'<div class="ss-upgrade-content"><p>Last checked: ' . date('F d, Y g:ia', strtotime($upgrade_notification->getVersionTimeStamp())) . '. <a href="admin/upgrade/refresh/" id="ss-version-check" class="ss-ui-button">Check again</a></p></div>'));
		if($upgrade_notification->isCurrentVersion())
			$fields->push(new LiteralField("Content",'<div class="ss-upgrade-content"><p>Congratulations, you are on the most current version of SilverStripe! Woohoo!</p></div>'));
		else
			$fields->push(new LiteralField("Content",'<div class="ss-upgrade-content"><p>Web maintenance is essential to keeping your site performance optimized and secure for you and your visitors. Given the custom features of your site, we would suggest contacting your account manager to discuss what an upgrade will entail <a href="mailto:'.$config->TechnicalContactEmail.'?Subject=Upgrade%SilverStripe" target="_top">'.$config->TechnicalContactEmail.'</a>.</p></div>'));

		$actions = new FieldList();
		$form = new Form($this, "EditForm", $fields, $actions);
		$form->addExtraClass('cms-edit-form cms-panel-padded center ' . $this->BaseCSSClasses());
		$form->loadDataFrom($this->request->getVars());

		$this->extend('updateEditForm', $form);

		return $form;
	}

}