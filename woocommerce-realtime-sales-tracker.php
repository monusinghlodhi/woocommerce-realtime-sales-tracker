<?php
/*
Plugin Name:        WooCommerce Realtime Sales Tracker
Plugin URI:         https://github.com/monusinghlodhi/woocommerce-realtime-sales-tracker
Description:        A plugin to Store WooCommerce Sales Into Firebase also retrieve data from firebase and send daily sales summaries via email
Version:            1.0
Author:             Monu Singh
Author URI:         https:/monusingh.com/
License:            GPL v2 or later
License URI:        https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:        woocommerce-realtime-sales-tracker
Domain Path:        /languages/
*/

function woo_firebase_setting_admin_page_create(){
    add_menu_page("Woocommerce Firebase Settings", "Woocommerce Firebase Settings","manage_options","woocommerce-firebase-settings","woocommercefirebasesettings");
 }

 add_action('admin_menu','woo_firebase_setting_admin_page_create');
function woocommercefirebasesettings() { ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
     <?php
      include 'settings.php';
      
}
// Hook into WooCommerce order completion
add_action( 'woocommerce_thankyou', 'firebase_woocommerce_order_details_store', 10 );

if ( ! function_exists( 'firebase_woocommerce_order_details_store' ) ) {
    function firebase_woocommerce_order_details_store( $order_id ) {
        // Get order object
        $order = wc_get_order( $order_id );

        // Get order data
        $order_data = $order->get_data();

        // Extract necessary information
        $customer_id     = $order->get_customer_id();
        $customer_email  = $order->get_billing_email();
        $total_amount    = $order->get_total();
        $products        = array();

        // Loop through order items
        foreach ( $order->get_items() as $item_id => $item ) {
            $product_name     = $item->get_name();
            $product_id       = $item->get_product_id();
            $product_quantity = $item->get_quantity();
            $products[]       = array(
                'product_name' => $product_name,
                'quantity'     => $product_quantity,
            );
        }

        // Your Firebase Realtime Database URL
        $databaseURL = get_option("firebase_database_url");        

        // Your Firebase Realtime Database secret key
        $apiKey = get_option("firebase_database_api_key");;

        // Set the default timezone
        date_default_timezone_set( 'Asia/Kolkata' );

        // Today's date
        $date = date( 'Y-m-d' );

        // Get the current time
        $time = date( 'H:i:s' );

        // Data to be stored
        $data = array(
            'amount'   => $total_amount,
            'date'     => $date,
            'products' => $products,
            'time'     => $time,
        );

        // Convert data to JSON format
        $jsonData = json_encode( $data );

        // Firebase database path where you want to store the data
        $databasePath = '/purchases.json'; // Assuming you want to store data under 'users' node

        // Initialize cURL session
        $ch = curl_init();

        // Set cURL options
        curl_setopt( $ch, CURLOPT_URL, $databaseURL . $databasePath . '?key=' . $apiKey );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'POST' );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $jsonData );

        // Execute cURL session
        $response = curl_exec( $ch );

        // Check for errors
        if ( $response === false ) {
            $error_message = curl_error( $ch );
            echo "Error: " . $error_message;
        } else {
            // Decode JSON response
            echo "success";
        }

        // Close cURL session
        curl_close( $ch );
        
    }
}

// Firebase function to compile summary and send email
function compile_daily_sales_summary() {

    // Firebase project URL
    $firebase_url = get_option("firebase_database_url");

    // Firebase database path
    $firebase_path = '/purchases.json';

    // Today's Date
    $query_date = date( 'Y-m-d' );

    // URL to fetch data for a specific date
    $url = $firebase_url . $firebase_path . '?orderBy="date"&equalTo="' . $query_date . '"';

    // Initialize cURL session
    $curl = curl_init();

    // Set cURL options
    curl_setopt( $curl, CURLOPT_URL, $url );
    curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );

    // Execute cURL request
    $response = curl_exec( $curl );

    // Check for errors
    if ( $response === false ) {
        $error_message = curl_error( $curl );
        // Handle error
        echo "Error: " . $error_message;
    } else {
        // Decode JSON response
        $data = json_decode( $response, true );
    }

    // Close cURL session
    curl_close( $curl );

    // Variables to store total amount and quantity
    $total_revenue = 0;
    $total_sales   = 0;

    if ( ! empty( $data ) ) {
        foreach ( $data as $key => $value ) {
            $total_revenue += $value['amount'];
            if ( isset( $value['products'] ) && is_array( $value['products'] ) ) {
                foreach ( $value['products'] as $product ) {
                    $total_sales += $product['quantity'];
                }
            }
        }
    }

    // Compose email message
    $to      = 'info@monusingh.com';
    $subject = 'Daily Sales Summary';
    $message = "Total Sales: $total_sales\n\n";
    $message .= "Total Revenue: $total_revenue";
    $headers = 'Content-Type: text/html; charset=UTF-8';

    // Send email
    wp_mail( $to, $subject, $message, $headers );
}

// Schedule Firebase function to run at the end of each day
function schedule_daily_sales_summary() {
    if ( ! wp_next_scheduled( 'compile_daily_sales_summary_event' ) ) {
        wp_schedule_event( strtotime( 'tomorrow' ), 'daily', 'compile_daily_sales_summary_event' );
    }
}
add_action( 'wp', 'schedule_daily_sales_summary' );

// Hook Firebase function to the scheduled event
add_action( 'compile_daily_sales_summary_event', 'compile_daily_sales_summary' );