<?php
if (!defined('ABSPATH')) exit;

function get_algeria_wilayas() {
    return array(
        '01-Adrar', '02-Chlef', '03-Laghouat', '04-Oum El Bouaghi', '05-Batna', '06-Béjaïa', '07-Biskra',
        '08-Béchar', '09-Blida', '10-Bouira', '11-Tamanrasset', '12-Tébessa', '13-Tlemcen', '14-Tiaret',
        '15-Tizi Ouzou', '16-Alger', '17-Djelfa', '18-Jijel', '19-Sétif', '20-Saïda', '21-Skikda',
        '22-Sidi Bel Abbès', '23-Annaba', '24-Guelma', '25-Constantine', '26-Médéa', '27-Mostaganem',
        '28-M\'Sila', '29-Mascara', '30-Ouargla', '31-Oran', '32-El Bayadh', '33-Illizi', '34-Bordj Bou Arréridj',
        '35-Boumerdès', '36-El Tarf', '37-Tindouf', '38-Tissemsilt', '39-El Oued', '40-Khenchela',
        '41-Souk Ahras', '42-Tipaza', '43-Mila', '44-Aïn Defla', '45-Naâma', '46-Aïn Témouchent',
        '47-Ghardaïa', '48-Relizane', '49-Timimoun', '50-Bordj Badji Mokhtar', '51-Ouled Djellal',
        '52-Béni Abbès', '53-In Salah', '54-In Guezzam', '55-Touggourt', '56-Djanet', '57-El M\'Ghair',
        '58-El Meniaa'
    );
}

function contact_dz_enqueue_scripts() {
    wp_enqueue_script('jquery');
}

function generate_product_id() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'contact_dz_products';
    
    // Keep trying until we find an unused ID
    while (true) {
        // Generate random number between 1 and 1000
        $product_id = rand(1, 1000);
        
        // Check if this ID already exists
        $exists = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $table_name WHERE product_id = %d",
            $product_id
        ));
        
        // If ID doesn't exist, return it
        if ($exists == 0) {
            return $product_id;
        }
    }
}