<?php
if (!defined('ABSPATH')) exit;

function contact_dz_products_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'contact_dz_products';
    
    // Handle delete action
    if (isset($_POST['delete_product']) && check_admin_referer('delete_product')) {
        $product_id = intval($_POST['product_id']);
        $wpdb->delete($table_name, array('id' => $product_id), array('%d'));
        echo '<div class="updated"><p>Product deleted successfully!</p></div>';
    }
    
    $products = $wpdb->get_results("SELECT * FROM $table_name ORDER BY name");
    
    // Include products page template
    require_once CONTACT_DZ_PLUGIN_DIR . 'templates/admin/products.php';
}

function contact_dz_manage_product_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'contact_dz_products';
    
    $product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $product = $product_id ? $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM $table_name WHERE id = %d",
        $product_id
    )) : null;
    
    if (isset($_POST['save_product']) && check_admin_referer('save_product')) {
        $name = sanitize_text_field($_POST['name']);
        $price = floatval($_POST['price']);
        $quantity = intval($_POST['quantity']);
        $image_url = esc_url_raw($_POST['image_url']);
        $sizes = isset($_POST['sizes']) ? sanitize_textarea_field($_POST['sizes']) : '';
        $colors = isset($_POST['colors']) ? sanitize_textarea_field($_POST['colors']) : '';
        $show_size = isset($_POST['show_size']) ? 1 : 0;
        $show_color = isset($_POST['show_color']) ? 1 : 0;
        $require_size = isset($_POST['require_size']) ? 1 : 0;
        $require_color = isset($_POST['require_color']) ? 1 : 0;
        
        // Generate shortcode from name
        $shortcode = sanitize_title($name);
        
        $data = array(
            'name' => $name,
            'price' => $price,
            'quantity' => $quantity,
            'image_url' => $image_url,
            'sizes' => $sizes,
            'colors' => $colors,
            'show_size' => $show_size,
            'show_color' => $show_color,
            'require_size' => $require_size,
            'require_color' => $require_color,
            'shortcode' => $shortcode
        );
        
        if ($product_id) {
            $wpdb->update($table_name, $data, array('id' => $product_id));
        } else {
            $wpdb->insert($table_name, $data);
            $product_id = $wpdb->insert_id;
        }
        
        echo '<div class="updated"><p>Product saved successfully!</p></div>';
        $product = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table_name WHERE id = %d",
            $product_id
        ));
    }
    
    // Include manage product template
    require_once CONTACT_DZ_PLUGIN_DIR . 'templates/admin/manage-product.php';
}