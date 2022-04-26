<?php
	$user = wp_get_current_user();
	$co_current_user = $user ? $user->ID : null;
	$is_admin = in_array('administrator', $user ? $user->roles : array());
	
	if ($co_current_user == 0) {
		$co_current_user = null;
	}
	$role = co_owner_get_user_field('s2member_access_role');
	$standard_plan = get_subscription_information('standard');
	$professional_plan = get_subscription_information('professional');
	
	$is_expire = false;
	if ($co_current_user) {
		if ($role == 's2member_level0') {
			$is_expire = user_plan_is_expire($co_current_user);
		}
	}
	
	$subscription_type = (isset($_GET['subscription_type']) && $co_current_user) ? $_GET['subscription_type'] : null;
	
	$is_register_page = (is_page('register') && isset($_GET['subscription']));
?>

<div class="modal fade plan-custom-modal" id="plan-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
			<?php 				
				$ref_url = wp_get_referer();				
				$gobackurl = wp_get_referer();				
				$results = explode('/', trim($ref_url,'/'));				
				if(count($results) > 0){					
					$last_word = $results[count($results) - 1];
				}
				if($last_word == "register" || $last_word = ""){
				$gobackurl = get_site_url()."/login";
				}
				if($gobackurl == ""){
				$gobackurl = get_site_url()."/login";
				}
			?>
            <div class="modal-body">
                <?php if (!is_page('register') || isset($_GET['subscription'])) { ?>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					<?php }else{ ?>
					<button type="button" onClick="location.href='<?php echo $gobackurl; ?>'" class="btn-backpage">Go Back</button>
				<?php } ?>
                <?php if ($role != 's2member_level2') : ?>
				<div class="plan-tabs">
					<ul class="nav nav-pills mb-2" id="pills-tab" role="tablist">
						<li class="nav-item" role="presentation">
							<button class="nav-link <?php echo $subscription_type == null ? 'active' : ''; ?>" id="pills-subscription-tab" data-bs-toggle="pill" data-bs-target="#pills-subscription" type="button" role="tab" aria-controls="pills-subscription" aria-selected="true">Subscription</button>
						</li>
					<li class="nav-item" role="presentation">
					<button class="nav-link <?php echo $subscription_type != null ? 'active' : ''; ?> d-none" id="pills-payments-tab" data-bs-toggle="pill" data-bs-target="#pills-payments" type="button" role="tab" aria-controls="pills-payments" aria-selected="false">Payments</button>
					</li>
					</ul>
					<div class="tab-content" id="pills-tabContent">
						<div class="tab-pane fade <?php echo ($subscription_type == null || $is_admin) ? 'show active' : ''; ?>" id="pills-subscription" role="tabpanel" aria-labelledby="pills-subscription-tab">
							<div class="plan-title-area text-center">
								<h3>Choose your plan</h3>
								<p>No commitment, cancel at any time.</p>
								<?php if ($is_expire) : ?>
								<p class="mt-3 text-orange">Your trial period is expired. Please purchase your subscription.</p>
								<?php endif; ?>
							</div>
							
							<div class="owl-carousel owl-theme property-one subscription-plans-co-owners">
								<?php if ($role != 's2member_level1') : ?>
								<div class="item">
									<div class="row">
										<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
											<div class="card plan-card standard h-100">
												<h5 class="card-header"><?php echo $standard_plan->name; ?></h5>
												<div class="card-body">
													<h4 class="text-center pt-2 pb-3">
														<?php echo CO_OWNER_CURRENCY_SYMBOL . $standard_plan->amount; ?><sub>/<?php echo $standard_plan->duration; ?></sub>
													</h4>
													<ul>
														<li>Browse unlimited properties</li>
														<li>Create up to <?php echo $standard_plan->create_property_upto; ?> Listings</li>
														<li>Access contact details of users</li>
														<li>Make unlimited connections</li>
														<li>Full use of Forum - read, reply, contact users & post new topics</li>
													</ul>
													<?php if (!$is_admin) : ?>
													<?php if (!$co_current_user && empty($role)) : ?>
													<div class="plan-bottom-main text-center pt-4">
														<a href="<?php echo $is_register_page ? '#' : home_url("register?subscription=trial"); ?>" class="btn btn-orange rounded-pill px-3 py-2 text-white trial-subscription">Start your 4 week free trial</a>
														<span class="text-uppercase d-block text-center pt-2">Or</span>
														<div class="d-block">
															<a href="<?php echo $is_register_page ? '#' : home_url("register?subscription=standard"); ?>" class="standard-subscription">Start membership today</a>
														</div>
													</div>
													<?php else : ?>
													<div class="plan-bottom-main text-center pt-4">
														<?php if (empty(get_user_meta($user->ID, 'user_touch_subscription', true))) : ?>
														<a href="<?php echo home_url("?co_owner_action=active_trial_subscription"); ?>" class="btn btn-orange rounded-pill px-3 py-2 text-white trial-subscription">Start your 4 week free trial</a>
														<span class="text-uppercase d-block text-center pt-2">Or</span>
														<div class="d-block">
															<a href="<?php echo home_url('?action=subscription&subscription_type=standard'); ?>" class="standard-subscription">Start membership today</a>
														</div>
														<?php else : ?>
														<a title="Get Plan" href="<?php echo home_url('?action=subscription&subscription_type=standard'); ?>" class="btn btn-orange rounded-pill px-3 py-2 text-white">Choose</a>
														<?php endif; ?>
													</div>
													<?php endif; ?>
													<?php endif; ?>
												</div>
											</div>
										</div>
									</div>
								</div>
								<?php endif; ?>
								<div class="item">
									<div class="row">
										<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
											<div class="card plan-card professional h-100">
												<h5 class="card-header"><?php echo $professional_plan->name; ?></h5>
												<div class="card-body">
													<h4 class="text-center pt-2 pb-3">
														<?php echo CO_OWNER_CURRENCY_SYMBOL . $professional_plan->amount; ?><sub>/<?php echo $professional_plan->duration; ?></sub>
													</h4>
													<ul>
														<li>Browse unlimited properties</li>
														<li>Create up to <?php echo $professional_plan->create_property_upto; ?> Listings</li>
														<li>Access contact details of users</li>
														<li>Make unlimited connections</li>
														<li>Full use of Forum - read, reply, contact users & post new topics</li>
													</ul>
													<?php if (!$is_admin) : ?>
													<?php if (!$co_current_user && empty($role)) : ?>
													<div class="plan-bottom-main text-center pt-4">
														<a  title="Get Plan"  href="<?php echo $is_register_page ? '#' : home_url("register?subscription=professional"); ?>" class="btn btn-blue rounded-pill px-3 py-2 text-white professional-subscription">Choose</a>
													</div>
													<?php else : ?>
													<div class="plan-bottom-main text-center pt-4">
														<a  title="Get Plan" href="<?php echo home_url('?action=subscription&subscription_type=professional'); ?>" class="btn btn-blue rounded-pill px-3 py-2 text-white">Choose</a>
													</div>
													<?php endif; ?>
													<?php endif; ?>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							
							
							<?php
							if (!$is_admin && $co_current_user && (!in_array($role, ['s2member_level0', 's2member_level1', 's2member_level2']) || $is_expire)) : ?>
							<div class="row">
								<div class="col col-md-12 text-center mt-5">
									<a href="<?php echo wp_logout_url(home_url('/')); ?>" class="btn btn-orange-bordered btn-rounded">Continue as Guest</a>
								</div>
							</div>
							<?php endif; ?>
						</div>
						<div class="tab-pane fade <?php echo ($subscription_type != null && !$is_admin) ? 'show active' : ''; ?>" id="pills-payments" role="tabpanel" aria-labelledby="pills-payments-tab">
							<div class="plan-title-area text-center">
								<h3>Payments</h3>
								<p>No commitments, cancel at any time.</p>
							</div>
							<div class="row px-30px">
								<?php if ($subscription_type == 'standard' && $role != 's2member_level1') : ?>
								<div class="col-sm-12 col-12 mb-3">
									<div class="card plan-card standard">
										<h5 class="card-header d-flex align-items-center">
											Standard
											<span class="ms-auto"><?php echo CO_OWNER_CURRENCY_SYMBOL . $standard_plan->amount; ?><sub>/<?php echo $standard_plan->duration; ?></sub></span>
										</h5>
										<div class="card-body">
											<div class="row">
												<div class="col-md-12 col-sm-12 col-12 mb-3 preview">
													<?php echo do_shortcode('[s2Member-Pro-Stripe-Form level="1" ccaps="" desc="$14.95 AUD / Monthly (recurring charge, for ongoing access)" cc="AUD" custom="' . ($_SERVER['HTTP_HOST']) . '" ta="0" tp="0" tt="M" ra="14.95" rp="1" rt="M" rr="1" coupon="" accept_coupons="0" default_country_code="AU" captcha="0" success="/?subscription=success" /]'); ?>
												</div>
											</div>
										</div>
									</div>
								</div>
								<?php elseif ($subscription_type == 'professional' || $role != 's2member_level2') : ?>
								<div class="col-sm-12 col-12">
									<div class="card plan-card professional">
										<h5 class="card-header d-flex align-items-center">
											Professional
											<span class="ms-auto">
												<?php echo CO_OWNER_CURRENCY_SYMBOL . $professional_plan->amount; ?><sub>/<?php echo $professional_plan->duration; ?></sub>
											</span>
										</h5>
										<div class="card-body">
											<div class="row">
												<div class="col-md-12 col-sm-12 col-12 mb-3 preview">
													<?php echo do_shortcode('[s2Member-Pro-Stripe-Form level="2" ccaps="" desc="$199.95 AUD / Monthly (recurring charge, for ongoing access)" cc="AUD" custom="' . ($_SERVER['HTTP_HOST']) . '" ta="0" tp="0" tt="M" ra="199.95" rp="1" rt="M" rr="1" coupon="" accept_coupons="0" default_country_code="AU" captcha="0" success="/?subscription=success" /]'); ?>
												</div>
											</div>
										</div>
									</div>
								</div>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
                <?php else : ?>
				<div class="plan-tabs">
					<div class="tab-content" id="pills-tabContent">
						<div class="tab-pane fade show active" id="pills-subscription" role="tabpanel" aria-labelledby="pills-subscription-tab">
							<h3 class="h3">You already have the highest subscription.</h3>
						</div>
					</div>
				</div>
                <?php endif; ?>
			</div>
		</div>
	</div>
</div>



<?php if (is_user_logged_in() && !$is_admin) : $current_subscription = get_user_subscription_level();  ?>
<?php if ($current_subscription && $plan = get_subscription_information($current_subscription)) :  ?>
<?php /* ?>
<div class="modal fade plan-custom-modal single-size" id="subscribed-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg">
		<div class="modal-content">
			<div class="modal-body">
				<!--<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>-->
				
				<div class="plan-tabs plan-single">
					<div class="tab-content" id="pills-tabContent">
						<div class="tab-pane fade show active" id="pills-subscription" role="tabpanel" aria-labelledby="pills-subscription-tab">
							<div class="row px-30px mt-3">
								<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
									<div class="card plan-card <?php echo in_array($plan->name, ['standard', 'trial']) ? 'standard' : 'professional'; ?> h-100">
										<h5 class="card-header text-start d-flex align-items-center">
											Plan: <?php echo $plan->name; ?>
											
											<?php if ($plan->slug != 'trial') : ?>
											<span class="ms-auto text-capitalize text-nowrap"><?php echo CO_OWNER_CURRENCY_SYMBOL . ' ' . $plan->amount; ?><sub>/<?php echo $plan->duration; ?></sub></span>
											<?php else : ?>
											<span class="ms-auto text-capitalize text-nowrap"><?php echo $plan->duration; ?></span>
											<?php endif; ?>
										</h5>
										<div class="card-body px-4">
											<h6 class="pt-2 pb-2">
												<?php if ($plan->slug == 'trial') : ?>
												Your free trial has been activated
												<?php elseif (isset($_GET['subscription']) && $_GET['subscription'] == 'success') : ?>
												You have successfully subscribed.
												<?php endif; ?>
												<span class="black-small pt-3 d-block">Here's what you get with Standard</span>
											</h6>
											<ul class="min-height-auto">
												<li>Browse unlimited properties</li>
												<li>Create up to <?php echo $plan->create_property_upto; ?> Listings</li>
												<li>Access contact details of users</li>
												<li>Make unlimited connections</li>
												<li>Full use of Forum - read, reply, contact users & post new topics</li>
											</ul>
											<?php if ($plan->slug == 'trial') : ?>
											<h6 class="pt-1 pb-1">
												<span class="black-small pt-3 d-block">Cancel any time. <span class="fw-400">We'll remind you 3 days before your trial ends.</span></span>
											</h6>
											<?php endif; ?>
											<div class="plan-bottom-main pt-4 bt-0">
												<a href="#" data-bs-dismiss="modal" aria-label="Close" class="btn btn-<?php echo in_array($plan->name, ['standard', 'trial']) ? 'orange' : 'blue'; ?> rounded-pill px-3 py-2 text-white">Continue</a>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php */ ?>
<?php endif; ?>
<?php endif; ?>