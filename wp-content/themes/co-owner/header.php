<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title>
        <?php wp_title('|', true, 'right'); ?><?php bloginfo('name'); ?>
    </title>
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
    <link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/images/favicon.png" />
    <?php wp_head(); ?>
    <?php $subscription = get_user_subscription_level(get_current_user_id()); ?>


<meta property="og:image" content="<?php echo get_site_url(); ?>/wp-content/uploads/2021/08/LinkedIn-Profile-Picture.png" />
    <!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-W85H23R');</script>
<!-- End Google Tag Manager -->



<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-W85H23R"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

<script type="text/javascript">
    jQuery(document).ready(function($) {
    $(".on-scroll").click(function(event){
        event.preventDefault();
        $('html,body').animate({scrollTop:$(this.hash).offset().top}, 500);
    });


});

function getCookie(name) {
  const value = `; ${document.cookie}`;
  const parts = value.split(`; ${name}=`);
  if (parts.length === 2) return parts.pop().split(';').shift();
}

var is_logged_in = getCookie('is_logged_in');



</script>
<?php
if(is_page('my-account')){
        ?>
        <script>

                if(is_logged_in!="Yes"){

                  console.log('Logged In = '+getCookie('is_logged_in'));
                window.location.href= '/';
        }

        </script>
        <?php
}

?>

 <?php
   /* #changed 11 */
        if(isset($_GET['logout']) && $_GET['logout']=='true'){
 ?>
 <script type = "text/javascript" >
   function preventBack(){window.history.forward();}
    setTimeout("preventBack()", 0);
    window.onunload=function(){null};
</script>

        <?php } ?>


<script type='text/javascript' src="jquery.creep.js"> </script>



<script>
    jQuery(document).ready(function(){


        jQuery('#user-selling').on('change', function() {

            var fullproperty = jQuery(this).val();

            if(fullproperty == "share"){
                jQuery('.full-property-input').addClass('share-container-enabled');
                }else{
                jQuery('.full-property-input').removeClass('share-container-enabled');
            }
        });

    });
</script>
<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '611123330010120');
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=611123330010120&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code -->
</head>

<body <?php body_class((!empty($subscription) && $subscription != 'subscriber') ? 'subscribed-user' : ''); ?>>
    <?php
    if (!is_404()) {
        include_once('parts/modals/subscription.php');
    }
    ?>
    <div class="modal video-pp fade default-modal-custom" id="staticBackdrop" data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12 col-12 d-flex">
                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="col-12">
                            <?php
                            $link = get_option('_how_its_works_button_link');
                            echo do_shortcode("[videojs_video url='{$link}'     loop='true'   poster='https://test.propertymates.io/wp-content/themes/co-owner/images/video-poster.jpg']");
                            ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="main-page w-100 d-block">
        <header class="w-100 fixed-top head-main small" id="header-sroll">
            <div class="head-section-two">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col col-sm-4 logo-area">
                            <?php
                            if (has_custom_logo()) {
                                the_custom_logo();
                            } else { ?>
                                <a  href="<?php echo home_url(); ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="123" height="27" viewBox="0 0 123 27">
                                        <g fill="none" fill-rule="evenodd">
                                            <g fill="#FFF">
                                                <g>
                                                    <text font-family="OpenSans-SemiBold, Open Sans" font-size="20" font-weight="500" transform="translate(-60 -27) translate(60 27)">
                                                        <tspan x="26" y="21">Co-Owner</tspan>
                                                    </text>
                                                    <path fill-rule="nonzero" d="M20 9.087V4.9c0-.507-.413-.9-.9-.9h-5.02c-.49 0-.9.395-.9.9v.355H8.35C7.455 4.444 6.305 4 5.087 4H.9c-.49 0-.9.395-.9.9v5.02c0 .508.413.9.9.9h.355v4.829C.444 16.546 0 17.695 0 18.913V23.1c0 .507.413.9.9.9h5.02c.238 0 .47-.096.636-.263.17-.17.264-.396.264-.637v-.355h4.829c.897.811 2.046 1.255 3.264 1.255H19.1c.489 0 .9-.395.9-.9v-5.02c0-.507-.413-.9-.9-.9h-.355V12.35C19.556 11.454 20 10.305 20 9.087zm-18.828.561V5.172h3.915c.985 0 1.912.382 2.61 1.078.107.11.255.177.42.177h5.063v2.594H8.117c-.196 0-.379.098-.487.26l-.56.84c-.214.321-.523.558-.877.683v-.57c0-.323-.263-.586-.586-.586H1.172zm5.062 8.16c-.323 0-.586.262-.586.585v4.435H1.172v-3.915c0-.988.385-1.917 1.083-2.616.11-.11.172-.259.172-.414V10.82h2.594v5.063c0 .196.098.379.26.487l.84.56c.321.214.558.523.683.877h-.57zm.536-1.854l-.577-.385v-3.553c.75-.152 1.418-.594 1.853-1.246l.385-.577h3.553c.152.75.594 1.418 1.246 1.853l.577.385v3.553c-.75.152-1.418.594-1.853 1.246l-.385.577H8.016c-.152-.75-.594-1.418-1.246-1.853zm12.058 2.398v4.476h-3.915c-.988 0-1.917-.385-2.616-1.083-.11-.11-.259-.172-.414-.172H6.82v-2.594h5.063c.196 0 .379-.098.487-.26l.56-.84c.214-.321.523-.558.877-.683v.57c0 .323.263.586.586.586h4.435zm-1.083-6.65c-.11.11-.172.26-.172.415v5.063h-2.594v-5.063c0-.196-.098-.379-.26-.487l-.84-.56c-.321-.214-.558-.523-.683-.877h.57c.323 0 .586-.263.586-.586V5.172h4.476v3.915c0 .988-.385 1.917-1.083 2.616z" transform="translate(-60 -27) translate(60 27)" />
                                                </g>
                                            </g>
                                        </g>
                                    </svg>
                                </a>
                            <?php
                            }
                            ?>
                        </div>

                        <div class="col col-sm-8 custom-top-nav">
                            <div class="navbar-expand-lg">
                                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                                    <span class="navbar-toggler-icon"></span>
                                </button>
                                <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
                                    <ul class="navbar-nav">
                                        <?php
                                        wp_nav_menu(array(
                                            'menu'              => 'primary',
                                            'theme_location'    => 'primary',
                                            'depth'             => 2,
                                            'container'            => false,
                                            'menu_class'        => 'navbar-nav me-auto nav-menu',
                                            'fallback_cb'       => '__return_false',
                                            'walker'             => new Co_owner_wp_nav_menu_walker()
                                        ));
                                        ?>
                                    </ul>
                                </div>
                                <div class="create-buttn-mobile"><a class="btn-create" href="https://propertymates.io/login/">Create an Account</a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="progress d-none">
                <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </header>
