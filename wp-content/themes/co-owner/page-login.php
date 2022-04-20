<?php

/**
 * Default header file
 *
 * This file is needed in case you wan't to use this theme for Woocommerce.
 * In favor of the parts structure, this file is constructed with parts.
 * Also this file is NOT used by default
 *
 * Please see /external/bootstrap-utilities.php for info on CoOwner::get_template_parts()
 *
 * @package 	WordPress
 * @subpackage 	Co-Owner
 * @autor 		TechXperts
 */
?>
<?php
if(isset($_GET['pwd']) &&  $_GET['pwd']=="updated"){
	wp_logout(); 
    wp_redirect(home_url()); 
die;  	
}

$http_ref = !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
is_user_login_redirect();

$errors = new WP_Error();
if (
    isset($_POST['user_login_attempt']) &&
    wp_verify_nonce($_POST['co_owner_login'], 'co_owner_login')
) {
    if (
        isset($_POST['user_login']) && !empty($_POST['user_login']) &&
        isset($_POST['user_password']) && !empty($_POST['user_password'])
    ) {
        $user_obj = wp_signon(array(
            'user_login' => $_POST['user_login'],
            'user_password' => $_POST['user_password'],
            'remember' => isset($_POST['remember']) ? true : false,
        ), is_ssl());
		if(isset($_POST['redirect_to'])){
		 $http_ref = $_POST['redirect_to'];
		}

        if ($user_obj instanceof WP_User) {
	   
            $user_status = get_user_meta($user_obj->ID, '_user_status', true);
		
			/*var_dump($user_status);
			die; */
            $is_admin = in_array('administrator', $user_obj->roles);

            if ($user_status == 1 || $user_status == 2 || $is_admin) {
                if (isset($_SESSION['social_login'])) {
                    unset($_SESSION['social_login']);
                }
                wp_set_current_user($user_obj->ID, $user_obj->user_login);
				if(isset($_POST['redirect_to'])){
					$url =  $http_ref;
				}else{
					$url = $is_admin ? admin_url() : (isset($_GET['redirect_to']) ? base64_decode($_GET['redirect_to']) :  home_url());	
				}             

                wp_redirect($url);
                exit();
            } else {
                wp_logout();
                $errors->add('error', 'Your account is not confirmed,Please confirm your mail to login.');
            }
        } else {
            wp_logout();
            $errors->add('error', 'Email id or password invalid.');
        }
    } else {
        if (isset($_POST['user_login']) && empty($_POST['user_login'])) {
            $errors->add('user_login', 'Email id is required.');
        }
        if (isset($_POST['user_password']) && empty($_POST['user_password'])) {
            $errors->add('user_password', 'Password is required.');
        }
    }
}

CoOwner::get_template_parts(array('parts/shared/html-header'));
?>
<div class="d-flex align-items-center">
    <div class="container login-main">
        <div class="top-logo text-center">
            <a href="<?php echo home_url('/') ?>">
                <svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40.58 40.58">
                    <defs>
                        <style>
                            .cls-1 {
                                fill: #272626;
                                fill-rule: evenodd;
                            }
                        </style>
                    </defs>
                    <path id="Combined-Shape" class="cls-1" d="M40.79,34.46a4.59,4.59,0,1,1-6.49,0A4.59,4.59,0,0,1,40.79,34.46ZM9.54,34.3a4.59,4.59,0,1,1-6.49,0A4.59,4.59,0,0,1,9.54,34.3Zm27.63-9.69a9.37,9.37,0,0,1,.53,7.74,4.91,4.91,0,0,0-5.42,5.41,9.31,9.31,0,0,1-9.86-2.12l-2.35-2.35a9.29,9.29,0,0,1-2.59-8.15,3.54,3.54,0,0,0,.64.92l.08.08,6.67,6.62a4.22,4.22,0,0,0,.61.49,3.69,3.69,0,0,0,5.11-1.13Q34.85,25.48,37.17,24.61ZM18.86,17.48a3.54,3.54,0,0,0-.92.64l-.08.08-6.62,6.67a4.22,4.22,0,0,0-.49.61,3.69,3.69,0,0,0,1.13,5.11q6.64,4.26,7.51,6.58a9.37,9.37,0,0,1-7.74.53,4.91,4.91,0,0,0-5.41-5.42,9.31,9.31,0,0,1,2.12-9.86l2.35-2.35A9.29,9.29,0,0,1,18.86,17.48ZM32.35,6.3a4.91,4.91,0,0,0,5.41,5.42,9.31,9.31,0,0,1-2.12,9.86l-2.35,2.35a9.29,9.29,0,0,1-8.15,2.59,3.54,3.54,0,0,0,.92-.64l.08-.08,6.62-6.67a4.22,4.22,0,0,0,.49-.61,3.69,3.69,0,0,0-1.13-5.11q-6.64-4.26-7.51-6.58A9.37,9.37,0,0,1,32.35,6.3ZM21.58,8.36l2.35,2.35a9.29,9.29,0,0,1,2.59,8.15,3.54,3.54,0,0,0-.64-.92l-.08-.08-6.67-6.62a4.22,4.22,0,0,0-.61-.49,3.69,3.69,0,0,0-5.11,1.13q-4.26,6.64-6.58,7.51a9.37,9.37,0,0,1-.53-7.74,4.91,4.91,0,0,0,5.42-5.41A9.31,9.31,0,0,1,21.58,8.36ZM41,3.21a4.59,4.59,0,1,1-6.49,0A4.59,4.59,0,0,1,41,3.21ZM9.7,3.05a4.59,4.59,0,1,1-6.49,0A4.59,4.59,0,0,1,9.7,3.05Z" transform="translate(-1.71 -1.71)" />
                </svg>
            </a>
        </div>

        <h1 class="text-center">Welcome to <?php echo $site = get_bloginfo('name'); ?></h1>

        <h4 class="text-center">Log in Now</h4>

        <div class="row justify-content-center mb-5">
            <div class="col col-xxl-6 col-xl-7 col-lg-8 col-md-10">
                <div class="card login-box">
                    <div class="card-body">
                        <h6 class="text-center">Enter via social media</h6>
                        <div class="row">
                            <div class="col text-center">
                                <a href="<?php echo home_url(); ?>/?action=redirect_to_facebook_login" class="btn btn-custom-pills btn-facebook">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                        <g fill="none" fill-rule="evenodd">
                                            <g>
                                                <g>
                                                    <g>
                                                        <path d="M0 0H16V16H0z" transform="translate(-501 -441) translate(483 431) translate(18 10)" />
                                                        <path fill="#FFF" d="M8.658 4.306c0-.629.065-.966 1.033-.966h1.323V1h-2.1C6.426 1 5.552 2.17 5.552 4.14v1.864H4V8h1.552v7h3.103V8h2.07l.304-1.996H8.655l.003-1.698z" transform="translate(-501 -441) translate(483 431) translate(18 10)" />
                                                    </g>
                                                </g>
                                            </g>
                                        </g>
                                    </svg>
                                    Log in with Facebook
                                </a>

                                <a href="<?php echo home_url(); ?>/?action=redirect_to_google_login" class="btn btn-custom-pills btn-g-plus">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="16" viewBox="0 0 24 16">
                                        <g fill="none" fill-rule="evenodd">
                                            <g fill="#FFF" fill-rule="nonzero">
                                                <g>
                                                    <g>
                                                        <path d="M0 7.978C0 .938 7.907-2.51 12.525 2.09l-2.036 2.078c-2.693-2.755-7.633-.643-7.633 3.809 0 6.18 8.154 6.597 8.902 1.599H7.501V6.836h7.08c.071.401.122.804.122 1.327C14.703 18.88 0 18.27 0 7.978zm21.823-3.19v2.314H24v2.326h-2.177v2.315h-2.187V9.428h-2.177V7.102h2.177V4.788h2.187z" transform="translate(-735 -441) translate(483 431) translate(234) translate(18 10)" />
                                                    </g>
                                                </g>
                                            </g>
                                        </g>
                                    </svg>
                                    Log in with Google+
                                </a>
                            </div>
                        </div>

                        <div class="frm-title">
                            <h6>Or Log in with email</h6>
                        </div>

                        <form class="w-75 frm-custom" action="" method="post">
						 <input type="hidden" name="redirect_to" value="<?php echo $http_ref; ?>" >
                            <?php include_once('parts/alert.message.php'); ?>
                            <?php wp_nonce_field('co_owner_login', 'co_owner_login'); ?>
                            <div class="row">
                                <div class="col col-sm-12 col-12 mb-3">
                                    <input name="user_login" type="text" class="form-control<?php echo $errors->get_error_message('user_login') ? ' is-invalid' : '' ?>" id="username" placeholder="Email id" value="<?php echo isset($_POST['user_login']) ? $_POST['user_login'] : null ?>">
                                    <label id="username-error" class="text-error" for="username"><?php echo $errors->get_error_message('user_login') ?></label>
                                </div>

                                <div class="col col-sm-12 col-12 mb-3 pro-eye">
                                    <input name="user_password" type="password" class="form-control<?php echo $errors->get_error_message('user_password') ? ' is-invalid' : '' ?>" id="password" placeholder="Password">
                                    <span class="LoginPasswordToggle"><img src="<?php echo esc_url(get_stylesheet_directory_uri() . '/images/show-pwd.svg'); ?>" class="login-password-toggle" /><img src="<?php echo esc_url(get_stylesheet_directory_uri() . '/images/hide-pwd.svg'); ?>" class="login-password-toggle" style="display: none;" /></span>
                                    <label id="password-error" class="text-error" for="password"><?php echo $errors->get_error_message('user_password') ?></label>
                                </div>

                                <div class="col col-sm-12 col-12 mb-3">
                                    <div class="text-error"><?php echo $errors->get_error_message('error') ?></div>
                                </div>

                                <div class="col col-sm-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" name="remember">
                                        <label class="form-check-label" for="flexCheckDefault">
                                            Remember me
                                        </label>
                                    </div>
                                </div>

                                <div class="col col-sm-6 text-end">
                                    <a href="<?php echo home_url('forgot-password'); ?>" class="link orange">Forgot Password?</a>
                                </div>

                                <div class="col col-sm-12 col-12 text-center pt-40px">
                                    <input class="btn btn-orange btn-rounded w-180px" type="submit" value="Log in" name="user_login_attempt">
                                </div>
                            </div>
                        </form>
                        <div class="box-bottom-cnt text-center">
                            New to <?php echo $site; ?>? <a href="<?php echo home_url('register'); ?>">Sign up</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php
        if (is_active_sidebar('copyright_disclaimer')) {
            dynamic_sidebar('copyright_disclaimer');
        }
        ?>
    </div>
</div>

<?php
CoOwner::get_template_parts(array('parts/shared/html-footer'));
?>