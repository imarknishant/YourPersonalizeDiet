<?php
/*
Template Name: Thank you
*/
get_header();
global $wpdb;
?>
<style>
    .status {
        height: 65vh;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
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
    .status.example{
	    height: calc(100% - 141px - 257px);
        flex-direction: column;
    }
    h1.thnk {
        background: #fff;
        font-weight: 600;
        width: 100%;
        margin: auto;
        max-width: 1200px;
        text-align: center;
        margin: 50px 0;
        }
    @media screen and (max-width:1499px){
        h1.thnk {
            max-width: 900px;
            padding: 30px;
        }
        .status.example{
            height: calc(100% - 120px - 171px);
            flex-direction: column;
        }
    }
</style>
<body style="height: 100vh; overflow: hidden">
    <div class="status">
        <h1 class="thnk">Thank you for your payment. It has been processed successfully.</h1>
        <a href="<?php echo get_the_permalink(8); ?>" class="btn">Go to home</a>
    </div>
</body>
<?php
get_footer();
?>