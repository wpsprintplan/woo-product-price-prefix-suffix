<?php
/*
Plugin Name: Woo Product Price Prefix Suffix
Description: Add prefixes and suffixes to WooCommerce product prices.
Version: 1.0
Author: Jyoti
*/

add_action('admin_menu' , 'wp_add_prefix_suffix');
function wp_add_prefix_suffix(){
	add_menu_page(
		'Price Prefix & Suffix',
		'price-prifix&suffix',
		'manage_options',
		'wppps',
		'wppps_callback',
		'dashicons-plugins-checked',

	);

}

// Store the custom prefix and suffix in WordPress options
function wppps_callback(){
    $confirmation_message = '';

    if (isset($_POST['submit'])) {
        // Get the values from the form fields
        $prefix = sanitize_text_field($_POST['prefix']);
        $suffix = sanitize_text_field($_POST['suffix']);
        
        // Save the values in WordPress options
        update_option('custom_price_prefix', $prefix);
        update_option('custom_price_suffix', $suffix);

        // Set the confirmation message
        $confirmation_message = 'Settings saved successfully!';
    }

    // Retrieve the custom prefix and suffix from options
    $custom_prefix = get_option('custom_price_prefix', '');
    $custom_suffix = get_option('custom_price_suffix', '');
    
    // Output the form with the stored values and confirmation message
    ?>
    <div class="wrap">
        <?php if (!empty($confirmation_message)) : ?>
            <div class="notice notice-success is-dismissible">
                <p><?php echo esc_html($confirmation_message); ?></p>
            </div>
        <?php endif; ?>

        <form method="post">
            <table>
                <tr>
                    <td><label for="prefix">Prefix:</label></td>
                    <td><input type="text" id="prefix" name="prefix" value="<?php echo esc_attr($custom_prefix); ?>"></td>
                </tr>
                <tr>
                    <td><label for="suffix">Suffix:</label></td>
                    <td><input type="text" id="suffix" name="suffix" value="<?php echo esc_attr($custom_suffix); ?>"></td>
                </tr>
                <tr>
                    <td colspan="2"><input type="submit" name="submit" value="Submit"></td>
                </tr>
            </table>
        </form>
    </div>
    <?php
}


// Modify WooCommerce prices using the stored prefix and suffix
add_filter('woocommerce_get_price_suffix', 'geekerhub_add_price_suffix', 99, 4);
add_filter('woocommerce_get_price_html', 'geekerhub_add_price_prefix', 99, 2);

function geekerhub_add_price_suffix($html, $product, $price, $qty) {
    // Retrieve the custom suffix from options
    $custom_suffix = get_option('custom_price_suffix', '');
    
    // Append the custom suffix to the price
    $html .= ' ' . $custom_suffix;
    
    return $html;
}

function geekerhub_add_price_prefix($price, $product) {
    // Retrieve the custom prefix from options
    $custom_prefix = get_option('custom_price_prefix', '');
    
    // Prepend the custom prefix to the price
    $price = $custom_prefix . ' ' . $price;
    
    return $price;
}
