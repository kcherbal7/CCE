<?php
if (!defined('ABSPATH')) exit;

// Add admin menu
add_action('admin_menu', 'contact_dz_admin_menu');
function contact_dz_admin_menu() {
    add_menu_page(
        'Orders',
        'Orders',
        'manage_options',
        'contact-dz-form',
        'contact_dz_admin_page',
        'dashicons-store',
        30
    );
    
    add_submenu_page(
        'contact-dz-form',
        'Settings',
        'Settings',
        'manage_options',
        'contact-dz-form-settings',
        'contact_dz_settings_page'
    );
    
    add_submenu_page(
        'contact-dz-form',
        'Shipping Rates',
        'Shipping Rates',
        'manage_options',
        'contact-dz-shipping',
        'contact_dz_shipping_page'
    );
    
    add_submenu_page(
        'contact-dz-form',
        'Products',
        'Products',
        'manage_options',
        'contact-dz-products',
        'contact_dz_products_page'
    );
    
    add_submenu_page(
        null,
        'Manage Product',
        'Manage Product',
        'manage_options',
        'contact-dz-manage-product',
        'contact_dz_manage_product_page'
    );
}