<?php
/**
 * @package Living_Heritage_Forms
 */

if (!defined('WPINC')) {
    die;
}

if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class LHF_Submissions_List_Table extends WP_List_Table
{

    public function prepare_items()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'lh_form_submissions';
        $this->_column_headers = [$this->get_columns(), $this->get_hidden_columns(), $this->get_sortable_columns()];

        $per_page = 20;
        $current_page = $this->get_pagenum();
        $offset = ($current_page - 1) * $per_page;

        $orderby = isset($_GET['orderby']) ? sanitize_sql_orderby($_GET['orderby']) : 'submission_date';
        $order = isset($_GET['order']) && in_array(strtoupper($_GET['order']), ['ASC', 'DESC']) ? strtoupper($_GET['order']) : 'DESC';

        $where_clause = '';
        $form_type = isset($_GET['form_type']) ? sanitize_key($_GET['form_type']) : '';
        if (in_array($form_type, ['registration', 'permissions'])) {
            $where_clause = $wpdb->prepare("WHERE form_type = %s", $form_type);
        }

        $total_items = $wpdb->get_var("SELECT COUNT(id) FROM $table_name $where_clause");

        $this->items = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT id, form_type, submission_date, child_first_name, child_surname, p1_first_name, p1_surname, p1_email FROM $table_name $where_clause ORDER BY $orderby $order LIMIT %d OFFSET %d",
                $per_page,
                $offset
            ),
            ARRAY_A
        );

        $this->set_pagination_args([
            'total_items' => $total_items,
            'per_page' => $per_page,
            'total_pages' => ceil($total_items / $per_page)
        ]);
    }

    protected function get_views()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'lh_form_submissions';
        $current = isset($_GET['form_type']) ? $_GET['form_type'] : '';

        $total_count = $wpdb->get_var("SELECT COUNT(id) FROM $table_name");
        $reg_count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(id) FROM $table_name WHERE form_type = %s", 'registration'));
        $perm_count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(id) FROM $table_name WHERE form_type = %s", 'permissions'));

        $base_url = admin_url('admin.php?page=lhf-submissions');

        $views = [
            'all' => sprintf('<a href="%s" class="%s">All <span class="count">(%d)</span></a>', esc_url($base_url), $current === '' ? 'current' : '', $total_count),
            'registration' => sprintf('<a href="%s" class="%s">Full Registrations <span class="count">(%d)</span></a>', esc_url(add_query_arg('form_type', 'registration', $base_url)), $current === 'registration' ? 'current' : '', $reg_count),
            'permissions' => sprintf('<a href="%s" class="%s">Permissions Forms <span class="count">(%d)</span></a>', esc_url(add_query_arg('form_type', 'permissions', $base_url)), $current === 'permissions' ? 'current' : '', $perm_count)
        ];

        return $views;
    }

    public function get_columns()
    {
        return ['cb' => '<input type="checkbox" />', 'child_name' => 'Child\'s Name', 'parent_name' => 'Parent\'s Name (P1)', 'parent_email' => 'Parent\'s Email (P1)', 'submission_date' => 'Submission Date'];
    }

    // ====================================================================
    // THIS IS THE FIX: This function MUST return an array.
    public function get_hidden_columns()
    {
        return [];
    }
    // ====================================================================

    public function get_sortable_columns()
    {
        return ['child_name' => ['child_first_name', false], 'submission_date' => ['submission_date', true]];
    }

    // ====================================================================
    // THIS IS THE SECOND FIX: A default case is required.
    public function column_default($item, $column_name)
    {
        switch ($column_name) {
            default:
                return isset($item[$column_name]) ? esc_html($item[$column_name]) : 'N/A';
        }
    }
    // ====================================================================

    protected function column_submission_date($item)
    {
        return date('F j, Y, g:i a', strtotime($item['submission_date']));
    }

    protected function column_child_name($item)
    {
        $full_name = esc_html($item['child_first_name']) . ' ' . esc_html($item['child_surname']);
        $delete_nonce = wp_create_nonce('lhf_delete_submission_' . $item['id']);
        $actions = [
            'view' => sprintf('<a href="?page=%s&action=view&id=%s">View</a>', $_REQUEST['page'], $item['id']),
            'delete' => sprintf('<a href="?page=%s&action=delete&id=%s&_wpnonce=%s" style="color:#a00;" onclick="return confirm(\'Are you sure?\');">Delete</a>', $_REQUEST['page'], $item['id'], $delete_nonce)
        ];
        return $full_name . $this->row_actions($actions);
    }

    protected function column_parent_name($item)
    {
        return esc_html($item['p1_first_name']) . ' ' . esc_html($item['p1_surname']);
    }

    protected function column_parent_email($item)
    {
        $email = esc_attr($item['p1_email']);
        return "<a href='mailto:{$email}'>{$email}</a>";
    }

    protected function column_cb($item)
    {
        return sprintf('<input type="checkbox" name="submission[]" value="%s" />', $item['id']);
    }
}