<?php include 'head.php'; ?>

<table style="text-align: center;width: 100%;">
    <tr>
       <td>
           <img src="<?php echo CO_OWNER_THEME_DIR_URI."images/fingerprint.png"; ?>" style="height: 150px;width: 150px;margin: 50px 0px;">
       </td>
    </tr>
    <tr>
        <td>
            <h1 style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; color: #3d4852; font-size: 25px; font-weight: bold; margin-top: 0; text-align: center;">
                Hello!
            </h1>
            <p style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; font-size: 20px; line-height: 1.5em; margin-top: 0; text-align: center;margin-bottom: 50px;">
                <?php echo $message; ?>
            </p>
        </td>
    </tr>
</table>


<p style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: center;">
    Thanks,<br>
    <?php echo get_bloginfo('name'); ?>
</p>
<?php include 'footer.php'; ?>

