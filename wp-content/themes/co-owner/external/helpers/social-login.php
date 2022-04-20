<?php
use Overtrue\Socialite\SocialiteManager;
use MetzWeb\Instagram\Instagram;

/**
 * Class CoOwnerSocial
 */
class CoOwnerSocial {

    public static function get_social_config($type)
    {
        if($type == 'google'){
            return array(
                'google' => array(
                    'client_id' => get_option('_crb_google_client_id'),
                    'client_secret' => get_option('_crb_google_client_secret'),
                    'redirect' => get_option('_crb_google_redirect')
                )
            );
        }

        elseif($type == 'facebook')
        {
            return array(
                'facebook' => array(
                    'client_id' => get_option('_crb_facebook_client_id'),
                    'client_secret' => get_option('_crb_facebook_client_secret'),
                    'redirect' => get_option('_crb_facebook_redirect')
                )
            );
        }

        elseif($type == 'linkedin')
        {
            return array(
                'linkedin' => array(
                    'client_id' => get_option('_crb_linkedin_client_id'),
                    'client_secret' => get_option('_crb_linkedin_client_secret'),
                    'redirect' => get_option('_crb_linkedin_redirect')
                )
            );
        }

        elseif($type == 'instagram')
        {
            return array(
                'apiKey' => get_option('_crb_instagram_client_id'),
                'apiSecret' => get_option('_crb_instagram_client_secret'),
                'apiCallback' => get_option('_crb_instagram_redirect')
            );
        }
    }

    public static function after_login_redirect($url = null)
    {
        $url = $url ? $url : home_url();
        wp_redirect($url);
        die;
    }

    public static function redirect_to_social($social)
    {
        $config = self::get_social_config($social);

        if($social == 'instagram'){
            $instagram = new Instagram($config);
            $url =  $instagram->getLoginUrl(array('basic'));

//            $provider = new League\OAuth2\Client\Provider\Instagram([
//                'clientId'          => get_option('_crb_instagram_client_id'),
//                'clientSecret'      => get_option('_crb_instagram_client_secret'),
//                'redirectUri'       => get_option('_crb_instagram_redirect'),
//                'host'              => 'https://api.instagram.com',  // Optional, defaults to https://api.instagram.com
//                'graphHost'         => 'https://graph.instagram.com' // Optional, defaults to https://graph.instagram.com
//            ]);
//            $options = [
//                'scope' => ['user_profile', 'user_media','basic'] // array or string
//            ];
//            $url = $provider->getAuthorizationUrl($options);
        } else {
            $socialite = new SocialiteManager($config);
            $url = $socialite->create($social)->redirect();
        }
        wp_redirect($url);
        die;
    }

    public static function create_wp_user($user,$type)
    {
        $wp_user = new WP_User();

        $wp_user->user_email = $user->getEmail();

        $first_name = $user->getNickname();
        $last_name = "";
        $getAttributes = $user->getAttributes();
        if($type == 'google'){
            $first_name = ((isset($getAttributes['raw']) && isset($getAttributes['raw']['given_name'])) ? $getAttributes['raw']['given_name'] : $user->getNickname());
            $last_name = ((isset($getAttributes['raw']) && isset($getAttributes['raw']['family_name'])) ? $getAttributes['raw']['family_name'] : "");
        }
        elseif($type == 'facebook'){
            $first_name = ((isset($getAttributes['raw']) && isset($getAttributes['raw']['first_name'])) ? $getAttributes['raw']['first_name'] : $user->getNickname());
            $last_name = ((isset($getAttributes['raw']) && isset($getAttributes['raw']['last_name'])) ? $getAttributes['raw']['last_name'] : "");
        }
        $wp_user->first_name = $first_name;
        $wp_user->last_name = $last_name;
        $wp_user->display_name = ucfirst($first_name) . ' ' . $last_name;

        $wp_user->user_login = strtolower(str_replace([" "],"",$user->getName()));
        $wp_user->password = wp_rand(00000000,999999999);
        $wp_user->user_registered = wp_date('Y-m-d H:i:s');

        $wp_user_id = wp_create_user($wp_user->user_login, $wp_user->password,$wp_user->user_email);
        if ($wp_user_id) {
            wp_update_user($wp_user);
            update_user_meta($wp_user_id,'first_name',$first_name);
            update_user_meta($wp_user_id,'last_name',$last_name);
            return $wp_user_id;
        }
        return null;
    }

    public static function wp_user_login_by_id($id,$social_id,$type)
    {
        $id = is_object($id) ? $id->ID : $id;
        wp_set_current_user( $id );
        wp_set_auth_cookie( $id );
		cookie_logged_in();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['social_login'] = $id.','.$social_id.','.$type;
        do_action( 'wp_login', $id );
    }

    public static function social_login($type)
    {
        try {
            $config = self::get_social_config($type);
            $logined_user = wp_get_current_user();

            if ($type == 'instagram' && isset($_GET['code'])) {
                $code = $_GET['code'];

//                $provider = new League\OAuth2\Client\Provider\Instagram([
//                    'clientId'          => get_option('_crb_instagram_client_id'),
//                    'clientSecret'      => get_option('_crb_instagram_client_secret'),
//                    'redirectUri'       => get_option('_crb_instagram_redirect'),
//                    'host'              => 'https://api.instagram.com',  // Optional, defaults to https://api.instagram.com
//                    'graphHost'         => 'https://graph.instagram.com' // Optional, defaults to https://graph.instagram.com
//                ]);
//                $token = $provider->getAccessToken('authorization_code', [
//                    'code' => $_GET['code']
//                ]);
//                $user = $provider->getResourceOwner($token);
//                CoOwner::print_a($user);
//                die;



                $instagram = new Instagram($config);
                if (true === isset($code)) {
                    $data = $instagram->getOAuthToken($code);
                    if (empty($data->user->id)) {
                        update_user_meta($logined_user->ID, "_user_{$type}_id", $data->user->id);
                        $url = home_url(CO_OWNER_MY_ACCOUNT_VERIFICATION . '?alert=your_account_linked');
                        self::after_login_redirect($url);
                    }
                }
            } else {
                $config = self::get_social_config($type);
                $socialite = new SocialiteManager($config);
                $user = $socialite->create($type)->userFromCode($_GET['code']);
                $email = $user->getEmail();

                $socialite_id = $user->getId();

                if (isset($logined_user->ID) && $logined_user->ID > 0) {
                    $user_meta = co_owner_get_meta_row("_user_{$type}_id", $socialite_id);
                    if (empty($user_meta)) {
                        update_user_meta($logined_user->ID, "_user_{$type}_id", $socialite_id);
                        $url = home_url(CO_OWNER_MY_ACCOUNT_VERIFICATION . '?alert=your_account_linked');
                    } else {
                        $message = $logined_user->ID == $user_meta->user_id ? 'your_account_already_linked' : 'your_account_already_linked_to_other_account';
                        $url = home_url(CO_OWNER_MY_ACCOUNT_VERIFICATION . '?alert=' . $message);
                    }
                    self::after_login_redirect($url);
                } elseif(in_array($type,['facebook','google'])) {
                    $wp_user = get_user_by('email', $email);
                    $user_meta = co_owner_get_meta_row("_user_{$type}_id", $socialite_id);

                    if (
                        ($wp_user && empty($user_meta)) ||
                        (empty($wp_user) && $user_meta) ||
                        (!empty($wp_user) && !empty($user_meta))
                    ) {
                        $userId = (!empty($wp_user) && !empty($user_meta) || (empty($wp_user) && !empty($user_meta))) ? $user_meta->user_id : $wp_user->ID;
                        if (empty($user_meta) || $user_meta == null) {
                            update_user_meta($userId, "_user_{$type}_id", $socialite_id);
                        }
                        $user = get_user_by('ID', $userId);
                        $status = get_user_meta($userId,'_user_status',true);

                        if (!user_can($user,'administrator') && ($status == 1 || $status == 2)) {
                            self::wp_user_login_by_id($userId,$socialite_id,$type);
                        } else {
                            $message = $status > 2 ? 'Your account hasbeen deleted please contact to admin.' : 'Something went wrong please try again.';
                            wp_logout();
                            wp_redirect(home_url('login')."/?alert=error&alert_message={$message}");
                            die;
                        }
                    } else {
                        $created_user = self::create_wp_user($user,$type);
                        if ($created_user) {
                            create_default_usermeta($created_user);
                            update_user_meta($created_user, "_user_{$type}_id", $socialite_id);
                            self::wp_user_login_by_id($created_user,$socialite_id,$type);
                            $type = $type == 'google' ? 'email' : $type;
                            update_user_meta($created_user,"_user_is_{$type}_verified",true);
                            update_user_meta($created_user,"_user_is_mobile_verified",false);
                        } else {
                            wp_redirect(home_url('login') . '/?alert=error&alert_message=Something went wrong please try again.');
                            die;
                        }
                    }
                    self::after_login_redirect();
                }
            }
        } catch (\Exception $exception){
            wp_redirect(home_url('login') . '/?alert=error&alert_message=Something went wrong please try again.');
            die;
        }
    }

}


if(
    isset($_GET['action']) &&
    in_array($_GET['action'],['redirect_to_google_login','redirect_to_facebook_login','redirect_to_linkedin_login','redirect_to_instagram_login'])
){
    if ($_GET['action'] == 'redirect_to_google_login') {
        $type = 'google';
    }
    if ($_GET['action'] == 'redirect_to_facebook_login') {
        $type = 'facebook';
    }
    if ($_GET['action'] == 'redirect_to_linkedin_login') {
        $type = 'linkedin';
    }
    if ($_GET['action'] == 'redirect_to_instagram_login') {
        $type = 'instagram';
    }
    CoOwnerSocial::redirect_to_social($type);
}

if(
    isset($_GET['login_callback']) &&
    isset($_GET['code']) &&
    in_array($_GET['login_callback'],['google','facebook','linkedin','instagram'])
){
    CoOwnerSocial::social_login($_GET['login_callback']);
}

if(
    isset($_GET['login_callback']) &&
    isset($_GET['error']) &&
    in_array($_GET['login_callback'],['google','facebook','linkedin','instagram'])
){
    $message = isset($_GET['error_description']) && !empty($_GET['error_description']) ? $_GET['error_description'] : "Something went wrong please try again";
    $url = "?alert=error&alert_message={$message}";
    if(get_current_user_id() > 0){
        /*wp_redirect(CO_OWNER_MY_ACCOUNT_VERIFICATION.$url);
        exit(); */
    }
    wp_redirect("login{$url}");
    exit();
}

