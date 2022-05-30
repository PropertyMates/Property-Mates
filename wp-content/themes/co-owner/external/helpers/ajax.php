<?php
/*For Signup*/
function get_draft_permalink( $post_id ) {

    require_once ABSPATH . '/wp-admin/includes/post.php';
    list( $permalink, $postname ) = get_sample_permalink( $post_id );

    return str_replace( '%postname%', $postname, $permalink );
}

/*For Signup*/
function send_verification_code_by_email($email)
{
    try {
        $code = get_verification_code();
        $_SESSION['user_register_verification_code'] = $code;
        $_SESSION['user_register_verification_email'] = $email;

        ob_start();
        $message = str_replace('{{account-verification-code}}', $code, get_option('_crb_account_verification_code_format'));
        include(CO_OWNER_THEME_DIR . '/parts/mails/account-verification.php');
        $html = ob_get_clean();

        $subject = get_bloginfo('name');
        wp_mail($email, "[$subject] verification code", $html, array('Content-Type: text/html; charset=UTF-8'));
        return true;
    } catch (\Exception $exception) {
        if (isset($_SESSION['user_register_verification_code'])) {
            unset($_SESSION['user_register_verification_code']);
        }
        if (isset($_SESSION['user_register_verification_email'])) {
            unset($_SESSION['user_register_verification_email']);
        }

        return false;
    }
}

function user_is_old_password()
{
    $response = array(
        'status' => false,
        'message' => 'Something went wrong please try again.'
    );

    if (isset($_POST['password'])) {
        $old_password = $_POST['password'];
        $user = wp_get_current_user();
        if (wp_check_password($old_password, $user->user_pass)) {
            $response = array(
                'status' => true,
                'message' => ''
            );
        } else {
            $response = array(
                'status' => false,
                'message' => 'Old password is invalid.'
            );
        }
    }
    echo json_encode($response);
    wp_die();
}
add_action('wp_ajax_nopriv_user_is_old_password', 'user_is_old_password');
add_action('wp_ajax_user_is_old_password', 'user_is_old_password');
/*For Signup*/
function php_only_email_validation()
{
    $response = [
        'status' => false,
        'message' => 'Email is invalid.'
    ];

    if (
        isset($_POST['email']) &&
        filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)
    ) {
        $response = [
            'status' => true,
            'message' => 'success'
        ];
    }
    echo json_encode($response);
    wp_die();
}
add_action('wp_ajax_nopriv_php_only_email_validation', 'php_only_email_validation');
add_action('wp_ajax_php_only_email_validation', 'php_only_email_validation');

/*For Signup*/
function php_email_validation()
{
    $response = [
        'status' => false,
        'message' => 'This field is required.'
    ];
    if (
        isset($_POST['email']) &&
        filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)
    ) {
        $user = get_user_by('email', $_POST['email']);
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $response = [
                'status' => true,
                'message' => 'Email is invalid.'
				];
            echo json_encode($response);
            wp_die();
        }

        if ($user) {
            $login_user = wp_get_current_user();
            if (
                isset($_POST['ignore']) &&
                $user->user_email === $login_user->user_email
            ) {
                $response = [
                    'status' => true,
                    'message' => 'success'
                ];
            } else {
                $response = [
                    'status' => false,
                    'message' => 'Email is already taken.'
                ];
            }
        } else {
            $response = [
                'status' => true,
                'message' => 'success'
            ];
        }
    } else {
        $response = [
            'status' => false,
            'message' => 'Email is invalid.'
        ];
    }
    echo json_encode($response);
    wp_die();
}
add_action('wp_ajax_nopriv_php_email_validation', 'php_email_validation');
add_action('wp_ajax_php_email_validation', 'php_email_validation');

function php_username_validation()
{
    $response = [
        'status' => false,
        'message' => 'This field is required.'
    ];
    if (isset($_POST['username'])) {
        if (get_user_by('login', $_POST['username'])) {
            $response = [
                'status' => false,
                'message' => 'Username is already taken.'
            ];
        } else {
            $response = [
                'status' => true,
                'message' => 'success'
            ];
        }
    }
    echo json_encode($response);
    wp_die();
}
add_action('wp_ajax_nopriv_php_username_validation', 'php_username_validation');
add_action('wp_ajax_php_username_validation', 'php_username_validation');

function php_password_validation()
{
    if (isset($_POST['password'])) {
        $password = $_POST['password'];
        // Validate password strength
        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $number    = preg_match('@[0-9]@', $password);
        $specialChars = preg_match('@[^\w]@', $password);

        if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8 || strlen($password) > 16) {
            echo 'false';
        } else {
            echo 'true';
        }
    } else {
        echo 'false';
    }
    wp_die();
}
add_action('wp_ajax_nopriv_php_password_validation', 'php_password_validation');
add_action('wp_ajax_php_password_validation', 'php_password_validation');

// CO OWNER USER REGISTER
function verify_user_email_new()
{

    if (
        isset($_POST['email']) &&
        filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)
    ) {
        $user = get_user_by('email', $_POST['email']);
        if ($user == false)
         {
            $response = [
                'status' => true
            ];
        } else {
            $response = [
                'status' => false,
                'message' => 'Email is already taken.',
                'is_deleted' => (int) get_user_meta($user->ID, '_user_status', true)
            ];
        }
    } else {
        $response = [
            'status' => false,
            'message' => 'Email is invalid.'
        ];
    }
    echo json_encode($response);
    wp_die();
}

/*For Signup*/
function verify_user_email()
{
   $isEmailStoredVerified = !empty($_SESSION['verify_emails'][$_POST['email']]) ? $_SESSION['verify_emails'][$_POST['email']]['status'] : false;
    if($isEmailStoredVerified==200){
		    $response = [
                'status' => false,
				'message' => 'Already verified',
				'verify_status'=>200
				
            ];
			
			echo json_encode($response);
			wp_die();	
	}	
	
	
    if (
        isset($_POST['email']) &&
        filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)
    ) {
        $user = get_user_by('email', $_POST['email']);
        if (
            $user == false && send_verification_code_by_email($_POST['email'])
        ) {
			$_SESSION['verify_emails'][$_POST['email']] = array('status'=>100);
			
            $response = [
                'status' => true
            ];
        } else {
            $response = [
                'status' => false,
                'message' => 'Email is already taken.',
                'is_deleted' => (int) get_user_meta($user->ID, '_user_status', true)
            ];
        }
    } else {
        $response = [
            'status' => false,
            'message' => 'Email is invalid.'
        ];
    }
    echo json_encode($response);
    wp_die();
}



add_action('wp_ajax_nopriv_verify_user_email', 'verify_user_email');
add_action('wp_ajax_verify_user_email', 'verify_user_email');
/*For Signup*/
function verify_user_email_code()
{
    sleep(1);
    $response = [
        'status' => false,
        'message' => 'Something went wrong please try again.'
    ];
    if (
        isset($_POST['email']) &&
        isset($_POST['code']) &&
        filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) &&
        isset($_SESSION['user_register_verification_code']) &&
        isset($_SESSION['user_register_verification_email'])
    ) {
        if (
            (int) $_SESSION['user_register_verification_code'] === (int) $_POST['code'] &&
            $_SESSION['user_register_verification_email'] == $_POST['email']
        ) {
            $_SESSION['user_verified'] = true;
			if(!empty($_SESSION['verify_emails'][$_POST['email']])){
				$_SESSION['verify_emails'][$_POST['email']]['status'] = 200;
			}
			
            $response = ['status' => true];
        } else {
            $response = [
                'status' => false,
                'message' => 'Your verification code is invalid.'
            ];
        }
    }
    echo json_encode($response);
    wp_die();
}
add_action('wp_ajax_nopriv_verify_user_email_code', 'verify_user_email_code');
add_action('wp_ajax_verify_user_email_code', 'verify_user_email_code');

/*For Signup*/
function co_owner_user_register()
{
    $response = [
        'status' => false,
        'message' => array()
    ];


    if (
        isset($_POST['first_name']) && !empty($_POST['first_name']) &&
        isset($_POST['last_name']) && !empty($_POST['last_name']) &&
        isset($_POST['email']) && !empty($_POST['email']) &&
        /*isset($_POST['mobile']) && !empty($_POST['mobile']) && */
        isset($_POST['password']) && !empty($_POST['password']) &&
        isset($_POST['_user_plan_type']) && !empty($_POST['_user_plan_type'])
    ) {
        $email = $_POST['email'];
        $username = strtolower(str_replace([" "], "", ($_POST['last_name'] . $_POST['first_name'])));
        $username = mt_rand(1, 100) . '_' . $username . '_' . str_replace(['@', '.'], '', $email) . '_' . mt_rand(1, 100);

        $is_validate = true;
      if (
            (empty($_POST['verify_code_1']) && $_POST['verify_code_1'] == null) ||
            (empty($_POST['verify_code_2']) && $_POST['verify_code_2'] == null) ||
            (empty($_POST['verify_code_3']) && $_POST['verify_code_3'] == null) ||
            (empty($_POST['verify_code_4']) && $_POST['verify_code_4'] == null)
        ) {
            $response['message'][] = 'Email verification code is invalid';
            $is_validate = false;
        } else {
            $code = (string) $_POST['verify_code_1'] . (string) $_POST['verify_code_2'] . (string) $_POST['verify_code_3'] . (string) $_POST['verify_code_4'];
            
			if(!empty($_SESSION['verify_emails'][$email]['status']) && $_SESSION['verify_emails'][$email]['status']==200)
			{
				    $is_validate = true;
				
			}else{
				if (
					$_SESSION['user_register_verification_code'] != $code ||
					$_SESSION['user_register_verification_email'] != $email
				) {
					$response['message'][] = 'Email verification code is invalid';
					$is_validate = false;
				}
			}
			
			
        }
		

        $mobile = str_replace([" ", "+"], "", $_POST['mobile']);
    /*    if (
            (empty($_POST['mobile_verify_code_1']) && $_POST['mobile_verify_code_1'] == null) ||
            (empty($_POST['mobile_verify_code_2']) && $_POST['mobile_verify_code_2'] == null) ||
            (empty($_POST['mobile_verify_code_3']) && $_POST['mobile_verify_code_3'] == null) ||
            (empty($_POST['mobile_verify_code_4']) && $_POST['mobile_verify_code_4'] == null)
        ) {
            $response['message'][] = 'Mobile verification code is invalid';
            $is_validate = false;
        } else {
            $code = (string) $_POST['mobile_verify_code_1'] . (string) $_POST['mobile_verify_code_2'] . (string) $_POST['mobile_verify_code_3'] . (string) $_POST['mobile_verify_code_4'];
            if (
                $_SESSION['user_mobile_verification_code'] != $code ||
                $_SESSION['user_mobile_verification_email'] != $mobile
            ) {
                $response['message'][] = 'Mobile verification code is invalid';
                $is_validate = false;
            }
        }
		*/
	  if(!empty($_SESSION['verify_emails'][$email]['status']) && $_SESSION['verify_emails'][$email]['status']==200)
			{
				    $is_validate = true;
				
			}else{
				if (isset($_POST['email_verified_']) && $_POST['email_verified_'] != 'true') {
					$response['message'][] = 'Please verify email verification code.';
					$is_validate = false;
				}
			}
		/*
        if (isset($_POST['email_verified_']) && $_POST['mobile_verified_'] != 'true') {
            $response['message'][] = 'Please verify mobile verification code.';
            $is_validate = false;
        }
		*/
		


        if (check_column_in_table('users', 'user_email', $email)) {
            $response['message'][] = 'Email is already taken.';
            $is_validate = false;
        }

        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $password = $_POST['password'];
        $mobile = $_POST['mobile'];

        if ($is_validate == true) {

            $wp_user_info = new WP_User();
            $wp_user_info->display_name = ucfirst($first_name) . ' ' . $last_name;
            $wp_user_info->user_email = $email;
            $wp_user_info->first_name = $first_name;
            $wp_user_info->last_name = $last_name;
            $wp_user_info->user_login = $username;
            $wp_user_info->password = $password;
            $wp_user_info->user_registered = wp_date('Y-m-d H:i:s');

            $wp_user_id = wp_create_user($username, $password, $email);
            if ($wp_user_id) {
                $wp_user_info->ID = $wp_user_id;
                $created_id = wp_update_user($wp_user_info);
                update_user_meta($wp_user_id, 'first_name', $first_name);
                update_user_meta($wp_user_id, 'last_name', $last_name);
                update_user_meta($wp_user_id, '_mobile', $mobile);
                update_user_meta($wp_user_id, '_user_is_email_verified', false);
               // update_user_meta($wp_user_id, '_user_is_mobile_verified', true);

                register_user_subscription_information($wp_user_id, $_POST['_user_plan_type']);
                if ($_POST['_user_plan_type'] == 'trial') {
                    $_SESSION['open_subscription_modal'] = true;
                } elseif ($_POST['_user_plan_type'] == 'standard' || $_POST['_user_plan_type'] == 'professional') {
                    $_SESSION['open_payment_modal'] = $_POST['_user_plan_type'];
                }

                if ($created_id) {
                    unset($_SESSION['user_register_verification_code']);
                    unset($_SESSION['user_register_verification_email']);
                    unset($_SESSION['user_mobile_verification_code']);
                    unset($_SESSION['user_mobile_verification_email']);
                    unset($_SESSION['verify_emails']);
                    create_default_usermeta($wp_user_id);
                    $response = [
                        'status' => true,
                        'username' => $username,
                        'password' => base64_encode($password),
						'user_id' =>$wp_user_id	
                    ];
                }
            } else {
                $response = [
                    'status' => false,
                    'message' => array('Something went wrong please try again.'),
                ];
            }
        }
    } else {
        $response['status'] = false;
       /* if (empty($_POST['username'])) {
            $response['status'][] = 'Username is required.';
        }
        if (strpos($_POST['username'], 'admin') !== false) {
            $response['status'][] =  'Username can\'t keep admin.';
        }
		*/
        if (empty($_POST['first_name'])) {
            $response['status'][] = 'First name is required.';
        }
        if (empty($_POST['last_name'])) {
            $response['status'][] = 'Last name is required.';
        }
        if (empty($_POST['email'])) {
            $response['status'][] = 'Email is required.';
        }
       /* if (empty($_POST['mobile'])) {
            $response['status'][] = 'Mobile is required.';
        }
		*/
        if (empty($_POST['password'])) {
            $response['status'][] = 'Password is required.';
        }
        if (empty($_POST['_user_plan_type'])) {
            $response['status'][] = 'Please select your plan.';
        }
    }
    echo json_encode($response);
    wp_die();
}
add_action('wp_ajax_nopriv_co_owner_user_register', 'co_owner_user_register');
add_action('wp_ajax_co_owner_user_register', 'co_owner_user_register');

function send_mobile_verification_code()
{
    if (isset($_POST['mobile']) && strlen($_POST['mobile']) >= 10) {
        $mobile = str_replace([" ", "+"], "", $_POST['mobile']);
        $result = check_value_in_usermeta('_mobile', $mobile);
        if (empty($result)) {
            $code = get_verification_code();
            $_SESSION['user_mobile_verification_code'] = $code;
            $_SESSION['user_mobile_verification_email'] = $mobile;
            $html = str_replace('{{account-verification-code}}', $code, get_option('_crb_account_verification_code_format'));
            $sms = CoOwner_Twilio::sand_message($mobile, $html);
            $response = [
                'status' => $sms->status,
                'message' => $sms->message,
            ];
        } else {
            $response = [
                'status' => false,
                'message' => 'Mobile is already taken.'

            ];
        }
    } else {
        $response = [
            'status' => false,
            'message' => 'Mobile is invalid.'
        ];
    }
    echo json_encode($response);
    wp_die();
}
add_action('wp_ajax_nopriv_send_mobile_verification_code', 'send_mobile_verification_code');
add_action('wp_ajax_send_mobile_verification_code', 'send_mobile_verification_code');

function verify_user_mobile_verification_code()
{
    $response = [
        'status' => false,
        'message' => 'Something went wrong please try again.'
    ];
    sleep(1);
    //if (!wp_verify_nonce($_POST['ajax_nonce'], 'ajax_nonce')){
    //  echo json_encode($response);
    //  wp_die();
    //  }

    if (
        isset($_POST['mobile']) &&
        isset($_POST['code']) &&
        strlen($_POST['mobile']) >= 10 &&
        isset($_SESSION['user_mobile_verification_code']) &&
        isset($_SESSION['user_mobile_verification_email'])
    ) {
        $mobile = str_replace([" ", "+"], "", $_POST['mobile']);
        if (
            (int) $_SESSION['user_mobile_verification_code'] === (int) $_POST['code'] &&
            $_SESSION['user_mobile_verification_email'] == $mobile
        ) {
            $_SESSION['user_mobile_verified'] = true;
            $response = [
                'status' => true
            ];
        } else {
            $response = [
                'status' => false,
                'message' => 'Your verification code in invalid.'
            ];
        }
    }
    echo json_encode($response);
    wp_die();
}
add_action('wp_ajax_nopriv_verify_user_mobile_verification_code', 'verify_user_mobile_verification_code');
add_action('wp_ajax_verify_user_mobile_verification_code', 'verify_user_mobile_verification_code');
// CO OWNER USER REGISTER



// FORGOT PASSWORD
function user_forgot_password()
{
    $response = [
        'status' => false,
        'message' => array()
    ];


    if (
        isset($_POST['email']) &&
        !empty($_POST['email'])
    ) {
        $user = get_user_by('email', $_POST['email']);
        if ($user) {
            $token = wp_generate_uuid4();
            $time = wp_date('Y-m-d H:i:s', strtotime('+1 hour'));

            update_user_meta($user->ID, 'reset_password_token', $token);
            update_user_meta($user->ID, 'reset_password_token_expire', $time);

            $email = base64_encode($user->user_email);

            $link = home_url('reset-password') . "?token={$token}&email={$email}";
            ob_start();
            include(CO_OWNER_THEME_DIR . '/parts/mails/forgot-password.php');
            $html = ob_get_clean();

            $headers = array('Content-Type: text/html; charset=UTF-8');
            $site = get_bloginfo('name');
            wp_mail($user->user_email, PROPERTY_MATES_PASSWORD_RESET, $html, $headers);

            $response = [
                'status' => true,
                'message' => array('Mail Sent. Please check your email.')
            ];
        } else {
            $response['message'][] = ['User not found for this email.'];
        }
    }
    echo json_encode($response);
    wp_die();
}
add_action('wp_ajax_nopriv_user_forgot_password', 'user_forgot_password');
add_action('wp_ajax_user_forgot_password', 'user_forgot_password');

function user_reset_password()
{
    $response = [
        'status' => false,
        'message' => array()
    ];


    if (
        isset($_POST['email']) && !empty($_POST['email']) &&
        isset($_POST['password']) && !empty($_POST['password']) &&
        isset($_POST['token']) && !empty($_POST['token'])
    ) {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $token = $_POST['token'];
        $user = get_user_by('email', base64_decode($email));
        if ($user) {
            $user_token = get_user_meta($user->ID, 'reset_password_token', true);
            if ($user_token == $token) {
                $user_is_expire_time = get_user_meta($user->ID, 'reset_password_token_expire', true);
                if ($user_is_expire_time > wp_date('Y-m-d H:i:s')) {
                    wp_set_password($password, $user->ID);
					 update_user_meta($user->ID, '_user_status', 1);
                    delete_user_meta($user->ID, 'reset_password_token');
                    delete_user_meta($user->ID, 'reset_password_token_expire');
                    $response = [
                        'status' => true,
                        'message' => array('Password reset successfully.')
                    ];
                } else {
                    $response['message'] = ['Token is expire.'];
                }
            } else {
                $response['message'] = ['Token is invalid.'];
            }
        } else {
            $response['message'] = ['User not found.'];
        }
    } else {
        $response['message'] = ['Something went wrong please try again.'];
    }

    echo json_encode($response);
    wp_die();
}
add_action('wp_ajax_nopriv_user_reset_password', 'user_reset_password');
add_action('wp_ajax_user_reset_password', 'user_reset_password');

function save_post_images($post_id)
{

    if (!isset($_FILES) || empty($_FILES) || !isset($_FILES['_pl_images']))
        return;

    if (!function_exists('wp_handle_upload')) {
        require_once(ABSPATH . 'wp-admin/includes/file.php');
    }
    $upload_overrides = array('test_form' => false);

    $files = $_FILES['_pl_images'];
    foreach ($files['name'] as $key => $value) {
        if ($files['name'][$key]) {
            $uploadedFile = array(
                'name'     => $files['name'][$key],
                'type'     => $files['type'][$key],
                'tmp_name' => $files['tmp_name'][$key],
                'error'    => $files['error'][$key],
                'size'     => $files['size'][$key]
            );
            $moveFile = wp_handle_upload($uploadedFile, $upload_overrides);
            if ($moveFile && !isset($moveFile['error'])) {
                $uploadFiles = get_post_meta($post_id, '_pl_images', true);
                if (empty($uploadFiles)) $uploadFiles = array();
                $uploadFiles[] = $moveFile;
                update_post_meta($post_id, '_pl_images', $uploadFiles);
            }
        }
    }
}

/* #changed 11*/
function save_post_data_images($base64_img, $title, $post_id ) {
	
	// Upload dir.
	$upload_dir  = wp_upload_dir();
	$upload_path = str_replace( '/', DIRECTORY_SEPARATOR, $upload_dir['path'] ) . DIRECTORY_SEPARATOR;

	$img             = str_replace( 'data:image/jpeg;base64,', '', $base64_img );
	$img             = str_replace( ' ', '+', $img );
	$decoded         = base64_decode( $img );
	$filename        = $title;
	$file_type       = 'image/jpeg';
	$hashed_filename = md5( $filename . microtime() ) . '_' . $filename;

	// Save the image in the uploads directory.
	$upload_file = file_put_contents( $upload_path . $hashed_filename, $decoded );

	$attachment = array(
		'post_mime_type' => $file_type,
		'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $hashed_filename ) ),
		'post_content'   => '',
		'post_status'    => 'inherit',
		'guid'           => $upload_dir['url'] . '/' . basename( $hashed_filename )
	);

	$attach_id = wp_insert_attachment( $attachment, $upload_dir['path'] . '/' . $hashed_filename );
	   if(	$attach_id){
		   $moveFile= array(
		    'file' => $upload_dir['path'] . '/' . $hashed_filename,
			'url'=> $upload_dir['url'] . '/' . $hashed_filename,
			'type'=> $file_type
		   );			
		       $uploadFiles = get_post_meta($post_id, '_pl_images', true);
                if (empty($uploadFiles)) $uploadFiles = array();
                $uploadFiles[] = $moveFile;
                update_post_meta($post_id, '_pl_images', $uploadFiles);				
			 return true;
	   }
	    return false;
	
}
/* #changed 11*/

/*For Signup*/
function insert_property()
{
	/* #changed 11*/
	$is_mobile = false;	
	if (preg_match('/iPhone|iPod|iPad|BlackBerry|Android/', $_SERVER['HTTP_USER_AGENT'])) {
	  $is_mobile= true;
	}
	/* #changed 11*/
	
	//pr($_POST);
	//pr($_FILES);
	//die;
	

    $response = [
        'status' => false,
        'message' => array()
    ];

    $post_id = !empty($_POST['property_id']) && is_numeric($_POST['property_id']) ? $_POST['property_id'] : null;

    $is_edit = $post_id ? true : false;
    $user_id = get_current_user_id();
    if (!$is_edit && check_create_upto_listings_by_plan($user_id) == false) {
        $response = [
            'status' => false,
            'message' => array('Please update your subscription to create more listings.')
        ];
        echo json_encode($response);
        die;
    }

    //    if (!wp_verify_nonce($_POST['ajax_nonce'], 'ajax_nonce')){
    //        $response['message'][] = 'Something went wrong please try again.';
    //    }

    if (isset($_FILES['_pl_images']) && is_array($_FILES['_pl_images'])) {
        $files = $_FILES['_pl_images'];
        foreach ($files['name'] as $key => $value) {
            $mb = CO_OWNER_PROPERTY_IMAGE_LIMIT_MB;
            if ($files['size'][$key] > $mb) {
                $response['message'][] = "You can upload image maximum size {$mb}mb.";
                break;
            }
        }
    }

    if (isset($_POST['action']) == false || empty($_POST['action']) && $_POST['action'] !== "insert_property") {
        $response['message'][] = 'Something went wrong please try again.';
    }

    if (isset($_POST['_pl_property_category']) == false || empty(trim($_POST['_pl_property_category']))) {
        $response['message'][] = 'The property category field is required.';
    }
    if (isset($_POST['_pl_posted_by']) == false || empty(trim($_POST['_pl_posted_by']))) {
        $response['message'][] = 'The property posted by field is required.';
    }
    $property_category = isset($_POST['_pl_property_category']) ? $_POST['_pl_property_category'] : null;

    if (isset($_POST['_pl_property_type']) == false || empty(trim($_POST['_pl_property_type']))) {
        $response['message'][] = 'The property type field is required.';
    }
    if (isset($_POST['_pl_descriptions']) == false || empty(trim($_POST['_pl_descriptions']))) {
        $response['message'][] = 'The descriptions field is required.';
    }
    if (isset($_POST['_pl_heading']) == false || empty(trim($_POST['_pl_heading']))) {
        $response['message'][] = 'The heading field is required.';
    }

    if (isset($_POST['_pl_suburb']) == false || empty(trim($_POST['_pl_suburb']))) {
        $response['message'][] = 'The suburb field is required.';
    }
    if (isset($_POST['_pl_street_no']) == false || empty(trim($_POST['_pl_street_no']))) {
        $response['message'][] = 'The street no field is required.';
    }
    if (isset($_POST['_pl_postcode']) == false || empty(trim($_POST['_pl_postcode']))) {
        $response['message'][] = 'The postcode field is required.';
    }
    if (isset($_POST['_pl_street_name']) == false || empty(trim($_POST['_pl_street_name']))) {
        $response['message'][] = 'The street name field is required.';
    }
    if (isset($_POST['_pl_state']) == false || empty(trim($_POST['_pl_state']))) {
        $response['message'][] = 'The state field is required.';
    }

    if (isset($_POST['_pl_land_area']) == false || empty(trim($_POST['_pl_land_area']))) {
        $response['message'][] = 'The land area field is required.';
    }
    if (isset($_POST['_pl_building_area']) == false || empty(trim($_POST['_pl_building_area']))) {
        $response['message'][] = 'The building area field is required.';
    }
    if (isset($_POST['_pl_age_year_built']) == false || empty(trim($_POST['_pl_age_year_built']))) {
        $response['message'][] = 'The age year built field is required.';
    }

    if ($property_category == 'residential') {
        if (isset($_POST['_pl_property_features']) == false || empty($_POST['_pl_property_features'])) {
            $response['message'][] = 'Please select property features.';
        }
    }

    if (isset($_POST['_pl_interested_in_selling']) == false || empty(trim($_POST['_pl_interested_in_selling']))) {
        $response['message'][] = 'Please select interested in selling.';
    }
    $interested_in_selling = isset($_POST['_pl_interested_in_selling']) ? $_POST['_pl_interested_in_selling'] : null;

    if ($property_category == 'residential') {
        if (isset($_POST['_pl_this_property_is']) == false || empty(trim($_POST['_pl_this_property_is']))) {
            $response['message'][] = 'Please select this property is.';
        }
    }

    if (isset($_POST['_pl_currently_on_leased']) == false || empty(trim($_POST['_pl_currently_on_leased']))) {
        $response['message'][] = 'Please currently leased.';
    }

    if (
        isset($_POST['_pl_currently_on_leased']) &&
        $_POST['_pl_currently_on_leased'] == 'Yes' &&
        (isset($_POST['_pl_rent_per_month']) == false || empty(trim($_POST['_pl_rent_per_month'])))
    ) {
        $response['message'][] = 'The rent per month field is required.';
    }

    if ($_POST['_pl_currently_on_leased'] == 'Yes' && !is_numeric($_POST['_pl_rent_per_month'])) {
        $response['message'][] = 'The rent per month field is must be numeric.';
    }

    if (isset($_POST['_pl_property_market_price']) == false || empty(trim($_POST['_pl_property_market_price']))) {
        $response['message'][] = 'The property market price field is required.';
    }

    if (!is_numeric($_POST['_pl_property_market_price'])) {
        $response['message'][] = 'The market price must be numeric.';
    }

    if ($property_category == 'residential') {
        if (!is_numeric($_POST['_pl_bathroom'])) {
            $response['message'][] = 'The bathroom field is must be numeric.';
        }
        if (!is_numeric($_POST['_pl_bedroom'])) {
            $response['message'][] = 'The bedroom field is must be numeric.';
        }
        if (!is_numeric($_POST['_pl_parking'])) {
            $response['message'][] = 'The parking field is must be numeric.';
        }
    }

    if ($interested_in_selling != 'full_property') {
        if (isset($_POST['_pl_i_want_to_sell']) == false || empty(trim($_POST['_pl_i_want_to_sell']))) {
            $response['message'][] = 'Please select i want to sell %';
        }
        if (isset($_POST['_pl_calculated']) == false || empty(trim($_POST['_pl_calculated']))) {
            $response['message'][] = 'The calculated field is required.';
        }
    }

    if (count($response['message']) == 0 && is_user_logged_in()) {

        $heading = !empty($_POST['_pl_heading']) ? $_POST['_pl_heading'] : null;
        $descriptions = !empty($_POST['_pl_descriptions']) ? $_POST['_pl_descriptions'] : null;
        $posted_by = !empty($_POST['_pl_posted_by']) ? $_POST['_pl_posted_by'] : null;

        $property_type = !empty($_POST['_pl_property_type']) ? $_POST['_pl_property_type'] : null;
        $negotiable = isset($_POST['_pl_negotiable']) ? true : false;

        $address_manually = isset($_POST['_pl_address_manually']) ? $_POST['_pl_address_manually'] : false;
        $address_manually = $address_manually == 'true' ? true : false;
        $address = !empty($_POST['_pl_address']) ? $_POST['_pl_address'] : null;
        $unit_no = !empty($_POST['_pl_unit_no']) ? $_POST['_pl_unit_no'] : null;
        $suburb = !empty($_POST['_pl_suburb']) ? $_POST['_pl_suburb'] : null;
        $only_display_suburb_in_my_ad = isset($_POST['_pl_only_display_suburb_in_my_ad']) ? true : false;
        $street_no = !empty($_POST['_pl_street_no']) ? $_POST['_pl_street_no'] : null;
        $postcode = !empty($_POST['_pl_postcode']) ? $_POST['_pl_postcode'] : null;
        $street_name = !empty($_POST['_pl_street_name']) ? $_POST['_pl_street_name'] : null;
        $state = !empty($_POST['_pl_state']) ? $_POST['_pl_state'] : null;

        $building_area = !empty($_POST['_pl_building_area']) ? $_POST['_pl_building_area'] : null;
        $land_area = !empty($_POST['_pl_land_area']) ? $_POST['_pl_land_area'] : null;
        $age_year_built = !empty($_POST['_pl_age_year_built']) ? $_POST['_pl_age_year_built'] : null;

        $bathroom = isset($_POST['_pl_bathroom']) ? ((!empty($_POST['_pl_bathroom']) && $property_category == 'residential') ? (int) $_POST['_pl_bathroom'] : 0) : 0;
        $bedroom = isset($_POST['_pl_bedroom']) ? ((!empty($_POST['_pl_bedroom']) && $property_category == 'residential') ? (int) $_POST['_pl_bedroom'] : 0) : 0;
        $parking = isset($_POST['_pl_parking']) ? ((!empty($_POST['_pl_parking']) && $property_category == 'residential') ? (int) $_POST['_pl_parking'] : 0) : 0;
        $property_features = (!empty($_POST['_pl_property_features']) && $property_category == 'residential') ? $_POST['_pl_property_features'] : null;
        $manually_features = (!empty($_POST['_pl_manually_features']) && $property_category == 'residential') ? $_POST['_pl_manually_features'] : null;

        $interested_in_selling = !empty($_POST['_pl_interested_in_selling']) ? $_POST['_pl_interested_in_selling'] : null;
        $this_property_is = (!empty($_POST['_pl_this_property_is']) && $property_category == 'residential') ? $_POST['_pl_this_property_is'] : null;
        $currently_on_leased = !empty($_POST['_pl_currently_on_leased']) ? $_POST['_pl_currently_on_leased'] : null;
        $rent_per_month = !empty($_POST['_pl_rent_per_month']) ? $_POST['_pl_rent_per_month'] : null;
        $enable_pool = isset($_POST['_pl_enable_pool']) ? true : false;
        $property_market_price = !empty($_POST['_pl_property_market_price']) ? (float) $_POST['_pl_property_market_price'] : null;
        if ($interested_in_selling == 'full_property') {
            $i_want_to_sell = null;
            $calculated = null;
        } else {
            $i_want_to_sell = !empty($_POST['_pl_i_want_to_sell']) ? (float) $_POST['_pl_i_want_to_sell'] : null;
            $calculated = !empty($_POST['_pl_calculated']) ? (float) $_POST['_pl_calculated'] : null;
        }

        $status = (isset($_POST['is_preview']) && $_POST['is_preview'] == 'true') ? 'draft' : 'publish';

        $post = array(
            'post_author' => $user_id,
            'post_title' => $heading,
            'post_content' => $descriptions,
            'post_status' => $status,
            'post_type' => 'property',
        );
        if ($post_id) {
            $post['ID'] = $post_id;
            $is_edit = true;
            wp_update_post($post);
        } else {
            $post_id = wp_insert_post($post);
        }

        if ($post_id) {
            $total_member = count(get_property_total_members($post_id, true));
            /* #changed 11*/
			if($is_mobile){
				
				$post_images = !empty($_POST['post_images']) ? $_POST['post_images'] : array();
				if($post_images){
					foreach($post_images as $key => $dataImg){
						$fileName = $_FILES['_pl_images'][$key]['name'];
						save_post_data_images($dataImg, $fileName , $post_id );
					}
				}
			
				
			}else{
				save_post_images($post_id);
			}
			/* #changed 11*/
			
			
			
			/* Create new image from saved property Images  added Techinno */
			makePropertiesImageCroppedByID($post_id);
            update_post_meta($post_id, '_pl_property_category', $property_category);
            update_post_meta($post_id, '_pl_property_type', $property_type);
            update_post_meta($post_id, '_pl_posted_by', $posted_by);
            update_post_meta($post_id, '_pl_negotiable', $negotiable);
            update_post_meta($post_id, '_pl_age_year_built', $age_year_built);


            update_post_meta($post_id, '_pl_address_manually', $address_manually);
            update_post_meta($post_id, '_pl_address', $address);
            update_post_meta($post_id, '_pl_unit_no', $unit_no);
            update_post_meta($post_id, '_pl_suburb', $suburb);
            update_post_meta($post_id, '_pl_only_display_suburb_in_my_ad', $only_display_suburb_in_my_ad);
            update_post_meta($post_id, '_pl_street_no', $street_no);
            update_post_meta($post_id, '_pl_postcode', $postcode);
            update_post_meta($post_id, '_pl_street_name', $street_name);
            update_post_meta($post_id, '_pl_state', $state);

            update_post_meta($post_id, '_pl_building_area', $building_area);
            update_post_meta($post_id, '_pl_land_area', $land_area);
            update_post_meta($post_id, '_pl_bathroom', $bathroom);
            update_post_meta($post_id, '_pl_bedroom', $bedroom);
            update_post_meta($post_id, '_pl_parking', $parking);
            update_post_meta($post_id, '_pl_property_features', $property_features);
            update_post_meta($post_id, '_pl_manually_features', $manually_features);
            update_post_meta($post_id, '_pl_currently_on_leased', $currently_on_leased);
            update_post_meta($post_id, '_pl_rent_per_month', $rent_per_month);


            if ($total_member <= 0) {
                update_post_meta($post_id, '_pl_property_original_price', $property_market_price);
                update_post_meta($post_id, '_pl_interested_in_selling', $interested_in_selling);
                update_post_meta($post_id, '_pl_this_property_is', $this_property_is);
                update_post_meta($post_id, '_pl_enable_pool', $enable_pool);
                update_post_meta($post_id, '_pl_property_market_price', $property_market_price);
                update_post_meta($post_id, '_pl_i_want_to_sell', $i_want_to_sell);
                update_post_meta($post_id, '_pl_calculated', $calculated);
            }
            check_and_create_property_group($post_id);

            $ispreview = !empty($_POST['_pl_review_post']) ? $_POST['_pl_review_post'] : null;
			if($ispreview == "preview"){
			  $response = [
                'status'    => true,
                'message'   => array("Property Added Successfully")
				
            ];
			$message = "Property Added Successfully";
			}else{
            $response = [
                'status'    => true,
                'message'   => array("Property " . ($is_edit ? "Updated" : "Added") . " Successfully.")
            ];
			$message = "Property " . ($is_edit ? "Updated" : "Added") . " Successfully.";
			}
            $response['is_edit'] = true;
           
            $html = $status == 'draft' ? "preview=true" : "alert=success&alert_message={$message}";
             $permalink = get_the_permalink($post_id)."/?$html";
			 if($status=="draft"){
				$permalink  = home_url(CO_OWNER_PROPERTY_DETAILS_PAGE . "/?id={$post_id}&{$html}");
			 }
            $response['redirect'] =  $permalink; /*home_url(CO_OWNER_PROPERTY_DETAILS_PAGE . "/?id={$post_id}{$html}"); */

            update_property_price_for_search($post_id);

            if (!$is_edit) {
                CoOwner_Notifications::send_new_property_notification_cron($post_id);
            }

            echo json_encode($response);
            wp_die();
        } else {
            $response = [
                'status'    => false,
                'message'   => array('Something went wrong please try again.')
            ];
            echo json_encode($response);
            wp_die();
        }
    }
    echo json_encode($response);
    wp_die();
}
add_action('wp_ajax_nopriv_insert_property', 'insert_property');
add_action('wp_ajax_insert_property', 'insert_property');


function api_domain_get_address($address, $max = 1)
{
    $address = http_build_query(array(
        'terms' => $address,
        'pageSize' => $max
    ));
    $key = get_option('_crb_api_domain_com');
    if ($key) {
        $curl = curl_init('https://api.domain.com.au/v1/properties/_suggest?' . $address);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            "X-API-Key: " . $key,
        ));
        $response = curl_exec($curl);
        $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $status = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
        $body = substr($response, $header_size);
        curl_close($curl);
        if ($status == 200) {
            return json_decode($body, true);
        }
    }
}

function api_domain_get_property_info($property_id)
{
    $key = get_option('_crb_api_domain_com');

    if ($key) {
        $curl = curl_init("https://api.domain.com.au/v1/properties/{$property_id}/priceEstimate");
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            "X-API-Key: " . $key,
        ));
        $response = curl_exec($curl);
        $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $status = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
        $body = substr($response, $header_size);
        curl_close($curl);
        if ($status == 200) {
            return json_decode($body, true);
        }
    }
    return json_decode([]);
}

function get_property_price_by_address()
{
    $response = array(
        'status' => false,
        'message' => 'Property Not Found.',
    );

    //if (!wp_verify_nonce($_POST['ajax_nonce'], 'ajax_nonce')){
    //  echo json_encode($response);
    //  wp_die();
    //  }

    if (isset($_POST['action']) == 'get_property_price_by_address') {
        $address = api_domain_get_address(trim($_POST['address']));
        if ($address) {
            $priceestimate = api_domain_get_property_info($address[0]['id']);

            $lowerPrice = isset($priceestimate['lowerPrice']) ? $priceestimate['lowerPrice'] : 0;
            $lower_price = $lowerPrice > 0 ? CO_OWNER_CURRENCY_SYMBOL . (number_format_short($lowerPrice)) : '-';
            $midPrice = isset($priceestimate['midPrice']) ? $priceestimate['midPrice'] : 0;
            $mid_price = $midPrice > 0 ? CO_OWNER_CURRENCY_SYMBOL . (number_format_short($midPrice)) : '-';
            $upperPrice = isset($priceestimate['upperPrice']) ? $priceestimate['upperPrice'] : 0;
            $upper_price = $upperPrice > 0 ? CO_OWNER_CURRENCY_SYMBOL . (number_format_short($upperPrice)) : '-';

            $html = "<div class='d-flex pt-3 mb-3'>
                        <div class='w-100 text-center domain-price'>
                            <span class='d-block'>LOW</span>
                            <h4 class='pb-0 pt-1 d-block'>{$lower_price}</h4>
                        </div>
                        <div class='w-100 text-center domain-price'>
                            <span class='d-block'>MID</span>
                            <h4 class='pb-0 pt-1 d-block green'>{$mid_price}</h4>
                        </div>
                        <div class='w-100 text-center domain-price'>
                            <span class='d-block'>HIGH</span>
                            <h4 class='pb-0 pt-1 d-block'>{$upper_price}</h4>
                        </div>
                    </div>
                    ";

            $response = array(
                'status' => true,
                'html' => $html,
            );
        }
    }
    echo json_encode($response);
    wp_die();
}
add_action('wp_ajax_nopriv_get_property_price_by_address', 'get_property_price_by_address');
add_action('wp_ajax_get_property_price_by_address', 'get_property_price_by_address');

function get_property_address()
{
    $response = array(
        'status' => false,
        'message' => 'Something went wrong please try again.'
    );
    //if (!wp_verify_nonce($_POST['ajax_nonce'], 'ajax_nonce')){
    //  echo json_encode($response);
    //  wp_die();
    //  }

    if (
        isset($_POST['action']) &&
        $_POST['action'] == 'get_property_address' &&
        isset($_POST['properties']) &&
        count($_POST['properties']) > 0
    ) {
        $property_address = array();
        foreach ($_POST['properties'] as $id) {
            $address = get_property_full_address($id, true);
            $property_address[] = array(
                'id'        => $id,
                'address'   => $address
            );
        }
        $response = array(
            'status'    => true,
            'data'      => $property_address
        );
    }

    echo json_encode($response);
    wp_die();
}
add_action('wp_ajax_nopriv_get_property_address', 'get_property_address');
add_action('wp_ajax_get_property_address', 'get_property_address');

function create_person_listing()
{
    $response = [
        'status' => false,
        'message' => array()
    ];

    $user_id = get_current_user_id();
    $listing_status = isset($_POST['_user_listing_status']) ? $_POST['_user_listing_status'] : 0;
    if ($listing_status == 0 && check_create_upto_listings_by_plan($user_id) == false) {
        $response = [
            'status' => false,
            'message' => array('Please update your subscription to create more listings.')
        ];
        echo json_encode($response);
        die;
    }

    if (isset($_POST['_user_listing_status'])) {
        if (isset($_POST['_user_property_category']) == false || count($_POST['_user_property_category']) === 0) {
            $response['message'][] = 'The property category field is required.';
        }
        // if (isset($_POST['_user_property_type']) == false || count($_POST['_user_property_type']) === 0) {
        //     $response['message'][] = 'The property type field is required.';
        // }
        // if(isset($_POST['_user_descriptions']) == false || empty(trim($_POST['_user_descriptions']))){
        //      $response['message'][] = 'The descriptions field is required.';
        // }
        if (isset($_POST['_user_preferred_location']) == false || count($_POST['_user_preferred_location']) === 0) {
            $response['message'][] = 'The preferred location field is required.';
        }
        // if (isset($_POST['_user_land_area']) == false || empty(trim($_POST['_user_land_area']))) {
        //     $response['message'][] = 'The land area field is required.';
        // }
        // if (isset($_POST['_user_building_area']) == false || empty(trim($_POST['_user_building_area']))) {
        //     $response['message'][] = 'The building area field is required.';
        // }
        // if (isset($_POST['_user_age_year_built']) == false || empty(trim($_POST['_user_age_year_built']))) {
        //     $response['message'][] = 'The age year built field is required.';
        // }

        if (isset($_POST['_user_property_category']) && in_array('residential', $_POST['_user_property_category']) && isset($_POST['_user_bedroom']) == false) {
            $response['message'][] = 'The bedroom field is required.';
        }
        if (isset($_POST['_user_property_category']) && in_array('residential', $_POST['_user_property_category']) && isset($_POST['_user_bathroom']) == false) {
            $response['message'][] = 'The bathroom field is required.';
        }
        if (isset($_POST['_user_property_category']) && in_array('residential', $_POST['_user_property_category']) && isset($_POST['_user_parking']) == false) {
            $response['message'][] = 'The parking field is required.';
        }
        // if (isset($_POST['_user_property_category']) && in_array('residential', $_POST['_user_property_category']) && isset($_POST['_user_property_features']) == false) {
        //     $response['message'][] = 'The property features field is required.';
        // }

        if (isset($_POST['user_budget_price']) == false || empty(trim($_POST['user_budget_price']))) {
            $response['message'][] = ((isset($_POST['user_budget_price']) && $_POST['user_budget_price'] == 0) ? 'The budget must be value greater than 0.' : 'The budget field is required.');
        }

        $budget_range = sanitize_text_field($_POST['user_budget_price']);
        $budget = explode(",", $budget_range);
        $user_budget = $minbudget = $maxbudget = 0;
        if (in_array('+', $budget)) {
            $user_budget = $budget[0];
            $minbudget = $budget[0];
            $maxbudget = '';
        } else {
            $user_budget = $maxbudget = $budget[1];
            $minbudget = $budget[0];
        }

        if (!is_numeric($user_budget)) {
            $response['message'][] = 'The budget must be numeric.';
        }

        $bedroom = 0;
        $bathroom = 0;
        $parking = 0;

        if (isset($_POST['_user_property_category']) && in_array('residential', $_POST['_user_property_category'])) {
            if (!is_numeric($_POST['_user_bedroom'])) {
                $response['message'][] = 'The bathroom must be numeric.';
            }

            if (!is_numeric($_POST['_user_bathroom'])) {
                $response['message'][] = 'The bedroom must be numeric.';
            }

            if (!is_numeric($_POST['_user_parking'])) {
                $response['message'][] = 'The parking must be numeric.';
            }
        } else {
            $bedroom = 0;
            $bathroom = 0;
            $parking = 0;
        }
        $user_location = $_POST['_user_preferred_location'];
        if (in_array('all', $user_location)) {
            $user_location = array('NSW', 'VIC', 'QLD', 'TAS', 'SA', 'WA', 'NT', 'ACT');
        }

        if (count($response['message']) == 0 && is_user_logged_in()) {
            $userMetaData = array(
                '_user_property_category' => $_POST['_user_property_category'],
                '_user_property_type' => $_POST['_user_property_type'],
                '_user_descriptions' => $_POST['_user_descriptions'],
                '_user_preferred_location' => $user_location,
                '_user_land_area' => $_POST['_user_land_area'],
                '_user_building_area' => $_POST['_user_building_area'],
                '_user_age_year_built' => $_POST['_user_age_year_built'],
                '_user_bedroom' => (int) $_POST['_user_bedroom'],
                '_user_bathroom' => (int) $_POST['_user_bathroom'],
                '_user_parking' => (int) $_POST['_user_parking'],
                '_user_property_features' => $_POST['_user_property_features'],
                '_user_budget' => (int)$user_budget,
                '_user_budget_range' => $budget_range,
                '_user_enable_pool' => isset($_POST['_user_enable_pool']) ? true : false,
                '_user_listing_status' => $listing_status
            );

            if ($maxbudget)
                $userMetaData['_max_budget'] = sanitize_text_field($maxbudget);
            else
                delete_user_meta($user_id, '_max_budget');

            if ($minbudget)
                $userMetaData['_min_budget'] = sanitize_text_field($minbudget);
            else
                delete_user_meta($user_id, '_min_budget');

            if (isset($_POST['_user_manually_features'])) {
                $userMetaData['_user_manually_features'] = $_POST['_user_manually_features'];
            } else {
                $userMetaData['_user_manually_features'] = array();
            }
			
			$typecheck = $_POST['_user_property_typecheck'];
			

            co_owner_update_user_meta($user_id, $userMetaData);
			if($typecheck == "create"){
            $message = $listing_status == 0 ? 'Person Created Successfully.' : 'Buyer Profile Created Successfully.';
			}else{
			$message = $listing_status == 0 ? 'Person Created Successfully.' : 'Buyer Profile Updated Successfully.';
			
			}

            $alert = $listing_status == 0 ? '&is_preview=true' : "&alert=success&alert_message={$message}";
			if($typecheck == "create"){
           $response = [
                'status'    => true,
                'message'   => array('Buyer Profile Create Successfully.'),
                'link' => home_url(CO_OWNER_PERSON_DETAILS_PAGE . "?id={$user_id}{$alert}")
            ];
			}else{
			 $response = [
                'status'    => true,
                'message'   => array('Buyer Profile Updated Successfully.'),
                'link' => home_url(CO_OWNER_PERSON_DETAILS_PAGE . "?id={$user_id}{$alert}")
            ];
			
			}
           
            CoOwner_Notifications::send_new_person_notification_cron($user_id);
            echo json_encode($response);
            wp_die();
        }
    }
    echo json_encode($response);
    wp_die();
}
add_action('wp_ajax_nopriv_create_person_listing', 'create_person_listing');
add_action('wp_ajax_create_person_listing', 'create_person_listing');

function property_mark_as_status()
{
    $response = array('status' => false, 'message' => 'Something went wrong please try again.');

    //if (!wp_verify_nonce($_POST['ajax_nonce'], 'ajax_nonce')){
    //  echo json_encode($response);
    //  wp_die();
    //  }

    $user_id = get_current_user_id();

    if ($user_id && get_user_status($user_id) != 1) {
        $response['message'] = 'Your account is deactivated.';
        echo json_encode($response);
        wp_die();
    }

    if (isset($_POST['id']) && isset($_POST['status'])) {
        $id = $_POST['id'];
        $status = $_POST['status'] == 'complete' ? 'completed' : 'publish';
        property_mark_as_complete($id, $status);
        $response = array('status' => true, 'message' => 'Property status updated successfully.');
    }
    echo json_encode($response);
    wp_die();
}
add_action('wp_ajax_nopriv_property_mark_as_status', 'property_mark_as_status');
add_action('wp_ajax_property_mark_as_status', 'property_mark_as_status');

function delete_property_listing()
{
    $response = [
        'status' => false,
        'message' => 'Something went wrong please try again.'
    ];

    //if (!wp_verify_nonce($_POST['ajax_nonce'], 'ajax_nonce')){
    //  echo json_encode($response);
    //  wp_die();
    //  }

    $userid = get_current_user_id();
    if ($userid && get_user_status($userid) != 1) {
        $response['message'] = 'Your account is deactivated.';
        echo json_encode($response);
        wp_die();
    }

    if (isset($_POST['id']) && !empty($_POST['id'])) {
        $post_id = (int) $_POST['id'];
        $post = get_post($post_id);
        if ($post->post_author == $userid) {
            $is_delted = wp_delete_post($post_id, true);
            if ($is_delted != false) {
                CoOwner_Groups::delete_row(CO_OWNER_GROUP_TABLE, ['property_id' => $post_id]);
                $response = array(
                    'status'    => true,
                    'message'   => 'Property Deleted Successfully.'
                );
            }
        } else {
            $response = array(
                'status'    => true,
                'message'   => 'You don\'t have a permission.'
            );
        }
    }
    echo json_encode($response);
    wp_die();
}
add_action('wp_ajax_nopriv_delete_property_listing', 'delete_property_listing');
add_action('wp_ajax_delete_property_listing', 'delete_property_listing');

function delete_property_image()
{
    $response = [
        'status' => false,
        'message' => 'Something went wrong please try again.'
    ];

    //if (!wp_verify_nonce($_POST['ajax_nonce'], 'ajax_nonce')){
    //  echo json_encode($response);
    //  wp_die();
    //  }

    if (
        isset($_POST['property_id']) &&
        isset($_POST['index']) &&
        !empty($_POST['property_id'])
    ) {
        $index = $_POST['index'];
        $post_id = $_POST['property_id'];
        $response = (array) remove_property_image($post_id, $index);
        echo json_encode($response);
        wp_die();
    }
    echo json_encode($response);
    wp_die();
}
add_action('wp_ajax_nopriv_delete_property_image', 'delete_property_image');
add_action('wp_ajax_delete_property_image', 'delete_property_image');

function make_like_dislike($type = "post")
{
    $response = array(
        'status' => false,
        'message' => 'Something went wrong please try again.'
    );
    if (current_user_can('administrator')) {
        return $response;
    }

    //if (!wp_verify_nonce($_POST['ajax_nonce'], 'ajax_nonce')){
    //  echo json_encode($response);
    //  wp_die();
    //  }

    if (
        !isset($_POST['id']) || !isset($_POST['like']) ||
        empty($_POST['id'])
    ) {
        echo json_encode($response);
        wp_die();
    }

    $user_id = get_current_user_id();

    if (!$user_id) {
        $response['message'] = 'You are not logged in. Please login';
        echo json_encode($response);
        wp_die();
    }
    if ($user_id && get_user_status($user_id) != 1) {
        $response['message'] = 'Your account is deactivated.';
        echo json_encode($response);
        wp_die();
    }

    $role = co_owner_get_user_field('s2member_access_role', $user_id);
    if (!in_array($role, ['s2member_level0', 's2member_level1', 's2member_level2'])) {
        $response['message'] = 'You don\'t have any subscription.';
        echo json_encode($response);
        wp_die();
    }


    $id = $_POST['id'];
    $is_like = isset($_POST['like']) ? $_POST['like'] : false;

    $data_array = array(
        'user_id'   =>  (int) $user_id,
        'favourite_type'   =>  $type,
        'favourite_id'   =>  (int) $id,
    );

    if ($is_like === "true") {
        $response['status'] = CoOwner_Favourite::like($data_array);
    } else {
        $response['status'] = CoOwner_Favourite::dislike($data_array);
    }
    echo json_encode($response);
    wp_die();
}


function make_property_like_dislike()
{
    make_like_dislike("post");
}

add_action('wp_ajax_nopriv_make_property_like_dislike', 'make_property_like_dislike');
add_action('wp_ajax_make_property_like_dislike', 'make_property_like_dislike');


function make_people_like_dislike()
{
    make_like_dislike("user");
}

add_action('wp_ajax_nopriv_make_people_like_dislike', 'make_people_like_dislike');
add_action('wp_ajax_make_people_like_dislike', 'make_people_like_dislike');

function sand_connection_request($array)
{
    $response = array(
        'status' => false,
        'message' => 'Something went wrong please try again.'
    );

    if (isset($array['sender_user']) && get_user_status($array['sender_user']) != 1) {
        $response = array(
            'status' => false,
            'message' => 'Your account is deactivated.'
        );
        echo json_encode($response);
        wp_die();
    }

    if (
        isset($array['sender_user']) &&
        isset($array['receiver_user']) &&
        isset($array['property_id']) &&
        isset($array['comment'])
    ) {
        $sender_user = $array['sender_user'];
        $sender = $receiver_user = $array['receiver_user'];
        $property_id = $array['property_id'];
        $comment = $array['comment'];

        $role = co_owner_get_user_field('s2member_access_role', $sender_user);
        if (!in_array($role, ['s2member_level0', 's2member_level1', 's2member_level2'])) {
            return $response = array(
                'status'    => false,
                'message'   => 'You don\'t have any subscription.'
            );
        }

        $connection = array(
            'sender_user'       => $sender_user,
            'receiver_user'     => $receiver_user,
            'property_id'       => $property_id,
            'comment'           => $comment,
            'calculated_price'  => null,
            'interested_in'     => null,
        );

        $property = get_property_detail_by_id($property_id);
        $enable_pool = get_post_meta($property_id, '_pl_enable_pool', true);

        /* IS GROUP REQUEST */
        if ($enable_pool && !isset($array['interested_in'])) {
            return $response = array(
                'status'    => false,
                'message'   => 'Please select your interest.'
            );
        } elseif ($enable_pool && isset($array['interested_in'])) {
            $interested = $array['interested_in'];
            if ($interested <= $property->available_share) {
                $calculated_price = calculate_property_share_interest($property->available_share, $array['interested_in'], $property->available_price);
                $connection['calculated_price'] = (float) $calculated_price;
                $connection['interested_in'] = (int) $array['interested_in'];
            } else {
                return $response = array(
                    'status'    => false,
                    'message'   => "Available Portion Only. {$property->available_share} %"
                );
            }
        }
        /* IS ONE TO ONE REQUEST */

        $sender_check = CoOwner_Connections::count(array('sender_user' => $connection['sender_user'], 'receiver_user' => $connection['receiver_user'], 'group_id' => NULL));
        $receiver_check = CoOwner_Connections::count(array('sender_user' => $connection['receiver_user'], 'receiver_user' => $connection['sender_user'], 'group_id' => NULL));

        if ($sender_check == 0 && $receiver_check == 0) {
            $id = CoOwner_Connections::create($connection);
            if ($id > 0) {
                CoOwner_Conversation::send_message($sender_user, $receiver_user, $comment, false, $connection['calculated_price'], $connection['interested_in'], 1, $property_id);
                $message = CoOwner_Conversation::get_conversations($sender_user, $receiver_user, false, 1, true);
                ob_start();
                include(CO_OWNER_THEME_DIR . '/parts/message.php');
                $html = ob_get_clean();
                $message_result = array(
                    'status' => true,
                    'html' => $html,
                    'files' => "",
                    'message' => $message
                );

                $pusher = new CoOwner_pusher();
                $channel = "chat-message-$receiver_user";
                $pusher->pusher->trigger($channel, "new-message", $message_result);

                CoOwner_Notifications::create($sender_user, $receiver_user, 'sent you a connection request', 1, $id);
            }
			$sendername = get_user_full_name($sender_user);
			$receivername = get_user_full_name($receiver_user);
			
            $url = home_url("messages?request={$id}&is_received={$receiver_user}&alert=success&alert_message={$sendername} sent Request to {$receivername} successfully.");
            $response = array(
                'status' => true,
                'message' => "{$sendername} sent Request to {$receivername} successfully",
                'url' => $url
            );
        } else {
            $con = CoOwner_Connections::get_connection_between_sender_receiver($connection['receiver_user'], $connection['sender_user']);
            if ($con && $con->status == 2) {
                $updated = CoOwner_Connections::update_connection_status($con->id, 0);
                if ($updated) {
                    CoOwner_Notifications::create($sender_user, $receiver_user, 'sent you a connection request', 1, $con->id);
                }
                $response = array('status' => true, 'message' => 'Request sent successfully.');
            } elseif ($con && $con->status == 3) {
                $message = $con->receiver_user == get_current_user_id() ? 'You have blocked this user. Please unblock and re-try sending the request..' : 'You are blocked.';
                $response = array(
                    'status' => false,
                    'message' => $message
                );
            } else {
                $is_requested = CoOwner_Connections::check_user_has_already_requested_in_property($sender_user, $receiver_user, $property_id, false);
                if ($is_requested) {
                    $message = null;
                    if ($is_requested->status == 1) {
                        $message = YOU_ARE_ALREADY_CONNECTED_WITH_THIS_PROPERTY;
                    } elseif ($is_requested->sender_user == $sender) {
                        $message = YOU_HAVE_ALREADY_PLACED_A_REQUEST_ON_THIS_PROPERTY;
                    } else {
                        $message = YOU_HAVE_ALREADY_RECEIVED_A_REQUEST_ON_THIS_PROPERTY;
                    }
                    $response = array(
                        'status' => false,
                        'message' => $message,
                    );
                } else {
                    $created = CoOwner_Conversation::send_message(
                        $sender_user,
                        $receiver_user,
                        $comment,
                        0,
                        ($connection['calculated_price'] ? $connection['calculated_price'] : 0),
                        ($connection['interested_in'] ? $connection['interested_in'] : 0),
                        1,
                        $property_id,
                    );

                    if ($created) {
                        $message = CoOwner_Conversation::get_conversations($sender_user, $receiver_user, false, 1, true);
                        ob_start();
                        include(CO_OWNER_THEME_DIR . '/parts/message.php');
                        $html = ob_get_clean();
                        $message_result = array(
                            'status' => true,
                            'html' => $html,
                            'files' => "",
                            'message' => $message
                        );

                        $pusher = new CoOwner_pusher();
                        $channel = "chat-message-$receiver_user";
                        $pusher->pusher->trigger($channel, "new-message", $message_result);

                        CoOwner_Notifications::create($sender_user, $receiver_user, 'sent you a request', 2, $message->id);

                        $is_sender = $array['sender_user'] != get_current_user_id() ? 'true' : 'false';
                        $url = home_url('messages') . ($con->status == 1 ? '?is_pool=false&with=' . $receiver_user : ("?request={$con->id}&is_received={$is_sender}"));
                        $response = array(
                            'status' => true,
                            'message' => 'Request sent successfully.',
                            'url' => $url . "&alert=success&alert_message=Request sent successfully.",
                        );
                    }
                }
            }
        }
    }
    return $response;
}

function send_person_connection_request()
{
    $response = array(
        'status' => false,
        'message' => 'Something went wrong please try again.'
    );

    //if (!wp_verify_nonce($_POST['ajax_nonce'], 'ajax_nonce')){
    //  echo json_encode($response);
    //  wp_die();
    //  }

    $auth_user = get_current_user_id();
	
    if (
        isset($_POST['description']) && !empty($_POST['description']) &&
        isset($_POST['user_id']) && !empty($_POST['user_id']) &&
       /* isset($_POST['property_id']) && !empty($_POST['property_id']) && */
        $auth_user > 0
    ) {
        $array = array(
            'sender_user' => $auth_user,
            'receiver_user' => (int) $_POST['user_id'],
            'property_id' => (int) $_POST['property_id'],
            'comment' => $_POST['description'],
            'interested_in' => isset($_POST['interested_in']) ? (int) $_POST['interested_in'] : 0
        );

        $is_requested = CoOwner_Connections::check_user_has_already_requested_in_property($auth_user, $_POST['user_id'], $_POST['property_id'], false);
        if ($is_requested) {
            $message = null;
            if ($is_requested->status == 1) {
                $message = YOU_ARE_ALREADY_CONNECTED_WITH_THIS_PROPERTY;
            } elseif ($is_requested->sender_user == $auth_user) {
                $message = YOU_HAVE_ALREADY_PLACED_A_REQUEST_ON_THIS_PROPERTY;
            } else {
                $message = YOU_HAVE_ALREADY_RECEIVED_A_REQUEST_ON_THIS_PROPERTY;
            }
            $response = array(
                'status' => false,
                'message' => $message,
            );
        } else {
            $response = sand_connection_request($array);
        }
    }
    if (!$auth_user) {
        $response['message'] = 'You are not logged in. Please login';
    }
    echo json_encode($response);
    wp_die();
}
add_action('wp_ajax_nopriv_send_person_connection_request', 'send_person_connection_request');
add_action('wp_ajax_send_person_connection_request', 'send_person_connection_request');

function send_property_connection_request()
{
    $response = array(
        'status' => false,
        'message' => 'Something went wrong please try again.'
    );

    //if (!wp_verify_nonce($_POST['ajax_nonce'], 'ajax_nonce')){
    //  echo json_encode($response);
    //  wp_die();
    //  }

    $auth_user = get_current_user_id();

    if (
        isset($_POST['description']) && !empty($_POST['description']) &&
        isset($_POST['property_id']) && !empty($_POST['property_id']) &&
        $auth_user > 0
    ) {
        $property = get_post($_POST['property_id']);
        if ($property) {
            $array = array(
                'sender_user' => (int) $auth_user,
                'receiver_user' => (int) $property->post_author,
                'property_id' => (int) $_POST['property_id'],
                'comment' => $_POST['description'],
                'interested_in' => isset($_POST['interested_in']) ? (int) $_POST['interested_in'] : 0
            );
            $is_requested = CoOwner_Connections::check_user_has_already_requested_in_property($auth_user, $property->post_author, $property->ID, false);
            if ($is_requested) {
                $message = null;
                if ($is_requested->status == 1) {
                    $message = "You are already connected with this property.";
                } elseif ($is_requested->sender_user == $auth_user) {
                    $message = "You have already placed a request on this property.";
                } else {
                    $message = "You have already received a request on this property.";
                }
                $response = array(
                    'status' => false,
                    'message' => $message,
                );
            } else {
                $response = sand_connection_request($array);
            }
        }
    }
    if (!$auth_user) {
        $response['message'] = 'You are not logged in. Please login';
    }
    echo json_encode($response);
    wp_die();
}
add_action('wp_ajax_nopriv_send_property_connection_request', 'send_property_connection_request');
add_action('wp_ajax_send_property_connection_request', 'send_property_connection_request');

function get_property_share_options_by_id($post_id, $total_share = null)
{
    if ($total_share == null) {
        $total_share = get_property_available_share($post_id);
    }
    $html = '<option value="">Select Interest</option>';

    for ($i = 1; $i <= $total_share; $i++) {
        $html .= "<option value='{$i}'>{$i}% </option>";
    }
    return $html;
}

function get_property_share_options()
{
    $html = '<option value="">Select Interest</option>';
    $response = array(
        'status' => false,
        'html' => $html
    );

    //if (!wp_verify_nonce($_POST['ajax_nonce'], 'ajax_nonce')){
    //  echo json_encode($response);
    //  wp_die();
    //  }
    try {
        if (isset($_POST['id'])) {
            $post_id = $_POST['id'];
            $with_info = isset($_POST['with_info']) ? true : false;

            if ($with_info) {
                $total_share = get_property_available_share($post_id);
                $total_price = get_property_available_price($post_id);
                $html = get_property_share_options_by_id($post_id, $total_share);
                $members = get_property_total_members($post_id);
                $group = CoOwner_Groups::find(array('property_id' => $post_id));

                $total_price_number_format = CO_OWNER_CURRENCY_SYMBOL . " " . number_format($total_price);
                $info_html = "<h6 class='pt-2 bb-1 pb-3'>
                {$group->name}          
                <span class='coman-orange-sub d-block pt-1'>
                    Pool Member(s): " . (count($members)) . " | Available Portion: {$total_share}% at {$total_price_number_format}
                </span>
            </h6>";

                $response = array(
                    'status' => true,
                    'html' => $html,
                    'info' => $info_html,
                    'total_share' => $total_share,
                    'total_price' => $total_price,
                );
            } else {
                $html = get_property_share_options_by_id($post_id);
                $response = array(
                    'status' => true,
                    'html' => $html
                );
            }
        }
    } catch (\Exception $exception) {
    }
    echo json_encode($response);
    wp_die();
}
add_action('wp_ajax_nopriv_get_property_share_options', 'get_property_share_options');
add_action('wp_ajax_get_property_share_options', 'get_property_share_options');





/* MESSAGE SCREEN APIS  */
function update_connection_status()
{
    $response = array(
        'status' => false,
        'message' => 'Something went wrong please try again.'
    );

    //if (!wp_verify_nonce($_POST['ajax_nonce'], 'ajax_nonce')){
    //  echo json_encode($response);
    //  wp_die();
    //  }

    try {

        if (
            isset($_POST['status']) && !empty($_POST['status']) &&
            isset($_POST['id']) && !empty($_POST['id']) &&
            in_array($_POST['status'], ['reject', 'accept', 'block', 'unblock'])
        ) {
            $status = $_POST['status'] == 'reject' ? 2 : ($_POST['status'] == 'accept' ? 1 : ($_POST['status'] == 'unblock' ? 0 : 3));
            $id = (int)$_POST['id'];

            global $wpdb;
            $table = $wpdb->prefix . CO_OWNER_CONNECTIONS_TABLE;

            $user_id = get_current_user_id();
            $connection = CoOwner_Connections::connection_request(array("{$table}.id" => $id, "{$table}.receiver_user" => (int)$user_id), true);
            if (!empty($connection)) {
                $is_already_connected = false;
                $user_updated = ($connection) ? ($connection->receiver_user != $user_id ? $connection->sender_user : $connection->receiver_user) : $user_id;

                if ($_POST['status'] == 'unblock' || $status == 1) {
                    $is_already_connected = get_user_meta($user_updated, "_user_already_connected_{$id}", true);
                    if ($is_already_connected) {
                        $status = 1;
                    }
                }

                $where = array(
                    'id' => (int)$id,
                    'receiver_user' => (int)$user_id,
                );

                $data = array('status' => $status, 'updated_at' => wp_date('Y-m-d H:i:s'), 'updated_by' => $user_id);
                $is_update = CoOwner_Connections::update_row($table, $data, $where);
                $response['data'] = $is_update;
                if ($is_update) {
                    $response = array(
                        'status' => true,
                        'link' => '',
                        'message' => "User " . (ucfirst($_POST['status'])) . 'ed successfully.'
                    );

                    if ($status == 1 || $status == 2) {
                        CoOwner_Notifications::delete_row(CO_OWNER_NOTIFICATIONS_TABLE, array('notify_type' => 1, 'notify_id' => $connection->id));
                    }

                    if ($status == 1) {
                        update_user_meta($user_updated, "_user_already_connected_{$connection->id}", true);

                        $response['link'] = home_url("messages/?is_pool=false&with={$connection->receiver_user}");
                        if (!$is_already_connected) {
                            $receiver_user = $connection->sender_user == $user_id ? $connection->receiver_user :  $connection->sender_user;
                            CoOwner_Notifications::create($user_id, $receiver_user, 'accepted your request. You can now message each other in Messages section.', 9, $connection->id);

                            $hello_created = CoOwner_Conversation::get(CO_OWNER_CONVERSATION_TABLE, array(
                                'sender_user' => $user_id,
                                'receiver_user' => $receiver_user,
                                'is_request' => 3,
                            ), true);
                            if (empty($hello_created)) {
                                CoOwner_Conversation::send_message($user_id, $receiver_user, "", false, 0, 0, 3);
                            }
                        }
                    }
                    if (get_user_meta($user_updated, "_user_already_connected_{$connection->id}", true)) {
                        $is_already_connected = true;
                    }

                    if ($is_already_connected) {
                        $user_updated = ($connection) ? ($connection->receiver_user == $user_id ? $connection->sender_user : $connection->receiver_user) : $user_id;
                        $response['link'] = home_url('messages/?is_pool=false&with=' . $user_updated);
                    }
                }
            } elseif ($status == 0 && empty($connection)) {
                $connection = CoOwner_Connections::connection_request(array("{$table}.id" => $id, "{$table}.sender_user" => (int)$user_id), true);
                if (!empty($connection)) {
                    $response = array(
                        'status' => true,
                        'link' =>  home_url("messages/?is_pool=false&with={$connection->receiver_user}"),
                        'message' => "Request " . (ucfirst($_POST['status'])) . 'ed successfully.'
                    );
                    CoOwner_Connections::update_connection_status($connection->id, 1);
                }
            }
        }
    } catch (\Exception $exception) {
    }

    echo json_encode($response);
    wp_die();
}
add_action('wp_ajax_nopriv_update_connection_status', 'update_connection_status');
add_action('wp_ajax_update_connection_status', 'update_connection_status');

function block_connected_user()
{
    $response = array(
        'status' => false,
        'message' => 'Something went wrong please try again.'
    );

    //if (!wp_verify_nonce($_POST['ajax_nonce'], 'ajax_nonce')){
    //  echo json_encode($response);
    //  wp_die();
    //  }

    if (
        isset($_POST['status']) && !empty($_POST['status']) &&
        isset($_POST['id']) && !empty($_POST['id']) &&
        in_array($_POST['status'], ['reject', 'accept', 'block', 'unblock'])
    ) {
        $user = get_current_user_id();
        $block_user = $_POST['id'];
        $connection = CoOwner_Connections::get_connection_between_sender_receiver($user, $block_user);
        if ($connection) {
            $status = $_POST['status'] == 'reject' ? 2 : ($_POST['status'] == 'accept' ? 1 : ($_POST['status'] == 'unblock' ? 0 : 3));
            CoOwner_Connections::update_connection_status($connection->id, $status);
            if ($status == 1) {
                update_user_meta($block_user, "_user_already_connected_{$connection->id}", true);
            }
            $response = array(
                'status' => true,
                'link' => home_url(CO_OWNER_MESSAGE_PAGE . "?is_pool=false&request={$connection->id}&is_received=" . ($connection->sender_user == $user ? 'false' : 'true') . "&alert=success&alert_message=Connection Blocked Successfully.")
            );
        }
    }
    echo json_encode($response);
    wp_die();
}
add_action('wp_ajax_nopriv_block_connected_user', 'block_connected_user');
add_action('wp_ajax_block_connected_user', 'block_connected_user');

function remove_user_connection()
{
    $response = array(
        'status' => false,
        'message' => 'Something went wrong please try again.'
    );

    //if (!wp_verify_nonce($_POST['ajax_nonce'], 'ajax_nonce')){
    //  echo json_encode($response);
    //  wp_die();
    //  }

    if (
        isset($_POST['user_id']) && !empty($_POST['user_id']) &&
        isset($_POST['connection_id']) && !empty($_POST['connection_id'])
    ) {
        $user = get_current_user_id();
        $user_id = $_POST['user_id'];
        $connection_id = $_POST['connection_id'];
        $connection = CoOwner_Connections::get_connection_between_sender_receiver($user, $user_id);
        if ($connection && $connection_id == $connection->id) {
            $table = CO_OWNER_CONNECTIONS_TABLE;
            $is_delete = CoOwner_Connections::delete_row($table, array('id' => $connection->id));
            if ($is_delete) {
                $array1 = array(
                    'sender_user' => $user,
                    'receiver_user' => $user_id,
                );
                $array2 = array(
                    'sender_user' => $user_id,
                    'receiver_user' => $user,
                );
                CoOwner_Conversation::delete_row(CO_OWNER_CONVERSATION_TABLE, $array1);
                CoOwner_Conversation::delete_row(CO_OWNER_CONVERSATION_TABLE, $array2);
                CoOwner_Conversation_Files::delete_row(CO_OWNER_CONVERSATION_FILES_TABLE, array(
                    'connection_id' => $connection->id
                ));
                delete_user_meta($user_id, "_user_already_connected_{$connection->id}");
                delete_user_meta($user, "_user_already_connected_{$connection->id}");

                $response = array(
                    'status' => $is_delete,
                    'link' => home_url(CO_OWNER_MESSAGE_PAGE . "?is_pool=false&alert=success&alert_message=Connection removed successfully."),
                    'message' => 'Connection removed successfully.'
                );
            }
        }
    }
    echo json_encode($response);
    wp_die();
}
add_action('wp_ajax_nopriv_remove_user_connection', 'remove_user_connection');
add_action('wp_ajax_remove_user_connection', 'remove_user_connection');

function remove_from_group_connection()
{
    $response = array(
        'status' => false,
        'message' => 'Something went wrong please try again.'
    );

    //if (!wp_verify_nonce($_POST['ajax_nonce'], 'ajax_nonce')){
    //  echo json_encode($response);
    //  wp_die();
    //  }

    if (
        isset($_POST['user_id']) && !empty($_POST['user_id']) &&
        isset($_POST['group_id']) && !empty($_POST['group_id'])
    ) {
        $user = get_current_user_id();
        $user_id = $_POST['user_id'];
        $group_id = $_POST['group_id'];
        $connection = CoOwner_Connections::get_connection_between_sender_receiver_and_group($user_id, $group_id);
        $is_leave = isset($_POST['is_leave']) ? true : false;

        if ($connection) {
            if ($is_leave && $user_id != $user) {
                echo json_encode($response);
                wp_die();
            }

            $group = CoOwner_Groups::find(array('property_id' => $connection->property_id));
            $sender = $user;
            $message = $is_leave ? " has left - {$group->name}" : " was removed from {$group->name} by Admin";
            CoOwner_Conversation::send_message($sender, $user_id, $message, true, 0, 0, 2, $connection->property_id, (int) $group->id);

            $user_id = $is_leave ? $group->user_id : $user_id;
            $pusher = new CoOwner_pusher();
            $channel = "chat-message-{$user_id}";
            $pusher->pusher->trigger($channel, "new-message", [
                'message' => [
                    'message' => $is_leave ? "Member left the pool {$group->name}." : "You left the pool successfully.<strong>Note: You are also removed from the pool chat room.</strong>",
                    'display_name' => get_user_full_name($sender)
                ],
            ]);

            $table = CO_OWNER_CONNECTIONS_TABLE;
            $is_delete = CoOwner_Connections::delete_row($table, array('id' => $connection->id));
            $url = $is_leave ? home_url(CO_OWNER_MESSAGE_PAGE . "?is_pool=true&alert=success&alert_message=You left the pool successfully. <strong>Note: You are also removed from the pool chat room.</strong>") : home_url(CO_OWNER_MESSAGE_PAGE . "?is_pool=true&with={$group_id}&alert=success&alert_message=Member removed successfully.");
            if ($is_delete) {
                $response = array(
                    'status' => $is_delete,
                    'link' => $url,
                    'message' => 'You left the pool successfully. <strong>Note: You are also removed from the pool chat room.</strong>'
                );
                CoOwner_Notifications::delete_row(CO_OWNER_NOTIFICATIONS_TABLE, array(
                    'receiver_user' => $is_leave ? $user : $user_id,
                    'notify_id' => $group_id,
                    'notify_type' => 10
                ));
            }
            if ($is_leave) {
                CoOwner_Notifications::create($user, $group->user_id, $message, 11, $group->id);
            }
        }
    }
    echo json_encode($response);
    wp_die();
}
add_action('wp_ajax_nopriv_remove_from_group_connection', 'remove_from_group_connection');
add_action('wp_ajax_remove_from_group_connection', 'remove_from_group_connection');

function remove_group_and_connection()
{
    $response = array(
        'status' => false,
        'message' => 'Something went wrong please try again.'
    );

    //if (!wp_verify_nonce($_POST['ajax_nonce'], 'ajax_nonce')){
    //  echo json_encode($response);
    //  wp_die();
    //  }

    if (
        isset($_POST['group_id']) &&
        !empty($_POST['group_id'])
    ) {
        $user_id = get_current_user_id();
        $group_id = $_POST['group_id'];
        $group = CoOwner_Groups::find(array('id' => $group_id, 'user_id' => $user_id));
        if ($group) {
            CoOwner_Groups::delete_row(CO_OWNER_GROUP_TABLE, array('id' => $group->id));
            CoOwner_Connections::delete_row(CO_OWNER_CONNECTIONS_TABLE, array('group_id' => $group->id));
            CoOwner_Conversation::delete_row(CO_OWNER_CONVERSATION_TABLE, array('group_id' => $group->id));
            CoOwner_Conversation_Files::delete_row(CO_OWNER_CONVERSATION_FILES_TABLE, array('group_id' => $group->id));
            CoOwner_Notifications::delete_row(CO_OWNER_NOTIFICATIONS_TABLE, array('notify_type' => array(10, 11), 'notify_id' => $group->id));

            $message = 'Delete Pool,Pool Contacts and Chat Messages Successfully.';
            $response = [
                'status' => true,
                'link'  => home_url(CO_OWNER_MESSAGE_PAGE . "?is_pool=true&alert=success&alert_message={$message}"),
                'message' => $message,
            ];
        }
    }
    echo json_encode($response);
    wp_die();
}
add_action('wp_ajax_nopriv_remove_group_and_connection', 'remove_group_and_connection');
add_action('wp_ajax_remove_group_and_connection', 'remove_group_and_connection');

function save_message_files($message, $receiver, $is_group)
{
    if (!isset($_FILES) || empty($_FILES) || !isset($_FILES['files']))
        return;

    if (!function_exists('wp_handle_upload')) {
        require_once(ABSPATH . 'wp-admin/includes/file.php');
    }
    $upload_overrides = array('test_form' => false);

    $files = $_FILES['files'];
    $connection = CoOwner_Connections::get_chat_with_connection($receiver, $is_group, true);
    foreach ($files['name'] as $key => $value) {
        if ($files['name'][$key]) {
            $uploadedFile = array(
                'name'     => $files['name'][$key],
                'type'     => $files['type'][$key],
                'tmp_name' => $files['tmp_name'][$key],
                'error'    => $files['error'][$key],
                'size'     => $files['size'][$key]
            );
            $moveFile = wp_handle_upload($uploadedFile, $upload_overrides);
            if ($moveFile && !isset($moveFile['error'])) {
                $data = array(
                    'user_id' => $message->sender_user,
                    'message_id' => $message->id,
                    'connection_id' => $is_group ? null : $connection->id,
                    'group_id' => $is_group ? $connection->id : null,
                    'file' => serialize($moveFile)
                );
                CoOwner_Conversation_Files::create($data);
            } else {
                return $moveFile['error'];
            }
        }
    }
    return true;
}

function save_message_links($message, $id, $sender_user, $receiver, $is_group)
{
    $links = get_url_into_hyperlink($message);
    $connection = CoOwner_Connections::get_chat_with_connection($receiver, $is_group, true);
    foreach ($links as $link) {
        $data = array(
            'user_id' => $sender_user,
            'message_id' => $id,
            'connection_id' => $is_group ? null : $connection->id,
            'group_id' => $is_group ? $connection->id : null,
            'file' => $link,
            'is_link' => 1
        );
        CoOwner_Conversation_Files::create($data);
    }
}

function send_message()
{
    $response = array(
        'status'    => false,
        'message'   => 'Something went wrong please try again.',
    );
    $files = $_FILES['files'];
    foreach ($files['name'] as $key => $value) {
        $uploadedFile = array('name'     => $files['name'][$key], 'tmp_name' => $files['tmp_name'][$key]);
        $wp_filetype     = wp_check_filetype_and_ext($uploadedFile['tmp_name'], $uploadedFile['name']);
        $ext             = empty($wp_filetype['ext']) ? '' : $wp_filetype['ext'];
        $type            = empty($wp_filetype['type']) ? '' : $wp_filetype['type'];
        if ((!$type || !$ext) && !current_user_can('unfiltered_upload')) {
            $response = array(
                'status'    => false,
                'message'   => __('Sorry, this file type is not permitted for security reasons.'),
                $wp_filetype
            );
            echo json_encode($response);
            die;
        }
    }

    //if (!wp_verify_nonce($_POST['ajax_nonce'], 'ajax_nonce')){
    //  echo json_encode($response);
    //  wp_die();
    //  }

    if (
        isset($_POST['id']) && !empty($_POST['id']) &&
        isset($_POST['is_group']) && !empty($_POST['is_group']) &&
        isset($_POST['message']) && (!empty($_POST['message']) || (isset($_FILES['files']) && count($_FILES['files']) > 0))
    ) {
        try {
            $sender = $sender_Id = get_current_user_id();
            $receiver = $_POST['id'];
            $is_group = $_POST['is_group'] == 'true' ? 1 : 0;
            $old_message = $message = isset($_POST['message']) && !empty($_POST['message']) ? $_POST['message'] : "";

            $connection = null;
            if ($is_group) {
                $group = CoOwner_Groups::find(['id' => $receiver]);
                if ($group && $group->user_id != $sender) {
                    $connection = CoOwner_Connections::get_connection_between_sender_receiver_and_group($sender, $receiver);
                    if (empty($connection)) {
                        $response['message'] = 'You are unable to message in this group.';
                        echo json_encode($response);
                        wp_die();
                    }
                }
            } else {
                $connection = CoOwner_Connections::get_connection_between_sender_receiver($sender, $receiver);
            }

            if (($connection && $connection->status != 1) || empty($connection) && !$is_group) {
                $response['message'] = empty($connection) ? 'You are unable to message.' : 'Connection are blocked.';
                echo json_encode($response);
                wp_die();
            }

            foreach (get_url_into_hyperlink($message) as $link) {
                $message = str_replace($link, "", $message);
            }

            $result = CoOwner_Conversation::send_message($sender, $receiver, $message, $is_group);
            if ($result) {
                /*SAVE MESSAGE HAVE ATTACHMENT FILE */
                $message = CoOwner_Conversation::get_conversations($sender, $receiver, $is_group, 1, true);

                $error = save_message_files($message, $receiver, $is_group);

                save_message_links($old_message, $message->id, $sender, $receiver, $is_group);

                $files = CoOwner_Conversation_Files::get_files(array('message_id' => $message->id));

                $old_message_date = (isset($_POST['date']) == true) ? $_POST['date'] : 0;
                ob_start();
                include(CO_OWNER_THEME_DIR . '/parts/message.php');
                $html = ob_get_clean();

                ob_start();
                foreach ($files as $file_attachment) {
                    include CO_OWNER_THEME_DIR . '/parts/files.php';
                }
                $files_html = ob_get_clean();

                $response = array(
                    'status' => true,
                    'html' => $html,
                    'files' => $files_html,
                    'file_error' => is_string($error) ? $error : true,
                );

                $message_result = $response;

                $old_message_date = (isset($_POST['date']) == true) ? $_POST['date'] : 0;
                $sender = $receiver;
                ob_start();
                include(CO_OWNER_THEME_DIR . '/parts/message.php');
                $html = ob_get_clean();
                CoOwner_Notifications::create($sender_Id, $receiver, $message->message, 3, $message->id, $is_group);
                $message_result['html'] = $html;
                $message->message = $message->message ? substr($message->message, 0, 50) : "Send A File";
                $message_result['message'] = $message;

                $pusher = new CoOwner_pusher();
                $channel = $is_group ? "chat-group-message" : "chat-message-$receiver";
                $pusher->pusher->trigger($channel, "new-message", $message_result);
            }
        } catch (\Exception $e) {
        }
    }
    echo json_encode($response);
    wp_die();
}
add_action('wp_ajax_nopriv_send_message', 'send_message');
add_action('wp_ajax_send_message', 'send_message');

function get_conversations_message()
{
    $response = array(
        'status' => false,
        'message' => 'Something went wrong please try again.'
    );

    //if (!wp_verify_nonce($_POST['ajax_nonce'], 'ajax_nonce')){
    //  echo json_encode($response);
    //  wp_die();
    //  }

    if (
        isset($_POST['id']) && !empty($_POST['id']) &&
        isset($_POST['is_group']) && !empty($_POST['is_group']) &&
        isset($_POST['page']) && !empty($_POST['page'])
    ) {
        $sender     = get_current_user_id();
        $receiver   = $_POST['id'];
        $is_group   = $_POST['is_group'] == 'true' ? 1 : 0;
        $page       = $_POST['page'];
        //$status = get_user_meta($receiver,'_user_status',true);

        $connection = CoOwner_Connections::get_connection_between_sender_receiver($sender, $receiver);
        if ((!$is_group && $connection && $connection->status != 1) || (!$is_group && empty($connection))) {
            $response = [
                'status'    => true,
                'html'      => "",
            ];
            echo json_encode($response);
            wp_die();
        } elseif ($is_group) {
            $group = CoOwner_Groups::find(['id' => $receiver]);
            if (empty($group) && $is_group) {
                $response = ['status' => true, 'html' => "",];
                echo json_encode($response);
                wp_die();
            }
            if ($group && $group->user_id != $sender) {
                $connection = CoOwner_Connections::get_connection_between_sender_receiver_and_group($sender, $receiver);
                if (empty($connection) || ($connection && $connection->status != 1)) {
                    $response = ['status' => true, 'html' => "",];
                    echo json_encode($response);
                    wp_die();
                }
            }
        }

        $after_created = (!$is_group && !empty($connection)) ? $connection->created_at : null;
        $result = CoOwner_Conversation::get_conversations($sender, $receiver, $is_group, $page, false, $after_created);
        $html = '';
        if ($result->messages) {
            if ($page < $result->max_page) {
                $html .= '<div class="col-12 text-center mb-2"><a href="#" class="load-more-message text-orange small-title">View More</a></div>';
            }
            $old_message_date = 0;
            foreach (array_reverse($result->messages) as $message) {
                $files = CoOwner_Conversation_Files::get_files(array('message_id' => $message->id));
                ob_start();
                include(CO_OWNER_THEME_DIR . '/parts/message.php');
                $html .= ob_get_clean();
            }
        }
        $response = [
            'status'    => true,
            'html'      => $html,
            $connection
        ];
    }
    echo json_encode($response);
    wp_die();
}
add_action('wp_ajax_nopriv_get_conversations_message', 'get_conversations_message');
add_action('wp_ajax_get_conversations_message', 'get_conversations_message');

function clear_conversations_chat()
{
    $response = array(
        'status' => false,
        'message' => 'Something went wrong please try again.'
    );

    //if (!wp_verify_nonce($_POST['ajax_nonce'], 'ajax_nonce')){
    //  echo json_encode($response);
    //  wp_die();
    //  }

    if (
        isset($_POST['id']) && !empty($_POST['id']) &&
        isset($_POST['is_group']) && !empty($_POST['is_group'])
    ) {
        $is_group = $_POST['is_group'];
        $id = $_POST['id'];
        $key = ($is_group == 'true' || $is_group === true) ? '_user_clear_chat_group_' : '_user_clear_chat_with_';
        $userId = get_current_user_id();
        update_user_meta($userId, "{$key}{$id}", wp_date('Y-m-d H:i:s'));
        $response = array(
            'status' => true,
            'message' => 'Chat clear successfully.'
        );
    }
    echo json_encode($response);
    wp_die();
}
add_action('wp_ajax_nopriv_clear_conversations_chat', 'clear_conversations_chat');
add_action('wp_ajax_clear_conversations_chat', 'clear_conversations_chat');

function get_connection_info_by_user_id()
{
    $response = array(
        'status' => false,
        'message' => 'Something went wrong please try again.'
    );

    //if (!wp_verify_nonce($_POST['ajax_nonce'], 'ajax_nonce')){
    //  echo json_encode($response);
    //  wp_die();
    //  }

    if (isset($_POST['id']) && !empty($_POST['id'])) {
        $sender = get_current_user_id();
        $receiver = $_POST['id'];
        $response['status'] = true;
        $response['data'] = "";
        $response['data'] = CoOwner_Connections::get_connection_between_sender_receiver($sender, $receiver);
    }

    echo json_encode($response);
    wp_die();
}
add_action('wp_ajax_nopriv_get_connection_info_by_user_id', 'get_connection_info_by_user_id');
add_action('wp_ajax_get_connection_info_by_user_id', 'get_connection_info_by_user_id');

function add_member_on_pool()
{
    $response = array(
        'status' => false,
        'message' => 'Something went wrong please try again.'
    );

    //if (!wp_verify_nonce($_POST['ajax_nonce'], 'ajax_nonce')){
    //  echo json_encode($response);
    //  wp_die();
    //  }

    if (
        isset($_POST['description']) && !empty($_POST['description']) &&
        isset($_POST['interested_in']) && !empty($_POST['interested_in']) &&
        isset($_POST['property_id']) && !empty($_POST['property_id']) &&
        isset($_POST['member_id']) && !empty($_POST['member_id'])
    ) {
        $description = $_POST['description'];
        $interested_in = (int) $_POST['interested_in'];
        $property_id = (int) $_POST['property_id'];
        $member_id = (int) $_POST['member_id'];

        $group = CoOwner_Groups::find(array('property_id' => $property_id));

        if (!$group) {
            check_and_create_property_group($property_id);
            $group = CoOwner_Groups::find(array('property_id' => $property_id));
        }

        $user = wp_get_current_user();

        $is_success = check_join_upto_pools_by_plan($member_id);
        if (!$is_success) {
            $response = array(
                'status' => false,
                'message' => 'According to the member subscription plan he is not able to join any pool',
            );
            echo json_encode($response);
            wp_die();
        }

        if ($group && $group->user_id == $user->ID) {
            $available_share = get_property_available_share($property_id);
            $available_price = get_property_available_price($property_id);

            if ($available_share >= $interested_in) {
                $user_id = $user->ID;
                $calculated_price = calculate_property_share_interest($available_share, $interested_in, $available_price);
                $connection = array(
                    'sender_user' => $user_id,
                    'receiver_user' => $member_id,
                    'group_id' => (int) $group->id,
                    'property_id' => $property_id,
                    'is_group' => 1,
                );
                $connection_count = CoOwner_Connections::count($connection);
                if ($connection_count == 0) {
                    $connection['status'] = 1;
                    $connection['comment'] = $description;
                    $connection['interested_in'] = $interested_in;
                    $connection['calculated_price'] = $calculated_price;
                    $is_created = CoOwner_Connections::create($connection);

                    if ($is_created) {
                        $message = " was added to {$group->name} by Admin {{comment}} {$description}";
                        $message = CoOwner_Conversation::send_message($user_id, $member_id, $message, true, $calculated_price, $interested_in, 2, $property_id, (int) $group->id);

                        CoOwner_Notifications::create($user_id, $member_id, $message, 10, $group->id);
                        $pusher = new CoOwner_pusher();
                        $channel = "chat-message-{$member_id}";
                        $pusher->pusher->trigger($channel, "new-message", [
                            'message' => [
                                'message' => "You have been added to the Pool: {$group->name}",
                                'display_name' => get_user_full_name($user->ID)
                            ],
                        ]);

                        $is_page = isset($_POST['page']) ? $_POST['page'] : 'property-details';
                        if ($is_page == 'messages') {
                            $url = home_url(CO_OWNER_MESSAGE_PAGE . "?is_pool=true&with={$group->id}&alert=new_member_added");
                        } else {
                            $url = get_the_permalink($property_id)."?alert=new_member_added&group_id={$group->id}"; 
							/*home_url(CO_OWNER_PROPERTY_DETAILS_PAGE . "?id={$property_id}&alert=new_member_added&group_id={$group->id}");*/
                        }
                        $response = array(
                            'status' => true,
                            'url' =>  $url,
                            'alert_message' => 'New member have been added to your Pool.'
                        );
                        update_property_price_for_search($property_id);
                    }
                } else {
                    $response = array(
                        'status' => false,
                        'message' => 'The property already has this member.'
                    );
                }
            }
        }
    }
    echo json_encode($response);
    wp_die();
}
add_action('wp_ajax_nopriv_add_member_on_pool', 'add_member_on_pool');
add_action('wp_ajax_add_member_on_pool', 'add_member_on_pool');

function update_status_from_group_connection()
{
    $response = array(
        'status' => false,
        'message' => 'Something went wrong please try again.'
    );
    //if (!wp_verify_nonce($_POST['ajax_nonce'], 'ajax_nonce')){
    //  echo json_encode($response);
    //  wp_die();
    //  }

    if (isset($_POST['group_id']) && isset($_POST['status']) && isset($_POST['user_id'])) {
        $group_id = $_POST['group_id'];
        $status = $_POST['status'] == 'block' ? 3 : 1;
        $sender = get_current_user_id();
        $receiver = $_POST['user_id'];

        global $wpdb;
        $table = $wpdb->prefix . CO_OWNER_CONNECTIONS_TABLE;
        $connection = CoOwner_Connections::connection_request(array(
            "{$table}.sender_user" => $sender,
            "{$table}.receiver_user" => $receiver,
            "{$table}.group_id" => $group_id,
        ), true);

        if ($connection) {
            $result = CoOwner_Connections::update_connection_status($connection->id, $status);
            if ($result) {
                $response = array(
                    'status' => true,
                    'message' => 'Member ' . (ucfirst($_POST['status'])) . 'ed successfully'
                );
            }
        }
    }

    echo json_encode($response);
    wp_die();
}
add_action('wp_ajax_nopriv_update_status_from_group_connection', 'update_status_from_group_connection');
add_action('wp_ajax_update_status_from_group_connection', 'update_status_from_group_connection');

/* MESSAGE SCREEN APIS  */





/* MY ACCOUNT APIS */
function sent_edit_email_verification_code()
{
    $response = array(
        'status' => false,
        'message' => 'Something went wrong please try again.'
    );

    //if (!wp_verify_nonce($_POST['ajax_nonce'], 'ajax_nonce')){
    //  echo json_encode($response);
    //  wp_die();
    //  }

    if (isset($_POST['email'])) {
        $email = $_POST['email'];
        $user = get_user_by('email', $email);
        $login_user = wp_get_current_user();

        if (
            $user->user_email === $login_user->user_email &&
            get_user_meta($login_user->ID, '_user_is_email_verified', true)
        ) {
            $response = array(
                'status' => false,
                'message' => 'Your Email is already verified.'
            );
        } else {
            if (send_verification_code_by_email($email)) {
                $response = array(
                    'status' => true,
                    'message' => 'Check your email and verify code.'
                );
            }
        }
    } else {
        $response = array(
            'status' => false,
            'message' => 'This field is required.'
        );
    }

    echo json_encode($response);
    wp_die();
}
add_action('wp_ajax_nopriv_sent_edit_email_verification_code', 'sent_edit_email_verification_code');
add_action('wp_ajax_sent_edit_email_verification_code', 'sent_edit_email_verification_code');

function verify_email_verification_code()
{
    $response = array(
        'status' => false,
        'message' => 'Something went wrong please try again.'
    );

    //if (!wp_verify_nonce($_POST['ajax_nonce'], 'ajax_nonce')){
    //  echo json_encode($response);
    //  wp_die();
    //  }

    if (
        isset($_POST['email']) && isset($_POST['email_code_1']) &&
        isset($_POST['email_code_2']) && isset($_POST['email_code_3']) &&
        isset($_POST['email_code_4'])
    ) {
        $email = $_POST['email'];
        $email_code_1 = $_POST['email_code_1'];
        $email_code_2 = $_POST['email_code_2'];
        $email_code_3 = $_POST['email_code_3'];
        $email_code_4 = $_POST['email_code_4'];
        $code = $email_code_1 . $email_code_2 . $email_code_3 . $email_code_4;

        if (
            isset($_SESSION['user_register_verification_code']) &&
            isset($_SESSION['user_register_verification_email'])
        ) {
            if ((int) $code === $_SESSION['user_register_verification_code']) {
                if ($email == $_SESSION['user_register_verification_email']) {
                    $current_user = wp_get_current_user();
                    $args = array(
                        'ID'         => $current_user->ID,
                        'user_email' => esc_attr($email)
                    );
                    if (is_numeric(wp_update_user($args))) {
                        unset($_SESSION['user_register_verification_code']);
                        unset($_SESSION['user_register_verification_email']);
                        update_user_meta($current_user->ID, '_user_is_email_verified', true);
                        update_user_meta($current_user->ID, '_user_status', 1);
                        $response = array('status' => true);
                    }
                } else {
                    $response = array(
                        'status' => false,
                        'message' => 'Your email is invalid.'
                    );
                }
            } else {
                $response = array(
                    'status' => false,
                    'message' => 'Your code is invalid.'
                );
            }
        }
    } else {
        $response = array(
            'status' => false,
            'message' => 'This field is required.'
        );
    }

    echo json_encode($response);
    wp_die();
}
add_action('wp_ajax_nopriv_verify_email_verification_code', 'verify_email_verification_code');
add_action('wp_ajax_verify_email_verification_code', 'verify_email_verification_code');

function sent_edit_mobile_verification_code()
{
    $response = array(
        'status' => false,
        'message' => 'Something went wrong please try again.'
    );

    //if (!wp_verify_nonce($_POST['ajax_nonce'], 'ajax_nonce')){
    //  echo json_encode($response);
    //  wp_die();
    //  }

    if (isset($_POST['mobile'])) {
        $mobile = str_replace([" ", "+"], "", $_POST['mobile']);

        $login_id = get_current_user_id();
        $result = check_value_in_usermeta('_mobile', $mobile);

        if ($result && $result->user_id != $login_id) {
            $response = array(
                'status' => false,
                'message' => 'The mobile number already exists.',
            );
        } else {
            $is_verified = get_user_meta($login_id, '_user_is_mobile_verified', true);
            if ($result && $result->user_id == $login_id && $is_verified) {
                $response = array(
                    'status' => false,
                    'message' => 'Your mobile number is already verified.'
                );
            } else {
                $code = get_verification_code();
                $_SESSION['user_mobile_verification_code'] = $code;
                $_SESSION['user_mobile_verification'] = $mobile;
                $message = "Your Verification Code is : {$code}";
                $sms = CoOwner_Twilio::sand_message($mobile, $message);
                $response = [
                    'status' => $sms->status,
                   /* 'message' => $sms->status ? 'Check your email and verify code.' : $sms->message, */
                    'message' => $sms->status ? 'Check your email and verify code.' : 'Please Enter Mobile Number with a Valid Country Code',
                ];
            }
        }
    } else {
        $response = array(
            'status' => false,
            'message' => 'This field is required.'
        );
    }

    echo json_encode($response);
    wp_die();
}
add_action('wp_ajax_nopriv_sent_edit_mobile_verification_code', 'sent_edit_mobile_verification_code');
add_action('wp_ajax_sent_edit_mobile_verification_code', 'sent_edit_mobile_verification_code');

function verify_mobile_verification_code()
{
    $response = array(
        'status' => false,
        'message' => 'Something went wrong please try again.'
    );

    //if (!wp_verify_nonce($_POST['ajax_nonce'], 'ajax_nonce')){
    //  echo json_encode($response);
    //  wp_die();
    //  }

    if (
        isset($_POST['mobile']) &&
        isset($_POST['mobile_code_1']) &&
        isset($_POST['mobile_code_2']) &&
        isset($_POST['mobile_code_3']) &&
        isset($_POST['mobile_code_4'])
    ) {
        $mobile = str_replace([" ", "+"], "", $_POST['mobile']);
        $mobile_code_1 = $_POST['mobile_code_1'];
        $mobile_code_2 = $_POST['mobile_code_2'];
        $mobile_code_3 = $_POST['mobile_code_3'];
        $mobile_code_4 = $_POST['mobile_code_4'];
        $code = $mobile_code_1 . $mobile_code_2 . $mobile_code_3 . $mobile_code_4;

        if (
            isset($_SESSION['user_mobile_verification_code']) &&
            isset($_SESSION['user_mobile_verification'])
        ) {
            if ((int) $code === $_SESSION['user_mobile_verification_code']) {
                if ($mobile == $_SESSION['user_mobile_verification']) {
                    $current_user = get_current_user_id();
                    unset($_SESSION['user_mobile_verification_code']);
                    unset($_SESSION['user_mobile_verification']);
                    update_user_meta($current_user, '_mobile', $mobile);
                    update_user_meta($current_user, '_user_is_mobile_verified', true);
                    update_user_meta($current_user, '_user_status', 1);
					
                    $response = array('status' => true);
                } else {
                    $response = array(
                        'status' => false,
                        'message' => 'Your mobile number is invalid.'
                    );
                }
            } else {
                $response = array(
                    'status' => false,
                    'message' => 'Your code is invalid.'
                );
            }
        }
    } else {
        $response = array(
            'status' => false,
            'message' => 'This field is required.'
        );
    }

    echo json_encode($response);
    wp_die();
}
add_action('wp_ajax_nopriv_verify_mobile_verification_code', 'verify_mobile_verification_code');
add_action('wp_ajax_verify_mobile_verification_code', 'verify_mobile_verification_code');

function update_notification_settings()
{
    $response = array(
        'status' => false,
        'message' => 'Something went wrong please try again.',
    );

    if (isset($_POST['key']) && isset($_POST['email']) && isset($_POST['mobile'])) {
        $email = filter_var($_POST['email'], FILTER_VALIDATE_BOOLEAN);
        $mobile = filter_var($_POST['mobile'], FILTER_VALIDATE_BOOLEAN);
        $key = $_POST['key'];
        $user_id = get_current_user_id();
        update_user_meta($user_id, $key . '_email', $email);
        update_user_meta($user_id, $key . '_mobile', $mobile);
        $response = array('status' => true);
    } else if (
        isset($_POST['key']) &&
        isset($_POST['daily']) &&
        isset($_POST['monthly']) &&
        isset($_POST['weekly']) &&
        $_POST['key'] ==  '_user_notify_when_have_new_notify_me'
    ) {
        $key = $_POST['key'];
        $daily = filter_var($_POST['daily'], FILTER_VALIDATE_BOOLEAN);
        $monthly = filter_var($_POST['monthly'], FILTER_VALIDATE_BOOLEAN);
        $weekly = filter_var($_POST['weekly'], FILTER_VALIDATE_BOOLEAN);
        $user_id = get_current_user_id();
        update_user_meta($user_id, $key . '_daily', $daily);
        update_user_meta($user_id, $key . '_monthly', $monthly);
        update_user_meta($user_id, $key . '_weekly', $weekly);
        $response = array('status' => true);
    }
    echo json_encode($response);
    wp_die();
}
add_action('wp_ajax_nopriv_update_notification_settings', 'update_notification_settings');
add_action('wp_ajax_update_notification_settings', 'update_notification_settings');


function save_data_image( $base64_img, $title,$user_id ) {

	// Upload dir.
	$upload_dir  = wp_upload_dir();
	$upload_path = str_replace( '/', DIRECTORY_SEPARATOR, $upload_dir['path'] ) . DIRECTORY_SEPARATOR;

	$img             = str_replace( 'data:image/jpeg;base64,', '', $base64_img );
	$img             = str_replace( ' ', '+', $img );
	$decoded         = base64_decode( $img );
	$filename        = $title;
	$file_type       = 'image/jpeg';
	$hashed_filename = md5( $filename . microtime() ) . '_' . $filename;

	// Save the image in the uploads directory.
	$upload_file = file_put_contents( $upload_path . $hashed_filename, $decoded );

	$attachment = array(
		'post_mime_type' => $file_type,
		'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $hashed_filename ) ),
		'post_content'   => '',
		'post_status'    => 'inherit',
		'guid'           => $upload_dir['url'] . '/' . basename( $hashed_filename )
	);

	$attach_id = wp_insert_attachment( $attachment, $upload_dir['path'] . '/' . $hashed_filename );
	   if(	$attach_id){
		   $moveFile= array(
		    'file' => $upload_dir['path'] . '/' . $hashed_filename,
			'url'=> $upload_dir['url'] . '/' . $hashed_filename,
			'type'=> $file_type
		   );
			$old_avatar = get_user_meta($user_id, '_user_profile_avatar', true);
			if ($old_avatar && $old_avatar['file']) {
				if (file_exists($old_avatar['file'])) {
					unlink($old_avatar['file']);
					delete_user_meta($user_id, '_user_profile_avatar');
				}
			}
			update_user_meta($user_id, '_user_profile_avatar', $moveFile);
			 return true;
	   }
	    return false;
	
}

function save_user_avatar_image($user_id)
{
    if (!isset($_FILES) || empty($_FILES) || !isset($_FILES['profile']))
        return;

    if (!function_exists('wp_handle_upload')) {
        require_once(ABSPATH . 'wp-admin/includes/file.php');
    }
    $upload_overrides = array('test_form' => false);

    $file = $_FILES['profile'];
	$pathinfo = pathinfo($file['name']);
	$newFileName = $pathinfo['filename'].time().'.'.$pathinfo['extension'];
	//pr($pathinfo);

    $uploadedFile = array(
        'name'     => $newFileName,
        'type'     => $file['type'],
        'tmp_name' => $file['tmp_name'],
        'error'    => $file['error'],
        'size'     => $file['size']
    );

    $moveFile = wp_handle_upload($uploadedFile, $upload_overrides);
	
	
    if ($moveFile && !isset($moveFile['error'])) {
        $old_avatar = get_user_meta($user_id, '_user_profile_avatar', true);
        if ($old_avatar && $old_avatar['file']) {
            if (file_exists($old_avatar['file'])) {
                unlink($old_avatar['file']);
               delete_user_meta($user_id, '_user_profile_avatar');
            }
        }
        update_user_meta($user_id, '_user_profile_avatar', $moveFile);
        return true;
    }
    return false;
}

/* #changed 11*/
function update_my_account_info()
{
    $response = array(
        'status' => false,
        'message' => 'Something went wrong please try again.'
    );

    //    if (!wp_verify_nonce($_POST['ajax_nonce'], 'ajax_nonce')) {
    //        echo json_encode($response);
    //        wp_die();
    //    }
	//pr($_FILES);
	// pr($_SERVER);
	
	$is_mobile = false;	
	if (preg_match('/iPhone|iPod|iPad|BlackBerry|Android/', $_SERVER['HTTP_USER_AGENT'])) {
	  $is_mobile= true;
	}
	
    if (
        isset($_POST['first_name']) &&
        isset($_POST['last_name']) &&
        !empty($_POST['first_name']) &&
        !empty($_POST['last_name'])
    ) {
        try {
			$data_img = !empty($_POST['profile_data_img']) ? $_POST['profile_data_img'] : '';
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
            $user_id = get_current_user_id();
            update_user_meta($user_id, 'first_name', $first_name);
            update_user_meta($user_id, 'last_name', $last_name);
            $response = array(
                'status' => true,
                'message' => 'Account info updated successfully.',
                'name' => $first_name . " " . substr($last_name, 0, 1)
            );
			
			if($is_mobile){
				if (isset($_FILES['profile']) && save_data_image($data_img,$_FILES['profile']['name'],$user_id)) {
					$response['profile_updated'] = true;
				}				
			}else{
				if (isset($_FILES['profile']) && save_user_avatar_image($user_id)) {
					$response['profile_updated'] = true;
				}
			}
			
        } catch (\Exception $exception) {
        }
    }
    echo json_encode($response);
    wp_die();
}
/* #changed 11*/
add_action('wp_ajax_nopriv_update_my_account_info', 'update_my_account_info');
add_action('wp_ajax_update_my_account_info', 'update_my_account_info');

function user_update_password()
{
    $response = array(
        'status' => false,
        'message' => 'Something went wrong please try again.'
    );

    //    if (!wp_verify_nonce($_POST['ajax_nonce'], 'ajax_nonce')) {
    //        echo json_encode($response);
    //        wp_die();
    //    }

    if (
        isset($_POST['old_password']) &&
        isset($_POST['new_password'])
    ) {
        try {
            $old_password = $_POST['old_password'];
            $new_password = $_POST['new_password'];
            $user = wp_get_current_user();
            if (wp_check_password($old_password, $user->user_pass)) {
                $userdata['ID'] = $user->ID;
                $userdata['user_pass'] = $new_password;
                if (wp_update_user($userdata)) {
					 update_user_meta($user->ID, '_user_status', 1);
					 /* change 005*/
				     //wp_logout();
						
                    $response = array(
                        'status' => true,
                        'message' => 'Password updated successfully.',
						'redirect' => home_url('login/?pwd=updated')
                    );
                }
            } else {
                $response = array(
                    'status' => false,
                    'message' => 'Something went wrong please try again.'
                );
            }
        } catch (\Exception $exception) {
        }
    }
    echo json_encode($response);
    wp_die();
}
add_action('wp_ajax_nopriv_user_update_password', 'user_update_password');
add_action('wp_ajax_user_update_password', 'user_update_password');


function save_user_files($user_id)
{
    if (!isset($_FILES) || empty($_FILES))
        return;

    if (!function_exists('wp_handle_upload')) {
        require_once(ABSPATH . 'wp-admin/includes/file.php');
    }

    $upload_overrides = array('test_form' => false);

    $files = $_FILES['document'];

    $uploadFiles = get_user_meta($user_id, '_user_profile_documents', true);
    if (empty($uploadFiles)) $uploadFiles = array();
    foreach ($files['name'] as $key => $value) {
        if ($files['name'][$key]) {
            $uploadedFile = array(
                'name'     => $files['name'][$key],
                'type'     => $files['type'][$key],
                'tmp_name' => $files['tmp_name'][$key],
                'error'    => $files['error'][$key],
                'size'     => $files['size'][$key]
            );
            $moveFile = wp_handle_upload($uploadedFile, $upload_overrides);
            if ($moveFile && !isset($moveFile['error'])) {
                $uploadFiles[] = $moveFile;
            } else {
                return $moveFile['error'];
            }
        }
    }
    update_user_meta($user_id, '_user_profile_documents', $uploadFiles);
    return true;
}

function delete_user_file($user_id, $key)
{
    $file = get_user_meta($user_id, $key, true);
    if ($file && $file['file']) {
        if (file_exists($file['file'])) {
            unlink($file['file']);
            delete_user_meta($user_id, $key);
            return true;
        }
    }
    return false;
}

function user_update_document_file()
{
    $response = array(
        'status' => false,
        'message' => 'Something went wrong please try again.'
    );

    //    if (!wp_verify_nonce($_POST['ajax_nonce'], 'ajax_nonce')) {
    //        echo json_encode($response);
    //        wp_die();
    //    }

    if (isset($_FILES['document'])) {
        $user_id = get_current_user_id();
        $is_upload = save_user_files($user_id);
        update_user_meta($user_id, '_document_shield_status', 0);
        $uploadFiles = get_user_meta($user_id, '_user_profile_documents', true);

        $html = "";
        foreach ((!empty($uploadFiles) ? $uploadFiles : array()) as $key => $document) {
            $file_name = wp_basename($document['url']);
            $html .= "<li>
                        {$file_name}
                        <a href='#' class='text-danger delete-document' data-index='{$key}'>
                            " . co_owner_get_svg('trash') . "
                        </a>
                    </li>";
        }


        $response = array(
            'status' => is_string($is_upload) ? false : true,
            'message' => is_string($is_upload) ? $is_upload : 'Document sent successfully for verification.',
            'html' => $html
        );
    }
    echo json_encode($response);
    wp_die();
}
add_action('wp_ajax_nopriv_user_update_document_file', 'user_update_document_file');
add_action('wp_ajax_user_update_document_file', 'user_update_document_file');


function delete_user_document()
{
    $response = array(
        'status' => false,
        'message' => 'Something went wrong please try again.'
    );

    //    if (!wp_verify_nonce($_POST['ajax_nonce'], 'ajax_nonce')) {
    //        echo json_encode($response);
    //        wp_die();
    //    }

    if (isset($_POST['index'])) {
        $index = $_POST['index'];
        $user_id = get_current_user_id();
        $result = remove_user_document($user_id, $index);
        if ($result) {
            $response = array(
                'status' => true,
                'message' => 'Document removed successfully.'
            );
        }
    }
    echo json_encode($response);
    wp_die();
}
add_action('wp_ajax_nopriv_delete_user_document', 'delete_user_document');
add_action('wp_ajax_delete_user_document', 'delete_user_document');




function user_delete_document_file()
{
    $response = array(
        'status' => false,
        'message' => 'Something went wrong please try again.'
    );

    //    if (!wp_verify_nonce($_POST['ajax_nonce'], 'ajax_nonce')) {
    //        echo json_encode($response);
    //        wp_die();
    //    }

    $user_id = get_current_user_id();
    if (delete_user_file($user_id, '_user_profile_document')) {
        $response = array(
            'status' => true,
            'message' => 'Document deleted successfully.'
        );
    }

    echo json_encode($response);
    wp_die();
}
add_action('wp_ajax_nopriv_user_delete_document_file', 'user_delete_document_file');
add_action('wp_ajax_user_delete_document_file', 'user_delete_document_file');

/* copy user data is for while account delete from frontend it will copy and save deactive user table*/
/* #change 009*/
function copy_user_data($user_id){
global	$wpdb;
  $db_table_name = $wpdb->prefix . 'deactive_users';  // table name
  $db_table_meta =  $wpdb->prefix . 'deactive_usersmeta';  // table name
  $db_user_table =  $wpdb->prefix . 'users';  // table name
  $db_userMeta =  $wpdb->prefix . 'usermeta';  // table name

  $user_data=array();
  $user_meta=array();

	if($wpdb->get_var( "show tables like '$db_table_name'" ) == $db_table_name ) 
	 {
		$user = $wpdb->get_row( "SELECT * FROM $db_user_table where ID='$user_id' ");
		$userMeta = $wpdb->get_results( "SELECT * FROM $db_userMeta where user_id='$user_id' ");		
		
		if(!empty($user)){
			foreach($user as $key => $row){ 
			   $user_data[$key]=$row;
			}
		}
		if(!empty($userMeta)){
			foreach($userMeta as $key => $row){		
			  $user_meta[$row->meta_key]=$row->meta_value;			  		  
			}
		}	
		$oldId = $user_data['ID'];
        unset($user_data['ID']);
	   $userID = $wpdb->insert($db_table_name,$user_data);	
       $lastid = $wpdb->insert_id;	 
       	if($user_meta){
			foreach($user_meta as $metaKey => $metaValue){
				$saveData= array(
				  'user_id'=>$lastid,
				  'meta_key'=>$metaKey,
				  'meta_value'=>$metaValue
				);
				$wpdb->insert($db_table_meta,$saveData);
			}
			$saveData= array();
			$saveData= array(
				  'user_id'=>$lastid,
				  'meta_key'=>'user_old_id',
				  'meta_value'=>$oldId
				);	
			$wpdb->insert($db_table_meta,$saveData);
			$saveData= array(
			  'user_id'=>$lastid,
			  'meta_key'=>'deleted_date',
			  'meta_value'=>date('Y-m-d h:i:s')
			);		
			
			$wpdb->insert($db_table_meta,$saveData);
         		
		}    
		
		  /* if($user_data){
				echo '<pre>';
				 print_r($user_data);
				 print_r($user_meta);
				echo '</pre>';
			}
		*/
	 }

	
}


function user_delete_my_account()
{
    $response = array(
        'status' => false,
        'message' => 'Something went wrong please try again.'
    );

    //    if (!wp_verify_nonce($_POST['ajax_nonce'], 'ajax_nonce')) {
    //        echo json_encode($response);
    //        wp_die();
    //    }


    if (isset($_POST['delete_action_type']) && !empty($_POST['delete_action_type'])) {
        $user_id = get_current_user_id();
        $type = $_POST['delete_action_type'];
	
        $status = $type == "delete" ? 3 : 2;
        update_user_meta($user_id, '_user_status', $status);
		
		
		
        if ($status == 3) {
			/* Now copy user data and meta data before delete*/
			copy_user_data($user_id);
			
            $sessions = WP_Session_Tokens::get_instance($user_id);
            if ($sessions) {
                $sessions->destroy_all();
            }
			
		   wp_delete_user($user_id);
			
        }
        $response = array(
            'status' => true,
            'message' => "Your account " . ($type == 'delete' ? "deleted" : "has been deactivated") . " successfully."
        );
    }

    echo json_encode($response);
    wp_die();
}
add_action('wp_ajax_nopriv_user_delete_my_account', 'user_delete_my_account');
add_action('wp_ajax_user_delete_my_account', 'user_delete_my_account');

function send_leave_feedback()
{
    $response = array(
        'status' => false,
        'message' => 'Something went wrong please try again.'
    );

    if (
        isset($_POST['leave_reason']) && !empty($_POST['leave_reason']) &&
        isset($_POST['user_id']) && !empty($_POST['user_id'])
    ) {
        $user_id = $_POST['user_id'];
        $feedback = array(
            'leave_reason' => $_POST['leave_reason'],
            'comment' => ($_POST['leave_reason'] == 'Other' && isset($_POST['comment'])) ? $_POST['comment'] : null
        );
        update_user_meta($user_id, '_user_leave_feedback', $feedback);
        $response = array(
            'status' => true,
            'message' => "Thank You for feedback."
        );
    }

    echo json_encode($response);
    wp_die();
}
add_action('wp_ajax_nopriv_send_leave_feedback', 'send_leave_feedback');
add_action('wp_ajax_send_leave_feedback', 'send_leave_feedback');

function active_user_account()
{
    $response = array(
        'status' => false,
        'message' => 'Something went wrong please try again.'
    );

    //    if (!wp_verify_nonce($_POST['ajax_nonce'], 'ajax_nonce')) {
    //        echo json_encode($response);
    //        wp_die();
    //    }

    $user_id = get_current_user_id();
    update_user_meta($user_id, '_user_status', 1);
    $response = array(
        'status' => true,
        'message' => "Your account has been activated successfully."
    );

    echo json_encode($response);
    wp_die();
}
add_action('wp_ajax_nopriv_active_user_account', 'active_user_account');
add_action('wp_ajax_active_user_account', 'active_user_account');
/* MY ACCOUNT APIS */

/* MY ACCOUNT VERIFICATIONS APIS */
function remove_social_account()
{
    $response = array(
        'status' => false,
        'message' => 'Something went wrong please try again.'
    );

    //    if (!wp_verify_nonce($_POST['ajax_nonce'], 'ajax_nonce')) {
    //        echo json_encode($response);
    //        wp_die();
    //    }

    if (isset($_POST['account']) && !empty($_POST['account'])) {
        $user_id = get_current_user_id();
        $account = $_POST['account'];
        delete_user_meta($user_id, "_user_{$account}_id");
        $response = array(
            'status' => true,
            'message' => 'Account unlinked successfully.'
        );
    }

    echo json_encode($response);
    wp_die();
}
add_action('wp_ajax_nopriv_remove_social_account', 'remove_social_account');
add_action('wp_ajax_remove_social_account', 'remove_social_account');

function link_social_account()
{
    $response = array(
        'status' => false,
        'message' => 'Something went wrong please try again.'
    );

    //    if (!wp_verify_nonce($_POST['ajax_nonce'], 'ajax_nonce')) {
    //        echo json_encode($response);
    //        wp_die();
    //    }

    echo json_encode($response);
    wp_die();
}
add_action('wp_ajax_nopriv_link_social_account', 'link_social_account');
add_action('wp_ajax_link_social_account', 'link_social_account');
/* MY ACCOUNT VERIFICATIONS APIS */

/* MY LISTINGS APIS */
function copy_property_image($id, $new_id)
{
    $new_images = array();
    try {
        $images = get_post_meta($id, '_pl_images', true);
        $uploads = wp_upload_dir();
        foreach ($images as $image) {
            $extension = pathinfo($image['url'], PATHINFO_EXTENSION);
            $filename = mt_rand() . '.' . $extension;
            $url = $uploads['url'] . "/$filename";
            $new_file = $uploads['path'] . "/$filename";
            if (@copy($image['file'], $new_file)) {
                $stat = stat(dirname($new_file));
                $perms = $stat['mode'] & 0000666;
                chmod($new_file, $perms);
                $url = $uploads['url'] . "/$filename";
                $type = wp_check_filetype($filename);
                $new_images[] = array(
                    'file' => $new_file,
                    'url' => $url,
                    'type' => $type['type'],
                );
            }
        }
    } catch (\Exception $exception) {
    }
    update_post_meta($new_id, '_pl_images', $new_images);
}

function make_duplicate_property()
{
    $response = array('status' => false, 'message' => 'Something went wrong please try again.');

    //if (!wp_verify_nonce($_POST['ajax_nonce'], 'ajax_nonce')){
    //  echo json_encode($response);
    //  wp_die();
    //  }

    if (isset($_POST['id'])) {
        $user_id = get_current_user_id();

        $is_create = check_create_upto_listings_by_plan($user_id);
        if (!$is_create) {
            $response = array('status' => false, 'message' => 'Please update your subscription to create more listings.');
            echo json_encode($response);
            wp_die();
        }

        $id = $_POST['id'];
        $post = get_property_detail_by_id($id);
        if ($post->post_author == $user_id) {
            $new_post = array(
                'post_author' => $user_id,
                'post_title' => $post->post_title,
                'post_content' => $post->post_content,
                'post_status' => $post->post_status,
                'post_type' => 'property',
            );
            $new_post_id = wp_insert_post($new_post);
            if ($new_post_id > 0) {
                update_post_meta($new_post_id, '_pl_posted_by', $post->posted_by);
                update_post_meta($new_post_id, '_pl_property_category', $post->property_category);
                update_post_meta($new_post_id, '_pl_property_type', $post->property_type);
                update_post_meta($new_post_id, '_pl_negotiable', $post->negotiable);
                update_post_meta($new_post_id, '_pl_age_year_built', $post->age_year_built);
                update_post_meta($new_post_id, '_pl_address', $post->address);
                update_post_meta($new_post_id, '_pl_unit_no', $post->unit_no);
                update_post_meta($new_post_id, '_pl_suburb', $post->suburb);
                update_post_meta($new_post_id, '_pl_only_display_suburb_in_my_ad', $post->only_display_suburb_in_my_ad);
                update_post_meta($new_post_id, '_pl_street_no', $post->street_no);
                update_post_meta($new_post_id, '_pl_postcode', $post->postcode);
                update_post_meta($new_post_id, '_pl_street_name', $post->street_name);
                update_post_meta($new_post_id, '_pl_state', $post->state);
                update_post_meta($new_post_id, '_pl_building_area', $post->building_area);
                update_post_meta($new_post_id, '_pl_land_area', $post->land_area);
                update_post_meta($new_post_id, '_pl_bathroom', $post->bathroom);
                update_post_meta($new_post_id, '_pl_bedroom', $post->bedroom);
                update_post_meta($new_post_id, '_pl_parking', $post->parking);
                update_post_meta($new_post_id, '_pl_property_features', $post->property_features);
                update_post_meta($new_post_id, '_pl_manually_features', $post->manually_features);
                update_post_meta($new_post_id, '_pl_interested_in_selling', $post->interested_in_selling);
                update_post_meta($new_post_id, '_pl_this_property_is', $post->this_property_is);
                update_post_meta($new_post_id, '_pl_currently_on_leased', $post->currently_on_leased);
                update_post_meta($new_post_id, '_pl_rent_per_month', $post->rent_per_month);
                update_post_meta($new_post_id, '_pl_enable_pool', $post->enable_pool);
                update_post_meta($new_post_id, '_pl_property_original_price', $post->property_market_price);
                if ($post->interested_in_selling != 'full_property') {
                    $post->property_market_price = $post->calculated;
                }
                update_post_meta($new_post_id, '_pl_property_market_price', $post->property_market_price);
                update_post_meta($new_post_id, '_pl_i_want_to_sell', $post->i_want_to_sell);
                update_post_meta($new_post_id, '_pl_calculated', $post->calculated);
                update_post_meta($new_post_id, '_pl_negotiable', $post->negotiable);

                copy_property_image($id, $new_post_id);
                check_and_create_property_group($new_post_id);
                update_property_price_for_search($new_post_id);

                $response = array('status' => true, 'message' => 'Property duplicated successfully.');
            }
        }
    }
    echo json_encode($response);
    wp_die();
}
add_action('wp_ajax_nopriv_make_duplicate_property', 'make_duplicate_property');
add_action('wp_ajax_make_duplicate_property', 'make_duplicate_property');
/* MY LISTINGS APIS */

function get_maps_property_view()
{
    $response = array(
        'status' => false,
        'message' => 'Something went wrong please try again.'
    );

    //if (!wp_verify_nonce($_POST['ajax_nonce'], 'ajax_nonce')){
    //  echo json_encode($response);
    //  wp_die();
    //  }

    if (isset($_POST['id']) && !empty($_POST['id'])) {
        $property_id = $_POST['id'];
        $property = get_property_detail_by_id($property_id);

        $html1 = "<div class='row'>";
        if ($property->enable_pool) {
            $html1 .= "<div class='fs-6 col-md-12'><strong class='title me-2'>Market Price :</strong>" . (CO_OWNER_CURRENCY_SYMBOL . " " . number_format($property->property_original_price)) . "</div>";
            $html1 .= "<div class='fs-6 col-md-6'><strong class='title me-2'>Available Portion :</strong>" . $property->available_share . " % </div>";
            $html1 .= "<div class='fs-6 col-md-6'><strong class='title me-2'>Will Cost :</strong>" . (CO_OWNER_CURRENCY_SYMBOL . " " . number_format($property->available_price)) . "</div>";
            $html1 .= "<div class='fs-6 col-md-12'><strong class='title me-2'>Members :</strong>" . count($property->members) . "</div>";
        } else {
            $html1 .= "<div class='fs-6 col-md-12 mb-3'><strong class='title me-2'>Market Price :</strong>" . (CO_OWNER_CURRENCY_SYMBOL . " " . number_format($property->property_market_price)) . "</div>";
        }

        if ($property->interested_in_selling == 'portion_of_it') {
            $html1 .= "<div class='fs-6 col-md-12'><strong class='title me-2'>I want to sell :</strong> " . $property->i_want_to_sell . " % </div>";
            $html1 .= "<div class='fs-6 col-md-12'><strong class='title me-2'>Selling Price :</strong> " . (CO_OWNER_CURRENCY_SYMBOL . " " . number_format($property->calculated)) . " </div>";
        }
        $html1 .= "<div class='fs-6 col-md-12'><strong class='title me-2'>Type :</strong> " . (ucfirst($property->property_category)) . " </div>";
        $html1 .= "</div>";

        $extraHtml = "";
        if ($property->bedroom > 0) {
            $extraHtml .= "<div class='col-md-4'>";
            $extraHtml .= "<span class='me-2'>" . co_owner_get_svg('bedroom') . "</span>";
            $extraHtml .= "{$property->bedroom}";
            $extraHtml .= "</div>";
        }
        if ($property->bathroom > 0) {
            $extraHtml .= "<div class='col-md-4'>";
            $extraHtml .= "<span class='me-2'>" . co_owner_get_svg('bathroom') . "</span>";
            $extraHtml .= "{$property->bathroom}";
            $extraHtml .= "</div>";
        }
        if ($property->parking > 0) {
            $extraHtml .= "<div class='col-md-4'>";
            $extraHtml .= "<span class='me-2'>" . co_owner_get_svg('parking') . "</span>";
            $extraHtml .= "{$property->parking}";
            $extraHtml .= "</div>";
        }

        $images = "<div id='owl-slider' class='owl-carousel owl-theme'>";
        foreach ($property->images as $img) {
            $images .= "<div style='height: 160px;width: 190px;'><img src='{$img['url']}' class='img-thumbnail item h-100 w-100'></div>";
        }
        $images .= "</div>";

        $images .=  "<img src='{$property->first_image['url']}' class='img-thumbnail first-image- h-100 w-100'>";

        $response = array(
            'status' => true,
            $property,
            'html' => "
            <div class='row'>
                <div class='col-md-4'>
                    <a href='" . get_the_permalink($property->ID) /*home_url(CO_OWNER_PROPERTY_DETAILS_PAGE . "?id={$property->ID}")*/ . "'>
                        {$images}
                    </a>
                </div>
                <div class='col-md-8'>
                    {$html1}
                    <h6 class='h6 mt-2'><strong class='title'>Address : </strong> {$property->address}</h6>
                    <div class='row'>
                        {$extraHtml}
                    </div>
                    <div class='row' style='position: absolute;bottom: 12px;right: 10px;font-size: 14px;font-weight: 600;'>
                        <div class='col-md-12 text-end'>
                            <a href='" . get_the_permalink($property->ID) /*home_url(CO_OWNER_PROPERTY_DETAILS_PAGE . "?id={$property->ID}") */ . "' class='me-2 text-orange'>
                                View Detail
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            ",
        );
    }
    echo json_encode($response);
    wp_die();
}
add_action('wp_ajax_nopriv_get_maps_property_view', 'get_maps_property_view');
add_action('wp_ajax_get_maps_property_view', 'get_maps_property_view');

function update_person_list_as_delete()
{
    $response = array(
        'status' => false,
        'message' => 'Something went wrong please try again.'
    );
    //if (!wp_verify_nonce($_POST['ajax_nonce'], 'ajax_nonce')){
    //  echo json_encode($response);
    //  wp_die();
    //  }

    $user_id = get_current_user_id();
    $user_meta = array(
        '_user_property_category' => array(),
        '_user_property_type' => array(),
        '_user_descriptions' => null,
        '_user_preferred_location' => array(),
        '_user_land_area' => null,
        '_user_building_area' => null,
        '_user_age_year_built' => null,
        '_user_bedroom' => (int) 0,
        '_user_bathroom' => (int) 0,
        '_user_parking' => (int) 0,
        '_user_property_features' => array(),
        '_user_manually_features' => array(),
        '_user_budget' => 0,
        '_user_budget_range' => null,
        '_user_enable_pool' => false,
        '_user_listing_status' => 0,
    );
    co_owner_update_user_meta($user_id, $user_meta);
		/* #changed 11*/
	$wp_upload_dir = wp_upload_dir();
	$basedir = $wp_upload_dir['basedir'];
	 $site_url=  site_url().'/wp-content/uploads';
	 $url = get_avatar_url($user_id);
     $url_replace = str_replace($site_url,'',$url);
     $url_replace = $basedir.$url_replace;
	// var_dump(file_exists($url_replace));
     if(file_exists($url_replace)){
		unlink($url_replace);
		
	 }
	 	/* #changed 11*/

	

    $response = array(
        'status' => true,
        'message' => 'Buyer Profile deleted successfully.',
        'link' => home_url("my-listings/?alert=success&alert_message=Person list deleted successfully.")
    );

    echo json_encode($response);
    wp_die();
}
add_action('wp_ajax_nopriv_update_person_list_as_delete', 'update_person_list_as_delete');
add_action('wp_ajax_update_person_list_as_delete', 'update_person_list_as_delete');

function update_person_list_meta()
{
    $response = array(
        'status' => false,
        'message' => 'Something went wrong please try again.'
    );
    //if (!wp_verify_nonce($_POST['ajax_nonce'], 'ajax_nonce')){
    //  echo json_encode($response);
    //  wp_die();
    //  }

    if (isset($_POST['listing_status'])) {
        $user_id = get_current_user_id();
        $status = $_POST['listing_status'];
        update_user_meta($user_id, '_user_listing_status', $status);
        $status = $status == 1 ? 'show' : 'hide';
        $response = array(
            'status' => true,
            'message' => "Person list {$status} successfully."
        );
    }

    echo json_encode($response);
    wp_die();
}
add_action('wp_ajax_nopriv_update_person_list_meta', 'update_person_list_meta');
add_action('wp_ajax_update_person_list_meta', 'update_person_list_meta');

function get_my_notifications()
{
    $response = array(
        'status'    => false,
        'message'   => 'Something went wrong please try again.',
        'html'      => '<li class="no-any-notification">
                            <div class="notification-item">
                                <div class="message py-3">
                                    <div class="side-lst-cnt ps-3">
                                        <h6 class="pb-2 pe-3 text-center">
                                            No New Notification(s)
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </li>'
    );

    //    if (!wp_verify_nonce($_POST['ajax_nonce'], 'ajax_nonce')){
    //        echo json_encode($response);
    //        wp_die();
    //    }

    $user_id = get_current_user_id();
    if (get_user_status($user_id) != 1) {
        echo json_encode($response);
        wp_die();
    }

    $page = isset($_POST['page']) ? $_POST['page'] : 1;

    $count = CoOwner_Notifications::count(array('receiver_user' => $user_id));
    $notifications = CoOwner_Notifications::get_my_notifications($user_id, $page);

    if (count($notifications) > 0) {
        $html = "";
        foreach ($notifications as $notification) {
            ob_start();
            include(CO_OWNER_THEME_DIR . '/parts/notification.php');
            $html .= ob_get_clean();
        }

        if ($count > count($notifications)) {
            $html .= "<li><a data-current-page='{$page}' href='#' class='load-more-notifications notification-item text-center text-orange form-text'>Load More</a></li>";
        }
        $response['html'] = $html;
        $response['count'] = $count;
        $response['data'] = CoOwner_Notifications::mark_as_read_my_notifications(" != 3");
    }

    echo json_encode($response);
    wp_die();
}
add_action('wp_ajax_nopriv_get_my_notifications', 'get_my_notifications');
add_action('wp_ajax_get_my_notifications', 'get_my_notifications');

function save_user_feedback()
{
    $response = array(
        'status' => false,
        'message' => 'Something went wrong please try again.'
    );
    //if (!wp_verify_nonce($_POST['ajax_nonce'], 'ajax_nonce')){
    //  echo json_encode($response);
    //  wp_die();
    //  }

    if (
        isset($_POST['rating_1']) && !empty($_POST['rating_1']) &&
        isset($_POST['rating_2']) && !empty($_POST['rating_2']) &&
        isset($_POST['rating_3']) && !empty($_POST['rating_3']) &&
        isset($_POST['rating_comment']) && !empty($_POST['rating_comment'])
    ) {
        $user_id = get_current_user_id();
        $heading = get_user_full_name($user_id) . " Feedback";

        $post = array(
            'post_author' => $user_id,
            'post_title' => $heading,
            'post_content' => $_POST['rating_comment'],
            'post_status' => 'publish',
            'post_type' => 'feedback',
        );

        $id = wp_insert_post($post);
        if ($id > 0) {
            update_post_meta($id, '_feedback_rating_1', $_POST['rating_1']);
            update_post_meta($id, '_feedback_rating_2', $_POST['rating_2']);
            update_post_meta($id, '_feedback_rating_3', $_POST['rating_3']);
            $response = array(
                'status' => true,
                'message' => "Feedback sent successfully."
            );
        }
    }


    echo json_encode($response);
    wp_die();
}
add_action('wp_ajax_nopriv_save_user_feedback', 'save_user_feedback');
add_action('wp_ajax_save_user_feedback', 'save_user_feedback');

function get_plan_info_by_type()
{
    $response = array(
        'status' => false,
        'message' => 'Something went wrong please try again.'
    );
    //if (!wp_verify_nonce($_POST['ajax_nonce'], 'ajax_nonce')){
    //  echo json_encode($response);
    //  wp_die();
    //  }

    if (isset($_POST['plan'])) {
        $plan = get_subscription_information($_POST['plan']);
        if ($plan) {
            sleep(1);
            $html = "<div class='card plan-card " . ($plan->slug == 'trial' ? 'standard' : $plan->slug) . " shadow-sm'>
                    <h5 class='card-header p-2'>{$plan->name}</h5>
                    <div class='card-body p-3'>
                        <h4 class='text-start pt-2 pb-3 text-capitalize'>
                            " . ($plan->slug != 'trial' ? CO_OWNER_CURRENCY_SYMBOL . "{$plan->amount} <sub>/{$plan->duration}</sub>" : "$plan->duration") . "
                            <a class='btn btn-" . ($plan->slug == 'professional' ? 'blue' : 'orange') . " btn-rounded float-end btn-sm' data-bs-target='#plan-modal' data-bs-toggle='modal' href='#'>Change</a>
                        </h4>
                    </div>
                </div>";
            $response = array(
                'status' => true,
                'plan' => $plan,
                'html' => $html
            );
        }
    }
    echo json_encode($response);
    wp_die();
}
add_action('wp_ajax_nopriv_get_plan_info_by_type', 'get_plan_info_by_type');
add_action('wp_ajax_get_plan_info_by_type', 'get_plan_info_by_type');

function get_property_addresses()
{
    $response = array();
    if (!wp_verify_nonce($_POST['ajax_nonce'], 'ajax_nonce') || !isset($_POST['search'])) {
        echo json_encode($response);
        wp_die();
    }

    $address = api_domain_get_address($_POST['search'], 50);

    $response['items'] = array();

    foreach ($address as $address) {
        $response['items'][] = array(
            'id' => $address['address'],
            'text' => $address['address'],
            'unitNumber' => $address['addressComponents']['unitNumber'],
            'streetNumber' => $address['addressComponents']['streetNumber'],
            'streetName' => $address['addressComponents']['streetName'],
            'suburb' => $address['addressComponents']['suburb'],
            'postCode' => $address['addressComponents']['postCode'],
            'state' => $address['addressComponents']['state'],
        );
    }

    echo json_encode($response);
    wp_die();
}
add_action('wp_ajax_nopriv_get_property_addresses', 'get_property_addresses');
add_action('wp_ajax_get_property_addresses', 'get_property_addresses');

function get_notification_count()
{
    $response = array(
        'status' => false,
        'message' => 'Something went wrong please try again.'
    );
    //if (!wp_verify_nonce($_POST['ajax_nonce'], 'ajax_nonce')){
    //  echo json_encode($response);
    //  wp_die();
    //  }

    $user = wp_get_current_user();
    if ($user) {
        $all_notifications = CoOwner_Notifications::count(array(
            'receiver_user' => $user->ID,
            'read_at' => null
        ));
        $response = array(
            'status' => true,
            'all' => $all_notifications,
        );
    }

    echo json_encode($response);
    wp_die();
}
add_action('wp_ajax_nopriv_get_notification_count', 'get_notification_count');
add_action('wp_ajax_get_notification_count', 'get_notification_count');

function load_more_files()
{
    $response = array(
        'html' => ''
    );
    //if (!wp_verify_nonce($_POST['ajax_nonce'], 'ajax_nonce')){
    //  echo json_encode($response);
    //  wp_die();
    //  }


    if (
        isset($_POST['chat_with_connected']) &&
        isset($_POST['page']) &&
        isset($_POST['chat_with']) &&
        isset($_POST['is_group'])
    ) {
        $user_id = get_current_user_id();
        $chat_with_connected = $_POST['chat_with_connected'];
        $page = $_POST['page'];
        $chat_with = $_POST['chat_with'];
        $is_group = $_POST['is_group'] == 'true' ? true : false;
        $clear_chat_date = $_POST['clear_chat_date'];
        $length = isset($_POST['length']) ? $_POST['length'] : 1;

        $filter = array(
            ($is_group ? 'group_id' : 'connection_id') => $chat_with_connected
        );

        $clear_chat_key = $is_group ? '_user_clear_chat_group_' : '_user_clear_chat_with_';
        $clear_chat = get_user_meta($user_id, "{$clear_chat_key}{$chat_with}", true);

        if (!empty($clear_chat)) {
            $clear_chat_date = $clear_chat;
            $filter['created_at'] = array('>=', $clear_chat);
        }

        if ($is_group && !empty($clear_chat_date) && $clear_chat_date) {
            $filter['created_at'] = array('>=', $clear_chat_date);
        }

        $count = CoOwner_Conversation_Files::count($filter);
        $chat_files = CoOwner_Conversation_Files::get_files($filter, false, true, $page);

        $html = "";
        foreach ($chat_files as $file_attachment) {
            ob_start();
            include(CO_OWNER_THEME_DIR . '/parts/files.php');
            $html .= ob_get_clean();
        }

        if ($length < $count) {
            $link = "<li>
                         <a 
                            data-is-group='{$is_group}'
                            data-chat-with-connected='{$chat_with_connected}'
                            data-current-page='{$page}'
                            data-chat-with='{$chat_with}'
                            data-clear-chat-date='{$clear_chat_date}'
                            href='#' class='load-more-files notification-item text-center text-orange form-text'>Load More
                        </a>
                    </li>";
            $html .= $link;
        }

        $response = array(
            'html' => $html
        );
    }
    echo json_encode($response);
    wp_die();
}
add_action('wp_ajax_nopriv_load_more_files', 'load_more_files');
add_action('wp_ajax_load_more_files', 'load_more_files');

// REPORT MESSAGE
function report_message()
{
    $response = array(
        'status' => false,
        'message' => 'Something went wrong please try again.'
    );
    //if (!wp_verify_nonce($_POST['ajax_nonce'], 'ajax_nonce')){
    //  echo json_encode($response);
    //  wp_die();
    //  }

    $user_id = get_current_user_id();
    if (isset($_POST['id']) && $user_id > 0) {
        $message_id = $_POST['id'];
        $message = CoOwner_Connections::get(CO_OWNER_CONVERSATION_TABLE, array(
            'id' => $message_id
        ), true);

        if ($message && $message->sender_user != $user_id) {
            $table = CoOwner_Reports::$table;
            $array = array(
                'user_id' => $user_id,
                'message_id' => $message->id
            );
            $is_reported = CoOwner_Reports::get($table, $array, true);
            if (!$is_reported) {
                CoOwner_Reports::insert_in_table($table, $array);
                $response = array(
                    'status' => true,
                    'message' => 'Report Successfully.'
                );
            } else {
                $response = array(
                    'status' => false,
                    'message' => 'You Already Reported.'
                );
            }
        }
    }

    echo json_encode($response);
    wp_die();
}
add_action('wp_ajax_nopriv_report_message', 'report_message');
add_action('wp_ajax_report_message', 'report_message');




function new_actionsssssssssss()
{
    $response = array(
        'status' => false,
        'message' => 'Something went wrong please try again.'
    );
    //if (!wp_verify_nonce($_POST['ajax_nonce'], 'ajax_nonce')){
    //  echo json_encode($response);
    //  wp_die();
    //  }
    echo json_encode($response);
    wp_die();
}

add_action('wp_ajax_nopriv_new_actionsssssssssss', 'new_actionsssssssssss');
add_action('wp_ajax_new_actionsssssssssss', 'new_actionsssssssssss');


function get_conversations_message_extend()
{
    $response = array(
        'status' => false,
        'message' => 'Something went wrong please try again.'
    );
        $sender     = get_current_user_id();
		//var_dump($_POST['is_group']);
		
		$is_group   = $_POST['is_group'] == 'true' ? 1 : 0;
		$page       = $_POST['page'];
		global $wpdb;
		$tbaleName = $wpdb->prefix."co_owner_conversation";
		$sql="SELECT *
		   FROM  $tbaleName cv1
		   WHERE ";		
             if($is_group){		   
			$sql.= " (cv1.sender_user='$sender' OR cv1.receiver_user='$sender') AND group_id is not null ";	
			 }else{
					$sql.= " (cv1.sender_user='$sender' OR cv1.receiver_user='$sender') AND group_id IS NULL "; 
			 }			
		   
		   $sql.= " ORDER BY cv1.id DESC limit 1";
		 // echo  $sql;
		$latestRecord = $wpdb->get_row($sql);	


		
		  //print_r($latestRecord);
		
		     if($is_group){	
        $receiver   =  $latestRecord->group_id;;
			 }else{
				  $sender     = $latestRecord->receiver_user;
				  $receiver= $latestRecord->sender_user;
			 }

        //$status = get_user_meta($receiver,'_user_status',true);


        ///$after_created = (!$is_group && !empty($connection)) ? $connection->created_at : null;
        $result = CoOwner_Conversation::get_conversations($sender, $receiver, $is_group, $page, false, null);
        $html = '';
        if ($result->messages) {
            if ($page < $result->max_page) {
                $html .= '<div class="col-12 text-center mb-2"><a href="#" class="load-more-message text-orange small-title">View More</a></div>';
            }
            $old_message_date = 0;
            foreach (array_reverse($result->messages) as $message) {
                $files = CoOwner_Conversation_Files::get_files(array('message_id' => $message->id));
                ob_start();
                include(CO_OWNER_THEME_DIR . '/parts/message.php');
                $html .= ob_get_clean();
            }
        }
        $response = [
            'status'    => true,
            'html'      => $html,
            $connection
        ];
    
    echo json_encode($response);
    wp_die();
}
add_action('wp_ajax_nopriv_get_conversations_message_extend', 'get_conversations_message_extend');
add_action('wp_ajax_get_conversations_message_extend', 'get_conversations_message_extend');

function genEmailVerification($user_id){
	
    // get user data
    $user_info = get_userdata($user_id);
	
	
    // create md5 code to verify later
    $code = md5(time());
    // make it into a code to send it to user via email
    $string = array('id'=>$user_id, 'code'=>$code, 'date'=>date('Y-m-d'));
    // create the activation code and activation status
    update_user_meta($user_id, 'account_activated', 0);
    update_user_meta($user_id, '_user_status', 0);
    update_user_meta($user_id, 'activation_code', $code);
    // create the url
    $url = get_site_url(). '/email-verification/?act=' .base64_encode( serialize($string));
	$body = '';
	ob_start();
	include get_template_directory() . '/emails/email-verification.html';
	$body = ob_get_contents();
	ob_get_clean();
	$body = str_replace('%code%',$url,$body);
	$body = str_replace('%code%',$url,$body); // two times code exist so two time replace
	$body = str_replace('%YOUR_EMAIl%',$user_info->user_email,$body);

    // basically we will edit here to make this nicer
      $html = $body;
	
		$content_type = function() { return 'text/html'; };
		add_filter( 'wp_mail_content_type', $content_type );	
		// send an email out to user
		wp_mail( $user_info->user_email, __('Email Verification','text-domain') , $html);	
		remove_filter( 'wp_mail_content_type', $content_type );
	
}

add_action( 'user_register', 'my_registration', 10, 2 );
function my_registration( $user_id ) {
/* Disabled the email verification link sent to mail  */
/*
genEmailVerification($user_id);
*/
}
/*For Signup*/
add_action('wp_ajax_save_register_modal','save_register_modal');
add_action('wp_ajax_nopriv_save_register_modal','save_register_modal');
function save_register_modal(){

	$property_option= $_POST['property_option'];
	$user_id= get_current_user_id();
	$steps= array();
	$result= array();
	/* #changed 11*/
	$is_mobile = false;	
	if (preg_match('/iPhone|iPod|iPad|BlackBerry|Android/', $_SERVER['HTTP_USER_AGENT'])) {
	  $is_mobile= true;
	}
	/* #changed 11*/
	

		 if($property_option){
		   update_user_meta($user_id,'property_option',$property_option);	
		 }
		 
		 /* #changed 11*/
		 if($is_mobile){
			 if(!empty($_FILES)){
				 $dataImageTitle = $_FILES['profile']['name'];
				// save_data_image($_POST['profile_data_img'], $dataImageTitle ,$user_id );				 
			 } 
		 }else{
			 if(!empty($_FILES)){
				// save_user_avatar_image($user_id);
			 }	 
		 }

		 
		 /* #changed 11*/
		 
		 
		  if($property_option=="buy_property"){
		   $result= array('status'=>200,'redirect'=> site_url('create-a-person-listing') );
		  }else{
			$result= array('status'=>200,'redirect'=> site_url('create-a-property-listing') );
		   }
			if($property_option){
				$steps['step1']='complete';
			}
			if(!empty($_FILES)){
				$steps['step2']='complete';
			}
			if($steps){
			 update_user_meta($user_id,'steps_complete',$steps);
			}

		  echo json_encode($result);

	
	die;
}


add_action('wp_ajax_checkFormEdit','checkFormEdit');
function checkFormEdit(){
	
	$post_id = !empty($_POST['property_id']) ? $_POST['property_id'] : '';
	$unsetAr= array('s2member_pro_stripe_checkout','stripe_pm_id','stripe_seti_secret','stripe_pi_secret','stripe_pi_id','stripe_pm_id','stripe_seti_id','stripe_sub_id','action','property_id');
	foreach($unsetAr as $ukey){
		unset($_POST[$ukey]);
	}	
	
	if($post_id){
		$dbData = get_property_detail_by_id($post_id);

		

	$formArray= array();
	$dbArray= array();
	foreach($_POST as $key =>$vl){
		$formArray[str_replace( '_pl_','',$key)] =$vl;
	}
	foreach($dbData as $key =>$vl){
		if($key=="post_title"){ $key = 'heading'; }
		if($key=="post_content"){ $key = 'descriptions'; }
		if($key=="enable_pool"){ 
		   if($vl==1){$vl = 'on';}
		}
	   if($key=="address_manually"){ 
		   if(!$vl){$vl = 'false';}
		}	
		
        $dbArray[$key] =$vl;
	}	
	
	$isEditActual = array();
    foreach($formArray as $key =>$fvl){
		if($dbArray[$key] != $fvl ){
			$isEditActual[$key] =$fvl;
		}
	}

}else{
	$isEditActual = array();
    foreach($_POST as $key =>$fvl){
		if(!empty($fvl)){
			$isEditActual[$key] =$fvl;
		}
	}	
	
}
        echo count($isEditActual);
	// pr($formArray);	 
	
	die;
}

/*
add_action('wp_ajax_remove_user_profile_image',function(){
	  $user_id = $_POST['user_id'];
	  $avatar = get_user_meta($id_or_email, '_user_profile_avatar', true);
	  update_user_meta($user_id,'_user_profile_avatar','');
	
	die;
});
*/

//copy_user_data($user_id=290);
/* #changed 11*/
function avatar_exist($user_id){
	$wp_upload_dir = wp_upload_dir();
	$basedir = $wp_upload_dir['basedir'];	
	$site_url=  site_url().'/wp-content/uploads';
	$url = get_avatar_url($user_id);
	$url_replace = str_replace($site_url,'',$url);
	$url_replace = $basedir.$url_replace;	
    return file_exists($url_replace);
}

function avatar_default(){
	return site_url().'/wp-content/themes/co-owner/images/person-icon-new.png';
}

function remove_profile_image()
{
	$user_id = $_POST['user_id'];
	$wp_upload_dir = wp_upload_dir();
	$basedir = $wp_upload_dir['basedir'];
     $response =100;
	 $site_url=  site_url().'/wp-content/uploads';
	 $url = get_avatar_url($user_id);
     $url_replace = str_replace($site_url,'',$url);
     $url_replace = $basedir.$url_replace;
	// var_dump(file_exists($url_replace));
	
	 
	 if(file_exists($url_replace)){
		unlink($url_replace);
		 $response=200;
	 }

	

    echo $response;
    wp_die();
}

add_action('wp_ajax_remove_profile_image', 'remove_profile_image');
/* #changed 11*/

add_action('wp_ajax_croppie_profile_fnc','croppie_profile_fnc');
function croppie_profile_fnc(){
	
if(isset($_POST["image"]))
{
	
$user_id = get_current_user_id();	

 $data = $_POST["image"];

 $image_array_1 = explode(";", $data);

 $image_array_2 = explode(",", $image_array_1[1]);

 $decoded = base64_decode($image_array_2[1]);
  $hashed_filename = time().'_'.$user_id.'_profile'.'.png';

     //file_put_contents($imageName, $data);
 
		//$dataImageTitle = $user_id.'_profile'.'.png';


	// Upload dir.
	$upload_dir  = wp_upload_dir();
	$upload_path = str_replace( '/', DIRECTORY_SEPARATOR, $upload_dir['path'] ) . DIRECTORY_SEPARATOR;

	//$img             = str_replace( 'data:image/jpeg;base64,', '', $base64_img );
	//$img             = str_replace( ' ', '+', $img );
	//$decoded         = base64_decode( $img );
	$filename        = $title;
	$file_type       = 'image/jpeg';
	//$hashed_filename = md5( $filename . microtime() ) . '_' . $filename;

	// Save the image in the uploads directory.
	$upload_file = file_put_contents( $upload_path . $hashed_filename, $decoded );

	$attachment = array(
		'post_mime_type' => $file_type,
		'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $hashed_filename ) ),
		'post_content'   => '',
		'post_status'    => 'inherit',
		'guid'           => $upload_dir['url'] . '/' . basename( $hashed_filename )
	);

	$attach_id = wp_insert_attachment( $attachment, $upload_dir['path'] . '/' . $hashed_filename );
	   if(	$attach_id){
		   $moveFile= array(
		    'file' => $upload_dir['path'] . '/' . $hashed_filename,
			'url'=> $upload_dir['url'] . '/' . $hashed_filename,
			'type'=> $file_type
		   );
			$old_avatar = get_user_meta($user_id, '_user_profile_avatar', true);
			if ($old_avatar && $old_avatar['file']) {
				if (file_exists($old_avatar['file'])) {
					unlink($old_avatar['file']);
					delete_user_meta($user_id, '_user_profile_avatar');
				}
			}
		
			update_user_meta($user_id, '_user_profile_avatar', $moveFile);

	   
	echo  $upload_dir['url'] . '/' . $hashed_filename;
}else{
	
	echo 100;
}


// echo '<img src="'.$imageName.'" class="img-thumbnail" />';
}
	
	die;
}

/* Assistance and Partner dropdown */
add_action('wp_ajax_fetch_lawyer_data_dropdown','fetch_lawyer_data_dropdown');
function fetch_lawyer_data_dropdown(){
	
	$html='';
	$catid = $_POST['id'];
	
	$args=array(
		'posts_per_page' => -1,    
		'post_type' => 'lawyer',
		'tax_query' => array(
			array(
				'taxonomy' => 'assistance',
				'field'    => 'id',
				'terms'    => $catid,
			),
		),
	 );
		$posts = new WP_Query( $args );	

		if ($posts->have_posts()){

			while ($posts->have_posts()){
				$posts->the_post(); 
				$html .= '<option value="'.get_the_ID().'">'.get_the_title().'</option>';
			}
		}
		wp_reset_postdata();

	  echo $html;
	die;
}
/* Assistance and Partner dropdown */


/*
ajax get time slot for lawyer
*/

function prepare_time_slots($starttime, $endtime, $duration, $currentTime){
	 
	$time_slots = array();
	$start_time    = strtotime($starttime); //change to strtotime
	$end_time      = strtotime($endtime); //change to strtotime
	$currentTime=    date('Y-m-d').' '.$currentTime;
	 $currentTime = strtotime($currentTime);
	 
	$add_mins  = $duration * 60;
	 
	while ($start_time <= $end_time) // loop between time
	{
		
		if($currentTime){
			if($start_time >= $currentTime && $end_time > $currentTime ){
			   $time_slots[] = date("H:i A", $start_time);
			}
		}else{
			 $time_slots[] = date("H:i A", $start_time);
		}
	   $start_time += $add_mins; // to check endtime
	}

	return $time_slots;
}


function gen_slot_html($timeSlots){
	 $html='';
	if($timeSlots){
	 foreach($timeSlots as $slot){
			   $html.='<li class="time_slot_'.$slot.'"><a href="javascript:void(0);" class="time_book_now"  data-slot="'.$slot.'">'.$slot.'</a></li>';
		 }
       }else{
		   $html ='<li class="time_no_slot"><strong>No slots available</strong></li>';
	   }

return $html;	   
}

add_action('wp_ajax_fetch_time_slot_by_date_user','fetch_time_slot_by_date_user');
add_action('wp_ajax_nopriv_fetch_time_slot_by_date_user','fetch_time_slot_by_date_user');
function fetch_time_slot_by_date_user(){
  	
	$is_time_responsive = true; // It will allow to get future time slots not morning to evening 
	
	$post_id = $_POST['pid'];
	$day = date('l', strtotime($_POST['date']));
	$week_group = 'week_days';
	$currentTime= $_POST['currentTime'];
	$today_date = date('y-m-d');
	
	if(strtotime($_POST['date'])  > strtotime($today_date)){
		$currentTime='';
	}
	$duration_field = 'slot_duration';
	
	$week_group_data =  get_field($week_group,$post_id);
	$duration =  get_field($duration_field,$post_id);
	if(empty($duration)){
		$duration = 60;
	}
	
//	pr($week_group_data);
	
	$day_start_time_field_name =  strtolower($day).'_start_time';
	$day_end_time_field_name =  strtolower($day).'_end_time';
	$day_off_field_name =  strtolower($day).'_off';
	$is_day_enabled = $week_group_data[$day_off_field_name];
	
	$default_day_off_field_name = 'use_default_time';
	$default_day_start_field_name = 'default_start_time';
	$default_day_end_field_name = 'default_end_time';
	
	$is_default_day_enabled = get_field($default_day_off_field_name,$post_id);
		
 // var_dump($is_day_enabled);
	
	$response = '';
	
	if($is_default_day_enabled){
	
		$start_time = get_field($default_day_start_field_name,$post_id);
		$end_time = get_field($default_day_end_field_name,$post_id);
		
		  
		
		if($start_time && $end_time){	
           		
			$response = prepare_time_slots($start_time, $end_time, $duration,$currentTime);
		}	
		
	}else if(!$is_day_enabled){
		
		
		$start_time = $week_group_data[$day_start_time_field_name];
		$end_time = $week_group_data[$day_end_time_field_name];
		
		
		if($start_time && $end_time){			
			$response = prepare_time_slots($start_time, $end_time, $duration,$currentTime);
		}	
	}else{
		
		$response= array();
	}
	
	  $response = gen_slot_html($response);
	
	
	echo $response;
	die;
}
/*
ajax get time slot for lawyer
*/