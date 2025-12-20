<?php
/**
 * @package Living_Heritage_Forms
 * Handles all form submissions.
 */

if (!defined('WPINC')) {
    die;
}

// ====================================================================
// THIS IS THE FIX:
// We must explicitly include the email functions file so the functions
// below can find and use it.
require_once LHF_PLUGIN_DIR . 'includes/email.php';
// ====================================================================


function lhf_handle_form_submission()
{
    if (!isset($_POST['lhf_nonce']) || !wp_verify_nonce($_POST['lhf_nonce'], 'lhf_registration_nonce')) {
        wp_die('Security check failed!', 'Error');
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'lh_form_submissions';

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
    foreach ($expected_fields as $field) {
        if (isset($_POST[$field])) {
            $data[$field] = is_array($_POST[$field]) ? implode(', ', array_map('sanitize_text_field', $_POST[$field])) : sanitize_text_field($_POST[$field]);
        } else {
            $data[$field] = '';
        }
    }

    $data['submission_date'] = current_time('mysql');
    $data['form_type'] = 'registration';

    $result = $wpdb->insert($table_name, $data);

    $redirect_url = wp_get_referer();
    if ($result === false) {
        $redirect_url = add_query_arg('submission', 'error', $redirect_url);
    } else {
        lhf_send_notification($data, 'registration');
        $redirect_url = add_query_arg('submission', 'success', $redirect_url);
    }

    wp_redirect($redirect_url);
    exit;
}

function lhf_handle_permissions_submission()
{
    if (!isset($_POST['lhf_nonce']) || !wp_verify_nonce($_POST['lhf_nonce'], 'lhf_permissions_nonce')) {
        wp_die('Security check failed!', 'Error');
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'lh_form_submissions';

    $expected_fields = [
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
    $data['form_type'] = 'permissions';

    $result = $wpdb->insert($table_name, $data);

    $redirect_url = wp_get_referer();
    if ($result === false) {
        $redirect_url = add_query_arg('submission', 'error', $redirect_url);
    } else {
        lhf_send_notification($data, 'permissions');
        $redirect_url = add_query_arg('submission', 'success', $redirect_url);
    }

    wp_redirect($redirect_url);
    exit;
}