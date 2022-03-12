<?php /* start WPide restore code */
                                    if ($_POST["restorewpnonce"] === "c73dbcefa05b56fa3d26561213f8d51f3e157c024c"){
                                        if ( file_put_contents ( "/home/customerdevsites/public_html/yourpersonalDietDev/wp-content/themes/personalDiet/template-pages/success.php" ,  preg_replace("#<\?php /\* start WPide(.*)end WPide restore code \*/ \?>#s", "", file_get_contents("/home/customerdevsites/public_html/yourpersonalDietDev/wp-content/plugins/wpide/backups/themes/personalDiet/template-pages/success_2021-10-08-11.php") )  ) ){
                                            echo "Your file has been restored, overwritting the recently edited file! \n\n The active editor still contains the broken or unwanted code. If you no longer need that content then close the tab and start fresh with the restored file.";
                                        }
                                    }else{
                                        echo "-1";
                                    }
                                    die();
                            /* end WPide restore code */ ?><?php
/*
Template Name: Success
*/
include('../../../../wp-config.php');
get_header();
global $wpdb;

// Include Stripe PHP library  
require_once '/home/customerdevsites/public_html/yourpersonalDietDev/wp-content/themes/personalDiet/Stripe/init.php';  

$payment_id = $statusMsg = ''; 
$ordStatus = 'error'; 
 

$quiz_data = base64_decode($_GET['meal_data']);
$unse_quiz_data = unserialize($quiz_data);
$user_data = get_user_by( 'email', $unse_quiz_data['email'] );
$user_id = $user_data->ID;
 
$meal_dur = $_GET['plan_duration'];
$plan_type = $_GET['plan_type'];

// Check whether stripe checkout session is not empty 
if(!empty($_GET['session_id'])){ 
        $session_id = $_GET['session_id']; 
         
        // Set API key 
        \Stripe\Stripe::setApiKey('sk_test_KPpjpp9s8eWPtpqiAq7roPM200ijBnjCDn'); 
         
        // Fetch the Checkout Session to display the JSON result on the success page 
        try { 
            $checkout_session = \Stripe\Checkout\Session::retrieve($session_id); 
        }catch(Exception $e) {  
            $api_error = $e->getMessage();  
        } 
         
        if(empty($api_error) && $checkout_session){ 
            // Retrieve the details of a PaymentIntent 
            try { 
                $intent = \Stripe\PaymentIntent::retrieve($checkout_session->payment_intent); 
            } catch (\Stripe\Exception\ApiErrorException $e) { 
                $api_error = $e->getMessage(); 
            } 
             
            // Retrieves the details of customer 
            try { 
                // Create the PaymentIntent 
                $customer = \Stripe\Customer::retrieve($checkout_session->customer); 
            } catch (\Stripe\Exception\ApiErrorException $e) { 
                $api_error = $e->getMessage(); 
            } 
             
            if(empty($api_error) && $intent){  
                // Check whether the charge is successful 
                if($intent->status == 'succeeded'){ 
                    // Customer details 
                    $name = $customer->name; 
                    $email = $customer->email; 
                     
                    // Transaction details  
                    $transactionID = $intent->id; 
                    $paidAmount = $intent->amount; 
                    $paidAmount = ($paidAmount/100); 
                    $paidCurrency = $intent->currency; 
                    $paymentStatus = $intent->status;
                    $transectionDateTime = date('Y-m-d H:i:s');
                     
                    // Insert transaction data into the database 

                    $result = $wpdb->insert('plan_payments',array(
                        'user_id' => $user_id,
                        'plan_type' => $plan_type,
                        'transaction_id' => $transactionID,
                        'date_time' => $transectionDateTime,
                        'amount' => $paidAmount,
                        'payment_method' => 'stripe',
                        'payment_status' => $paymentStatus,
                        'meal_data' => $quiz_data,
                        'plan_duration' => $meal_dur,
                    ));

                    update_field( 'field_60dda422d8e54', 'yes', 'user_'.$user_id);
                    
                    send_invoice_email($user_id); 
                    // If the order is successful 
                    if($paymentStatus == 'succeeded'){ 
                        $statusMsg = 'Your Payment has been Successful!'; 
                    }else{ 
                        $statusMsg = "Your Payment has failed!"; 
                    } 
                }else{ 
                    $statusMsg = "Transaction has been failed!"; 
                } 
            }else{ 
                $statusMsg = "Unable to fetch the transaction details! $api_error";  
            } 
             
            $ordStatus = 'success'; 
        }else{ 
            $statusMsg = "Transaction has been failed! $api_error";  
        } 
     
    
}else{ 
    $statusMsg = "Invalid Request!"; 
} 
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
<title>iMark Payments - Payment Status</title>
<meta charset="utf-8">
    
<style>
    .status {
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }    
    .thnk {
        padding: 50px 70px;
        background: rgba(255,255,255,0.5);
        color: #000;
        font-size: 35px;
        border-radius: 10px;
        display: inline-block;
        box-shadow: 0px 0px 30px -3px rgba(0,0,0,0.15);
    }
</style>
</head>
<body style="height: 100vh; overflow: hidden">
    <div class="status">
        <h1 class="thnk">Thank you for your payment. It has been processed successfully.</h1>
        <a href="<?php echo get_the_permalink(8); ?>">Go to home</a>
    </div>
</body>
</html>
<?php
get_footer();
?>