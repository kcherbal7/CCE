<?php 
if (!defined('ABSPATH')) exit;

// Initialize default values
$form_title = get_option('form_title', array(
    'text' => '',
    'enabled' => false,
    'css' => ''
));

$fields = get_option('contact_dz_fields', array(
    'full_name' => array('label' => 'Full Name', 'required' => true, 'enabled' => true),
    'phone_number' => array('label' => 'Phone Number', 'required' => true, 'enabled' => true),
    'address' => array('label' => 'Address', 'required' => true, 'enabled' => true),
    'wilaya' => array('label' => 'Wilaya', 'required' => true, 'enabled' => true)
));
$button_text = get_option('button_text', 'Submit');
$shipping_text = get_option('shipping_text', 'Shipping Cost:');
?>

<div class="wrap">
    <h1>Form Settings</h1>
    <form method="post" action="">
        <?php wp_nonce_field('update_settings'); ?>
        
        <h2>Form Title</h2>
        <table class="form-table">
            <tr>
                <th><label for="form_title">Title Text</label></th>
                <td>
                    <input type="text" id="form_title" name="form_title" 
                           value="<?php echo esc_attr($form_title['text']); ?>" class="regular-text">
                </td>
            </tr>
            <tr>
                <th>Enable Title</th>
                <td>
                    <label>
                        <input type="checkbox" name="title_enabled" 
                               <?php checked($form_title['enabled']); ?>>
                        Show form title
                    </label>
                </td>
            </tr>
            <tr>
                <th><label for="title_css">Title CSS</label></th>
                <td>
                    <textarea id="title_css" name="title_css" rows="3" class="large-text code"><?php 
                        echo esc_textarea($form_title['css']); 
                    ?></textarea>
                </td>
            </tr>
        </table>
        
        <h2>Form Fields</h2>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>Field</th>
                    <th>Label</th>
                    <th>Required</th>
                    <th>Enabled</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($fields)): ?>
                    <?php foreach ($fields as $key => $field): ?>
                    <tr>
                        <td><?php echo esc_html(ucfirst(str_replace('_', ' ', $key))); ?></td>
                        <td>
                            <input type="text" 
                                   name="fields[<?php echo esc_attr($key); ?>][label]" 
                                   value="<?php echo esc_attr($field['label']); ?>"
                                   class="regular-text">
                        </td>
                        <td>
                            <input type="checkbox" 
                                   name="fields[<?php echo esc_attr($key); ?>][required]"
                                   <?php checked($field['required']); ?>>
                        </td>
                        <td>
                            <input type="checkbox" 
                                   name="fields[<?php echo esc_attr($key); ?>][enabled]"
                                   <?php checked($field['enabled']); ?>>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No fields configured yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        
        <h2>Text Settings</h2>
        <table class="form-table">
            <tr>
                <th><label for="button_text">Button Text</label></th>
                <td>
                    <input type="text" id="button_text" name="button_text" 
                           value="<?php echo esc_attr($button_text); ?>" class="regular-text">
                </td>
            </tr>
            <tr>
                <th><label for="shipping_text">Shipping Price Text</label></th>
                <td>
                    <input type="text" id="shipping_text" name="shipping_text" 
                           value="<?php echo esc_attr($shipping_text); ?>" class="regular-text">
                    <p class="description">Text displayed before the shipping price</p>
                </td>
            </tr>
        </table>
        
        <p class="submit">
            <input type="submit" name="update_settings" class="button-primary" value="Save Settings">
        </p>
    </form>
</div>