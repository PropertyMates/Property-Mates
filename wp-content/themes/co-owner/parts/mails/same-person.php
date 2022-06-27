<?php include 'head.php'; ?>
<h1 style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; color: #3d4852; font-size: 18px; font-weight: bold; margin-top: 0; text-align: left;">
    Hi <?php echo ucfirst($receiver_user->first_name); ?>
</h1>
<p style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">
    You have a new matching profile for your listing <strong><?php echo $property->post_title; ?>.</strong>
</p>
<p style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">
    <?php echo get_property_full_address($property->ID); ?>
</p>
<table class="panel" width="100%" cellpadding="0" cellspacing="0" role="presentation">
    <tr>
        <td style="justify-content: center;display: flex;">
            <img src="<?php echo get_avatar_url($sender_user->ID); ?>" style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; max-width: 100%; border-radius: 50%; height: 130px;min-height:130px;width: 130px;padding: .25rem;background-color: #fff;border: 1px solid #dee2e6;">
        </td>
    </tr>
    <tr>
        <td style="justify-content: center;display: flex;">
            <p><?php echo $sender_title; ?></p>
        </td>
    </tr>
    <tr>
        <td>
            <table style="text-align: center;" width="100%">
                <tr>
                    <th width="50%">Email</th>
                    <th width="50%">Mobile</th>
                </tr>
                <tr>
                    <td width="50%"><?php echo $sender_user->user_email; ?></td>
                    <td width="50%"><?php echo get_user_meta($sender_user->ID, '_mobile', true); ?></td>
                </tr>


                <tr>
                    <th width="50%" style="padding-top: 15px;">Budget</th>
                    <th style="padding-top: 15px;">Preferred Locations</th>
                </tr>
                <tr>
                    <td width="50%"><?php echo price_range_show((get_user_meta($user_id, '_user_budget_range', true))); ?></td>
                    <td>
                        <?php
                        foreach (get_user_meta($sender_user->ID, '_user_preferred_location', true) as $key => $location) {
                            echo ($key == 0 ? '' : '<br> ') . $location;
                        } ?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<table class="action" align="center" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%; margin: 30px auto; padding: 0; text-align: center; width: 100%;">
    <tr>
        <td align="center" style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative;">
            <table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative;">
                <tr>
                    <td align="center" style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative;">
                        <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative;">
                            <tr>
                                <td style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative;">
                                    <a href="<?php echo home_url(CO_OWNER_PERSON_DETAILS_PAGE . "?id={$sender_user->ID}"); ?>" class="button button-primary" target="_blank" rel="noopener" style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; -webkit-text-size-adjust: none; border-radius: 4px; color: #fff; display: inline-block; overflow: hidden; text-decoration: none; background-color: #2d3748; border-bottom: 8px solid #2d3748; border-left: 18px solid #2d3748; border-right: 18px solid #2d3748; border-top: 8px solid #2d3748;">
                                        View Profile
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<p style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">
    Best Regards,<br>
    <?php echo get_bloginfo('name'); ?>
</p>
<?php include 'footer.php'; ?>