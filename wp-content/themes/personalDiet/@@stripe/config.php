<?php

require('init.php');

$publish_key = "pk_test_51J2rWCSBWTIe5ShVX7lw6ON7FpqOQQY9YsZ1HSDRxgNrXMwzEAorHfGFIIANYyFfM2pUfbLeyPZxLYuqQIKLC63e00q6kVmmb5";

$secret_key = "sk_test_51J2rWCSBWTIe5ShVrOtsh9AK1MUuNKfvABQyErQlSqQRNxR2zmrWvy5OK5oOpMR7mHykZANpaYolDRpqnikyFmf500oakKH6P1";


\Stripe\Stripe::setApiKey($secret_key);
    
?>