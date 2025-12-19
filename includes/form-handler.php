<?php
/**
 * @package Living_Heritage_Forms
 *
 * Handles the submission of the registration form.
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * The main function to process the form submission.
 */
function lhf_handle_form_submission()
{

    // 1. Verify the nonce for security
    if (!isset($_POST['lhf_nonce']) || !wp_verify_nonce($_POST['lhf_nonce'], 'lhf_registration_nonce')) {
        wp_die('Security check failed!', 'Error');
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'lh_form_submissions';

    // 2. Define ALL expected fields from the form
    // This is the robust way to ensure no 'NOT NULL' columns are missed.
    $expected_fields = [
        'child_first_name',
        'child_middle_name',
        'child_surname',
        'child_dob',
        'child_gender',
        'child_religion',
        'child_nationality',
        'child_ethnicity',
        'child_house_number',
        'child_street',
        'child_town_city',
        'child_postcode',
        'p1_first_name',
        'p1_middle_name',
        'p1_surname',
        'p1_relationship',
        'p1_parental_responsibility',
        'p1_dob',
        'p1_email',
        'p1_phone_home',
        'p1_mobile',
        'p1_work_phone',
        'p1_family_email',
        'p1_address',
        'p1_postcode',
        'p1_place_of_work',
        'p1_occupation',
        'p1_insurance_number',
        'p2_first_name',
        'p2_middle_name',
        'p2_surname',
        'p2_relationship',
        'p2_parental_responsibility',
        'p2_dob',
        'p2_email',
        'p2_phone_home',
        'p2_mobile',
        'p2_work_phone',
        'p2_address',
        'p2_postcode',
        'p2_place_of_work',
        'p2_occupation',
        'p2_insurance_number',
        'other_contacts_parental',
        'emergency_contacts',
        'authorized_pickup',
        'people_in_household',
        'languages_spoken',
        'child_position_family',
        'other_siblings_home',
        'siblings_details',
        'additional_info',
        'pickup_password',
        'preferred_start_date',
        'preferred_session',
        'additional_hours',
        'preferred_days',
        'funding_eligibility',
        'attended_nursery_before',
        'attended_nursery_details',
        'special_needs',
        'special_needs_details',
        'additional_support',
        'doctor_name',
        'health_visitor_name',
        'health_visitor_phone',
        'immunized_against',
        'immunisations_up_to_date',
        'ongoing_medical_conditions',
        'ongoing_medical_conditions_details',
        'special_health_considerations',
        'allergies',
        'allergies_details',
        'medications_required',
        'medications_details',
        'prescribed_medication',
        'prescribed_medication_details',
        'dietary_restrictions',
        'agree_emergency_contact',
        'agree_policies',
        'agree_activities',
        'permission_photos_internal',
        'permission_photos_social',
        'permission_photos_photographer',
        'permission_local_outings',
        'permission_products',
        'permission_products_exceptions',
        'permission_share_school',
        'agree_privacy_notice',
        'agree_terms',
        'confirm_accuracy',
    ];

    $data = [];

    // 3. Sanitize and prepare the data from the $_POST array
    foreach ($expected_fields as $field) {
        if (isset($_POST[$field])) {
            if (is_array($_POST[$field])) {
                $sanitized_array = array_map('sanitize_text_field', $_POST[$field]);
                $data[$field] = implode(', ', $sanitized_array);
            } else {
                $data[$field] = sanitize_text_field($_POST[$field]);
            }
        } else {
            // If the field isn't in the POST data (e.g., unchecked checkbox), provide a default empty value.
            $data[$field] = '';
        }
    }

    // Add submission date
    $data['submission_date'] = current_time('mysql');

    // 4. Insert the data into the database
    $result = $wpdb->insert($table_name, $data); // We can omit the format array for simplicity, $wpdb->insert handles sanitization.

    // 5. Redirect the user after submission, now with better error handling
    $redirect_url = wp_get_referer();

    if ($result === false) {
        // Database insertion failed. Let's find out why.
        // For debugging, you can uncomment the next line to see the error.
        // wp_die( 'Database insert failed: ' . $wpdb->last_error ); 

        $redirect_url = add_query_arg('submission', 'error', $redirect_url);
    } else {
        // Success!
        require_once LHF_PLUGIN_DIR . 'includes/email.php';
        lhf_send_admin_notification($data);

        $redirect_url = add_query_arg('submission', 'success', $redirect_url);
    }

    wp_redirect($redirect_url);
    exit;
}

// IMPORTANT: This hook MUST match the 'action' value in the hidden form field.
add_action('admin_post_nopriv_submit_lh_registration', 'lhf_handle_form_submission');
add_action('admin_post_submit_lh_registration', 'lhf_handle_form_submission');


/**
 * The main function to process the PERMISSIONS form submission.
 */
function lhf_handle_permissions_submission()
{

    // 1. Verify the nonce
    if (!isset($_POST['lhf_nonce']) || !wp_verify_nonce($_POST['lhf_nonce'], 'lhf_permissions_nonce')) {
        wp_die('Security check failed!', 'Error');
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'lh_form_submissions';

    // 2. Define expected fields for THIS form
    $expected_fields = [
        'form_type',
        'child_first_name',
        'child_surname',
        'p1_first_name',
        'p1_email',
        'agree_emergency_contact',
        'agree_policies',
        'agree_activities',
        'permission_photos_internal',
        'permission_photos_social',
        'permission_photos_photographer',
        'permission_local_outings',
        'permission_products',
        'permission_products_exceptions',
        'permission_share_school',
        'agree_privacy_notice',
        'agree_terms',
        'confirm_accuracy',
    ];

    $data = [];
    foreach ($expected_fields as $field) {
        $data[$field] = isset($_POST[$field]) ? sanitize_text_field($_POST[$field]) : '';
    }

    $data['submission_date'] = current_time('mysql');

    // 3. Insert the data
    $result = $wpdb->insert($table_name, $data);

    // 4. Redirect
    $redirect_url = wp_get_referer();
    if ($result === false) {
        $redirect_url = add_query_arg('submission', 'error', $redirect_url);
    } else {
        // You could create a new, separate email notification function for this if you wish
        // lhf_send_permissions_notification( $data );
        $redirect_url = add_query_arg('submission', 'success', $redirect_url);
    }

    wp_redirect($redirect_url);
    exit;
}