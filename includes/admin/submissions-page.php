<?php
if (!defined('ABSPATH')) exit;

function contact_dz_admin_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'contact_dz_submissions';
    
    // Handle submission deletion
    if (isset($_POST['delete_submission']) && check_admin_referer('delete_submission')) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'contact_dz_submissions';
        $submission_id = intval($_POST['submission_id']);
        
        $wpdb->delete(
            $table_name,
            ['id' => $submission_id],
            ['%d']
        );
        
        add_action('admin_notices', function() {
            echo '<div class="notice notice-success is-dismissible"><p>Submission deleted successfully.</p></div>';
        });
    }
    
    // Handle status updates
    if (isset($_POST['update_status']) && check_admin_referer('update_submission_status')) {
        $submission_id = intval($_POST['submission_id']);
        $new_status = sanitize_text_field($_POST['status']);
        $wpdb->update(
            $table_name,
            array('status' => $new_status),
            array('id' => $submission_id),
            array('%s'),
            array('%d')
        );
    }
    
    // Handle export
    if (isset($_POST['export_submissions']) && check_admin_referer('export_submissions')) {
        contact_dz_export_submissions();
    }
    
    $submissions = $wpdb->get_results("SELECT * FROM $table_name ORDER BY submission_date DESC");
    
    // Include submissions page template
    require_once CONTACT_DZ_PLUGIN_DIR . 'templates/admin/submissions.php';
}

function contact_dz_export_submissions() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'contact_dz_submissions';
    $submissions = $wpdb->get_results("SELECT full_name, phone_number, address, status FROM $table_name ORDER BY submission_date DESC", ARRAY_A);
    
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename="submissions-export.csv"');
    
    $fp = fopen('php://output', 'w');
    
    // Add headers
    fputcsv($fp, array('Full Name', 'Phone Number', 'Address', 'Status'));
    
    // Add data
    foreach ($submissions as $submission) {
        fputcsv($fp, $submission);
    }
    
    fclose($fp);
    exit;
}