<?php if (!defined('ABSPATH')) exit; ?>

<div class="wrap">
    <h1 class="wp-heading-inline">Products</h1>
    <a href="<?php echo admin_url('admin.php?page=contact-dz-manage-product'); ?>" class="page-title-action">Add New Product</a>
    
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th>Product ID</th>
                <th>Image</th>
                <th>Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Shortcode</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
            <tr>
                <td><?php echo esc_html($product->id); ?></td>
                <td>
                    <?php if ($product->image_url): ?>
                        <img src="<?php echo esc_url($product->image_url); ?>" 
                             alt="<?php echo esc_attr($product->name); ?>"
                             style="max-width: 50px; height: auto;">
                    <?php endif; ?>
                </td>
                <td><?php echo esc_html($product->name); ?></td>
                <td><?php echo esc_html($product->price); ?> DZD</td>
                <td><?php echo esc_html($product->quantity); ?></td>
                <td><code>[<?php echo esc_html($product->shortcode); ?>]</code></td>
                <td>
                    <a href="<?php echo add_query_arg(array(
                        'page' => 'contact-dz-manage-product',
                        'id' => $product->id
                    ), admin_url('admin.php')); ?>" class="button">Edit</a>
                    
                    <form method="post" style="display:inline;">
                        <?php wp_nonce_field('delete_product'); ?>
                        <input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
                        <button type="submit" name="delete_product" class="button delete" 
                                onclick="return confirm('Are you sure you want to delete this product?')">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>