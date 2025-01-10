<?php
if (!defined('ABSPATH')) exit;

// Register shortcodes
add_shortcode('contact_dz_form', 'contact_dz_shortcode');
add_action('init', 'register_product_shortcodes');

add_action('init', function() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'contact_dz_products';
    $products = $wpdb->get_results("SELECT * FROM $table_name");
    error_log('Available products: ' . print_r($products, true));
});

function contact_dz_shortcode($atts = []) {
    // Enqueue necessary scripts and styles
    wp_enqueue_script('jquery');
    wp_enqueue_script('contact-dz-form', CONTACT_DZ_PLUGIN_URL . 'assets/js/form.js', array('jquery'), CONTACT_DZ_VERSION, true);
    
    // Create nonce for AJAX requests
    $nonce = wp_create_nonce('contact_dz_nonce');
    
    // Localize script with AJAX URL and nonce
    wp_localize_script('contact-dz-form', 'contactDZ', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => $nonce
    ));

    // Get form settings
    $form_title = get_option('form_title', array(
        'text' => '',
        'enabled' => false,
        'css' => ''
    ));
    
    $fields = get_option('contact_dz_fields');
    $button_text = get_option('button_text', 'Submit');
    $shipping_text = get_option('shipping_text', 'Shipping Cost:');
    
    // Include and return the form template
    ob_start();
    include CONTACT_DZ_PLUGIN_DIR . 'templates/form.php';
    return ob_get_clean();
}

function register_product_shortcodes() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'contact_dz_products';
    
    // Add debug logging
    error_log('Registering product shortcodes');
    
    $products = $wpdb->get_results("SELECT id, shortcode FROM $table_name");
    error_log('Found products: ' . print_r($products, true));
    
    foreach ($products as $product) {
        // Ensure we have a valid shortcode
        $shortcode = !empty($product->shortcode) ? $product->shortcode : 'product_' . $product->id;
        
        // Remove any existing shortcode before adding
        remove_shortcode($shortcode);
        
        // Add the shortcode with a closure to pass the product ID
        add_shortcode($shortcode, function($atts) use ($product) {
            return contact_dz_product_shortcode(['product_id' => $product->id]);
        });
        
        error_log('Registered shortcode: ' . $shortcode);
    }
}

function contact_dz_product_shortcode($atts) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'contact_dz_products';
    
    // Get product ID from attributes
    $product_id = isset($atts['product_id']) ? intval($atts['product_id']) : 0;
    
    // Get product details
    $product = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM $table_name WHERE id = %d",
        $product_id
    ));
    
    if (!$product) {
        return sprintf('Product not found (ID: %d)', $product_id);
    }
    
    // Format price with thousand separator
    $formatted_price = number_format($product->price, 0, '.', ',');
    
    ob_start();
    ?>
    <div class="contact-dz-product-container">
        <div class="product-image">
            <?php if ($product->image_url): ?>
                <img src="<?php echo esc_url($product->image_url); ?>" 
                     alt="<?php echo esc_attr($product->name); ?>">
            <?php endif; ?>
        </div>
        <div class="product-details">
            <div class="product-info">
                <h2 class="product-name"><?php echo esc_html($product->name); ?></h2>
                <div class="product-price"><?php echo esc_html($formatted_price); ?> DZD</div>
            </div>
            <div class="product-form desktop-only">
                <?php echo contact_dz_shortcode(['product_id' => $product_id]); ?>
            </div>
        </div>
        <div class="product-form mobile-only">
            <?php echo contact_dz_shortcode(['product_id' => $product_id]); ?>
        </div>
    </div>

    <style>
    .contact-dz-product-container {
        display: flex;
        flex-direction: row;
        gap: 2rem;
        max-width: 1200px;
        margin: 2rem auto;
        padding: 2rem;
        background: #ffffff;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .product-image {
        flex: 0 0 45%;
        max-width: 600px;
        display: flex;
        align-items: center;
    }

    .product-image img {
        width: 100%;
        height: auto;
        border-radius: 8px;
        object-fit: contain;
        max-height: 600px;
    }

    .product-details {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 2rem;
        padding: 1rem 0;
        justify-content: center;
    }

    .product-info {
        margin-bottom: 1rem;
    }

    .product-name {
        font-size: 2.2rem;
        color: #333;
        margin: 0 0 1.5rem 0;
        line-height: 1.2;
        font-weight: 600;
    }

    .product-price {
        font-size: 1.8rem;
        color: #4CAF50;
        font-weight: 500;
    }

    .mobile-only {
        display: none;
    }

    /* Mobile Responsive Design */
    @media (max-width: 768px) {
        .contact-dz-product-container {
            flex-direction: column;
            gap: 1rem;
            padding: 1rem;
            margin: 1rem;
        }

        .product-image {
            max-width: 100%;
            order: 2;
        }

        .product-details {
            order: 1;
        }

        .product-info {
            text-align: center;
        }

        .product-name {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .product-price {
            font-size: 1.25rem;
            margin-top: 0.5rem;
        }

        .desktop-only {
            display: none;
        }

        .mobile-only {
            display: block;
            order: 3;
        }
    }
    </style>
    <?php
    return ob_get_clean();
}