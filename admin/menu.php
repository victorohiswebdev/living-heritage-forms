<?php
/**
 * @package Living_Heritage_Forms
 *
 * Creates the admin menu and pages.
 */

if (!defined('WPINC')) {
    die;
}

// ====================================================================
// THIS IS THE FIX: We must include the class file before using it.
require_once LHF_PLUGIN_DIR . 'admin/class-submissions-list-table.php';
// ====================================================================

add_action('admin_menu', 'lhf_add_admin_menu');
add_action('admin_init', 'lhf_handle_row_actions');

function lhf_add_admin_menu()
{
    add_menu_page('Form Submissions', 'LH Forms', 'manage_options', 'lhf-submissions', 'lhf_render_submissions_page', 'dashicons-feedback', 25);
    add_submenu_page('lhf-submissions', 'All Submissions', 'All Submissions', 'manage_options', 'lhf-submissions', 'lhf_render_submissions_page');
    add_submenu_page('lhf-submissions', 'Settings', 'Settings', 'manage_options', 'lhf-settings', 'lhf_render_settings_page');
}

function lhf_handle_row_actions()
{
    if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id']) && isset($_GET['_wpnonce']) && wp_verify_nonce($_GET['_wpnonce'], 'lhf_delete_submission_' . $_GET['id'])) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'lh_form_submissions';
        $id = absint($_GET['id']);
        $wpdb->delete($table_name, ['id' => $id], ['%d']);
        wp_redirect(admin_url('admin.php?page=lhf-submissions&message=deleted'));
        exit;
    }
}

function lhf_render_submissions_page()
{
    if (isset($_GET['action']) && $_GET['action'] === 'view' && isset($_GET['id'])) {
        lhf_render_single_submission_view(absint($_GET['id']));
    } else {
        lhf_render_submissions_list_table();
    }
}

function lhf_render_submissions_list_table()
{
    $submissions_list_table = new LHF_Submissions_List_Table();
    $submissions_list_table->prepare_items();
    ?>
    <div class="wrap">
        <h1 class="wp-heading-inline">Registration Submissions</h1>
        <hr class="wp-header-end">
        <?php if (isset($_GET['message']) && $_GET['message'] === 'deleted'): ?>
            <div id="message" class="updated notice is-dismissible">
                <p>Submission deleted successfully.</p>
            </div>
        <?php endif; ?>
        <?php $submissions_list_table->views(); // Manually render views ?>
        <form method="post">
            <?php $submissions_list_table->display(); ?>
        </form>
    </div>
    <?php
}

function lhf_render_single_submission_view($id)
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'lh_form_submissions';
    $submission = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id), ARRAY_A);
    if (!$submission) {
        echo '<div class="wrap"><h2>Submission not found</h2><p>Could not find a submission with this ID.</p></div>';
        return;
    }
    ?>
    <div class="wrap">
        <h1>Viewing Submission #<?php echo esc_html($id); ?></h1>
        <p><strong>Submitted on:</strong>
            <?php echo esc_html(date('F j, Y, g:i a', strtotime($submission['submission_date']))); ?></p>
        <a href="<?php echo admin_url('admin.php?page=lhf-submissions'); ?>" class="button">&larr; Back to All
            Submissions</a>
        <hr>
        <table class="form-table">
            <?php foreach ($submission as $key => $value):
                // Treat the string "0" as a valid stored value — don't drop it.
                if (!isset($value) || $value === '' || $key === 'id')
                    continue;
                $label = ucwords(str_replace('_', ' ', $key));
                ?>
                <tr>
                    <th scope="row" style="text-align: left; vertical-align: top; width: 200px;">
                        <?php echo esc_html($label); ?>
                    </th>
                    <td><?php echo nl2br(esc_html($value)); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <?php
}