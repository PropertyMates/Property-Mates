<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * Please see /external/bootsrap-utilities.php for info on CoOwner::get_template_parts()
 *
 * @package 	WordPress
 * @subpackage 	Co-Owner
 * @autor 		TechXperts
 */
get_header();
?>

<div class="center-area">
    <div class="main-section bg-about">
        <div class="container">
            <div class="row align-items-lg-start align-items-sm-center">
                <div class="col-md-5 col-sm-12 about-cnt">
                    <h1>About Us</h1>
                    <h4><strong>Property Mates</strong> is Australia’s first digital platform that makes it easy to buy or sell a portion of a property – from a 1% stake to 100% ownership.</h4>
                    <div class="about-btn pb-3">
                        <a title="Watch Video" href="#" class="btn btn-rounded btn-grey px-4" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                <g fill="none" fill-rule="evenodd">
                                    <g fill="#FE7400" fill-rule="nonzero">
                                        <g>
                                            <g>
                                                <path d="M32 10c3.205 0 6.219 1.248 8.485 3.515C42.752 15.78 44 18.795 44 22c0 2.369-.703 4.672-2.033 6.662-.288.43-.87.546-1.3.258-.431-.288-.547-.87-.259-1.3 1.123-1.68 1.717-3.624 1.717-5.62 0-5.583-4.542-10.125-10.125-10.125S21.875 16.417 21.875 22 26.417 32.125 32 32.125c1.852 0 3.663-.504 5.238-1.458.442-.268 1.019-.127 1.287.316.269.443.127 1.02-.316 1.288C36.341 33.402 34.194 34 32 34c-3.205 0-6.219-1.248-8.485-3.515C21.248 28.22 20 25.205 20 22c0-3.205 1.248-6.219 3.515-8.485C25.78 11.248 28.795 10 32 10zm-3.111 7.094c.661-.38 1.45-.38 2.11.004l5.29 3.07c.66.382 1.055 1.068 1.055 1.832s-.394 1.45-1.054 1.833L31 26.902c-.331.192-.695.288-1.059.288-.36 0-.722-.095-1.052-.284-.664-.383-1.06-1.07-1.06-1.836v-6.14c0-.767.396-1.453 1.06-1.836zm1.053 1.592c-.047 0-.087.016-.117.033-.055.032-.121.094-.121.212v6.138c0 .118.066.18.12.212.054.03.138.055.235 0l5.29-3.07c.099-.058.12-.147.12-.211s-.021-.153-.12-.21l-5.29-3.07c-.042-.025-.082-.034-.117-.034z" transform="translate(-80 -378) translate(60 120) translate(0 248)"></path>
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                            <span class="ps-1">
                                <?php if($text = get_option('_how_its_works_button_title')): ?>
                                    <?php echo $text; ?>
                                <?php else: ?>
                                    How it works?
                                <?php endif; ?>
                            </span>
                        </a>
                    </div>
                </div>
                <div class="col-md-7 col-sm-12 about-top-thumb">
                    <img src="<?php echo CO_OWNER_THEME_DIR_URI; ?>images/about-banner.png" class="img-fluid" alt="">
                </div>
            </div>
        </div>
    </div>
    <div class="main-section bg-black w-100 d-block helping-cnt">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <h3>Helping smart investors buy property together</h3>
                    <p>Property prices are continuing to grow faster than incomes – putting property ownership out of reach of many Australians.
                        What if there was a way to make the property market more accessible – regardless of budget?</p>

                    <p>We believe the solution lies in collaborating with other like-minded property investors to buy and own property together, using
                        technology to enable the perfect match.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="main-section bg-social-about w-100 d-block">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <h2 class="text-center mb-0">Property Mates - Making property ownership accessible to everyone.</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="main-section bg-white steps-cnt-section w-100 d-block py-40px">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <h3>You’re just 3 steps away from co-owning the property that’s right for you.</h3>
                </div>

                <div class="col-md-4 col-sm-12">
                    <div class="icon-area text-center bg-grey px-2">
                        <img src="<?php echo CO_OWNER_THEME_DIR_URI; ?>images/icon-profile.png" class="img-fluid">
                    </div>
                    <h5 class="text-center icon-area-cnt">1. Create a profile</h5>
                </div>

                <div class="col-md-4 col-sm-12">
                    <div class="icon-area text-center bg-grey px-2">
                        <img src="<?php echo CO_OWNER_THEME_DIR_URI; ?>images/icon-locations.png" class="img-fluid">
                    </div>
                    <h5 class="text-center icon-area-cnt">2. Select your preferred locations
                        and budget</h5>
                </div>

                <div class="col-md-4 col-sm-12">
                    <div class="icon-area text-center bg-grey px-2">
                        <img src="<?php echo CO_OWNER_THEME_DIR_URI; ?>images/icon-connect.png" class="img-fluid">
                    </div>
                    <h5 class="text-center icon-area-cnt">3. Connect with others to purchase</h5>
                </div>
            </div>
        </div>
    </div>
    <div class="main-section bg-grey w-100 d-block py-40px">
        <div class="container">
            <div class="col-sm-12">
                <div class="own-cnt">
                    <h3>Do you own property in Australia?</h3>
                    <p>If yes, you can sell the entire property or just a percentage of it on Property Mates, allowing you to retain some ownership of the property in a way that suits your financial goals.</p>
                </div>
                <div class="own-cnt">
                    <h3>Less risk and more reward</h3>
                    <p>Our strict verification process means you’re only dealing with genuine buyers and sellers – all with the same goal of wanting a more flexible approach to property ownership. And when you’re ready to sell your portion, you can easily find the next owner on Property Mates.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="main-section bg-white w-100 d-block py-40px">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="d-flex align-items-center que-section">
                        <div class="pe-3">
                            <img src="<?php echo CO_OWNER_THEME_DIR_URI; ?>images/icon-email.png" class="img-fluid" alt="">
                        </div>
                        <h4 class="mb-0">Have a question?<a title="Ask us anything!" href="<?php echo home_url(CO_OWNER_CONTACT_US_PAGE); ?>"> Contact us</a></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
