<?php
/*
Plugin Name:  Email Before Download Popup Add On
Description:  Encapsulates the email before download plugin function within a download button which opens a pop-up form instead of displaying it on the page. To use this plugin, simply use the same shortcode as you do for the Email Before Download plugin, but change the shortcode name from [email-download] to [email-download-popup]. For example: [email-download-popup download_id="9" contact_form_id="5"]
Plugin URI:   https://jeswebdevelopment.com
Author:       Jesse Sugden
Version:      1.1
Text Domain:  email-download-popup
License:      GPL v2 or later
License URI:  https://www.gnu.org/licenses/gpl-2.0.txt
*/



// disable direct file access
if ( ! defined( 'ABSPATH' ) ) {

	exit;

}

// default plugin options
function myplugin_options_default() {

	return array(
		'custom_message' => '<p class="custom-message">My custom message</p>',
		'custom_footer'  => 'Special message for users',
		'custom_toolbar' => false,
		'custom_scheme'  => 'default',
	);

}

// Add styles and scripts

add_action('wp_enqueue_scripts', 'callback_for_setting_up_scripts');

function callback_for_setting_up_scripts() {
	$plugin_dir_path = dirname(__FILE__);
    wp_register_style( 'email-download-popup', plugins_url('ebdp_styles.css', __FILE__) );
    wp_enqueue_style( 'email-download-popup' );
    wp_register_script('email-download-popup', plugins_url('ebdp_scripts.js', __FILE__), array('jquery'),'1.1', true);
 	wp_enqueue_script('email-download-popup');
}

// Build shortcode

function email_download_popup($atts){
					// Add shortcode attributes
					extract(shortcode_atts(array(
						'download_id' => '',
						'contact_form_id' => '',
					),
					$atts));
					// Check if Email Before Download plugin is active
					if(in_array('email-before-download/email-before-download.php', apply_filters('active_plugins', get_option('active_plugins')))){
					// If it is, return the HTML and shortcode from Email Before Download plugin
					  return	
					  		'<button id="email-download-btn" class="btn-bt default">Download</button>
							<div id="email-download-modal" class="popup-modal">
								<div class="email-download-popup">
									<span class="popup-close">&times;</span>
									<h2 id="ebdp-h2">Please enter your name and email and you will receive your download</h2>
									</hr>
									'.do_shortcode('[email-download download_id="'.$atts["download_id"].'" contact_form_id="'.$atts["contact_form_id"].'"]').'
								</div>
							</div>';
					}
					else {
						return 'You need to activate the Email Before Download plugin before this shortcode will work';
					}
}

// Add shortcode
add_shortcode("email-download-popup","email_download_popup");

// Remove shortcode on plugin deactivation
function email_download_popup_deactivation() {
	remove_shortcode('email-download-popup');
} 

register_deactivation_hook( __FILE__, 'email_download_popup_deactivation' );