<?php

/**
 * This recipe will add a fee to the initial and recurring value for specific levels when using Stripe
 * Specify your membership levels on line 23
 * 
 * title: Add A Level-Specific Fixed Fee to Checkout When Using Stripe
 * layout: snippet
 * collection: checkout
 * category: stripe
 * url: https://www.paidmembershipspro.com/adjust-membership-pricing-by-payment-gateway/
 * 
 * You can add this recipe to your site by creating a custom plugin
 * or using the Code Snippets plugin available for free in the WordPress repository.
 * Read this companion article for step-by-step directions on either method.
 * https://www.paidmembershipspro.com/create-a-plugin-for-pmpro-customizations/
 */

function my_pmpro_checkout_level( $level ){
	if( !empty( $_REQUEST['gateway'] ) ){
		if( $_REQUEST['gateway'] == 'stripe' ){
			if( !empty( $_REQUEST['level'] ) ){
				if( in_array( $_REQUEST['level'], array( 1, 2, 3 ) ) ){
					$level->initial_payment = $level->initial_payment + 3;	//Updates initial payment value
					$level->billing_amount = $level->billing_amount + 3;	//Updates recurring payment value 
				}
			}
		}
	}
	return $level;
}

add_filter( "pmpro_checkout_level", "my_pmpro_checkout_level" );
