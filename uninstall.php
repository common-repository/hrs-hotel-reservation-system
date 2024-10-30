<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package   MHotel Reservation System
 * @author    MozakDesign
 * @link      http://mozakdesign.com/
 * @copyright 2015 mozakdesign.com
 */

// If uninstall not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
        exit;
}
require_once 'define.php';
global $wpdb;
