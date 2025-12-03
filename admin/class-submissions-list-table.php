<?php
/**
 * @package Living_Heritage_Forms
 *
 * This class creates the list table for viewing submissions.
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

// We need to extend the WP_List_Table class
if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class LHF_Submissions_List_Table extends WP_List_Table
{

    /**
     * Prepare the items for the table to process
     */
    public function prepare_items()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'lh_form_submissions';

        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();

        $this->_column_headers = [$columns, $hidden, $sortable];

        $per_page = 20;
        $current_page = $this->get_pagenum();
        $offset = ($current_page - 1) * $per_page;

        // Order by logic
        $orderby = isset($_GET['orderby']) ? sanitize_sql_orderby($_GET['orderby']) : 'submission_date';
        $order = isset($_GET['order']) ? strtoupper($_GET['order']) : 'DESC';

        // Get the total number of items
        $total_items = $wpdb->get_var("SELECT COUNT(id) FROM $table_name");

        // Get the data
        $this->items = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT id, submission_date, child_first_name, child_surname, p1_first_name, p1_surname, p1_email FROM $table_name ORDER BY $orderby $order LIMIT %d OFFSET %d",
                $per_page,
                $offset
            ),
            ARRAY_A
        );

        // Set pagination arguments
        $this->set_pagination_args([
            'total_items' => $total_items,
            'per_page' => $per_page,
            'total_pages' => ceil($total_items / $per_page)
        ]);
    }

    /**
     *  Define the columns that are going to be used in the table
     * @return array $columns, the array of columns to use with the table
     */
    public function get_columns()
    {
        return [
            'cb' => '<input type="checkbox" />', // For bulk actions
            'child_name' => __('Child\'s Name', 'living-heritage-forms'),
            'parent_name' => __('Parent\'s Name (P1)', 'living-heritage-forms'),
            'parent_email' => __('Parent\'s Email (P1)', 'living-heritage-forms'),
            'submission_date' => __('Submission Date', 'living-heritage-forms')
        ];
    }

    /**
     * Define which columns are hidden
     * @return array
     */
    public function get_hidden_columns()
    {
        return [];
    }

    /**
     * Define the sortable columns
     * @return array
     */
    public function get_sortable_columns()
    {
        return [
            'child_name' => ['child_first_name', false],
            'submission_date' => ['submission_date', true] // True means it's sorted DESC by default
        ];
    }

    /**
     * Define what data to show on each column of the table
     */
    public function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'submission_date':
                return date('F j, Y, g:i a', strtotime($item[$column_name]));
            default:
                return print_r($item, true); // For debugging
        }
    }

    /**
     * Renders the 'child_name' column.
     */
    protected function column_child_name($item)
    {
        $full_name = esc_html($item['child_first_name']) . ' ' . esc_html($item['child_surname']);

        // Add nonce to the delete link for security
        $delete_nonce = wp_create_nonce('lhf_delete_submission_' . $item['id']);

        $actions = [
            'view' => sprintf('<a href="?page=%s&action=view&id=%s">View</a>', $_REQUEST['page'], $item['id']),
            'delete' => sprintf('<a href="?page=%s&action=delete&id=%s&_wpnonce=%s" style="color:#a00;" onclick="return confirm(\'Are you sure you want to delete this submission?\');">Delete</a>', $_REQUEST['page'], $item['id'], $delete_nonce),
        ];
        return $full_name . $this->row_actions($actions);
    }

    /**
     * Renders the 'parent_name' column.
     */
    protected function column_parent_name($item)
    {
        return esc_html($item['p1_first_name']) . ' ' . esc_html($item['p1_surname']);
    }

    /**
     * Renders the 'parent_email' column with a mailto link.
     */
    protected function column_parent_email($item)
    {
        $email = esc_attr($item['p1_email']);
        return "<a href='mailto:{$email}'>{$email}</a>";
    }

    /**
     * Renders the checkbox column.
     */
    protected function column_cb($item)
    {
        return sprintf('<input type="checkbox" name="submission[]" value="%s" />', $item['id']);
    }
}