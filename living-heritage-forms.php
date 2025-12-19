<?php
/**
 * Plugin Name:       Living Heritage Forms
 * Plugin URI:        https://github.com/your-username/living-heritage-forms
 * Description:       Provides a complete registration form for Living Heritage Nursery School. Includes a shortcode for display, an admin panel for viewing submissions, and email notifications.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Victor Ohis
 * Author URI:        https://your-website.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       living-heritage-forms
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Define constants for the plugin.
 */
define('LHF_VERSION', '1.0.0');
define('LHF_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('LHF_PLUGIN_URL', plugin_dir_url(__FILE__));

/**
 * The code that runs during plugin activation.
 */
function lhf_activate_plugin()
{
    require_once LHF_PLUGIN_DIR . 'includes/activation.php';
    lhf_create_database_table();
}
register_activation_hook(__FILE__, 'lhf_activate_plugin');


/**
 * Register frontend scripts and styles.
 */
function lhf_register_assets()
{
    wp_register_style(
        'lhf-frontend-style',
        LHF_PLUGIN_URL . 'assets/css/frontend.css',
        [],
        LHF_VERSION
    );

    // ADD THIS SCRIPT REGISTRATION
    wp_register_script(
        'lhf-frontend-js',
        LHF_PLUGIN_URL . 'assets/js/frontend.js',
        ['jquery'], // Dependency
        LHF_VERSION,
        true // Load in footer
    );
}
add_action('wp_enqueue_scripts', 'lhf_register_assets');


/**
 * Include all necessary files.
 */
require_once LHF_PLUGIN_DIR . 'includes/shortcodes.php';
require_once LHF_PLUGIN_DIR . 'includes/form-handler.php'; // <-- UNCOMMENT THIS
require_once LHF_PLUGIN_DIR . 'admin/menu.php';
require_once LHF_PLUGIN_DIR . 'admin/settings-page.php';


/**
 * Hook the form handler to admin-post.
 * admin_post_nopriv is for non-logged-in users.
 * admin_post is for logged-in users.
 */
// The 'submit_lh_registration' matches the 'action' field in our form.
add_action('admin_post_nopriv_submit_lh_registration', 'lhf_handle_form_submission'); // <-- ADD THIS
add_action('admin_post_submit_lh_registration', 'lhf_handle_form_submission');       // <-- AND THIS
// Hook the NEW permissions form handler
add_action('admin_post_nopriv_submit_lh_permissions', 'lhf_handle_permissions_submission');
add_action('admin_post_submit_lh_permissions', 'lhf_handle_permissions_submission');
?>