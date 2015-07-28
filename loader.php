<?php

/*
Plugin Name: Track Delivery
Plugin URI: https://github.com/the-martin/td_track_delivery
Description: Post services delivery tracking by track code widget
Author: The Martin
Author URI: https://github.com/the-martin
Version: 1.0
License: MIT
*/

define('TD_PLUGIN_URL',  plugin_dir_url(  __FILE__ ));
define('TD_PLUGIN_DIR',  plugin_dir_path( __FILE__ ));
define('TD_INCLUDE_DIR', plugin_dir_path( __FILE__ ) . 'inc/');
define('TD_VIEWS_DIR',   plugin_dir_path( __FILE__ ) . 'views/');
define('TD_ASSETS_DIR',  plugin_dir_url(  __FILE__ ) . 'assets/');
define('TD_LANG_DIR', dirname( plugin_basename( __FILE__ ) ) . '/lang/');

// ID of translate file
define('TD_LANG_DOMAIN', 'td');

include_once(TD_INCLUDE_DIR . 'TrackDeliveryException.php');
include_once(TD_INCLUDE_DIR . 'TrackDeliveryWidget.php');
include_once(TD_INCLUDE_DIR . 'Tracker.php');
include_once(TD_INCLUDE_DIR . 'View.php');
include_once(TD_INCLUDE_DIR . 'Helper.php');

include_once(TD_INCLUDE_DIR . 'IDeliveryService.php');
include_once(TD_INCLUDE_DIR . 'DeliveryServices/UkrPostService.php');
include_once(TD_INCLUDE_DIR . 'DeliveryServices/NewPostService.php');

wp_register_style(  'td-style', TD_ASSETS_DIR . 'css/style.css' );
wp_register_script( 'td-tracker-client-script', TD_ASSETS_DIR . 'js/tracker_client.js', ['jquery'] );
wp_register_script( 'td-main-script', TD_ASSETS_DIR . 'js/main.js', ['jquery', 'td-tracker-client-script'] );

// Language support
add_action('plugins_loaded', function() {
    load_plugin_textdomain(TD_LANG_DOMAIN, false,  TD_LANG_DIR);
});

$td_tracker = new Tracker();
// Ajax handler for main form
add_action('wp_ajax_nopriv_td_check_code_action', [$td_tracker, 'mainFormHandler']);
add_action('wp_ajax_td_check_code_action', [$td_tracker, 'mainFormHandler']);

// Init the widget
add_action( 'widgets_init', function(){
    register_widget( 'TrackDeliveryWidget' );
});