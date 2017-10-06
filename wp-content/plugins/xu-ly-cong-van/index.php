<?php
/*
Plugin Name: Xử lý công văn
Plugin URI: http://loanbk.xyz
Description: Plugin xử lý công văn
Author: Lê Thanh Loan
Version: 1.0
Author URI: http://loanbk.xyz
*/

include_once 'includes/Utils.php';
include_once 'includes/CongVanBase.php';
include_once 'includes/CongVanCustomContent.php';
include_once 'includes/CongVanDenCustomContent.php';
include_once 'includes/CongVanDen.php';
include_once 'includes/CongVanDi.php';
require_once( dirname(__DIR__) . '/timber-library/vendor/autoload.php' );

add_action('init', 'CongVanDen::register_my_post_type');
add_action('admin_menu','CongVanDenCustomContent::create_meta_box');
add_action('save_post', 'CongVanDenCustomContent::save_custom_fields', 1, 2);
add_action('admin_enqueue_scripts', 'Utils::admin_enqueue_scripts');
add_action('admin_print_styles', 'Utils::admin_enqueue_styles');
add_action('post_edit_form_tag', 'CongVanCustomContent::update_edit_form');
add_filter( 'pre_get_posts', 'Utils::namespace_add_custom_types' );

Timber::$locations = array(__DIR__.'/tpls');

/* EOF */