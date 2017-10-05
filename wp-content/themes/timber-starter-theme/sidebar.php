<?php
/**
 * The Template for displaying all single posts
 *
 *
 * @package  WordPress
 * @subpackage  Timber
 */
$context = array();
$context['primary_widget_area'] = Timber::get_widgets('primary-widget-area');
$context['secondary_widget_area'] = Timber::get_widgets('secondary-widget-area');
Timber::render( array( 'sidebar.twig' ), $context );
