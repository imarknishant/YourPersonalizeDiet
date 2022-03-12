<?php
/* Template Name: dashboard profile */
global $user,$wpdb;
$user_id = get_current_user_id();
$res = $wpdb->get_results("SELECT * FROM plan_payments WHERE user_id=$user_id");
if(empty($res)){
    header("Location: ".site_url()."?plan=no");
}
get_header('dashboard');
$user = get_userdata($user_id);
$email = $user->user_email;
$fname = get_user_meta($user_id,'first_name',true);
$lname = get_user_meta($user_id,'last_name',true);
$name  = $fname.' '.$lname;
$phoneNumber = get_user_meta($user_id,'phone_number',true);
$image = get_field('image','user_'.$user_id);


?>

  <main id="dash-main">
    <div class="get-fit-mainbox">
        <div class="heading">
            <h4>Profile</h4>
        </div>
        <form id="dash_profile_form" method="post" action="" enctype="multipart/form-data">
        <div class="dash-profile">  
            <figure>
                <div class="image-upload">
                    <label for="file-input">
                        <i class="fa fa-pencil" aria-hidden="true"></i>
                    </label>               
                    <input id="file-input" type="file" name="dash_image" onchange="readURL(this);"/>
                </div>
                   <img id="blah" src="<?php
                    if($image!=''){
                        
                        echo $image;
                    }else{                   
                   echo get_template_directory_uri(); ?>/assets/images/avatar.png <?php } ?>">
                
               </figure>
    
               <div class="add-profile">
               <p>Add profile picture</p>
            </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>First Name</label>
                            <input type="text" class="form-control" name="first_name" placeholder="First Name" value="<?php echo $fname; ?>">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Last Name</label>
                               
                            <input type="text" class="form-control" name="last_name" placeholder="Last Name" value="<?php echo $lname; ?>">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Email Address</label>
                            <input type="email" class="form-control" name="email" placeholder="Email Address" value="<?php echo $email; ?>">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="tel" class="form-control" name="phone" placeholder="Phone Number" value="<?php echo $phoneNumber; ?>">
                        </div>
                    </div>
                 
                </div>
    
                <div class="save-cancel-btns">
                    <!--<button type="submit" class="btn">Save</button>-->
                    <input type="submit" class="btn" value="Save" name="dash_profile">
                    <input type="hidden" name="action" value="dash_profile_save">
                    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                    <button type="submit" class="btn btn-cancel">Cancel</button>
                </div>
            </div>
            </form>
        
                <div class="change-password-area">
                    <div class="heading">
                        <h3>Change Password</h3>
                    </div>
            <form id="dash_pass_change_form" method="post" action="">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Old Password</label>
                                <input id="old_pass" type="password" name="old_pass" class="form-control" placeholder="**********">
                                <span toggle="#password-field" class="fa fa-fw fa-eye field_icon old-toggle-password"></span>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>New Password</label>
                                <input id="new_pass" type="password" name="new_pass" class="form-control" placeholder="**********">
                                <span toggle="#new-password-field" class="fa fa-fw fa-eye field_icon new-toggle-password"></span>
                            </div>
                        </div>
                    </div>
    
                    <div class="save-cancel-btns">
                        <input type="submit" class="btn" value="Save">
                        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                        <input type="hidden" name="action" value="update_password">
                        <button type="submit" class="btn btn-cancel">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
     
    </div>
  </main>

<?php
get_footer('dashboard');
?>
      