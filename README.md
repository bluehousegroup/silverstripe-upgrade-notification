Silverstripe Upgrade Notification
=================================

A module to alert when a newer version of SilverStripe is available

![Screenshot](https://github.com/bluehousegroup/silverstripe-upgrade-notification/blob/master/images/ScreenShot1.png)

![Screenshot](https://github.com/bluehousegroup/silverstripe-upgrade-notification/blob/master/images/ScreenShot2.png)

### Install with Composer  
	composer require bluehousegroup/silverstripe-upgrade-notification

## Usage

 - example.com/dev/build
 - example.com/?flush=all
 - example.com/admin?flush=all
 - add any of the following config variables to your mysite/_config/config.yml file

	UpgradeNotificationPage:
		TechnicalContactEmail: 
		ActionMessageUpToDate: "Congratulations, you are on the most current version of SilverStripe! Woohoo!"
		ActionMessageOutOfDate: "Web maintenance is essential to keeping your site performance optimized and secure for you and your visitors. Given the custom features of your site, we would suggest contacting your account manager to discuss what an upgrade will entail"
		CustomMenuTextUpToDate: 
		CustomMenuTextOutToDate: 
		UpToDateMessage: "Your SilverStripe is up to date."
		OutToDateMessage: "Your SilverStripe is out of date." 
		ShowMenuItemWhenCurrent: true
