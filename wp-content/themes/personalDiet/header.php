<!doctype html>
<html>

<head>
  <meta charset="utf-8">
  <title>Your Personalized Diet | Homepage</title>
  <meta name="viewport"
    content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
    
  <!-- Favicon -->
  <link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.png" sizes="32x32" type="image/x-icon">

  <!-- CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.79/theme-default.min.css"/>
  <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/main.css">
  <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/custom.css">
  <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/custom-data.css">
    
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.css"/>
 
  <script src="https://js.stripe.com/v3/"></script>
  <script src="https://js.stripe.com/v2/"></script>
    
    <script src="<?php echo get_template_directory_uri(); ?>/assets/js/jquery.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.js"></script>
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/latest/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/latest/respond.min.js"></script>
    <![endif]-->
	<?php wp_head(); ?>
    
<!--
 <script src="https://static-eu.payments-amazon.com/checkout.js"></script>
 <script type='text/javascript'>
    window.onAmazonLoginReady = function() {
      amazon.Login.setClientId('amzn1.application-oa2-client.364efbea1379435d9ee7d14c16fea721');
        amazon.Login.setUseCookie(true);
    };
    
 </script>
 <script async="async" src='https://static-na.payments-amazon.com/OffAmazonPayments/us/sandbox/js/Widgets.js'>
  </script>
-->
</head>

<body class="white-bg">

  <header id="header">
     <div class="container">
	 <input type="hidden" id="admin-ajax-url" value="<?php echo admin_url('admin-ajax.php'); ?>">
      <nav class="navbar navbar-expand-lg">
        <a class="navbar-brand" href="<?php echo get_site_url(); ?>">
          <img src="<?php the_field('header_logo','option'); ?>">
        </a>


        <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>


        <div class="collapse navbar-collapse" id="navbarResponsive">			
		<?php
		$args = array(
		           'menu_class' => 'navbar-nav',
				   'menu'       => 'Header menu',	
		);
		
		wp_nav_menu($args);
        ?>		
			
             <ul class="navbar-nav sign-in">
               <li class="nav-item">
                <?php if(!is_user_logged_in()){?>
                <a class="nav-link" href="<?php echo get_the_permalink(50); ?>">
                  <i class="fa fa-user" aria-hidden="true"></i> Log in</a>
                   <?php }else{ ?>
                <a class="nav-link" href="<?php echo get_the_permalink(21); ?>">
                  <i class="fa fa-user" aria-hidden="true"></i> Profile</a>
                   <?php }?>
               </li>          
             </ul>
            </div>
      </nav>
    </div> 
  </header>