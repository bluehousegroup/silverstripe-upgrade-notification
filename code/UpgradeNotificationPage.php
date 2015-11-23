<?php

class UpgradeNotificationPage extends LeftAndMain {

	static $url_segment = "upgrade";
	static $menu_title = '';
	static $menu_icon = '';
	static $menu_priority = 1000;
	static $allowed_actions = array ('upgrade', 'refresh');

	public function init() {
		parent::init();
		Requirements::css('silverstripe-upgrade-notification/css/custom.css');
		Requirements::javascript('silverstripe-upgrade-notification/javascript/custom.js');
	}

	public function getEditForm($id = null, $fields = null) {
		$upgrade_notification = new UpgradeNotification();
		$config = SiteConfig::current_site_config(); 

		$fields = new FieldList();
		$fields->push(new LiteralField("Versions",'<div class="ss-upgrade-content"><h2>Installed Version: '. $upgrade_notification->getInstalledVersion() .'</h2><h2>Latest Version: '. $upgrade_notification->getLatestVersion() .'</h2></div>'));
		if($upgrade_notification->isCurrentVersion())
			$fields->push(new LiteralField("Message",'<div class="message good ss-upgrade-content"><p>' . $this->config()->UpToDateMessage . '</p></div>'));
		else
			$fields->push(new LiteralField("Message",'<div class="message notice ss-upgrade-content"><p>' . $this->config()->OutToDateMessage . '</p></div>'));
		$fields->push(new LiteralField("Status",'<div class="ss-upgrade-content"><p>Last checked: ' . date('F d, Y g:ia', strtotime($upgrade_notification->getVersionTimeStamp())) . '. <a href="admin/upgrade/refresh/" id="ss-version-check" class="ss-ui-button">Check again</a></p></div>'));
		if($upgrade_notification->isCurrentVersion())
			$fields->push(new LiteralField("Content",'<div class="ss-upgrade-content"><p>' . $this->config()->ActionMessageUpToDate . '</p></div>'));
		else
			$fields->push(new LiteralField("Content",'<div class="ss-upgrade-content"><p>' . $this->config()->ActionMessageOutOfDate . ' <a href="mailto:' . $this->config()->TechnicalContactEmail . '?Subject=Upgrade%SilverStripe" target="_top">'.$this->config()->TechnicalContactEmail.'</a>.</p></div>'));

		$actions = new FieldList();
		$form = new Form($this, "EditForm", $fields, $actions);
		$form->addExtraClass('cms-edit-form cms-panel-padded center ' . $this->BaseCSSClasses());
		$form->loadDataFrom($this->request->getVars());

		$this->extend('updateEditForm', $form);

		return $form;
	}

}