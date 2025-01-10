<?php
if (!defined('ABSPATH')) exit;

function contact_dz_settings_page() {
    // Handle form submission
    if (isset($_POST['update_settings'])) {
        // Verify nonce before processing form
        check_admin_referer('update_settings');
        
        // Process form submission
        $form_title = array(
            'text' => sanitize_text_field($_POST['form_title']),
            'enabled' => isset($_POST['title_enabled']),
            'css' => sanitize_textarea_field($_POST['title_css'])
        );
        update_option('form_title', $form_title);
        
        // Update fields
        if (isset($_POST['fields'])) {
            $fields = array();
            foreach ($_POST['fields'] as $key => $field) {
                $fields[$key] = array(
                    'label' => sanitize_text_field($field['label']),
                    'required' => isset($field['required']),
                    'enabled' => isset($field['enabled'])
                );
            }
            update_option('contact_dz_fields', $fields);
        }
        
        // Update text settings
        update_option('button_text', sanitize_text_field($_POST['button_text']));
        update_option('shipping_text', sanitize_text_field($_POST['shipping_text']));
        
        // Show success message
        add_settings_error(
            'contact_dz_messages',
            'contact_dz_message',
            'Settings saved successfully.',
            'updated'
        );
    }

    // Get current settings
    $form_title = get_option('form_title', array(
        'text' => '',
        'enabled' => false,
        'css' => ''
    ));

    $fields = get_option('contact_dz_fields', array(
        'full_name' => array('label' => 'Full Name', 'required' => true, 'enabled' => true),
        'phone_number' => array('label' => 'Phone Number', 'required' => true, 'enabled' => true),
        'address' => array('label' => 'Address', 'required' => true, 'enabled' => true),
        'wilaya' => array('label' => 'Wilaya', 'required' => true, 'enabled' => true)
    ));
    
    $button_text = get_option('button_text', 'Submit');
    $shipping_text = get_option('shipping_text', 'Shipping Cost:');

    // Display settings form
    require_once CONTACT_DZ_PLUGIN_DIR . 'templates/admin/settings.php';
}