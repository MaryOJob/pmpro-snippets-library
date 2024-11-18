<?php

/**
* Create A Unique Email For Membership Renewals in Paid Memberships Pro
* Make sure there is a folder /email/ in your plugin folder with the file named checkout_renewal.html
* 
* URL: https://www.paidmembershipspro.com/customize-membership-checkout-confirmation-email-membership-renewals/
* 
* title: create a unique email for renewals
* layout: snippet
* collection: email
* category: email templates
*
* You can add this recipe to your site by creating a custom plugin
* or using the Code Snippets plugin available for free in the WordPress repository.
* Read this companion article for step-by-step directions on either method.
* https://www.paidmembershipspro.com/create-a-plugin-for-pmpro-customizations/
*/

function my_pmpro_email_checkout_renewal($email) {
	$replace_data_again = false;

	//only filter emails with invoices
	if(empty($email->data['invoice_id']))
		return $email;

	//get order
	$order = new MemberOrder($email->data['invoice_id']);

	//make sure we have a real order
	if(empty($order) || empty($order->id))
		return $email;

	//check if there has been another order for the same user/level in the past
	global $wpdb;
	$is_renewal = $order->is_renewal();
	if(!$is_renewal)
		return $email;

	//this is a renewal! let's do our stuff
	//update subject
	$email->subject = "Thank you for your renewal.";
		
	//update body
	$email->body = file_get_contents(dirname(__FILE__) . "/email/checkout_renewal.html");
		
	//replace data
	if(is_string($email->data))
		$email->data = array("body"=>$email->data);			
	if(is_array($email->data)) {
		foreach($email->data as $key => $value) {
			$email->body = str_replace("!!" . $key . "!!", $value, $email->body);
		}	
	}

	return $email;
}

add_filter('pmpro_email_filter', 'my_pmpro_email_checkout_renewal', 20);
