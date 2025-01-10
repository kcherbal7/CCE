<?php if (!defined('ABSPATH')) exit; 

$product_id = isset($atts['product_id']) ? intval($atts['product_id']) : 1;

?>

<div class="contact-dz-form-container">
    <div id="form-message" class="form-message" style="display: none;"></div>
    
    <?php if ($form_title['enabled'] && !empty($form_title['text'])): ?>
        <h2 style="<?php echo esc_attr($form_title['css']); ?>"><?php echo esc_html($form_title['text']); ?></h2>
    <?php endif; ?>

    <form id="contact-dz-form" method="post">
        <?php 
        // Get product details
        global $wpdb;
        $product = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}contact_dz_products WHERE id = %d",
            $product_id
        ));
        ?>
        
        <!-- Form fields first -->
        <?php foreach ($fields as $key => $field): ?>
            <?php if ($field['enabled']): ?>
            <div class="form-group">
                <label for="<?php echo esc_attr($key); ?>"><?php echo esc_html($field['label']); ?></label>
                <?php if ($key === 'wilaya'): ?>
                    <select id="<?php echo esc_attr($key); ?>" 
                            name="<?php echo esc_attr($key); ?>" 
                            <?php echo $field['required'] ? 'required' : ''; ?>>
                        <option value="">Select Wilaya</option>
                        <?php foreach (get_algeria_wilayas() as $wilaya): ?>
                            <option value="<?php echo esc_attr($wilaya); ?>"><?php echo esc_html($wilaya); ?></option>
                        <?php endforeach; ?>
                    </select>
                <?php else: ?>
                    <input type="text" 
                           id="<?php echo esc_attr($key); ?>" 
                           name="<?php echo esc_attr($key); ?>" 
                           <?php echo $field['required'] ? 'required' : ''; ?>
                           <?php if ($key === 'full_name'): ?>maxlength="100"<?php endif; ?>
                           <?php if ($key === 'phone_number'): ?>maxlength="20"<?php endif; ?>
                           <?php if ($key === 'address'): ?>maxlength="500"<?php endif; ?>>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        <?php endforeach; ?>

        <!-- Price Summary Box with improved design -->
        <div class="price-summary-box">
            <h3>Order Summary</h3>
            <div class="price-summary-item">
                <span class="label">Product Price:</span>
                <span class="value"><?php echo number_format($product->price, 0, '.', ','); ?> DZD</span>
            </div>
            <div class="price-summary-item">
                <span class="label"><?php echo esc_html($shipping_text); ?></span>
                <span class="value"><span id="shipping-price">0</span> DZD</span>
            </div>
            <div class="price-summary-item total">
                <span class="label">Total Price:</span>
                <span class="value"><span id="total-price"><?php echo number_format($product->price, 0, '.', ','); ?></span> DZD</span>
            </div>
        </div>

        <!-- Hidden fields and submit button -->
        <input type="hidden" name="form_token" value="<?php echo esc_attr($form_token); ?>">
        <input type="hidden" name="product_price" value="<?php echo esc_attr($product->price); ?>">
        <input type="hidden" name="product_name" value="<?php echo esc_attr($product->name); ?>">
        <?php wp_nonce_field('contact_dz_submit', 'contact_dz_nonce_field'); ?>
        <input type="hidden" name="action" value="contact_dz_submit">
        <input type="hidden" name="product_id" value="<?php echo esc_attr($product_id); ?>">
        <input type="hidden" name="shipping_price" id="shipping-price-input" value="0">
        <input type="hidden" name="total_price" id="total-price-input" value="<?php echo esc_attr($product->price); ?>">

        <button type="submit" class="submit-btn"><?php echo esc_html($button_text); ?></button>
    </form>
</div>

<style>
.contact-dz-form-container {
    max-width: 600px;
    margin: 0 auto;
    padding: 2.5rem;
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 4px 24px rgba(0,0,0,0.08);
}

.contact-dz-form-container h2 {
    color: #333;
    margin-bottom: 2rem;
    text-align: center;
    font-size: 1.8rem;
    font-weight: 600;
}

.form-group {
    margin-bottom: 1.8rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.6rem;
    color: #444;
    font-weight: 500;
    font-size: 0.95rem;
}

.form-group input,
.form-group select {
    width: 100%;
    padding: 0.9rem;
    border: 1.5px solid #e0e0e0;
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background-color: #fafafa;
}

.form-group input:focus,
.form-group select:focus {
    border-color: #4CAF50;
    outline: none;
    box-shadow: 0 0 0 3px rgba(76,175,80,0.1);
    background-color: #ffffff;
}

.shipping-cost {
    background: #f8f9fa;
    padding: 1.2rem;
    border-radius: 8px;
    margin: 2rem 0;
    text-align: center;
    font-size: 1.1rem;
    color: #333;
    border: 1px solid #e9ecef;
}

.submit-btn {
    width: 100%;
    padding: 1.1rem;
    background: #4CAF50;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 1.1rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.submit-btn:hover {
    background: #45a049;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(76,175,80,0.2);
}

/* Responsive Design */
@media (max-width: 768px) {
    .contact-dz-form-container {
        margin: 1rem;
        padding: 1.5rem;
        border-radius: 10px;
    }
    
    .contact-dz-form-container h2 {
        font-size: 1.5rem;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
}

.form-message {
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 5px;
    text-align: center;
}

.form-message.success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.form-message.error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.success-message {
    background: #f8fffa;
    border: 2px solid #4CAF50;
    border-radius: 10px;
    padding: 2rem;
    text-align: center;
    margin: 2rem 0;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.success-icon {
    background: #4CAF50;
    color: white;
    width: 60px;
    height: 60px;
    line-height: 60px;
    font-size: 30px;
    border-radius: 50%;
    margin: 0 auto 1rem;
}

.success-message h3 {
    color: #2e7d32;
    font-size: 24px;
    margin: 1rem 0;
}

.success-message p {
    color: #4a4a4a;
    font-size: 16px;
    line-height: 1.5;
    margin: 0;
}

.order-success-container {
    text-align: center;
    padding: 3rem 2rem;
    background: #f8fffa;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    max-width: 600px;
    margin: 2rem auto;
}

.order-success-icon {
    width: 80px;
    height: 80px;
    background: #4CAF50;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 2rem;
    color: white;
    font-size: 40px;
    box-shadow: 0 4px 10px rgba(76, 175, 80, 0.3);
}

.order-success-container h2 {
    color: #2e7d32;
    font-size: 28px;
    margin-bottom: 1rem;
    font-weight: 600;
}

.order-success-container p {
    color: #555;
    font-size: 16px;
    line-height: 1.6;
    margin-bottom: 0.5rem;
}

.order-details {
    margin-top: 2rem;
    padding: 1.5rem;
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.order-details p {
    margin: 0.5rem 0;
    color: #333;
}

/* Animation for the success icon */
@keyframes checkmark {
    0% {
        transform: scale(0);
    }
    50% {
        transform: scale(1.2);
    }
    100% {
        transform: scale(1);
    }
}

.order-success-icon {
    animation: checkmark 0.5s ease-in-out forwards;
}

.thank-you-message {
    text-align: center;
    padding: 3rem;
    background: #f8fff9;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    max-width: 600px;
    margin: 2rem auto;
}

.thank-you-icon {
    width: 80px;
    height: 80px;
    background: #4CAF50;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 2rem;
    color: white;
    font-size: 40px;
    box-shadow: 0 4px 15px rgba(76, 175, 80, 0.3);
    animation: scaleIn 0.5s ease-out;
}

.thank-you-message h2 {
    color: #2e7d32;
    font-size: 28px;
    margin-bottom: 1rem;
    font-weight: 600;
}

.thank-you-message p {
    color: #555;
    font-size: 16px;
    line-height: 1.6;
    margin-bottom: 0.5rem;
}

.order-info {
    margin-top: 2rem;
    padding: 1.5rem;
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.order-info p {
    margin: 0.5rem 0;
    color: #333;
}

@keyframes scaleIn {
    0% {
        transform: scale(0);
    }
    70% {
        transform: scale(1.1);
    }
    100% {
        transform: scale(1);
    }
}

.price-summary-box {
    background: #f8f9fa;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    padding: 1.5rem;
    margin: 2rem 0;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.price-summary-box h3 {
    color: #2c3e50;
    font-size: 1.25rem;
    margin: 0 0 1rem 0;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid #e9ecef;
    text-align: center;
    font-weight: 600;
}

.price-summary-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    color: #495057;
    font-size: 1.1rem;
}

.price-summary-item:not(:last-child) {
    border-bottom: 1px dashed #dee2e6;
}

.price-summary-item .label {
    font-weight: 500;
    color: #6c757d;
}

.price-summary-item .value {
    font-weight: 600;
    color: #2c3e50;
}

.price-summary-item.total {
    margin-top: 0.5rem;
    padding-top: 1rem;
    border-top: 2px solid #e9ecef;
    font-size: 1.25rem;
    color: #2c3e50;
}

.price-summary-item.total .label {
    color: #2c3e50;
    font-weight: 600;
}

.price-summary-item.total .value {
    color: #4CAF50;
    font-weight: 700;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .price-summary-box {
        padding: 1rem;
        margin: 1.5rem 0;
    }

    .price-summary-item {
        font-size: 1rem;
    }

    .price-summary-item.total {
        font-size: 1.15rem;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    // Update shipping price and total when wilaya changes
    $('#wilaya').on('change', function() {
        var selectedWilaya = $(this).val();
        var productPrice = parseFloat($('input[name="product_price"]').val());
        
        if (selectedWilaya) {
            $.ajax({
                url: contactDZ.ajaxurl,
                type: 'POST',
                data: {
                    action: 'get_shipping_price',
                    wilaya: selectedWilaya,
                    nonce: contactDZ.nonce
                },
                success: function(response) {
                    if (response.success) {
                        var shippingPrice = parseFloat(response.data.price);
                        var totalPrice = productPrice + shippingPrice;
                        
                        $('#shipping-price').text(shippingPrice.toLocaleString());
                        $('#shipping-price-input').val(shippingPrice);
                        $('#total-price').text(totalPrice.toLocaleString());
                        $('#total-price-input').val(totalPrice);
                    } else {
                        console.error('Shipping price error:', response.data.message);
                        $('#shipping-price').text('0');
                        $('#shipping-price-input').val('0');
                        $('#total-price').text(productPrice.toLocaleString());
                        $('#total-price-input').val(productPrice);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Shipping price AJAX error:', error);
                    $('#shipping-price').text('0');
                    $('#shipping-price-input').val('0');
                    $('#total-price').text(productPrice.toLocaleString());
                    $('#total-price-input').val(productPrice);
                }
            });
        } else {
            $('#shipping-price').text('0');
            $('#shipping-price-input').val('0');
            $('#total-price').text(productPrice.toLocaleString());
            $('#total-price-input').val(productPrice);
        }
    });

    // Handle form submission
    var submitting = false;
    var $form = $('#contact-dz-form');
    var $formContainer = $('.contact-dz-form-container');
    var $submitBtn = $form.find('button[type="submit"]');

    $form.on('submit', function(e) {
        e.preventDefault();
        
        if (submitting) {
            return false;
        }
        
        submitting = true;
        $submitBtn.prop('disabled', true);
        
        $('#form-message').hide().removeClass('success error');
        
        $.ajax({
            url: contactDZ.ajaxurl,
            type: 'POST',
            data: $form.serialize(),
            success: function(response) {
                if (response.success) {
                    // Create success message HTML without showing the order ID
                    var successHTML = `
                        <div class="thank-you-message">
                            <div class="thank-you-icon">✓</div>
                            <h2>Thank You for Your Order!</h2>
                            <p>Your order has been successfully placed.</p>
                            <div class="order-info">
                                <p>We will contact you shortly to confirm your order.</p>
                            </div>
                        </div>
                    `;
                    
                    // Replace form with success message
                    $formContainer.fadeOut(400, function() {
                        $(this).html(successHTML).fadeIn(400);
                    });
                    
                } else {
                    $('#form-message')
                        .html(response.data.message)
                        .addClass('error')
                        .fadeIn();
                    submitting = false;
                    $submitBtn.prop('disabled', false);
                }
            },
            error: function() {
                $('#form-message')
                    .html('Network error. Please try again.')
                    .addClass('error')
                    .fadeIn();
                submitting = false;
                $submitBtn.prop('disabled', false);
            }
        });
        
        return false;
    });
});
</script>