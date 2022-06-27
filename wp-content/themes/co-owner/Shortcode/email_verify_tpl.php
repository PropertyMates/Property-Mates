<div class="center-area">

    <div class="main-section bg-white public-title for-my-list py-20px">
        <div class="container">
            <?php 
               if(!empty($_GET['checkVarify']) && $_GET['checkVarify']==1){
				     ?>
					 <h1> SIGN UP</h1>
					 <h2> Thank you for joining PropertyMates! </h2>
					 <strong>Just one more step</strong>
					 <p>To finish signing up, you just read to confirm that </p>
					 <p>we got your email right.</p>
					 <br>
					 <br>
					 <p>Please check your email for the confirmation link.</p>
					 
					 <?php
			   }?>
			   
            <?php 
               if(!empty($_GET['act'])){
				    $code = unserialize(base64_decode($_GET['act']));
					/*echo '<pre>';
					 print_r($code);
					echo '</pre>';
					*/
					$user_id = $code['id'];
					$issuedDate =  strtotime($code['date']);
					$currentDate= strtotime(date('Y-m-d'));
					$hourdiff = round(($currentDate - $issuedDate)/3600, 1);
					if($hourdiff > 48 ){
						  genEmailVerification($user_id);
						?>
						<h1> Opps! Verification code was expired !</h1>
						<p>We have sent one more verification code to your email.Please check and verify again. Thanks </p>
						<?php
						
					}else{
					   update_user_meta($user_id,'account_activated',1);  
					   update_user_meta($user_id,'_user_status',1);  
                        ?>
						<h1> Account Varified!</h1>
						 <p>Please <a href="<?php echo site_url('login'); ?>">Click Here</a> to login.</p>
						<?php 					
					}
			   }?>
			   
			   
        </div>
    </div>
</div>