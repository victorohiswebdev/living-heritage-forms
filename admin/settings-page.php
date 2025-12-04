<?php
/**
 * @package Living_Heritage_Forms
 *
 * Handles the plugin's settings page.
 */

if (!defined('WPINC')) {
    die;
}

// Hook our settings registration function to the admin_init action
add_action('admin_init', 'lhf_register_settings');

/**
 * Register the settings and fields for the plugin.
 */
function lhf_register_settings()
{
    // Register the setting
    register_setting(
        'lhf_settings_group',           // Option group
        'lhf_notification_email',       // Option name
        [
            'type' => 'string',
            'sanitize_callback' => 'sanitize_email', // Use WordPress's built-in email sanitizer
            'default' => '',
        ]
    );

    // Add a settings section
    add_settings_section(
        'lhf_email_section',            // Section ID
        'Email Notification Settings',  // Section Title
        'lhf_email_section_callback',   // Callback function to render the section description
        'lhf-settings'                  // Page slug on which to show this section
    );

    // Add the email input field
    add_settings_field(
        'lhf_notification_email_field', // Field ID
        'Notification Recipient Email', // Field Title
        'lhf_email_field_callback',     // Callback function to render the field
        'lhf-settings',                 // Page slug
        'lhf_email_section'             // Section ID
    );
}

/**
 * Renders the description for the email settings section.
 */
function lhf_email_section_callback()
{
    echo '<p>Configure the email address where new registration notifications will be sent.</p>';
}

/**
 * Renders the email input field.
 */
function lhf_email_field_callback()
{
    // Get the saved value, or fall back to the site admin's email as a placeholder
    $saved_email = get_option('lhf_notification_email');
    $placeholder = get_option('admin_email');
    $value = $saved_email ? $saved_email : $placeholder;

    echo '<input type="email" id="lhf_notification_email" name="lhf_notification_email" value="' . esc_attr($value) . '" class="regular-text" />';
    echo '<p class="description">If left blank, notifications will be sent to the site administrator (' . esc_html($placeholder) . ').</p>';
}

/**
 * Renders the main settings page wrapper and form.
 */
function lhf_render_settings_page()
{
    ?>
    <div class="wrap">
        <h1>Living Heritage Forms Settings</h1>
        <form action="options.php" method="post">
            <?php
            // Output security fields for the registered setting group
            settings_fields('lhf_settings_group');

            // Output the settings sections and their fields
            do_settings_sections('lhf-settings');

            // Output the save button
            submit_button('Save Settings');
            ?>
        </form>
    </div>
    <?php
}