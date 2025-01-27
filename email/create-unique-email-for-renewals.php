<?php

/*
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

// Filters the email content for membership renewals in Paid Memberships Pro.
function my_pmpro_email_checkout_renewal( $email ) {
	// Only filter emails with invoice data.
	if ( empty( $email->data['invoice_id'] ) ) {
		return $email;
	}

	// Get order details.
	$order = new MemberOrder( $email->data['invoice_id'] );

	// Ensure the order is valid.
	if ( empty( $order ) || empty( $order->id ) ) {
		return $email;
	}

	// Check if this is a renewal order.
	if ( ! $order->is_renewal() ) {
		return $email;
	}

	// This is a renewal! Modify the email.
	$email->subject = 'Thank you for your renewal.';

	// Define the email file path.
	$email_file = dirname( __FILE__ ) . '/email/checkout_renewal.html';

	// Check if the custom email file exists.
	if ( file_exists( $email_file ) ) {
		$email->body = file_get_contents( $email_file );
	} else {
		// Fallback to default text if the file doesn't exist.
		$email->body = 'Thank you for renewing your membership. We appreciate your continued support.';
	}

	// Replace placeholders with dynamic data.
	if ( is_string( $email->data ) ) {
		$email->data = array( 'body' => $email->data );
	}

	if ( is_array( $email->data ) ) {
		foreach ( $email->data as $key => $value ) {
			$email->body = str_replace( '!!' . $key . '!!', $value, $email->body );
		}
	}

	return $email;
}

add_filter( 'pmpro_email_filter', 'my_pmpro_email_checkout_renewal', 20 );
