            <footer class="ftr-main">
                <div class="ftr-section-one">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-5 col-sm-12 col-xs-12 footer-frst">
                                <div class="w-100 d-block ftr-section">
                                    <?php
                                        if(is_active_sidebar('footer_menu_1')) {
                                            dynamic_sidebar('footer_menu_1');
                                        }
                                    ?>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-12 col-xs-12 footer-sec">
                                <div class="row">
                                    <div class="col-sm-6 col-md-6 d-block ftr-section">
                                        <?php
                                            if(is_active_sidebar('footer_menu_2')) {
                                                dynamic_sidebar('footer_menu_2');
                                            }
                                        ?>
                                    </div>
                                    <div class="col-sm-6 col-md-6 d-block ftr-section p-xl-0">
                                        <?php
                                            if(is_active_sidebar('footer_menu_3')) {
                                                dynamic_sidebar('footer_menu_3');
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-12 col-xs-12 ftr-last">
                                <div class="w-100 d-block ftr-section">
                                    <h5>Connect with us</h5>
                                    <div class="w-100 d-block ftr-social text-start">
                                    <?php
                                        if(get_option('_crb_facebook_account')) {
                                            echo '<a title="Like us on Facebook" target="_blanck" href="'.get_option('_crb_facebook_account').'">';
                                            echo co_owner_get_svg('small_facebook');
                                            echo '</a>';
                                        }
                                        if(get_option('_crb_instagram_account')) {
                                            echo '<a title="Follow us on Instagram" target="_blanck" href="'.get_option('_crb_instagram_account').'">';
                                            echo co_owner_get_svg('small_instagram');
                                            echo '</a>';
                                        }
                                        if(get_option('_crb_linkedin_account')) {
                                            echo '<a title="Follow us on Linkedin" target="_blanck" href="'.get_option('_crb_linkedin_account').'">';
                                            echo co_owner_get_svg('small_linkedin');
                                            echo '</a>';
                                        }
                                        if(get_option('_crb_twitter_account')) {
                                            echo '<a title="Follow us on Twitter" target="_blanck" href="'.get_option('_crb_twitter_account').'">';
                                            echo co_owner_get_svg('small_twitter');
                                            echo '</a>';
                                        }
										 
                                    ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="c-right-main ftr-cnt-main text-center widget_text">
                    <div class="textwidget">
                        <p>
                            <?php echo get_option('_copyright_disclaimer'); ?>
                        </p>
                    </div>
                </div>
            </footer>
            </div>
        <?php wp_footer(); ?>
    </body>
</html>
