<?php
/**
 * @package Living_Heritage_Forms
 *
 * Handles sending email notifications.
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Sends an email notification to the admin with the form data.
 *
 * @param array $data The sanitized form data.
 */
function lhf_send_admin_notification($data)
{

    // Get the custom email string from our settings page
    $recipient_string = get_option('lhf_notification_email');
    $admin_email = get_option('admin_email');
    $to = [];

    if (!empty($recipient_string)) {
        // Explode the string into an array
        $recipients = explode(',', $recipient_string);

        foreach ($recipients as $recipient) {
            $email = trim($recipient);
            if (is_email($email)) {
                $to[] = $email;
            }
        }
    }

    // If, after all that, the $to array is empty, fall back to the admin email
    if (empty($to)) {
        $to = $admin_email;
    }

    $subject = 'New Nursery Registration Submission: ' . esc_html($data['child_first_name']) . ' ' . esc_html($data['child_surname']);
    $headers = ['Content-Type: text/html; charset=UTF-8'];

    // Build the email body
    $message = '<html><body>';
    $message .= '<h2>New Registration Received</h2>';
    $message .= '<p>A new registration form has been submitted. Details are below:</p>';
    $message .= '<hr>';
    $message .= '<table style="width: 100%; border-collapse: collapse;">';

    foreach ($data as $key => $value) {
        // Skip empty fields and format the key for readability
        if (empty($value))
            continue;

        $label = ucwords(str_replace('_', ' ', $key));

        $message .= '<tr>';
        $message .= '<td style="padding: 8px; border: 1px solid #ddd; background-color: #f2f2f2; width: 30%;"><strong>' . esc_html($label) . '</strong></td>';
        $message .= '<td style="padding: 8px; border: 1px solid #ddd;">' . nl2br(esc_html($value)) . '</td>';
        $message .= '</tr>';
    }

    $message .= '</table>';
    $message .= '</body></html>';

    // Send the email
    wp_mail($to, $subject, $message, $headers);
}