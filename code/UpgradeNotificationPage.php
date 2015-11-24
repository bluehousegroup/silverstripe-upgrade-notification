<?php

class UpgradeNotificationPage extends LeftAndMain {

	static $url_segment = "upgrade";
	static $menu_title = '';
	static $menu_icon = '';
	static $menu_priority = 1000;
	static $allowed_actions = array ('upgrade', 'refresh');

	protected $silverstripe_version = null;
	protected $silverstripe_latest_version = null;
	protected $version_time_stamp = null;
	protected $refresh = false;

	public function init() {
		parent::init();
		Requirements::css('silverstripe-upgrade-notification/css/custom.css');
		Requirements::javascript('silverstripe-upgrade-notification/javascript/custom.js');
		$slug_parts = explode('/', $_SERVER['REQUEST_URI']);
		if(count($slug_parts) > 3){
			if($slug_parts[1] == 'admin' && $slug_parts[2] == 'upgrade' && $slug_parts[3] == 'refresh')
				$this->refresh = true;
		}
	}

	public function getEditForm($id = null, $fields = null) {
		$config = SiteConfig::current_site_config(); 

		$fields = new FieldList();
		$fields->push(new LiteralField("Versions",'<div class="ss-upgrade-content"><h2>Installed Version: '. $this->getInstalledVersion() .'</h2><h2>Latest Version: '. $this->getLatestVersion() .'</h2></div>'));
		if($this->isCurrentVersion())
			$fields->push(new LiteralField("Message",'<div class="message good ss-upgrade-content"><p>' . $this->config()->UpToDateMessage . '</p></div>'));
		else
			$fields->push(new LiteralField("Message",'<div class="message notice ss-upgrade-content"><p>' . $this->config()->OutToDateMessage . '</p></div>'));
		$fields->push(new LiteralField("Status",'<div class="ss-upgrade-content"><p>Last checked: ' . date('F d, Y g:ia', strtotime($this->getVersionTimeStamp())) . '. <a href="admin/upgrade/refresh/" id="ss-version-check" class="ss-ui-button">Check again</a></p></div>'));
		if($this->isCurrentVersion())
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

	public function getInstalledVersion() {
		if(!Session::get('silverstripe_version') || $this->refresh) {
			if(!$this->silverstripe_version) {
				if(file_exists(__DIR__ . '/../../composer.lock')) {
					$composer_array = json_decode(file_get_contents(__DIR__ . '/../../composer.lock'), true);

					if($composer_array && isset($composer_array['packages'])) {
						foreach ($composer_array['packages'] as $composer_item) {
							if($composer_item['name'] == 'silverstripe/framework') {
								$this->silverstripe_version = $composer_item['version'];
								Session::set('silverstripe_version', $composer_item['version']);
							}
						}
					}
				}
			}
		} else {
			$this->silverstripe_version = Session::get('silverstripe_version');
		}

		//uncomment and set to latest version to test success
		//$this->silverstripe_version = '3.2.0';

		return $this->silverstripe_version;

		// //Alterntive method to use built in detection
		// $LeftAndMain = new LeftAndMain();
		// return $LeftAndMain->CMSVersion();
	}

	public function getLatestVersion() {
		if(!Session::get('silverstripe_latest_version') || $this->refresh) {
			if(!$this->silverstripe_latest_version) {
				$package_feed = file_get_contents('https://packagist.org/feeds/package.silverstripe/framework.rss');
				if($package_feed) {
					$xml = simplexml_load_string($package_feed);
					$json = json_encode($xml);
					$package_array = json_decode($json,TRUE);

					if($package_array && isset($package_array['channel']['item'][0]['title'])) {
						$this->silverstripe_latest_version = preg_replace('@[^0-9\.]+@i', '', $package_array['channel']['item'][0]['title']);
						Session::set('silverstripe_latest_version', $this->silverstripe_latest_version);
					}
				}
			}
		} else {
			$this->silverstripe_latest_version = Session::get('silverstripe_latest_version');
		}

		return $this->silverstripe_latest_version;
	}

	public function getVersionTimeStamp() {
		if(!Session::get('version_time_stamp') || $this->refresh) {
			if(!$this->version_time_stamp) {
				$this->version_time_stamp = date('Y-m-d H:i:s');
				Session::set('version_time_stamp', $this->version_time_stamp);
			}
		} else {
			$this->version_time_stamp = Session::get('version_time_stamp');
		}

		return $this->version_time_stamp;
	}

	public function getUpgradeMessage() {
		$upgrade_message = 'SilverStripe Version: unknown';
		if($this->getInstalledVersion() && $this->getLatestVersion()) {
			if($this->getInstalledVersion() < $this->getLatestVersion())
				$upgrade_message = ($this->config()->CustomMenuTextOutToDate) ? $this->config()->CustomMenuTextOutToDate:'SilverStripe ' . $this->getInstalledVersion() . ' (' . $this->getLatestVersion() . ')';
			else
				$upgrade_message = ($this->config()->CustomMenuTextUpToDate) ? $this->config()->CustomMenuTextUpToDate:'SilverStripe ' . $this->getInstalledVersion() . ' (current)';
		}

		return $upgrade_message;
	}

	public function getCode() {
		$code = 'upgrade-notification-alert';
		if($this->getInstalledVersion() && $this->getLatestVersion()) {
			if($this->getInstalledVersion() < $this->getLatestVersion())
				$code = 'upgrade-notification-alert';
			else
				$code = 'upgrade-notification-success';
		}

		return $code;
	}

	public function isCurrentVersion() {
		if($this->getInstalledVersion() && $this->getLatestVersion()) {
			if($this->getInstalledVersion() < $this->getLatestVersion())
				return false;
			else
				return true;
		}

		return false;
	}
}