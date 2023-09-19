<?php
/*
Plugin Name: Woo Product Price Prefix Suffix
Description: Add prefixes and suffixes to WooCommerce product prices.
Version: 1.0
Author: Jyoti
*/

add_action('admin_menu' , 'wppps_add_prefix_suffix');
function wppps_add_prefix_suffix(){
    add_menu_page(
        'Price Prefix & Suffix',
        'Price Prefix & Suffix',
        'manage_options',
        'wpps',
        'wppps_callback',
        'dashicons-plugins-checked',

    );

}

// Store the custom prefix and suffix in WordPress options
function wppps_callback(){
        // Initialize a variable for the confirmation message.
    $confirmation_message = '';

    // Check if the form has been submitted.
    if (isset($_POST['submit'])) {
        // Get the values from the form fields and sanitize them.
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

        <h2>Custom Price Prefix and Suffix</h2>
                <form method="post">
        <table class="form-table">
                <tbody><tr>
                    <th scope="row"><label for="prefix">Prefix:</label></th>
                    <td><input type="text" id="prefix" name="prefix" value="wp_woocommerce" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="suffix">Suffix:</label></th>
                    <td><input type="text" id="suffix" name="suffix" value="two" class="regular-text"></td>
                </tr>
            </tbody></table>
            <p class="submit"><input type="submit" name="submit" value="Save Changes" class="button-primary"></p>
        </form>
    </div>

    <!-- <style>
        .form-table th {
            width: 150px;
            text-align: right;
        }
        .form-table td {
            padding-left: 20px;
        }
    </style> -->
    <?php
}

// Modify WooCommerce prices using the stored prefix and suffix
add_filter('woocommerce_get_price_suffix', 'wppps_add_price_suffix', 99, 4);
add_filter('woocommerce_get_price_html', 'wppps_add_price_prefix', 99, 2);

function wppps_add_price_suffix($html, $product, $price, $qty) {
    // Retrieve the custom suffix from options
    $custom_suffix = get_option('custom_price_suffix', '');
    
    // Append the custom suffix to the price
    $html .= ' ' . $custom_suffix;
    
    return $html;
}

function wppps_add_price_prefix($price, $product) {
    // Retrieve the custom prefix from options
    $custom_prefix = get_option('custom_price_prefix', '');
    
    // Prepend the custom prefix to the price
    $price = $custom_prefix . ' ' . $price;
    
    return $price;
}
