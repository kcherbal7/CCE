<?php if (!defined('ABSPATH')) exit; ?>

<div class="wrap">
    <h1><?php echo $product ? 'Edit Product' : 'Add New Product'; ?></h1>
    
    <form method="post" class="product-form">
        <?php wp_nonce_field('save_product'); ?>
        
        <table class="form-table">
            <tr>
                <th><label for="product_id">Product ID</label></th>
                <td>
                    <input type="text" id="product_id" name="product_id" class="regular-text" 
                           value="<?php echo $product ? esc_attr($product->id) : ''; ?>" 
                           <?php echo $product ? 'readonly' : ''; ?>>
                    <p class="description">Enter a unique numeric ID for this product</p>
                </td>
            </tr>
            
            <tr>
                <th><label for="name">Product Name</label></th>
                <td>
                    <input type="text" id="name" name="name" class="regular-text" 
                           value="<?php echo $product ? esc_attr($product->name) : ''; ?>" required>
                </td>
            </tr>
            
            <tr>
                <th><label for="price">Price (DZD)</label></th>
                <td>
                    <input type="number" id="price" name="price" class="regular-text" 
                           value="<?php echo $product ? esc_attr($product->price) : ''; ?>" 
                           step="0.01" min="0" required>
                </td>
            </tr>
            
            <tr>
                <th><label for="quantity">Quantity</label></th>
                <td>
                    <input type="number" id="quantity" name="quantity" class="regular-text" 
                           value="<?php echo $product ? esc_attr($product->quantity) : ''; ?>" 
                           min="0" required>
                </td>
            </tr>
            
            <tr>
                <th><label for="image">Product Image</label></th>
                <td>
                    <input type="hidden" id="image_url" name="image_url" 
                           value="<?php echo $product ? esc_attr($product->image_url) : ''; ?>">
                    <div id="image-preview">
                        <?php if ($product && $product->image_url): ?>
                            <img src="<?php echo esc_url($product->image_url); ?>" 
                                 style="max-width: 150px; height: auto;">
                        <?php endif; ?>
                    </div>
                    <input type="button" id="upload-image" class="button" value="Choose Image">
                </td>
            </tr>
            
            <tr>
                <th><label for="sizes">Sizes (one per line)</label></th>
                <td>
                    <textarea id="sizes" name="sizes" rows="5" class="large-text"><?php 
                        echo $product ? esc_textarea($product->sizes) : ''; 
                    ?></textarea>
                </td>
            </tr>
            
            <tr>
                <th><label for="colors">Colors (one per line)</label></th>
                <td>
                    <textarea id="colors" name="colors" rows="5" class="large-text"><?php 
                        echo $product ? esc_textarea($product->colors) : ''; 
                    ?></textarea>
                </td>
            </tr>
            
            <tr>
                <th>Display Options</th>
                <td>
                    <label>
                        <input type="checkbox" name="show_size" 
                               <?php checked($product ? $product->show_size : true); ?>>
                        Show Size Selection
                    </label>
                    <br>
                    <label>
                        <input type="checkbox" name="show_color" 
                               <?php checked($product ? $product->show_color : true); ?>>
                        Show Color Selection
                    </label>
                </td>
            </tr>
            
            <tr>
                <th>Required Fields</th>
                <td>
                    <label>
                        <input type="checkbox" name="require_size" 
                               <?php checked($product ? $product->require_size : false); ?>>
                        Size is Required
                    </label>
                    <br>
                    <label>
                        <input type="checkbox" name="require_color" 
                               <?php checked($product ? $product->require_color : false); ?>>
                        Color is Required
                    </label>
                </td>
            </tr>
        </table>
        
        <p class="submit">
            <input type="submit" name="save_product" class="button-primary" 
                   value="<?php echo $product ? 'Update Product' : 'Add Product'; ?>">
            <a href="<?php echo admin_url('admin.php?page=contact-dz-products'); ?>" 
               class="button">Cancel</a>
        </p>
    </form>
</div>

<?php
wp_enqueue_media();
?>

<script>
jQuery(document).ready(function($) {
    var mediaUploader;
    
    $('#upload-image').click(function(e) {
        e.preventDefault();
        
        if (mediaUploader) {
            mediaUploader.open();
            return;
        }

        mediaUploader = wp.media({
            title: 'Select or Upload Product Image',
            button: {
                text: 'Use this image'
            },
            multiple: false
        });

        mediaUploader.on('select', function() {
            var attachment = mediaUploader.state().get('selection').first().toJSON();
            $('#image_url').val(attachment.url);
            $('#image-preview').html('<img src="' + attachment.url + '" style="max-width: 150px; height: auto;">');
        });

        mediaUploader.open();
    });
});
</script>