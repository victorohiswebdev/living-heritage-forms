<?php
/**
 * @package Living_Heritage_Forms
 *
 * Defines the shortcode for the registration form.
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Renders the registration form.
 *
 * @return string The HTML for the form.
 */
function lhf_render_registration_form()
{

    // Enqueue the frontend stylesheet only when the shortcode is used.
    wp_enqueue_style('lhf-frontend-style');
    wp_enqueue_script('lhf-frontend-js');

    ob_start();
    // Display submission status messages
    if (isset($_GET['submission'])) {
        if ($_GET['submission'] === 'success') {
            echo '<div class="lhf-alert lhf-alert-success">Thank you for your registration! Your submission was successful.</div>';
        } elseif ($_GET['submission'] === 'error') {
            echo '<div class="lhf-alert lhf-alert-error">An error occurred. Please try again.</div>';
        }
    }

    ?>
    <div class="lhf-form-container">
        <form id="lhf-registration-form" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">

            <!-- Security fields -->
            <input type="hidden" name="action" value="submit_lh_registration">
            <?php wp_nonce_field('lhf_registration_nonce', 'lhf_nonce'); ?>

            <h2>Registration</h2>
            <p class="lhf-required-note">Fields marked with an <span>*</span> are required.</p>

            <!-- =================================================================== -->
            <!-- Child's Details -->
            <!-- =================================================================== -->
            <fieldset>
                <h3>Child's Details</h3>
                <div class="lhf-form-row lhf-three-col">
                    <div class="lhf-form-group">
                        <label for="child_first_name">First Name <span>*</span></label>
                        <input type="text" id="child_first_name" name="child_first_name" required>
                    </div>
                    <div class="lhf-form-group">
                        <label for="child_middle_name">Middle Name</label>
                        <input type="text" id="child_middle_name" name="child_middle_name">
                    </div>
                    <div class="lhf-form-group">
                        <label for="child_surname">Surname <span>*</span></label>
                        <input type="text" id="child_surname" name="child_surname" required>
                    </div>
                </div>
                <div class="lhf-form-row lhf-three-col">
                    <div class="lhf-form-group">
                        <label for="child_dob">Date of Birth <span>*</span></label>
                        <input type="date" id="child_dob" name="child_dob" required>
                    </div>
                    <div class="lhf-form-group">
                        <label>Gender <span>*</span></label>
                        <div class="lhf-radio-group">
                            <label><input type="radio" name="child_gender" value="Male" required> Male</label>
                            <label><input type="radio" name="child_gender" value="Female"> Female</label>
                        </div>
                    </div>
                    <div class="lhf-form-group">
                        <label for="child_religion">Religion <span>*</span></label>
                        <input type="text" id="child_religion" name="child_religion" required>
                    </div>
                </div>
                <div class="lhf-form-row lhf-two-col">
                    <div class="lhf-form-group">
                        <label for="child_nationality">Nationality <span>*</span></label>
                        <input type="text" id="child_nationality" name="child_nationality" placeholder="e.g. British"
                            required>
                    </div>
                    <div class="lhf-form-group">
                        <label for="child_ethnicity">Ethnicity</label>
                        <select id="child_ethnicity" name="child_ethnicity">
                            <option value="">Please choose...</option>
                            <option value="White British">White British</option>
                            <option value="White Irish">White Irish</option>
                            <option value="Any other White background">Any other White background</option>
                            <option value="White and Black Caribbean">White and Black Caribbean</option>
                            <option value="White and Black African">White and Black African</option>
                            <option value="White and Asian">White and Asian</option>
                            <option value="Any other mixed background">Any other mixed background</option>
                            <option value="Indian">Indian</option>
                            <option value="Pakistani">Pakistani</option>
                            <option value="Bangladeshi">Bangladeshi</option>
                            <option value="Any other Asian background">Any other Asian background</option>
                            <option value="Caribbean">Caribbean</option>
                            <option value="African">African</option>
                            <option value="Any other Black background">Any other Black background</option>
                            <option value="Chinese">Chinese</option>
                            <option value="Any other ethnic group">Any other ethnic group</option>
                            <option value="Prefer not to say">Prefer not to say</option>
                            <option value="Unknown">Unknown</option>
                        </select>
                    </div>
                </div>
                <h4>Child's Home Address</h4>
                <div class="lhf-form-row lhf-three-col">
                    <div class="lhf-form-group">
                        <label for="child_house_number">House Name/Number <span>*</span></label>
                        <input type="text" id="child_house_number" name="child_house_number" required>
                    </div>
                    <div class="lhf-form-group">
                        <label for="child_street">Street <span>*</span></label>
                        <input type="text" id="child_street" name="child_street" required>
                    </div>
                    <div class="lhf-form-group">
                        <label for="child_town_city">Town/City <span>*</span></label>
                        <input type="text" id="child_town_city" name="child_town_city" required>
                    </div>
                </div>
                <div class="lhf-form-row lhf-half-width">
                    <div class="lhf-form-group">
                        <label for="child_postcode">Post Code <span>*</span></label>
                        <input type="text" id="child_postcode" name="child_postcode" required>
                    </div>
                </div>
            </fieldset>

            <!-- =================================================================== -->
            <!-- Parent/Guardian Details (Parent 1) -->
            <!-- =================================================================== -->
            <fieldset>
                <h3>Parent/Guardian Details (Parent 1)</h3>
                <div class="lhf-form-row lhf-three-col">
                    <div class="lhf-form-group">
                        <label for="p1_first_name">First Name <span>*</span></label>
                        <input type="text" id="p1_first_name" name="p1_first_name" required>
                    </div>
                    <div class="lhf-form-group">
                        <label for="p1_middle_name">Middle Name</label>
                        <input type="text" id="p1_middle_name" name="p1_middle_name">
                    </div>
                    <div class="lhf-form-group">
                        <label for="p1_surname">Surname <span>*</span></label>
                        <input type="text" id="p1_surname" name="p1_surname" required>
                    </div>
                </div>
                <div class="lhf-form-row lhf-three-col">
                    <div class="lhf-form-group">
                        <label for="p1_relationship">Relationship To Child <span>*</span></label>
                        <input type="text" id="p1_relationship" name="p1_relationship" required>
                    </div>
                    <div class="lhf-form-group">
                        <label>Do you have parental responsibility? <span>*</span></label>
                        <div class="lhf-radio-group">
                            <label><input type="radio" name="p1_parental_responsibility" value="1" required> Yes</label>
                            <label><input type="radio" name="p1_parental_responsibility" value="0"> No</label>
                        </div>
                    </div>
                    <div class="lhf-form-group">
                        <label for="p1_dob">Date of Birth <span>*</span></label>
                        <input type="date" id="p1_dob" name="p1_dob" required>
                    </div>
                </div>
                <div class="lhf-form-row lhf-three-col">
                    <div class="lhf-form-group">
                        <label for="p1_email">Email <span>*</span></label>
                        <input type="email" id="p1_email" name="p1_email" required>
                    </div>
                    <div class="lhf-form-group">
                        <label for="p1_phone_home">Contact Phone (Home)</label>
                        <input type="tel" id="p1_phone_home" name="p1_phone_home">
                    </div>
                    <div class="lhf-form-group">
                        <label for="p1_mobile">Mobile <span>*</span></label>
                        <input type="tel" id="p1_mobile" name="p1_mobile" required>
                    </div>
                </div>
                <div class="lhf-form-row lhf-two-col">
                    <div class="lhf-form-group">
                        <label for="p1_work_phone">Work</label>
                        <input type="tel" id="p1_work_phone" name="p1_work_phone">
                    </div>
                    <div class="lhf-form-group">
                        <label for="p1_family_email">Family email (For invoices) <span>*</span></label>
                        <input type="email" id="p1_family_email" name="p1_family_email" required>
                    </div>
                </div>
                <div class="lhf-form-row">
                    <div class="lhf-form-group">
                        <label for="p1_address">Address (If Different From Child's)</label>
                        <textarea id="p1_address" name="p1_address" rows="3"></textarea>
                    </div>
                </div>
                <div class="lhf-form-row lhf-half-width">
                    <div class="lhf-form-group">
                        <label for="p1_postcode">Post Code</label>
                        <input type="text" id="p1_postcode" name="p1_postcode">
                    </div>
                </div>
                <div class="lhf-form-row lhf-three-col">
                    <div class="lhf-form-group">
                        <label for="p1_place_of_work">Place of Work <span>*</span></label>
                        <input type="text" id="p1_place_of_work" name="p1_place_of_work" required>
                    </div>
                    <div class="lhf-form-group">
                        <label for="p1_occupation">Occupation <span>*</span></label>
                        <input type="text" id="p1_occupation" name="p1_occupation" required>
                    </div>
                    <div class="lhf-form-group">
                        <label for="p1_insurance_number">Insurance Number <span>*</span></label>
                        <input type="text" id="p1_insurance_number" name="p1_insurance_number" required>
                    </div>
                </div>
            </fieldset>

            <!-- =================================================================== -->
            <!-- Parent/Guardian Details (Parent 2) -->
            <!-- =================================================================== -->
            <fieldset>
                <h3>Parent/Guardian Details (Parent 2)</h3>
                <div class="lhf-form-row lhf-three-col">
                    <div class="lhf-form-group">
                        <label for="p2_first_name">First Name <span>*</span></label>
                        <input type="text" id="p2_first_name" name="p2_first_name" required>
                    </div>
                    <div class="lhf-form-group">
                        <label for="p2_middle_name">Middle Name</label>
                        <input type="text" id="p2_middle_name" name="p2_middle_name">
                    </div>
                    <div class="lhf-form-group">
                        <label for="p2_surname">Surname <span>*</span></label>
                        <input type="text" id="p2_surname" name="p2_surname" required>
                    </div>
                </div>
                <div class="lhf-form-row lhf-three-col">
                    <div class="lhf-form-group">
                        <label for="p2_relationship">Relationship To Child <span>*</span></label>
                        <input type="text" id="p2_relationship" name="p2_relationship" required>
                    </div>
                    <div class="lhf-form-group">
                        <label>Do you have parental responsibility? <span>*</span></label>
                        <div class="lhf-radio-group">
                            <label><input type="radio" name="p2_parental_responsibility" value="1" required> Yes</label>
                            <label><input type="radio" name="p2_parental_responsibility" value="0"> No</label>
                        </div>
                    </div>
                    <div class="lhf-form-group">
                        <label for="p2_dob">Date of Birth <span>*</span></label>
                        <input type="date" id="p2_dob" name="p2_dob" required>
                    </div>
                </div>
                <div class="lhf-form-row lhf-three-col">
                    <div class="lhf-form-group">
                        <label for="p2_email">Email <span>*</span></label>
                        <input type="email" id="p2_email" name="p2_email" required>
                    </div>
                    <div class="lhf-form-group">
                        <label for="p2_phone_home">Contact Phone (Home)</label>
                        <input type="tel" id="p2_phone_home" name="p2_phone_home">
                    </div>
                    <div class="lhf-form-group">
                        <label for="p2_mobile">Mobile <span>*</span></label>
                        <input type="tel" id="p2_mobile" name="p2_mobile" required>
                    </div>
                </div>
                <div class="lhf-form-row">
                    <div class="lhf-form-group lhf-half-width">
                        <label for="p2_work_phone">Work</label>
                        <input type="tel" id="p2_work_phone" name="p2_work_phone">
                    </div>
                </div>
                <div class="lhf-form-row">
                    <div class="lhf-form-group">
                        <label for="p2_address">Address (If Different From Child's)</label>
                        <textarea id="p2_address" name="p2_address" rows="3"></textarea>
                    </div>
                </div>
                <div class="lhf-form-row lhf-half-width">
                    <div class="lhf-form-group">
                        <label for="p2_postcode">Post Code</label>
                        <input type="text" id="p2_postcode" name="p2_postcode">
                    </div>
                </div>
                <div class="lhf-form-row lhf-three-col">
                    <div class="lhf-form-group">
                        <label for="p2_place_of_work">Place of Work <span>*</span></label>
                        <input type="text" id="p2_place_of_work" name="p2_place_of_work" required>
                    </div>
                    <div class="lhf-form-group">
                        <label for="p2_occupation">Occupation <span>*</span></label>
                        <input type="text" id="p2_occupation" name="p2_occupation" required>
                    </div>
                    <div class="lhf-form-group">
                        <label for="p2_insurance_number">National Insurance Number <span>*</span></label>
                        <input type="text" id="p2_insurance_number" name="p2_insurance_number" required>
                    </div>
                </div>
            </fieldset>

            <!-- =================================================================== -->
            <!-- Additional Information -->
            <!-- =================================================================== -->
            <fieldset>
                <h3>Additional Information</h3>
                <div class="lhf-form-group">
                    <label for="other_contacts_parental">Other Contact(s) With Parental Responsibility</label>
                    <p>Please list contacts with their full name, address, contact number and relationship to child:</p>
                    <textarea id="other_contacts_parental" name="other_contacts_parental" rows="4"></textarea>
                </div>
            </fieldset>

            <!-- =================================================================== -->
            <!-- Emergency & Household -->
            <!-- =================================================================== -->
            <fieldset>
                <h3>Emergency & Household</h3>
                <div class="lhf-form-group">
                    <label for="emergency_contacts">Other Emergency contact(s) - Please add 3 emergency Contacts
                        <span>*</span></label>
                    <p>List their full name, address, contact number and relationship to child:</p>
                    <textarea id="emergency_contacts" name="emergency_contacts" rows="5" required></textarea>
                </div>
                <div class="lhf-form-group">
                    <label for="authorized_pickup">People Authorized To Pick Your Child <span>*</span></label>
                    <p>List below, names and relationship of people authorized to pick up your child:</p>
                    <textarea id="authorized_pickup" name="authorized_pickup" rows="4" required></textarea>
                </div>
                <div class="lhf-form-group">
                    <label for="people_in_household">List People Living In Same Household</label>
                    <p>List below, names and relationship of any other people living in the same household (if applicable):
                    </p>
                    <textarea id="people_in_household" name="people_in_household" rows="4"></textarea>
                </div>
            </fieldset>

            <!-- =================================================================== -->
            <!-- Further Details -->
            <!-- =================================================================== -->
            <fieldset>
                <h3>Further Details</h3>
                <div class="lhf-form-row lhf-two-col">
                    <div class="lhf-form-group">
                        <label for="languages_spoken">What Languages Are Spoken At Home? <span>*</span></label>
                        <input type="text" id="languages_spoken" name="languages_spoken" required>
                    </div>
                    <div class="lhf-form-group">
                        <label for="child_position_family">Child's Position In Family <span>*</span></label>
                        <input type="text" id="child_position_family" name="child_position_family" required>
                    </div>
                </div>
                <div class="lhf-form-row lhf-two-col">
                    <div class="lhf-form-group">
                        <label for="other_siblings_home">List Details of Other Siblings Living At Home</label>
                        <textarea id="other_siblings_home" name="other_siblings_home" rows="4"></textarea>
                    </div>
                    <div class="lhf-form-group">
                        <label for="siblings_details">List Name(s), Date of Birth, School Attended:</label>
                        <textarea id="siblings_details" name="siblings_details" rows="4"></textarea>
                    </div>
                </div>
                <div class="lhf-form-group">
                    <label for="additional_info">Any Other Additional Information?</label>
                    <textarea id="additional_info" name="additional_info" rows="4"></textarea>
                </div>
                <div class="lhf-form-row lhf-half-width">
                    <div class="lhf-form-group">
                        <label for="pickup_password">Choose a password for use by anyone collecting your child
                            <span>*</span></label>
                        <input type="password" id="pickup_password" name="pickup_password" required>
                    </div>
                </div>
            </fieldset>

            <!-- =================================================================== -->
            <!-- Preferences & Funding -->
            <!-- =================================================================== -->
            <fieldset>
                <h3>Preferences & Funding</h3>
                <div class="lhf-form-row lhf-three-col">
                    <div class="lhf-form-group">
                        <label for="preferred_start_date">Preferred Start Date <span>*</span></label>
                        <input type="date" id="preferred_start_date" name="preferred_start_date" required>
                    </div>
                    <div class="lhf-form-group">
                        <label>Preferred Session <span>*</span></label>
                        <div class="lhf-radio-group">
                            <label><input type="radio" name="preferred_session" value="Morning" required> Morning
                                (8am-1pm)</label>
                            <label><input type="radio" name="preferred_session" value="Afternoon"> Afternoon
                                (1pm-6pm)</label>
                            <label><input type="radio" name="preferred_session" value="Full Day"> Full Day (8am-6pm)</label>
                            <label><input type="radio" name="preferred_session" value="Additional Hours"> Additional
                                Hours</label>
                        </div>
                    </div>
                    <div class="lhf-form-group">
                        <label for="additional_hours">Specify Additional Hours</label>
                        <input type="text" id="additional_hours" name="additional_hours">
                    </div>
                </div>
                <div class="lhf-form-row lhf-two-col">
                    <div class="lhf-form-group">
                        <label>Preferred Days? <span>*</span></label>
                        <div class="lhf-checkbox-group">
                            <label><input type="checkbox" name="preferred_days[]" value="Monday"> Monday</label>
                            <label><input type="checkbox" name="preferred_days[]" value="Tuesday"> Tuesday</label>
                            <label><input type="checkbox" name="preferred_days[]" value="Wednesday"> Wednesday</label>
                            <label><input type="checkbox" name="preferred_days[]" value="Thursday"> Thursday</label>
                            <label><input type="checkbox" name="preferred_days[]" value="Friday"> Friday</label>
                        </div>
                    </div>
                    <div class="lhf-form-group">
                        <label>Funding Eligibility <span>*</span></label>
                        <div class="lhf-radio-group">
                            <label><input type="radio" name="funding_eligibility" value="15 Hours" required> 15 Hours Free
                                Entitlement</label>
                            <label><input type="radio" name="funding_eligibility" value="30 Hours"> 30 Hours Free
                                Entitlement</label>
                            <label><input type="radio" name="funding_eligibility" value="Other"> Other Funding
                                Sources</label>
                            <label><input type="radio" name="funding_eligibility" value="None"> None</label>
                        </div>
                    </div>
                </div>
            </fieldset>

            <!-- =================================================================== -->
            <!-- Child Development Information -->
            <!-- =================================================================== -->
            <fieldset>
                <h3>Child Development Information</h3>
                <div class="lhf-form-group">
                    <label>Has your child attended a nursery before? <span>*</span></label>
                    <div class="lhf-radio-group">
                        <label><input type="radio" name="attended_nursery_before" value="1" required> Yes</label>
                        <label><input type="radio" name="attended_nursery_before" value="0"> No</label>
                    </div>
                    <label for="attended_nursery_details">If yes, please specify:</label>
                    <textarea id="attended_nursery_details" name="attended_nursery_details" rows="2"></textarea>
                </div>
                <div class="lhf-form-group">
                    <label>Does your child have any special educational needs or disabilities? <span>*</span></label>
                    <div class="lhf-radio-group">
                        <label><input type="radio" name="special_needs" value="1" required> Yes</label>
                        <label><input type="radio" name="special_needs" value="0"> No</label>
                    </div>
                    <label for="special_needs_details">If yes, please specify:</label>
                    <textarea id="special_needs_details" name="special_needs_details" rows="2"></textarea>
                </div>
                <div class="lhf-form-group">
                    <label for="additional_support">Kindly state any additional support required</label>
                    <textarea id="additional_support" name="additional_support" rows="3"></textarea>
                </div>
            </fieldset>

            <!-- =================================================================== -->
            <!-- Medical & Health Information -->
            <!-- =================================================================== -->
            <fieldset>
                <h3>Medical & Health Information</h3>
                <div class="lhf-form-row lhf-three-col">
                    <div class="lhf-form-group">
                        <label for="doctor_name">Doctor's Full Name</label>
                        <input type="text" id="doctor_name" name="doctor_name">
                    </div>
                    <div class="lhf-form-group">
                        <label for="health_visitor_name">Health Visitor's Name</label>
                        <input type="text" id="health_visitor_name" name="health_visitor_name">
                    </div>
                    <div class="lhf-form-group">
                        <label for="health_visitor_phone">Health Visitor's Phone</label>
                        <input type="tel" id="health_visitor_phone" name="health_visitor_phone">
                    </div>
                </div>
                <div class="lhf-form-row lhf-two-col">
                    <div class="lhf-form-group">
                        <label>Immunized Against <span>*</span></label>
                        <div class="lhf-checkbox-group">
                            <label><input type="checkbox" name="immunized_against[]" value="Polio"> Polio</label>
                            <label><input type="checkbox" name="immunized_against[]" value="Diphtheria"> Diphtheria</label>
                            <label><input type="checkbox" name="immunized_against[]" value="Tetanus"> Tetanus</label>
                            <label><input type="checkbox" name="immunized_against[]" value="Cough"> Cough</label>
                            <label><input type="checkbox" name="immunized_against[]" value="MMR"> MMR</label>
                        </div>
                    </div>
                    <div class="lhf-form-group">
                        <label>Immunisations up to date? <span>*</span></label>
                        <div class="lhf-radio-group">
                            <label><input type="radio" name="immunisations_up_to_date" value="1" required> Yes</label>
                            <label><input type="radio" name="immunisations_up_to_date" value="0"> No</label>
                        </div>
                    </div>
                </div>
                <div class="lhf-form-group">
                    <label>Any ongoing medical conditions? <span>*</span></label>
                    <div class="lhf-radio-group">
                        <label><input type="radio" name="ongoing_medical_conditions" value="1" required> Yes</label>
                        <label><input type="radio" name="ongoing_medical_conditions" value="0"> No</label>
                    </div>
                    <label for="ongoing_medical_conditions_details">If Yes, Please State:</label>
                    <textarea id="ongoing_medical_conditions_details" name="ongoing_medical_conditions_details"
                        rows="2"></textarea>
                </div>
                <div class="lhf-form-group">
                    <label>Any special health considerations? <span>*</span></label>
                    <div class="lhf-radio-group">
                        <label><input type="radio" name="special_health_considerations" value="1" required> Yes</label>
                        <label><input type="radio" name="special_health_considerations" value="0"> No</label>
                    </div>
                </div>
                <div class="lhf-form-group">
                    <label>Any allergies or special dietary requirements? <span>*</span></label>
                    <div class="lhf-radio-group">
                        <label><input type="radio" name="allergies" value="1" required> Yes</label>
                        <label><input type="radio" name="allergies" value="0"> No</label>
                    </div>
                    <label for="allergies_details">If yes, please specify:</label>
                    <textarea id="allergies_details" name="allergies_details" rows="2"></textarea>
                </div>
                <div class="lhf-form-group">
                    <label>Any medications required during nursery hours? <span>*</span></label>
                    <div class="lhf-radio-group">
                        <label><input type="radio" name="medications_required" value="1" required> Yes</label>
                        <label><input type="radio" name="medications_required" value="0"> No</label>
                    </div>
                    <label for="medications_details">If yes, please provide details:</label>
                    <textarea id="medications_details" name="medications_details" rows="2"></textarea>
                </div>
                <div class="lhf-form-group">
                    <label>Any ongoing administration of prescribed medication required? <span>*</span></label>
                    <div class="lhf-radio-group">
                        <label><input type="radio" name="prescribed_medication" value="1" required> Yes</label>
                        <label><input type="radio" name="prescribed_medication" value="0"> No</label>
                    </div>
                    <label for="prescribed_medication_details">If Yes, Please Give Details Below:</label>
                    <textarea id="prescribed_medication_details" name="prescribed_medication_details" rows="2"></textarea>
                </div>
                <div class="lhf-form-group">
                    <label for="dietary_restrictions">Dietry Restrictions <span>*</span></label>
                    <textarea id="dietary_restrictions" name="dietary_restrictions" rows="3" required></textarea>
                </div>
            </fieldset>

            <!-- =================================================================== -->
            <!-- Parental Agreements & Permissions -->
            <!-- =================================================================== -->
            <fieldset>
                <h3>Parental Agreements & Permissions</h3>
                <div class="lhf-form-row lhf-three-col">
                    <div class="lhf-form-group">
                        <label>I agree to provide necessary emergency contact information <span>*</span></label>
                        <div class="lhf-radio-group">
                            <label><input type="radio" name="agree_emergency_contact" value="1" required> Yes</label>
                            <label><input type="radio" name="agree_emergency_contact" value="0"> No</label>
                        </div>
                    </div>
                    <div class="lhf-form-group">
                        <label>I acknowledge and understand the nursery's policies and procedures <span>*</span></label>
                        <div class="lhf-radio-group">
                            <label><input type="radio" name="agree_policies" value="1" required> Yes</label>
                            <label><input type="radio" name="agree_policies" value="0"> No</label>
                        </div>
                    </div>
                    <div class="lhf-form-group">
                        <label>I consent to my child's participation in nursery activities <span>*</span></label>
                        <div class="lhf-radio-group">
                            <label><input type="radio" name="agree_activities" value="1" required> Yes</label>
                            <label><input type="radio" name="agree_activities" value="0"> No</label>
                        </div>
                    </div>
                </div>
                <!-- Permissions -->
                <div class="lhf-form-group">
                    <label>Permission for Photographs / Videos (Internal) <span>*</span></label>
                    <p>Photographs and videos of your child are taken routinely for display purposes within the nursery and
                        to record observations of your child to enable us to assess his/her development. These are shared
                        with carers via the secure nursery management system.</p>
                    <div class="lhf-radio-group">
                        <label><input type="radio" name="permission_photos_internal" value="1" required> Yes</label>
                        <label><input type="radio" name="permission_photos_internal" value="0"> No</label>
                    </div>
                </div>
                <div class="lhf-form-group">
                    <label>Permission for Photographs / Videos (social media) <span>*</span></label>
                    <p>I give my permission for static and/or moving images to be used for: Living Heritage
                        Nursery/Facebook/Instagram page/Social media platforms</p>
                    <div class="lhf-radio-group">
                        <label><input type="radio" name="permission_photos_social" value="1" required> Yes</label>
                        <label><input type="radio" name="permission_photos_social" value="0"> No</label>
                    </div>
                </div>
                <div class="lhf-form-group">
                    <label>Permission for Photographs (Photographer) <span>*</span></label>
                    <p>I give my permission for photographs to be taken of my child from time to time by an approved
                        photographer for my consideration to purchase copies. All copies not purchased will be destroyed.
                    </p>
                    <div class="lhf-radio-group">
                        <label><input type="radio" name="permission_photos_photographer" value="1" required> Yes</label>
                        <label><input type="radio" name="permission_photos_photographer" value="0"> No</label>
                    </div>
                </div>
                <div class="lhf-form-group">
                    <label>Permission for Local Outings <span>*</span></label>
                    <p>I give permission for my child to be taken on supervised visits, e.g. to local shops, local parks, or
                        to post a letter.</p>
                    <div class="lhf-radio-group">
                        <label><input type="radio" name="permission_local_outings" value="1" required> Yes</label>
                        <label><input type="radio" name="permission_local_outings" value="0"> No</label>
                    </div>
                </div>
                <div class="lhf-form-group">
                    <label>Permission for use of normal baby/childcare products <span>*</span></label>
                    <p>I give my consent to staff at Living Heritage Nursery to use all normal baby/child care products,
                        including washing products, cotton wool, and sun cream.</p>
                    <div class="lhf-radio-group">
                        <label><input type="radio" name="permission_products" value="1" required> Yes</label>
                        <label><input type="radio" name="permission_products" value="0"> No</label>
                    </div>
                    <label for="permission_products_exceptions">Please list any products you do not wish us to use on your
                        child:</label>
                    <textarea id="permission_products_exceptions" name="permission_products_exceptions" rows="2"></textarea>
                </div>
                <div class="lhf-form-group">
                    <label>Permission for sharing child's details with schools <span>*</span></label>
                    <p>I give my consent to Living Heritage Nursery to share my child's development details with the school
                        that he/she will be moving to from a LHN setting, in support of transition.</p>
                    <div class="lhf-radio-group">
                        <label><input type="radio" name="permission_share_school" value="1" required> Yes</label>
                        <label><input type="radio" name="permission_share_school" value="0"> No</label>
                    </div>
                </div>

                <div class="lhf-form-group">
                    <label>
                        <input type="checkbox" name="agree_privacy_notice" value="1" required>
                        I have read and agree with the privacy notice <span>*</span>
                    </label>
                </div>
                <div class="lhf-form-group">
                    <label>
                        <input type="checkbox" name="agree_terms" value="1" required>
                        I have read and agree to the nursery's terms and conditions. <span>*</span>
                    </label>
                </div>
            </fieldset>

            <!-- =================================================================== -->
            <!-- Submission -->
            <!-- =================================================================== -->
            <fieldset>
                <h3>Submission</h3>
                <div class="lhf-form-group lhf-final-agreement">
                    <label>
                        <input type="checkbox" name="confirm_accuracy" value="1" required>
                        By clicking submit, I confirm that the information provided is accurate and complete. I give my
                        permission for all stated above. I agree to abide by the policies and procedures of Living Heritage
                        Nursery. <span>*</span>
                    </label>
                </div>
                <div class="lhf-form-group">
                    <button type="submit" class="lhf-submit-btn">Submit Registration</button>
                </div>
            </fieldset>

        </form>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('living_heritage_registration_form', 'lhf_render_registration_form');