<?php

class UpgradeNotification extends DataExtension
{
	protected $silverstripe_version = null;
	protected $silverstripe_latest_version = null;
	protected $version_time_stamp = null;
	protected $refresh = false;

	function __construct() {
		parent::__construct();
		$slug_parts = explode('/', $_SERVER['REQUEST_URI']);
		if(count($slug_parts) > 3){
			if($slug_parts[1] == 'admin' && $slug_parts[2] == 'upgrade' && $slug_parts[3] == 'refresh')
				$this->refresh = true;
		}
	}

	public function getInstalledVersion()
	{
		if(!Session::get('silverstripe_version') || $this->refresh)
		{
			// Parse out the installed version from composer.lock
			if(!$this->silverstripe_version)
			{
				if(file_exists(__DIR__ . '/../../composer.lock'))
				{
					$composer_array = json_decode(file_get_contents(__DIR__ . '/../../composer.lock'), true);

					if($composer_array && isset($composer_array['packages']))
					{
						foreach ($composer_array['packages'] as $composer_item) 
						{
							if($composer_item['name'] == 'silverstripe/framework')
							{
								$this->silverstripe_version = $composer_item['version'];
								Session::set('silverstripe_version', $composer_item['version']);
							}
						}
					}
				}
			}
		}
		else
		{
			$this->silverstripe_version = Session::get('silverstripe_version');
		}

		//uncomment and set to latest version to test success
		//$this->silverstripe_version = '3.2.1';

		return $this->silverstripe_version;

		// //Alterntive method to use built in detection
		// $LeftAndMain = new LeftAndMain();
		// return $LeftAndMain->CMSVersion();
	}

	public function getLatestVersion()
	{
		if(!Session::get('silverstripe_latest_version') || $this->refresh)
		{
			// Parse out latest version from packagist feed
			if(!$this->silverstripe_latest_version)
			{
				$package_feed = file_get_contents('https://packagist.org/feeds/package.silverstripe/framework.rss');
				if($package_feed)
				{
					$xml = simplexml_load_string($package_feed);
					$json = json_encode($xml);
					$package_array = json_decode($json,TRUE);

					if($package_array && isset($package_array['channel']['item'][0]['title']))
					{
						$this->silverstripe_latest_version = preg_replace('@[^0-9\.]+@i', '', $package_array['channel']['item'][0]['title']);
						Session::set('silverstripe_latest_version', $this->silverstripe_latest_version);
					}
				}
			}
		}
		else
		{
			$this->silverstripe_latest_version = Session::get('silverstripe_latest_version');
		}

		return $this->silverstripe_latest_version;
	}

	public function getVersionTimeStamp()
	{
		if(!Session::get('version_time_stamp') || $this->refresh)
		{
			// Parse out latest version from packagist feed
			if(!$this->version_time_stamp)
			{
				$this->version_time_stamp = date('Y-m-d h:i:s');
				Session::set('version_time_stamp', date('Y-m-d h:i:s'));
			}
		}
		else
		{
			$this->version_time_stamp = Session::get('version_time_stamp');
		}

		return $this->version_time_stamp;
	}

	public function getUpgradeMessage()
	{
		$upgrade_notification = new UpgradeNotification();
		$upgrade_notification_page = new UpgradeNotificationPage();
		$silverstripe_version = $upgrade_notification->getInstalledVersion();
		$silverstripe_latest_version = $upgrade_notification->getLatestVersion();

		$upgrade_message = 'SilverStripe Version: unknown';
		// Compare and sete whether version is current or not
		if($silverstripe_version && $silverstripe_latest_version)
		{
			if($silverstripe_version < $silverstripe_latest_version)
				$upgrade_message = ($upgrade_notification_page->config()->CustomMenuTextOutToDate) ? $upgrade_notification_page->config()->CustomMenuTextOutToDate:'SilverStripe ' . $silverstripe_version . ' (' . $silverstripe_latest_version . ')';
			else
				$upgrade_message = ($upgrade_notification_page->config()->CustomMenuTextUpToDate) ? $upgrade_notification_page->config()->CustomMenuTextUpToDate:'SilverStripe ' . $silverstripe_version . ' (current)';
		}

		return $upgrade_message;
	}

	public function getIcon()
	{
		$upgrade_notification = new UpgradeNotification();
		$silverstripe_version = $upgrade_notification->getInstalledVersion();
		$silverstripe_latest_version = $upgrade_notification->getLatestVersion();

		$code = 'silverstripe-upgrade-notification/images/warning-16.png';
		// Compare and set whether version is current or not
		if($silverstripe_version && $silverstripe_latest_version)
		{
			if($silverstripe_version < $silverstripe_latest_version)
				$code = 'silverstripe-upgrade-notification/images/warning-16.png';
			else
				$code = 'silverstripe-upgrade-notification/images/success-16.png';
		}

		return $code;
	}

	public function getCode()
	{
		$upgrade_notification = new UpgradeNotification();
		$silverstripe_version = $upgrade_notification->getInstalledVersion();
		$silverstripe_latest_version = $upgrade_notification->getLatestVersion();
		$code = 'upgrade-notification-alert';
		//Compare and sete whether version is current or not
		if($silverstripe_version && $silverstripe_latest_version)
		{
			if($silverstripe_version < $silverstripe_latest_version)
				$code = 'upgrade-notification-alert';
			else
				$code = 'upgrade-notification-success';
		}
		return $code;
	}

	public function isCurrentVersion()
	{
		$upgrade_notification = new UpgradeNotification();
		$silverstripe_version = $upgrade_notification->getInstalledVersion();
		$silverstripe_latest_version = $upgrade_notification->getLatestVersion();

		if($silverstripe_version && $silverstripe_latest_version)
		{
			if($silverstripe_version < $silverstripe_latest_version)
				return false;
			else
				return true;
		}

		return false;
	}
}