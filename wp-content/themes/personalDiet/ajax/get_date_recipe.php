<?php
include('../../../../wp-config.php');
global $wpdb;

$Days = array();
$Dates = array();
$response = array();

$userid = $_POST['userid'];
$week = $_POST['week'];

$w=0;
$terms = get_terms(['taxonomy' => 'week','hide_empty' => false]);
foreach($terms as $t){
    $w++;
    if($week == $t->slug){
        $next_week = $terms[$w]->slug;
    }  
}

$meal_data = $wpdb->get_results("SELECT * FROM plan_payments WHERE user_id = $userid");

/*** Variables ***/
$PlanStartDate = date('F d',strtotime($_POST['date'])); 

$PlanEndDate = date('F d',strtotime($_POST['date']. ' + 6 day')); 
$PlanEndDate_timestamp = date('m/d/Y',strtotime($_POST['date']. ' + 6 day'));


/*** Calculate days ***/

for($i=0; $i<1; $i++){
    for($j=0; $j<7; $j++){
        $Days[] =  date('l',strtotime($_POST['date'].' + '.$j.' day'));
        $Dates[] = date('F d',strtotime($_POST['date'].' + '.$j.' day'));
    }
}


$meals = unserialize($meal_data[0]->meal_data);

$mt = explode(',',$meals['meat_arr']);
$veg = explode(',',$meals['vege_arr']);
$fruit = explode(',',$meals['fruits_arr']);
$grain = explode(',',$meals['grains_arr']);
$dairy = explode(',',$meals['dairy_arr']);
$beans = explode(',',$meals['beans_arr']);
$allergy = explode(',',$meals['allergies_arr']);


$input = array($mt,$veg,$fruit,$grain,$dairy,$beans);
$inc = call_user_func_array("array_merge", $input);

$i_am = $meals['gender'];
$i_want_to = $meals['weight'];
$current_activity = $meals['activity'];
$meat = $meals['meat_arr'];
$no_of_meals = $meals['meal'];

$menu = '';

$main = array('female','Lose Weight','Maintain Weight','Low','Medium');
$build = array('male','Build Up','High');
$user = array($i_am,$i_want_to,$current_activity);

$mm = array_intersect($main,$user);
$bm = array_intersect($build,$user);

if(count($mm) >=2){
    if(empty($meat)){
        $menu = 'vegan-menu';
    }else{
        $menu = 'main-menu';
    }
}elseif(count($bm) >=2){
    if(empty($meat)){
        $menu = 'vegan-menu';
    }else{
        $menu = 'build-up-menu';
    }
}

$recipes = array();
$kilo_cal = array();
$recipes_video = array();
$change_recipe = array();

if($no_of_meals == 1){
    $diet_type = array('lunch');
}else if($no_of_meals == 2){
    $diet_type = array('breakfast','dinner');
}else if($no_of_meals == 3){
    $diet_type = array('breakfast','snack-2','dinner');
}else if($no_of_meals == 4){
    $diet_type = array('breakfast','lunch','snack-2','dinner');
}else if($no_of_meals == 5){
    $diet_type = array('breakfast','snack-1','lunch','snack-2','dinner');
}

/***** getting recipes start *****/
foreach($Days as $d){
    foreach($diet_type as $diet){
        
        if($no_of_meals == 1){
            
            if($diet == 'lunch'){
                
                $key = $d.'_'.$diet;
                $cal_key = $diet.'_cal';
                $args = array(
                'post_type' => 'recipes',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'tax_query' => array(
                        array(
                            'taxonomy' => 'menu-type',
                            'terms' => $menu,
                            'field' => 'slug',
                        ),
                        array(
                            'taxonomy' => 'diet-type',
                            'terms' => $diet,
                            'field' => 'slug',
                        ),
                        array(
                            'taxonomy' => 'recipe-days',
                            'terms' => $d,
                            'field' => 'slug',
                        ),
                        array(
                                'taxonomy' => 'week',
                                'terms' => $week,
                                'field' => 'slug',
                        ),
                    ),
                );
            }
            
        }else if($no_of_meals == 2){
            
            if($diet == 'breakfast' || $diet == 'dinner'){
                $key = $d.'_'.$diet;
                $cal_key = $diet.'_cal';
                $args = array(
                'post_type' => 'recipes',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'tax_query' => array(
                        array(
                            'taxonomy' => 'menu-type',
                            'terms' => $menu,
                            'field' => 'slug',
                        ),
                        array(
                            'taxonomy' => 'diet-type',
                            'terms' => $diet,
                            'field' => 'slug',
                        ),
                        array(
                            'taxonomy' => 'recipe-days',
                            'terms' => $d,
                            'field' => 'slug',
                        ),
                        array(
                            'taxonomy' => 'week',
                            'terms' => $week,
                            'field' => 'slug',
                        ),
                    ),
                );
            }
        }else if($no_of_meals == 3){
            
            if($diet == 'breakfast' ||  $diet == 'snack-2' || $diet == 'dinner' ){
                
                $key = $d.'_'.$diet;
                $cal_key = $diet.'_cal';
                $args = array(
                'post_type' => 'recipes',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'tax_query' => array(
                        array(
                            'taxonomy' => 'menu-type',
                            'terms' => $menu,
                            'field' => 'slug',
                        ),
                        array(
                            'taxonomy' => 'diet-type',
                            'terms' => $diet,
                            'field' => 'slug',
                        ),
                        array(
                            'taxonomy' => 'recipe-days',
                            'terms' => $d,
                            'field' => 'slug',
                        ),
                        array(
                            'taxonomy' => 'week',
                            'terms' => $week,
                            'field' => 'slug',
                        ),
                    ),
                );
            }
            
        }else if($no_of_meals == 4){
            
            if($diet == 'breakfast' || $diet == 'dinner' ||  $diet == 'snack-2' || $diet == 'lunch'){
                $key = $d.'_'.$diet;
                $cal_key = $diet.'_cal';
                $args = array(
                'post_type' => 'recipes',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'tax_query' => array(
                        array(
                            'taxonomy' => 'menu-type',
                            'terms' => $menu,
                            'field' => 'slug',
                        ),
                        array(
                            'taxonomy' => 'diet-type',
                            'terms' => $diet,
                            'field' => 'slug',
                        ),
                        array(
                            'taxonomy' => 'recipe-days',
                            'terms' => $d,
                            'field' => 'slug',
                        ),
                        array(
                            'taxonomy' => 'week',
                            'terms' => $week,
                            'field' => 'slug',
                        ),
                    ),
                );
            }
            
        }else if($no_of_meals == 5){
            $key = $d.'_'.$diet;
        
            $cal_key = $diet.'_cal';

            $args = array(
            'post_type' => 'recipes',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'tax_query' => array(
                    array(
                        'taxonomy' => 'menu-type',
                        'terms' => $menu,
                        'field' => 'slug',
                    ),
                    array(
                        'taxonomy' => 'diet-type',
                        'terms' => $diet,
                        'field' => 'slug',
                    ),
                    array(
                        'taxonomy' => 'recipe-days',
                        'terms' => $d,
                        'field' => 'slug',
                    ),
                    array(
                            'taxonomy' => 'week',
                            'terms' => $week,
                            'field' => 'slug',
                    ),
                ),
            );
        }


         $loop = new WP_Query( $args );
        $final = array();
            if($loop->have_posts()){
            while ($loop->have_posts()){
                    $loop->the_post();
//                    foreach($inc as $include){
//                        echo 'SELECT * FROM `wp_postmeta` WHERE `meta_value` LIKE "%'.$include.'%" AND `post_id`='.$post->ID;
//                        echo '<br>';
//                        $data = $wpdb->get_row('SELECT * FROM `wp_postmeta` WHERE `meta_value` LIKE "%'.$include.'%" AND `post_id`='.$post->ID);
//                    }
                    foreach($allergy as $allgy){
                        $data = $wpdb->get_row('SELECT * FROM `wp_postmeta` WHERE `meta_value` LIKE "%'.$allgy.'%" AND `post_id`='.$post->ID);
                        if(empty($data)){
                             $final[] = $post->ID;
                        }
                    }
//                    if(!empty($data)){
//                          $recipes[$d][$key] = get_the_title();  
//                          $kilo_cal[$d][$diet] = get_field('recipe_calories',$post->ID);
//                          $change_recipe[$key] = array($post->ID);
//                    }
                }
            }
            
        wp_reset_query();
        foreach($final as $fin){
            foreach($inc as $includes){
                $data = $wpdb->get_row('SELECT * FROM `wp_postmeta` WHERE `meta_value` LIKE "%'.$includes.'%" AND `post_id`='.$fin);
                if(!empty($data)){
                      $recipes[$d][$key] = get_the_title($fin);  
                      $kilo_cal[$d][$diet] = get_field('recipe_calories',$fin);
                      $change_recipe[$key] = array($fin);
                }
                if(empty($recipes[$d][$key])){
                    $recipes[$d][$key] = 'N/A';  
                    $kilo_cal[$d][$diet] = 0;
                    $change_recipe[$key] = 0;
                }
            }

        }
        
    }
}
?>
            <?php 

                $response['next_date'] = $PlanEndDate_timestamp;
                $response['showing_dates'] = $PlanStartDate.' - '.$PlanEndDate;
                $response['next_week'] = $next_week;
                $k=0;
                 foreach($recipes as $key=>$res){
                     $sd = explode('_',$key);
                     $day_name = substr($sd[0],0,3);
                     $day_name = strtoupper($day_name);
                     
                     $response['html_d'] .= '<div class="male-table">';
                     $response['html_d'] .= '<div class="heading">';
                     $response['html_d'] .= '<h3>'.$day_name.', '.$Dates[$k].'</h3>';
                     $response['html_d'] .= '</div>';
                     $response['html_d'] .= '<table>';
                     $response['html_d'] .= '<tbody>';
                     
                     $i=0;
                     $graph_data = '';
                     $graph_domain = '';
                     $graph_cal = '';

                     foreach($res as $r_key=>$r){ 
                     $dietType = explode("_",$r_key);
                     $diet = $dietType[1];
                     if (strpos($diet, '-') !== false) {
                         $diet = str_replace("-"," ",$diet);
                     }else{
                         $diet = $diet;
                     }
                     
                     $response['html_d'] .= '<tr>';
                     $response['html_d'] .= '<td >'.$diet.'</td>';
                     $response['html_d'] .= '<td class="'.$r_key.'_name">'.$r.'</td>';
                     $response['html_d'] .= '<td class="'.$r_key.'_link"><a href="#"><span><i class="fab fa-youtube"></i></span><img src="'.get_template_directory_uri().'/assets/images/pic.png"></a></td>';
                     $response['html'] .= '<td><a href="javascript:void(0);" onclick="change_recipe('.$change_recipe[$r_key][0].','.$r_key.');" ><i class="fa fa-refresh" aria-hidden="true"></i></a></td>';
                     $response['html_d'] .= '<input type="hidden" class="'.$r_key.'_count" value="0">';
                     $response['html_d'] .= '</tr>';
                     }
                     $response['html_d'] .= '</tbody>';
                     $response['html_d'] .= '</table>';
                     $response['html_d'] .= '</div>';
                     
                     $k++;
                }

                echo json_encode($response);
                 ?>