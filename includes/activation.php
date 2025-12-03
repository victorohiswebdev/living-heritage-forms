<?php
/**
 * @package Living_Heritage_Forms
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Create the custom database table for form submissions.
 */
function lhf_create_database_table()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'lh_form_submissions';
    $charset_collate = $wpdb->get_charset_collate();

    // SQL statement to create the table
    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        submission_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        
        -- Child's Details
        child_first_name varchar(100) NOT NULL,
        child_middle_name varchar(100) DEFAULT '' NOT NULL,
        child_surname varchar(100) NOT NULL,
        child_dob date NOT NULL,
        child_gender varchar(10) NOT NULL,
        child_religion varchar(100) NOT NULL,
        child_nationality varchar(100) NOT NULL,
        child_ethnicity varchar(100) DEFAULT '' NOT NULL,
        child_house_number varchar(255) NOT NULL,
        child_street varchar(255) NOT NULL,
        child_town_city varchar(100) NOT NULL,
        child_postcode varchar(20) NOT NULL,
        
        -- Parent 1 Details
        p1_first_name varchar(100) NOT NULL,
        p1_middle_name varchar(100) DEFAULT '' NOT NULL,
        p1_surname varchar(100) NOT NULL,
        p1_relationship varchar(100) NOT NULL,
        p1_parental_responsibility tinyint(1) NOT NULL,
        p1_dob date NOT NULL,
        p1_email varchar(100) NOT NULL,
        p1_phone_home varchar(20) DEFAULT '' NOT NULL,
        p1_mobile varchar(20) NOT NULL,
        p1_work_phone varchar(20) DEFAULT '' NOT NULL,
        p1_family_email varchar(100) NOT NULL,
        p1_address text DEFAULT '' NOT NULL,
        p1_postcode varchar(20) DEFAULT '' NOT NULL,
        p1_place_of_work varchar(255) NOT NULL,
        p1_occupation varchar(100) NOT NULL,
        p1_insurance_number varchar(50) NOT NULL,

        -- Parent 2 Details
        p2_first_name varchar(100) NOT NULL,
        p2_middle_name varchar(100) DEFAULT '' NOT NULL,
        p2_surname varchar(100) NOT NULL,
        p2_relationship varchar(100) NOT NULL,
        p2_parental_responsibility tinyint(1) NOT NULL,
        p2_dob date NOT NULL,
        p2_email varchar(100) NOT NULL,
        p2_phone_home varchar(20) DEFAULT '' NOT NULL,
        p2_mobile varchar(20) NOT NULL,
        p2_work_phone varchar(20) DEFAULT '' NOT NULL,
        p2_address text DEFAULT '' NOT NULL,
        p2_postcode varchar(20) DEFAULT '' NOT NULL,
        p2_place_of_work varchar(255) NOT NULL,
        p2_occupation varchar(100) NOT NULL,
        p2_insurance_number varchar(50) NOT NULL,

        -- Additional/Emergency Info
        other_contacts_parental text DEFAULT '' NOT NULL,
        emergency_contacts text NOT NULL,
        authorized_pickup text NOT NULL,
        people_in_household text DEFAULT '' NOT NULL,

        -- Further Details
        languages_spoken varchar(255) NOT NULL,
        child_position_family varchar(100) NOT NULL,
        other_siblings_home text DEFAULT '' NOT NULL,
        siblings_details text DEFAULT '' NOT NULL,
        additional_info text DEFAULT '' NOT NULL,
        pickup_password varchar(100) NOT NULL,

        -- Preferences & Funding
        preferred_start_date date NOT NULL,
        preferred_session varchar(50) NOT NULL,
        additional_hours varchar(255) DEFAULT '' NOT NULL,
        preferred_days text NOT NULL, -- Storing as serialized/JSON string
        funding_eligibility varchar(50) NOT NULL,
        
        -- Child Development
        attended_nursery_before tinyint(1) NOT NULL,
        attended_nursery_details text DEFAULT '' NOT NULL,
        special_needs tinyint(1) NOT NULL,
        special_needs_details text DEFAULT '' NOT NULL,
        additional_support text DEFAULT '' NOT NULL,

        -- Medical & Health
        doctor_name varchar(255) DEFAULT '' NOT NULL,
        health_visitor_name varchar(255) DEFAULT '' NOT NULL,
        health_visitor_phone varchar(20) DEFAULT '' NOT NULL,
        immunized_against text NOT NULL, -- Storing as serialized/JSON string
        immunisations_up_to_date tinyint(1) NOT NULL,
        ongoing_medical_conditions tinyint(1) NOT NULL,
        ongoing_medical_conditions_details text DEFAULT '' NOT NULL,
        special_health_considerations tinyint(1) NOT NULL,
        allergies tinyint(1) NOT NULL,
        allergies_details text DEFAULT '' NOT NULL,
        medications_required tinyint(1) NOT NULL,
        medications_details text DEFAULT '' NOT NULL,
        prescribed_medication tinyint(1) NOT NULL,
        prescribed_medication_details text DEFAULT '' NOT NULL,
        dietary_restrictions text NOT NULL,
        
        -- Agreements & Permissions
        agree_emergency_contact tinyint(1) NOT NULL,
        agree_policies tinyint(1) NOT NULL,
        agree_activities tinyint(1) NOT NULL,
        permission_photos_internal tinyint(1) NOT NULL,
        permission_photos_social tinyint(1) NOT NULL,
        permission_photos_photographer tinyint(1) NOT NULL,
        permission_local_outings tinyint(1) NOT NULL,
        permission_products tinyint(1) NOT NULL,
        permission_products_exceptions text DEFAULT '' NOT NULL,
        permission_share_school tinyint(1) NOT NULL,
        agree_privacy_notice tinyint(1) NOT NULL,
        agree_terms tinyint(1) NOT NULL,
        confirm_accuracy tinyint(1) NOT NULL,

        PRIMARY KEY  (id)
    ) $charset_collate;";

    // We need to bring in dbDelta
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
?>