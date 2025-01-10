<?php if (!defined('ABSPATH')) exit; ?>

<div class="wrap">
    <h1>Shipping Rates</h1>
    <form method="post" action="">
        <?php wp_nonce_field('update_shipping_rates'); ?>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>Wilaya</th>
                    <th>Price (DZD)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rates as $rate): ?>
                <tr>
                    <td><?php echo esc_html($rate->wilaya); ?></td>
                    <td>
                        <input type="number" 
                               name="shipping_rate[<?php echo esc_attr($rate->wilaya); ?>]" 
                               value="<?php echo esc_attr($rate->price); ?>"
                               step="0.01"
                               min="0">
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <p class="submit">
            <input type="submit" name="update_shipping_rates" class="button-primary" value="Update Shipping Rates">
        </p>
    </form>
</div>