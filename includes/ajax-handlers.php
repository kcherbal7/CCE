<?php
if (!defined('ABSPATH')) exit;

// Register AJAX handlers
add_action('wp_ajax_get_shipping_price', 'get_shipping_price');
add_action('wp_ajax_nopriv_get_shipping_price', 'get_shipping_price');
add_action('wp_ajax_contact_dz_submit', 'handle_form_submission');
add_action('wp_ajax_nopriv_contact_dz_submit', 'handle_form_submission');

function get_shipping_price() {
    // Verify nonce with correct nonce name
    if (!check_ajax_referer('contact_dz_nonce', 'nonce', false)) {
        wp_send_json_error(array('message' => 'Security check failed'));
        return;
    }

    if (!isset($_POST['wilaya'])) {
        wp_send_json_error(array('message' => 'Wilaya not specified'));
        return;
    }

    global $wpdb;
    $wilaya = sanitize_text_field($_POST['wilaya']);
    $table_name = $wpdb->prefix . 'contact_dz_shipping_rates';
    
    // Debug logging
    error_log('Looking up shipping price for wilaya: ' . $wilaya);
    
    $price = $wpdb->get_var($wpdb->prepare(
        "SELECT price FROM $table_name WHERE wilaya = %s",
        $wilaya
    ));
    
    if ($price === null) {
        error_log('No shipping price found for wilaya: ' . $wilaya);
        wp_send_json_error(array('message' => 'Shipping price not found for ' . $wilaya));
        return;
    }

    wp_send_json_success(array('price' => floatval($price)));
}

function generate_submission_id() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'contact_dz_submissions';
    
    // Generate a random alphanumeric code (2 letters + 4 numbers)
    while (true) {
        // Generate 2 random uppercase letters
        $letters = chr(rand(65, 90)) . chr(rand(65, 90));
        // Generate 4 random numbers
        $numbers = sprintf("%04d", rand(0, 9999));
        $submission_id = $letters . $numbers;
        
        // Check if this ID already exists
        $exists = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $table_name WHERE submission_id = %s",
            $submission_id
        ));
        
        // If ID doesn't exist, return it
        if ($exists == 0) {
            return $submission_id;
        }
    }
}

function handle_form_submission() {
    try {
        global $wpdb;
        $table_name = $wpdb->prefix . 'contact_dz_submissions';

        // Add debug logging
        error_log('Form submission started');
        error_log('POST data: ' . print_r($_POST, true));

        // Verify nonce
        if (!check_ajax_referer('contact_dz_submit', 'contact_dz_nonce_field', false)) {
            wp_send_json_error(array(
                'message' => 'Security check failed',
                'success' => false
            ));
            return;
        }

        // Validate field lengths and format
        $full_name = sanitize_text_field($_POST['full_name']);
        $phone_number = sanitize_text_field($_POST['phone_number']);
        $address = sanitize_textarea_field($_POST['address']);
        $wilaya = sanitize_text_field($_POST['wilaya']);

        // Add length validation
        if (strlen($full_name) > 100) {
            wp_send_json_error(array(
                'message' => 'Full name is too long (maximum 100 characters)',
                'success' => false
            ));
            return;
        }

        if (strlen($phone_number) > 20) {
            wp_send_json_error(array(
                'message' => 'Phone number is too long (maximum 20 characters)',
                'success' => false
            ));
            return;
        }

        if (strlen($address) > 500) {  // Adjust limit as needed
            wp_send_json_error(array(
                'message' => 'Address is too long (maximum 500 characters)',
                'success' => false
            ));
            return;
        }

        // Check for form token to prevent duplicate submissions
        $form_token = sanitize_text_field($_POST['form_token'] ?? '');
        $transient_key = 'form_submission_' . $form_token;
        
        if (get_transient($transient_key)) {
            wp_send_json_error(array(
                'message' => 'This form has already been submitted',
                'duplicate' => true,
                'success' => false
            ));
            return;
        }

        // Validate required fields
        $required_fields = array('full_name', 'phone_number', 'address', 'wilaya');
        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                wp_send_json_error(array(
                    'message' => 'Please fill in all required fields',
                    'success' => false
                ));
                return;
            }
        }

        // Generate submission ID before preparing data
        $submission_id = generate_submission_id();

        // Prepare data for database insertion
        $data = array(
            'submission_id' => $submission_id,
            'product_name' => sanitize_text_field($_POST['product_name']),
            'product_price' => floatval($_POST['product_price']),
            'shipping_price' => floatval($_POST['shipping_price']),
            'total_price' => floatval($_POST['total_price']),
            'full_name' => $full_name,
            'phone_number' => $phone_number,
            'address' => $address,
            'wilaya' => $wilaya,
            'status' => 'pending'
        );

        // Store submission in database
        $result = $wpdb->insert(
            $table_name,
            $data,
            array(
                '%s', // submission_id
                '%s', // product_name
                '%f', // product_price
                '%f', // shipping_price
                '%f', // total_price
                '%s', // full_name
                '%s', // phone_number
                '%s', // address
                '%s', // wilaya
                '%s'  // status
            )
        );

        if ($result === false) {
            delete_transient($transient_key); // Clean up transient if insert fails
            throw new Exception('Database error: ' . $wpdb->last_error);
        }

        wp_send_json_success(array(
            'message' => sprintf('Your order has been submitted successfully. Order ID: %s', $submission_id),
            'submission_id' => $submission_id,
            'success' => true
        ));
        
    } catch (Exception $e) {
        error_log('Submission error: ' . $e->getMessage());
        wp_send_json_error(array(
            'message' => 'An error occurred while processing your submission. Please try again.',
            'success' => false
        ));
    }
}