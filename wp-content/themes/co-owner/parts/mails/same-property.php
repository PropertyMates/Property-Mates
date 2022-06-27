<?php include 'head.php'; ?>
<h1 style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; color: #3d4852; font-size: 18px; font-weight: bold; margin-top: 0; text-align: left;">
    Hi <?php echo ucfirst($receiver_user->first_name); ?>
</h1>
<p style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">You have a new matching property.</p>
<p>Title : <?php echo $property->post_title; ?></p>
<p>Address : <?php echo $property->address; ?></p>
<table class="panel" width="100%" cellpadding="0" cellspacing="0" role="presentation"
       style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; border-left: #2d3748 solid 4px; margin: 21px 0;">
    <tr>
        <td class="panel-content"
            style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; background-color: #edf2f7; color: #718096; padding: 16px; color: #718096; padding: 15px;">
            <table width="100%" cellpadding="0" cellspacing="0" role="presentation"
                   style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative;text-align: left;">
                <tr style="margin-bottom: 10px;">
                    <th width="50%">Market Price</th>
                    <td width="50%"><?php echo get_updated_property_price($property->ID,true); ?></td>
                </tr>
            </table>
        </td>
    </tr>
    <?php if ($property->enable_pool): ?>
        <tr>
            <td class="panel-content"
                style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; background-color: #edf2f7; color: #718096; padding: 16px; color: #718096; padding: 0 15px 15px 15px;">
                <table width="100%" cellpadding="0" cellspacing="0" role="presentation"
                       style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative;text-align: left;">
                    <tr style="margin-bottom: 10px;">
                        <th width="50%">I Want To Sell</th>
                        <td width="50%"><?php echo $property->i_want_to_sell; ?> %</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td class="panel-content"
                style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; background-color: #edf2f7; color: #718096; padding: 16px; color: #718096; padding: 0 15px 15px 15px;">
                <table width="100%" cellpadding="0" cellspacing="0" role="presentation"
                       style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative;text-align: left;">
                    <tr style="margin-bottom: 10px;">
                        <th width="50%">Selling Price</th>
                        <td width="50%"><?php echo CO_OWNER_CURRENCY_SYMBOL; ?><?php echo number_format($property->calculated); ?></td>
                    </tr>
                </table>
            </td>
        </tr>
        <?php if ($property->available_share > 0): ?>
            <tr>
                <td class="panel-content"
                    style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; background-color: #edf2f7; color: #718096; padding: 16px; color: #718096; padding: 0 15px 15px 15px;">
                    <table width="100%" cellpadding="0" cellspacing="0" role="presentation"
                           style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative;text-align: left;">
                        <tr style="margin-bottom: 10px;">
                            <th width="50%">Available Share</th>
                            <td width="50%"><?php echo $property->available_share; ?> %</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="panel-content"
                    style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; background-color: #edf2f7; color: #718096; padding: 16px; color: #718096; padding: 0 15px 15px 15px;">
                    <table width="100%" cellpadding="0" cellspacing="0" role="presentation"
                           style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative;text-align: left;">
                        <tr style="margin-bottom: 10px;">
                            <th width="50%">Will Cost</th>
                            <td width="50%"><?php echo CO_OWNER_CURRENCY_SYMBOL . ' ' . number_format($property->available_price); ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
        <?php endif; ?>
    <?php endif; ?>
</table>
<table class="action" align="center" width="100%" cellpadding="0" cellspacing="0" role="presentation"
       style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%; margin: 30px auto; padding: 0; text-align: center; width: 100%;">
    <tr>
        <td align="center"
            style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative;">
            <table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation"
                   style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative;">
                <tr>
                    <td align="center"
                        style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative;">
                        <table border="0" cellpadding="0" cellspacing="0" role="presentation"
                               style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative;">
                            <tr>
                                <td style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative;">
                                    <a href="<?php echo $link; ?>" class="button button-primary" target="_blank"
                                       rel="noopener"
                                       style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; -webkit-text-size-adjust: none; border-radius: 4px; color: #fff; display: inline-block; overflow: hidden; text-decoration: none; background-color: #2d3748; border-bottom: 8px solid #2d3748; border-left: 18px solid #2d3748; border-right: 18px solid #2d3748; border-top: 8px solid #2d3748;">
                                        View Property
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
