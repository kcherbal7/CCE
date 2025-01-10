<?php
if (!defined('ABSPATH')) exit;

function contact_dz_shipping_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'contact_dz_shipping_rates';
    
    if (isset($_POST['update_shipping_rates']) && check_admin_referer('update_shipping_rates')) {
        $wilayas = get_algeria_wilayas();
        foreach ($wilayas as $wilaya) {
            if (isset($_POST['shipping_rate'][$wilaya])) {
                $price = floatval($_POST['shipping_rate'][$wilaya]);
                $wpdb->update(
                    $table_name,
                    array('price' => $price),
                    array('wilaya' => $wilaya),
                    array('%f'),
                    array('%s')
                );
            }
        }
        echo '<div class="updated"><p>Shipping rates updated successfully!</p></div>';
    }
    
    // Get current rates
    $rates = $wpdb->get_results("SELECT * FROM $table_name ORDER BY wilaya");
    
    // Include shipping page template
    require_once CONTACT_DZ_PLUGIN_DIR . 'templates/admin/shipping-rates.php';
}