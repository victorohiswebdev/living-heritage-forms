<?php
/**
 * @package Living_Heritage_Forms
 * Handles sending all email notifications.
 */

if (!defined('WPINC')) {
    die;
}

/**
 * Sends a notification email based on the form type.
 * This is now the ONLY email function.
 *
 * @param array $data The submitted form data.
 * @param string $form_type A string to identify the form ('registration' or 'permissions').
 */
function lhf_send_notification($data, $form_type)
{

    $recipient_string = get_option('lhf_notification_email');
    $admin_email = get_option('admin_email');
    $to = [];

    if (!empty($recipient_string)) {
        $recipients = explode(',', $recipient_string);
        foreach ($recipients as $recipient) {
            $email = trim($recipient);
            if (is_email($email)) {
                $to[] = $email;
            }
        }
    }

    if (empty($to)) {
        $to = $admin_email;
    }

    // -- Dynamically set the subject and title based on the form type --
    if ('permissions' === $form_type) {
        $subject = 'New Permissions Form Submitted for: ' . esc_html($data['child_first_name']) . ' ' . esc_html($data['child_surname']);
        $title = 'New Permissions Form Received';
        $intro = '<p>A new parental agreements and permissions form has been submitted.</p>';
    } else {
        $subject = 'New Nursery Registration: ' . esc_html($data['child_first_name']) . ' ' . esc_html($data['child_surname']);
        $title = 'New Registration Received';
        $intro = '<p>A new registration form has been submitted. Details are below:</p>';
    }

    $headers = 'Content-Type: text/html; charset=UTF-8';

    ob_start();
    ?>
    <h2><?php echo $title; ?></h2>
    <?php echo $intro; ?>
    <hr>
    <table style="width: 100%; border-collapse: collapse;">
        <?php foreach ($data as $key => $value):
            // Treat the string "0" as a valid value — don't drop it.
            if (!isset($value) || $value === '')
                continue;
            $label = ucwords(str_replace('_', ' ', $key));
            ?>
            <tr>
                <td style="padding: 8px; border: 1px solid #ddd; background-color: #f2f2f2; width: 30%;">
                    <strong><?php echo esc_html($label); ?></strong>
                </td>
                <td style="padding: 8px; border: 1px solid #ddd;"><?php echo nl_to_br(esc_html($value)); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <?php
    $message = ob_get_clean();

    // Send the email
    wp_mail($to, $subject, $message, $headers);
}

// A helper function to avoid potential fatal errors in some PHP versions
if (!function_exists('nl_to_br')) {
    function nl_to_br($string)
    {
        return str_replace(array("\r\n", "\r", "\n"), "<br />", $string);
    }
}