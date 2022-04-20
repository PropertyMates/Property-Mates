<?php include 'head.php'; ?>

<table style="text-align: center;width: 100%;">
    <tr>
        <td>
            <div style="
                width: 200px;
                height: 200px;
                border-radius: 20px;
                box-shadow: 0 0 2px 2px rgb(0 0 0 / 18%);
                border: 4px solid #fff;
                margin-bottom: 16px;
                margin-left: auto;
                margin-right: auto;"
                >
                <img src="<?php echo get_avatar_url($user->ID); ?>" style="
                    max-width: inherit;
                    height: 100%;
                    width: 100%;
                    border-radius: 20px;
                ">
                <?php

                if(get_user_shield_status($user->ID)){
                    echo "<div style='margin-top: -10px;' >";
                    echo $status == 1 ? co_owner_get_svg('big_shield') : null;
                    echo "</div>";
                }
                ?>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <h1 style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; color: #3d4852; font-size: 30px; font-weight: bold; margin-top: 0; text-align: center;">
                <?php echo $status == 1 ? 'Congratulations!' : 'Oops...'; ?>
            </h1>
            <h3 style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; color: #3d4852; font-size: 23px; font-weight: bold; margin-top: 0; text-align: center;">
                Hi <?php echo ucfirst($user->first_name); ?>
            </h3>
            <p style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; font-size: 20px; line-height: 1.5em; margin-top: 0; text-align: center;">
                <?php if($status == 1): ?>
                    Your document has been approved and you are listed as a verified and trusted user. Your profile will receive a golden shield. <?php echo co_owner_get_svg('shield'); ?>
                <?php else: ?>
                    The document you submitted has been rejected. Please refer to the below details and try resubmitting it.
                <?php endif; ?>
            </p>
            <p style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; font-size: 20px; line-height: 1.5em; margin-top: 0; text-align: center;padding-top: 10px;">
                <?php if($status != 1): ?>
                    <strong>Reason:-</strong>
                    <?php echo $reason; ?>
                <?php endif; ?>
            </p>
        </td>
    </tr>
    <tr>
        <td>
            <table class="action" align="center" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%; margin: 30px auto; padding: 0; text-align: center; width: 100%;">
                <tbody>
                    <tr>
                        <td align="center" style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative;">
                            <table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative;">
                                <tbody>
                                    <tr>
                                        <td align="center" style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative;">
                                            <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative;">
                                                <tbody>
                                                    <tr>
                                                        <td style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative;">
                                                            <a target="_blank" rel="noopener noreferrer" href="<?php echo home_url(CO_OWNER_PERSON_DETAILS_PAGE."?id={$user->ID}"); ?>" class="button button-primary" style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; -webkit-text-size-adjust: none; border-radius: 4px; color: #fff; display: inline-block; overflow: hidden; text-decoration: none; background-color: #2d3748; border-bottom: 8px solid #2d3748; border-left: 18px solid #2d3748; border-right: 18px solid #2d3748; border-top: 8px solid #2d3748;">
                                                                View Profile
                                                            </a>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tr>
</table>


<p style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: center;">
    Best Regards,<br>
    <?php echo get_bloginfo('name'); ?>
</p>
<?php include 'footer.php'; ?>

