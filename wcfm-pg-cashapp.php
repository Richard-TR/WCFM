<?php
/**
 * Plugin Name: WCFM Marketplace Vendor Payment - Cash App
 * Plugin URI: 
 * Description: WCFM Marketplace Cash App vendor payment gateway 
 * Author: Richard - TapRoot Consulting Ltd
 * Version: 1.1.3
 * Author URI: https://taprootconsulting.co.uk
 *
 * Text Domain: wcfm-pg-cashapp
 * Domain Path: /lang/
 *
 * WC requires at least: 3.0.0
 * WC tested up to: 3.4.0
 *
 */

add_filter( 'wcfm_marketplace_withdrwal_payment_methods', function( $payment_methods ) {
$payment_methods['cashapp'] = 'Cash App';
return $payment_methods;
});



/*********************/

add_filter( 'wcfm_marketplace_settings_fields_billing', function( $vendor_billing_fileds, $vendor_id ) {
$gateway_slug = 'cashapp';
$vendor_data = get_user_meta( $vendor_id, 'wcfmmp_profile_settings', true );
if( !$vendor_data ) $vendor_data = array();
	
$cashapp_id = isset( $vendor_data['payment'][$gateway_slug]['cashtag'] ) ? esc_attr( $vendor_data['payment'][$gateway_slug]['cashtag'] ) : '' ;
$full_name = isset( $vendor_data['payment'][$gateway_slug]['fullname'] ) ? esc_attr( $vendor_data['payment'][$gateway_slug]['fullname'] ) : '' ;
$email_addr = isset( $vendor_data['payment'][$gateway_slug]['email'] ) ? esc_attr( $vendor_data['payment'][$gateway_slug]['email'] ) : '' ;
	
$vendor_brain_tree_billing_fileds = array(
"cashappid" => array(
	'label' => __('$Cashtag', 'wc-frontend-manager'), 
	'name' => 'payment['.$gateway_slug.'][cashtag]',
	'type' => 'text',
	'class' => 'wcfm-text wcfm_ele paymode_field paymode_'.$gateway_slug,
	'label_class' => 'wcfm_title wcfm_ele paymode_field paymode_'.$gateway_slug,
	'value' => $cashapp_id ),
	
"fullname" => array(
	'label' => __('Full Name', 'wc-frontend-manager'), 
	'name' => 'payment['.$gateway_slug.'][fullname]',
	'type' => 'text',
	'class' => 'wcfm-text wcfm_ele paymode_field paymode_'.$gateway_slug,
	'label_class' => 'wcfm_title wcfm_ele paymode_field paymode_'.$gateway_slug,
	'value' => $full_name ),	
	
"emailaddr" => array(
	'label' => __('Email Address', 'wc-frontend-manager'), 
	'name' => 'payment['.$gateway_slug.'][email]',
	'type' => 'text',
	'class' => 'wcfm-text wcfm_ele paymode_field paymode_'.$gateway_slug,
	'label_class' => 'wcfm_title wcfm_ele paymode_field paymode_'.$gateway_slug,
	'value' => $email_addr ),
	
);
$vendor_billing_fileds = array_merge( $vendor_billing_fileds, $vendor_brain_tree_billing_fileds );
return $vendor_billing_fileds;
}, 50, 2);

/** removed API key inputs as not required **/




class WCFMmp_Gateway_cashapp {
public $id;
public $message = array();
public $gateway_title;
public $payment_gateway;
public $withdrawal_id;
public $vendor_id;
public $withdraw_amount = 0;
public $currency;
public $transaction_mode;
private $reciver_email;
public $test_mode = false;
public $client_id;
public $client_secret;
public function __construct() {
$this->id = 'cashapp';
$this->gateway_title = __('Cash App', 'wc-multivendor-marketplace');
$this->payment_gateway = $this->id;
}
public function gateway_logo() { global $WCFMmp; return $WCFMmp->plugin_url . 'assets/images/'.$this->id.'.png'; }
public function process_payment( $withdrawal_id, $vendor_id, $withdraw_amount, $withdraw_charges, $transaction_mode = 'auto' ) {
global $WCFM, $WCFMmp;
$this->withdrawal_id = $withdrawal_id;
$this->vendor_id = $vendor_id;
$this->withdraw_amount = $withdraw_amount;
$this->currency = get_woocommerce_currency();
$this->transaction_mode = $transaction_mode;
$this->reciver_email = $WCFMmp->wcfmmp_vendor->get_vendor_payment_account( $this->vendor_id, $this->id );
$withdrawal_test_mode = isset( $WCFMmp->wcfmmp_withdrawal_options['test_mode'] ) ? 'yes' : 'no';
$this->client_id = isset( $WCFMmp->wcfmmp_withdrawal_options[$this->id.'_client_id'] ) ? $WCFMmp->wcfmmp_withdrawal_options[$this->id.'_client_id'] : '';
$this->client_secret = isset( $WCFMmp->wcfmmp_withdrawal_options[$this->id.'_secret_key'] ) ? $WCFMmp->wcfmmp_withdrawal_options[$this->id.'_secret_key'] : '';
if ( $withdrawal_test_mode == 'yes') {
$this->test_mode = true;
$this->client_id = isset( $WCFMmp->wcfmmp_withdrawal_options[$this->id.'_test_client_id'] ) ? $WCFMmp->wcfmmp_withdrawal_options[$this->id.'_test_client_id'] : '';
$this->client_secret = isset( $WCFMmp->wcfmmp_withdrawal_options[$this->id.'_test_secret_key'] ) ? $WCFMmp->wcfmmp_withdrawal_options[$this->id.'_test_secret_key'] : '';
}
if ( $this->validate_request() ) {
// Updating withdrawal meta
$WCFMmp->wcfmmp_withdraw->wcfmmp_update_withdrawal_meta( $this->withdrawal_id, 'withdraw_amount', $this->withdraw_amount );
$WCFMmp->wcfmmp_withdraw->wcfmmp_update_withdrawal_meta( $this->withdrawal_id, 'currency', $this->currency );
$WCFMmp->wcfmmp_withdraw->wcfmmp_update_withdrawal_meta( $this->withdrawal_id, 'reciver_email', $this->reciver_email );
return array( 'status' => true, 'message' => __('New transaction has been initiated', 'wc-multivendor-marketplace') );
} else {
return $this->message;
}
}
public function validate_request() {
global $WCFMmp;
return true;
}
}