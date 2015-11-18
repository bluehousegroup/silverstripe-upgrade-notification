jQuery(document).ready(function(){
	jQuery('body').on('click', '#Menu-upgrade-notification-alert', function(e){
		e.preventDefault();
		alert(e.srcElement.textContent);
		return false;
	});
	jQuery('body').on('click', '#Menu-upgrade-notification-success', function(e){
		e.preventDefault();
		alert(e.srcElement.textContent);
		return false;
	});
});