<?php

class UpgradeNotification extends DataExtension
{
	protected $silverstripe_version = null;
	protected $silverstripe_latest_version = null;

	public function getInstalledVersion()
	{
		//Parse out the installed version from composer.lock
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
							$this->silverstripe_version = $composer_item['version'];
					}
				}
			}
		}

		return $this->silverstripe_version;
	}

	public function getLatestVersion()
	{
		//Parse out latest version from packagist feed
		if(!$this->silverstripe_latest_version)
		{
			$package_feed = file_get_contents('https://packagist.org/feeds/package.silverstripe/framework.rss');
			if($package_feed)
			{
				$xml = simplexml_load_string($package_feed);
				$json = json_encode($xml);
				$package_array = json_decode($json,TRUE);

				if($package_array && isset($package_array['channel']['item'][0]['title']))
					$this->silverstripe_latest_version = preg_replace('@[^0-9\.]+@i', '', $package_array['channel']['item'][0]['title']);
			}
		}

		return $this->silverstripe_latest_version;
	}

	public static function getUpgradeMessage()
	{
		$upgrade_notification = new UpgradeNotification();
		$silverstripe_version = $upgrade_notification->getInstalledVersion();
		$silverstripe_latest_version = $upgrade_notification->getLatestVersion();

		$upgrade_message = 'SilverStripe Version: unknown';
		//Compare and sete whether version is current or not
		if($silverstripe_version && $silverstripe_latest_version)
		{
			if($silverstripe_version < $silverstripe_latest_version)
				$upgrade_message = 'SilverStripe ' . $silverstripe_version . ' (' . $silverstripe_latest_version . ')';
			else
				$upgrade_message = 'SilverStripe ' . $silverstripe_version . ' (up-to-date)';
		}

		return $upgrade_message;
	}

	public static function getIcon()
	{
		$upgrade_notification = new UpgradeNotification();
		$silverstripe_version = $upgrade_notification->getInstalledVersion();
		$silverstripe_latest_version = $upgrade_notification->getLatestVersion();

		$code = '/silverstripe-upgrade-notification/images/warning-16.png';
		//Compare and sete whether version is current or not
		if($silverstripe_version && $silverstripe_latest_version)
		{
			if($silverstripe_version < $silverstripe_latest_version)
				$code = '/silverstripe-upgrade-notification/images/warning-16.png';
			else
				$code = '/silverstripe-upgrade-notification/images/success-16.png';
		}

		return $code;
	}
}