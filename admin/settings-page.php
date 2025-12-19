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
            'sanitize_callback' => 'lhf_sanitize_email_list',
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
    // Use the saved value directly, don't fallback in the input field itself
    $value = $saved_email ? $saved_email : '';

    echo '<input type="text" id="lhf_notification_email" name="lhf_notification_email" value="' . esc_attr($value) . '" class="regular-text" placeholder="' . esc_attr($placeholder) . '" />';
    // THIS IS THE LINE WE ARE CHANGING
    echo '<p class="description">Enter one or more email addresses, separated by commas. If left blank, notifications will be sent to the site administrator (' . esc_html($placeholder) . ').</p>';
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

/**
 * Sanitizes a comma-separated list of email addresses.
 *
 * @param string $input The raw string from the settings field.
 * @return string The sanitized, comma-separated string of valid emails.
 */
function lhf_sanitize_email_list($input)
{
    // Explode the input string into an array of potential emails
    $emails = explode(',', $input);

    // An array to hold only the valid, sanitized emails
    $clean_emails = [];

    foreach ($emails as $email) {
        // Trim whitespace from the email
        $trimmed_email = trim($email);

        // If it's a valid email address, add it to our clean array
        if (is_email($trimmed_email)) {
            $clean_emails[] = $trimmed_email;
        }
    }

    // Implode the clean array back into a properly formatted, comma-separated string
    return implode(', ', $clean_emails);
}