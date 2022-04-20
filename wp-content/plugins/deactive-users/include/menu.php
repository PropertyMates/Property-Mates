<?php 
if(is_admin())
{
    new Deactive_User_Table();
}

/**
 * Paulund_Wp_List_Table class will create the page to load the table
 */
class Deactive_User_Table
{
    /**
     * Constructor will create the menu item
     */
    public function __construct()
    {
        add_action( 'admin_menu', array($this, 'add_menu_list_table_page' ));
    }

    /**
     * Menu item will allow us to load the page to display the table
     */
    public function add_menu_list_table_page()
    {
        add_menu_page( 'Deleted Users List', 'Deleted Users', 'manage_options', 'user_table.php', array($this, 'deactive_user_page') );
    }

    /**
     * Display the list table page
     *
     * @return Void
     */
    public function deactive_user_page()
    {
        $deactiveUsersObj = new User_List_Table();
        $deactiveUsersObj->prepare_items();
        ?>
            <div class="wrap">
                <div id="icon-users" class="icon32"></div>
                <h2>Deleted Users</h2>
                <?php $deactiveUsersObj->display(); ?>
            </div>
        <?php
    }
}
?>