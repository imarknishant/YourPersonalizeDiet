<?php

/* Template Name: Submit payment */
get_header();

include __DIR__ . '/../stripe/config.php';

//\Stripe\Stripe::setVerifySslCerts(false);

echo '<pre>';
print_r($_POST);
echo '</pre>';

$token = $_POST['stripeToken'];

$data=\Stripe\Charge::Create(array(
"amount" => 500,
"currency" => 'usd',
"description" => 'kamal bamola',
"source"     => $token,
));

echo '<pre>';
print_r($data);
echo '</pre>';
?>

<?php
get_footer();
?>