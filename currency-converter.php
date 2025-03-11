<?php
/**
 * Plugin Name: Currency Converter
 * Description: A simple currency converter using ExchangeRate-API.
 * Version: 1.0
 * Author: Sujoy Sen
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Function to get exchange rates
function get_exchange_rate($from, $to)
{
    $api_key = '1432caa1e584519352ad4da2'; // Replace with your API key
    $url = "https://v6.exchangerate-api.com/v6/$api_key/latest/$from";
    
    $response = wp_remote_get($url);
    
    if (is_wp_error($response)) {
        return false;
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    return isset($data['conversion_rates'][$to]) ? $data['conversion_rates'][$to] : false;
}

// Function to display the converter form
function currency_converter_form()
{
    $currencies = array(
        "USD" => "United States Dollar",
        "INR" => "Indian Rupee",
        "EUR" => "Euro",
        "GBP" => "British Pound",
        "JPY" => "Japanese Yen",
        "AUD" => "Australian Dollar",
        "CAD" => "Canadian Dollar",
        "CNY" => "Chinese Yuan",
        "SGD" => "Singapore Dollar",
        "AED" => "UAE Dirham",
        "BRL" => "Brazilian Real",
        "ZAR" => "South African Rand",
        "MXN" => "Mexican Peso",
        "THB" => "Thai Baht",
        "CHF" => "Swiss Franc",
        "MYR" => "Malaysian Ringgit",
        "RUB" => "Russian Ruble",
        "PKR" => "Pakistani Rupee",
        "BDT" => "Bangladeshi Taka",
        "NGN" => "Nigerian Naira",
        "KRW" => "South Korean Won",
        "VND" => "Vietnamese Dong"
    );

    ob_start();
    ?>
    <form method="post">
        <input type="number" name="amount" placeholder="Enter amount" required>
        <select name="from_currency">
            <?php foreach ($currencies as $code => $name) : ?>
                <option value="<?php echo esc_attr($code); ?>"><?php echo esc_html($name . " (" . $code . ")"); ?></option>
            <?php endforeach; ?>
        </select>
        to
        <select name="to_currency">
            <?php foreach ($currencies as $code => $name) : ?>
                <option value="<?php echo esc_attr($code); ?>"><?php echo esc_html($name . " (" . $code . ")"); ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" name="convert">Convert</button>
    </form>
    <?php

    if (isset($_POST['convert'])) {
        $amount = floatval($_POST['amount']);
        $from_currency = sanitize_text_field($_POST['from_currency']);
        $to_currency = sanitize_text_field($_POST['to_currency']);
        
        $rate = get_exchange_rate($from_currency, $to_currency);

        if ($rate) {
            $converted_amount = $amount * $rate;
            echo "<p>$amount $from_currency = $converted_amount $to_currency</p>";
            echo "<p>Current Rate: 1 $from_currency = $rate $to_currency</p>";
        } else {
            echo "<p>Exchange rate not available.</p>";
        }
    }

    return ob_get_clean();
}

// Register shortcode
add_shortcode('currency_converter', 'currency_converter_form');
