<?php
/**
 * Created by PhpStorm.
 * User: vanpt
 * Date: 9/7/2017
 * Time: 10:27 PM
 */

class Utils 
{
    public static function parse($tpl, $hash) {

        foreach ($hash as $key => $value) {
            $tpl = str_replace('{{'.$key.'}}', $value, $tpl);
        }
        return $tpl;
    }

    public static function admin_enqueue_scripts() {
        if (!is_admin()) {
            return;
        }
        wp_enqueue_script('media-upload');
        wp_enqueue_script('thickbox');
        wp_enqueue_script('docman-admin-cong_van', plugins_url('js/admin-cong-van.js', dirname(__FILE__)), array('jquery',  'jquery-ui-datepicker'), '170908');
        wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css');
    }

    public static function admin_enqueue_styles() {
        wp_enqueue_style('thickbox');
    }
    
    public static function namespace_add_custom_types($query) {
        if (is_admin()) {
            return;
        }
        if( (is_category() || is_tag()) && $query->is_archive() && empty( $query->query_vars['suppress_filters'] ) ) {
            $query->set( 'post_type', array(
                'post', CongVanDen::POST_TYPE, CongVanDi::POST_TYPE,
            ));
        }
        return $query;
    }

    public static function getIdFromGuid($guid) {
        global $wpdb;
        return $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE guid=%s", $guid ) );
    }
}