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
    is_user_login_redirect();
    CoOwner::get_template_parts( array( 'parts/shared/html-header') );
?>
<div class="d-flex align-items-center">
    <div class="container login-main">
        <div class="top-logo text-center">
            <a href="<?php echo home_url('/') ?>">
                <svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40.58 40.58"><defs><style>.cls-1{fill:#272626;fill-rule:evenodd;}</style></defs><path id="Combined-Shape" class="cls-1" d="M40.79,34.46a4.59,4.59,0,1,1-6.49,0A4.59,4.59,0,0,1,40.79,34.46ZM9.54,34.3a4.59,4.59,0,1,1-6.49,0A4.59,4.59,0,0,1,9.54,34.3Zm27.63-9.69a9.37,9.37,0,0,1,.53,7.74,4.91,4.91,0,0,0-5.42,5.41,9.31,9.31,0,0,1-9.86-2.12l-2.35-2.35a9.29,9.29,0,0,1-2.59-8.15,3.54,3.54,0,0,0,.64.92l.08.08,6.67,6.62a4.22,4.22,0,0,0,.61.49,3.69,3.69,0,0,0,5.11-1.13Q34.85,25.48,37.17,24.61ZM18.86,17.48a3.54,3.54,0,0,0-.92.64l-.08.08-6.62,6.67a4.22,4.22,0,0,0-.49.61,3.69,3.69,0,0,0,1.13,5.11q6.64,4.26,7.51,6.58a9.37,9.37,0,0,1-7.74.53,4.91,4.91,0,0,0-5.41-5.42,9.31,9.31,0,0,1,2.12-9.86l2.35-2.35A9.29,9.29,0,0,1,18.86,17.48ZM32.35,6.3a4.91,4.91,0,0,0,5.41,5.42,9.31,9.31,0,0,1-2.12,9.86l-2.35,2.35a9.29,9.29,0,0,1-8.15,2.59,3.54,3.54,0,0,0,.92-.64l.08-.08,6.62-6.67a4.22,4.22,0,0,0,.49-.61,3.69,3.69,0,0,0-1.13-5.11q-6.64-4.26-7.51-6.58A9.37,9.37,0,0,1,32.35,6.3ZM21.58,8.36l2.35,2.35a9.29,9.29,0,0,1,2.59,8.15,3.54,3.54,0,0,0-.64-.92l-.08-.08-6.67-6.62a4.22,4.22,0,0,0-.61-.49,3.69,3.69,0,0,0-5.11,1.13q-4.26,6.64-6.58,7.51a9.37,9.37,0,0,1-.53-7.74,4.91,4.91,0,0,0,5.42-5.41A9.31,9.31,0,0,1,21.58,8.36ZM41,3.21a4.59,4.59,0,1,1-6.49,0A4.59,4.59,0,0,1,41,3.21ZM9.7,3.05a4.59,4.59,0,1,1-6.49,0A4.59,4.59,0,0,1,9.7,3.05Z" transform="translate(-1.71 -1.71)"/></svg>
            </a>
        </div>

        <h1 class="text-center">Welcome to Co-owner</h1>

        <h6 class="text-center">Reset Password with Email-ID</h6>

        <h4 class="text-center">Reset Password with Email-ID</h4>

        <div class="row justify-content-center">
            <div class="col col-xxl-7 col-xl-7 col-lg-7 col-md-10">
                <div class="card login-box">
                    <div class="card-body">
                        <form id="user-forgot-password" class="w-75 frm-custom pt-0" action="" method="post">
                            <div class="frm-title mt-1">
                                <h6>Reset Password with Email-ID</h6>
                            </div>

                            <div class="row" id="error-block" style="display:none;"></div>

                            <div class="row">
                                <div class="col col-sm-12 col-12 mb-3">
                                    <input name="email" type="text" class="form-control" id="email" placeholder="Email id">
                                </div>
                                <div class="col col-sm-12 col-12 text-center pt-40px">
                                    <input class="btn btn-orange btn-rounded w-180px" type="submit" value="Submit">
                                </div>
                            </div>
                        </form>
                        <div class="box-bottom-cnt text-center">
                            Already have an account? <a href="<?php echo home_url('login'); ?>">Sign In</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php
        if (is_active_sidebar('copyright_disclaimer')) {
            dynamic_sidebar( 'copyright_disclaimer' );
        }
        ?>
    </div>
</div>

<?php
    CoOwner::get_template_parts( array( 'parts/shared/html-footer') );
?>
