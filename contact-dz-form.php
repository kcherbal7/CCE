<?php
/*
Plugin Name: Contact DZ Form
Description: Advanced contact form plugin with customizable fields, shipping rates, and admin dashboard
Version: 1.1
Author: Your Name
*/

if (!defined('ABSPATH')) exit;

// Add near the top of the file, after the ABSPATH check
if (!session_id()) {
    session_start();
}

// Define plugin constants
define('CONTACT_DZ_VERSION', '1.1');
define('CONTACT_DZ_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('CONTACT_DZ_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include required files
require_once CONTACT_DZ_PLUGIN_DIR . 'includes/admin/admin-menu.php';
require_once CONTACT_DZ_PLUGIN_DIR . 'includes/admin/settings-page.php';
require_once CONTACT_DZ_PLUGIN_DIR . 'includes/admin/shipping-page.php';
require_once CONTACT_DZ_PLUGIN_DIR . 'includes/admin/products-page.php';
require_once CONTACT_DZ_PLUGIN_DIR . 'includes/admin/submissions-page.php';
require_once CONTACT_DZ_PLUGIN_DIR . 'includes/database.php';
require_once CONTACT_DZ_PLUGIN_DIR . 'includes/shortcodes.php';
require_once CONTACT_DZ_PLUGIN_DIR . 'includes/ajax-handlers.php';
require_once CONTACT_DZ_PLUGIN_DIR . 'includes/helpers.php';

// Initialize the plugin
function contact_dz_init() {
    // Enqueue scripts
    add_action('wp_enqueue_scripts', 'contact_dz_enqueue_scripts');
}
add_action('init', 'contact_dz_init');

// Add activation hook at plugin level
register_activation_hook(__FILE__, 'contact_dz_activate');

function contact_dz_activate() {
    // Create database tables
    contact_dz_create_tables();
    
    // Flush rewrite rules
    flush_rewrite_rules();
}

// Add this function to clear the session when needed
function contact_dz_clear_session() {
    if (session_id()) {
        session_destroy();
    }
}
add_action('wp_logout', 'contact_dz_clear_session');
add_action('wp_login', 'contact_dz_clear_session');