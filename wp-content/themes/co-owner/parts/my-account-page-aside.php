<div class="my-listings-left Account-tgg h-100 py-40px">
	<span class="myaccount-toggle">Account</span>
	<div class="account-droplist">
    <ul>
        <li class="<?php echo is_page(CO_OWNER_MY_LISTINGS_PAGE) ? 'active' : null?>"><a href="<?php echo home_url('my-listings');?>">My Listings</a></li>
        <li class="<?php echo is_page(CO_OWNER_MY_ACCOUNT_VERIFICATION) ? 'active' : null?>"><a href="<?php echo home_url('my-account-verification');?>">My Account Verification</a></li>
        <li class="<?php echo is_page(CO_OWNER_MY_ACCOUNT_PAGE) ? 'active' : null?>"><a href="<?php echo home_url('my-account');?>">My Account</a></li>
        <li class="<?php echo is_page(CO_OWNER_MY_CONNECTIONS_PAGE) ? 'active' : null?>"><a href="<?php echo home_url('my-connections');?>">My Connections</a></li>
        <li class="<?php echo is_page(CO_OWNER_MY_NOTIFICATION_SETTINGS) ? 'active' : null?>"><a href="<?php echo home_url('my-notification-settings');?>">Notification Settings</a></li>
    </ul>
    <a class="d-block my-5 logoutme" href="<?php echo wp_logout_url(home_url('/?logout=true')); ?>">Logout</a>
		</div>
</div>

<script>
jQuery(document).ready(function(){
  jQuery(".myaccount-toggle").click(function(){
    jQuery(".Account-tgg .account-droplist").toggle();
  });
  
});
</script>