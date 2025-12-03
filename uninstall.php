<?php
/**
 * @package Living_Heritage_Forms
 *
 * This file runs when the plugin is deleted.
 */

// If uninstall is not called from WordPress, exit
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

global $wpdb;
$table_name = $wpdb->prefix . 'lh_form_submissions';

// Drop the custom database table
$wpdb->query("DROP TABLE IF EXISTS {$table_name}");

// Optional: You could also delete any saved settings from the wp_options table here.
// delete_option('lhf_notification_email');