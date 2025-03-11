<?php
/**
 * Plugin Name: Currency Converter
 * Description: A user-friendly plugin to check and convert currency values.
 * Version: 1.1
 * Author: Sujoy Sen
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Enqueue scripts and styles
function currency_converter_enqueue_assets() {
    wp_enqueue_style('currency-converter-css', plugin_dir_url(__FILE__) . 'currency-converter.css');
    wp_enqueue_script('currency-converter-js', plugin_dir_url(__FILE__) . 'currency-converter.js', array('jquery'), null, true);
    wp_localize_script('currency-converter-js', 'currencyConverter', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'api_key' => '1432caa1e584519352ad4da2' // Replace with your API key
    ));
}
add_action('wp_enqueue_scripts', 'currency_converter_enqueue_assets');

// Shortcode to display currency converter
function currency_converter_display() {
    ob_start();
    ?>
    <div id="currency-converter" class="currency-converter-container">
        <h2>Currency Converter</h2>
        <div class="converter-wrapper">
            <label for="from-currency">From:</label>
            <select id="from-currency" class="currency-select">
                <option value="USD">USD - US Dollar</option>
                <option value="EUR">EUR - Euro</option>
                <option value="INR">INR - Indian Rupee</option>
                <option value="GBP">GBP - British Pound</option>
            </select>
            <label for="to-currency">To:</label>
            <select id="to-currency" class="currency-select">
                <option value="USD">USD - US Dollar</option>
                <option value="EUR">EUR - Euro</option>
                <option value="INR">INR - Indian Rupee</option>
                <option value="GBP">GBP - British Pound</option>
            </select>
            <button id="convert-currency" class="convert-button">Convert</button>
        </div>
        <p id="conversion-result" class="conversion-result"></p>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('currency_converter', 'currency_converter_display');

// Handle AJAX request
function currency_converter_ajax_handler() {
    $from_currency = sanitize_text_field($_POST['from_currency']);
    $to_currency = sanitize_text_field($_POST['to_currency']);
    $api_key = '1432caa1e584519352ad4da2'; // Replace with your API key

    $response = wp_remote_get("https://v6.exchangerate-api.com/v6/$api_key/pair/$from_currency/$to_currency");

    if (is_wp_error($response)) {
        echo json_encode(array('error' => 'Unable to fetch data. Please try again later.'));
    } else {
        $data = json_decode(wp_remote_retrieve_body($response), true);
        echo json_encode(array('rate' => $data['conversion_rate']));
    }
    wp_die();
}
add_action('wp_ajax_currency_converter', 'currency_converter_ajax_handler');
add_action('wp_ajax_nopriv_currency_converter', 'currency_converter_ajax_handler');
