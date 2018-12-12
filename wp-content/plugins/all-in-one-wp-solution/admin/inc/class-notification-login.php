<?php

# Important configutation data such as start of the seassion
# the website name and the admin email
	require 'class-email-noti-config.php';
# Check if the user is an admin
	function aiows_check_if_admin(){
		if(current_user_can('manage_options')){
			return true;
		}else{
			return false;
		}
	}
# Gets the time the admin logged in
	function aiows_get_time_of_login(){
		$time_of_login = date('l jS F Y');
		return $time_of_login;
	}
# Gets the IP of the user that logged himself as admin
	function aiows_noti_get_ip(){
		$sources = array(
	'REMOTE_ADDR',
	'HTTP_X_FORWARDED_FOR',
	'HTTP_CLIENT_IP',
);
	foreach ($sources as $source) {
		if(!empty($_SERVER[$source])){
			$ip = $_SERVER[$source];
		}
	}
	return $ip;
	}
#   Email all the info above to a pointed email address
	function aiows_send_noti_email(){
		global $website_name;
		if(aiows_check_if_admin() === true && !isset($_SESSION['logged_in_once'])) {
		$get_time_of_login = aiows_get_time_of_login();
		$get_ip =  aiows_noti_get_ip();
# Email subject and message
$subject = sprintf('An administrator of your website %s has just logged in!', $website_name);
$message = <<<MESSAGE
		An admin logged in your WordPress website {$website_name} on {$get_time_of_login}
		with the IP: {$get_ip}
MESSAGE;
# Sending of the email notification
				wp_mail(
						ADMIN_EMAIL
						, $subject
						, $message
						);
# We assign 1 as to make so that the script does not sends emails on each page refresh like a deaf rooster
			$_SESSION['logged_in_once'] = 1;
		}
	}
add_action('admin_notices', 'aiows_send_noti_email');

?>