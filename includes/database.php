<?php
if (!defined('ABSPATH')) exit;

function contact_dz_create_tables() {
    // Include WordPress upgrade utilities
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    
    // Submissions table
    $table_name = $wpdb->prefix . 'contact_dz_submissions';
    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        submission_id varchar(50) NOT NULL,
        submission_date datetime DEFAULT CURRENT_TIMESTAMP,
        product_id mediumint(9) NOT NULL,
        product_name varchar(255) NOT NULL,
        product_price decimal(10,2) NOT NULL,
        shipping_price decimal(10,2) NOT NULL,
        total_price decimal(10,2) NOT NULL,
        full_name varchar(100) NOT NULL,
        phone_number varchar(20) NOT NULL,
        address text NOT NULL,
        wilaya varchar(50) NOT NULL,
        status varchar(20) DEFAULT 'pending',
        PRIMARY KEY  (id),
        UNIQUE KEY submission_id (submission_id)
    ) $charset_collate;";
    dbDelta($sql);
    
    // Shipping rates table
    $shipping_table = $wpdb->prefix . 'contact_dz_shipping_rates';
    $sql2 = "CREATE TABLE IF NOT EXISTS $shipping_table (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        wilaya varchar(50) NOT NULL UNIQUE,
        price decimal(10,2) NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";
    dbDelta($sql2);
    
    // Products table
    $products_table = $wpdb->prefix . 'contact_dz_products';
    $sql3 = "CREATE TABLE IF NOT EXISTS $products_table (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        name varchar(255) NOT NULL,
        price decimal(10,2) NOT NULL,
        quantity int NOT NULL DEFAULT 0,
        image_url text,
        sizes text,
        colors text,
        show_size tinyint(1) DEFAULT 1,
        show_color tinyint(1) DEFAULT 1,
        require_size tinyint(1) DEFAULT 0,
        require_color tinyint(1) DEFAULT 0,
        shortcode varchar(50) UNIQUE,
        PRIMARY KEY (id)
    ) $charset_collate;";
    dbDelta($sql3);
    
    // Initialize default form fields if not exist
    if (!get_option('contact_dz_fields')) {
        update_option('contact_dz_fields', array(
            'full_name' => array('label' => 'Full Name', 'required' => true, 'enabled' => true),
            'phone_number' => array('label' => 'Phone Number', 'required' => true, 'enabled' => true),
            'address' => array('label' => 'Address', 'required' => true, 'enabled' => true),
            'wilaya' => array('label' => 'Wilaya', 'required' => true, 'enabled' => true)
        ));
    }
    
    // Initialize shipping rates
    initialize_shipping_rates();
}

function initialize_shipping_rates() {
    global $wpdb;
    $shipping_table = $wpdb->prefix . 'contact_dz_shipping_rates';
    
    // Default shipping price for all wilayas
    $default_price = 800.00;
    
    $wilayas = array(
        '01-Adrar' => $default_price,
        '02-Chlef' => $default_price,
        '03-Laghouat' => $default_price,
        '04-Oum El Bouaghi' => $default_price,
        '05-Batna' => $default_price,
        '06-Béjaïa' => $default_price,
        '07-Biskra' => $default_price,
        '08-Béchar' => $default_price,
        '09-Blida' => $default_price,
        '10-Bouira' => $default_price,
        '11-Tamanrasset' => $default_price,
        '12-Tébessa' => $default_price,
        '13-Tlemcen' => $default_price,
        '14-Tiaret' => $default_price,
        '15-Tizi Ouzou' => $default_price,
        '16-Alger' => $default_price,
        '17-Djelfa' => $default_price,
        '18-Jijel' => $default_price,
        '19-Sétif' => $default_price,
        '20-Saïda' => $default_price,
        '21-Skikda' => $default_price,
        '22-Sidi Bel Abbès' => $default_price,
        '23-Annaba' => $default_price,
        '24-Guelma' => $default_price,
        '25-Constantine' => $default_price,
        '26-Médéa' => $default_price,
        '27-Mostaganem' => $default_price,
        '28-M\'Sila' => $default_price,
        '29-Mascara' => $default_price,
        '30-Ouargla' => $default_price,
        '31-Oran' => $default_price,
        '32-El Bayadh' => $default_price,
        '33-Illizi' => $default_price,
        '34-Bordj Bou Arréridj' => $default_price,
        '35-Boumerdès' => $default_price,
        '36-El Tarf' => $default_price,
        '37-Tindouf' => $default_price,
        '38-Tissemsilt' => $default_price,
        '39-El Oued' => $default_price,
        '40-Khenchela' => $default_price,
        '41-Souk Ahras' => $default_price,
        '42-Tipaza' => $default_price,
        '43-Mila' => $default_price,
        '44-Aïn Defla' => $default_price,
        '45-Naâma' => $default_price,
        '46-Aïn Témouchent' => $default_price,
        '47-Ghardaïa' => $default_price,
        '48-Relizane' => $default_price,
        '49-Timimoun' => $default_price,
        '50-Bordj Badji Mokhtar' => $default_price,
        '51-Ouled Djellal' => $default_price,
        '52-Béni Abbès' => $default_price,
        '53-In Salah' => $default_price,
        '54-In Guezzam' => $default_price,
        '55-Touggourt' => $default_price,
        '56-Djanet' => $default_price,
        '57-El M\'Ghair' => $default_price,
        '58-El Meniaa' => $default_price
    );
    
    foreach ($wilayas as $wilaya => $price) {
        $wpdb->replace(
            $shipping_table,
            array(
                'wilaya' => $wilaya,
                'price' => $price
            ),
            array('%s', '%f')
        );
    }
}