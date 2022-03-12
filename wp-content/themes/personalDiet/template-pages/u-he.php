<?php
/*
Template Name: U he
*/
 
function display($number,$cu){   
//    ini_set('display_errors', '1');
//ini_set('display_startup_errors', '1');
//error_reporting(E_ALL);
 
    if($number>0){
        
        if($cu == 5){
            exit();
        }
        $output = (1+$number)/2;
        //echo round($output).' ,';
        echo abs($output - 5);

        $number++;
        $cu++;
    //    $count++;
        display($number,$cu); 
        
    }
}    
    
display(5,0);    

?>

<!--so it must print 3,2,4,1,5-->