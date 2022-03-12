<?php
include('../../../../wp-config.php');
global $wpdb;

//error_reporting(E_ALL);
//ini_set('display_errors', '1');
require_once '../Stripe/init.php'; 
 
/*** Check if test mode or Live mode ***/
$keysType = get_field("stripe_test_keys","option");
if( $keysType[0] == 'yes'){
  $k = get_field("testing_secret_key","option");
}else{
  $k = get_field("live_secret_key","option");
}

// Set API key 
\Stripe\Stripe::setApiKey($k);
//\Stripe\Stripe::setApiKey('sk_test_51JAa6SKQXlQ0vsKHaPmOW2VcCSXQU1KGYRKlEqNEJ2Y0x5pQTdupa2aYwLoXDP2Zm3GB6jLQXp2ATz7aRZVuh44x00EVSfJuCw');

$response = array( 
    'status' => 0, 
    'error' => array( 
        'message' => 'Invalid Request!'    
    ) 
); 
 
if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
    $input = file_get_contents('php://input'); 
    $request = json_decode($input);
 
} 
 
if (json_last_error() !== JSON_ERROR_NONE) { 
    http_response_code(400); 
    echo json_encode($response); 
    exit; 
} 

//print_r($request);

if(!empty($request->checkoutSession)){ 
    // Create new Checkout Session for the order 
    try { 
        $session = \Stripe\Checkout\Session::create([ 
            'payment_method_types' => ['card'], 
            'line_items' => [[ 
                'price_data' => [ 
                    'product_data' => [ 
                        'name' => $request->productName
                    ], 
                    'unit_amount' => $request->productPrice*100, 
                    'currency' => 'USD', 
                ], 
                'quantity' => 1, 
                'description' => $request->productDescription, 
            ]], 
            'mode' => 'payment', 
            'success_url' => 'https://yourpersonalizeddiet.com/success/'.'?session_id={CHECKOUT_SESSION_ID}&mealplan_id='.$request->mealplan_id.'&plan_type='.$request->productDescription.'&plan_duration='.$request->planDuration, 
            'cancel_url' => 'https://yourpersonalizeddiet.com/cancel/', 
        ]); 
    }catch(Exception $e) {  
        $api_error = $e->getMessage();  
    } 
     
    if(empty($api_error) && $session){ 
        $response = array( 
            'status' => 1, 
            'message' => 'Checkout Session created successfully!', 
            'sessionId' => $session['id'] 
        ); 
    }else{ 
        $response = array( 
            'status' => 0, 
            'error' => array( 
                'message' => 'Checkout Session creation failed! '.$api_error    
            ) 
        ); 
    } 
} 

// Return response 
echo json_encode($response);
?>