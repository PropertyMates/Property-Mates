<?php
// WP_List_Table is not loaded automatically so we need to load it in our application
if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * Create a new table class that will extend the WP_List_Table
 */
class User_List_Table extends WP_List_Table
{
    /**
     * Prepare the items for the table to process
     *
     * @return Void
     */
    public function prepare_items()
    {
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();

        $data = $this->table_data();
        usort( $data, array( &$this, 'sort_data' ) );

        $perPage = 2;
        $currentPage = $this->get_pagenum();
        $totalItems = count($data);

        $this->set_pagination_args( array(
            'total_items' => $totalItems,
            'per_page'    => $perPage
        ) );

        $data = array_slice($data,(($currentPage-1)*$perPage),$perPage);

        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $data;
    }

    /**
     * Override the parent columns method. Defines the columns to use in your listing table
     *
     * @return Array
     */
    public function get_columns()
    {
        $columns = array(
            'id'          => 'ID',
            'name'       => 'Name',
            'email' => 'Email',
            'doj'        => 'Date of join',
            'dod'    => 'Date of Deactive',
            'action'    => 'Action',
        );

        return $columns;
    }

    /**
     * Define which columns are hidden
     *
     * @return Array
     */
    public function get_hidden_columns()
    {
        return array();
    }

    /**
     * Define the sortable columns
     *
     * @return Array
     */
    public function get_sortable_columns()
    {
        return array('name' => array('name', false));
    }

    /**
     * Get the table data
     *
     * @return Array
     */
    private function table_data()
    {
        $data = array();
		global $wpdb;
		$db_table_name = $wpdb->prefix . 'deactive_users';  // table name
		$db_table_meta =  $wpdb->prefix . 'deactive_usersmeta';  // table name
		
		$users = $wpdb->get_results( "SELECT * FROM $db_table_name  ");
		//$userMeta = $wpdb->get_results( "SELECT * FROM $db_table_meta where user_id='$user_id' ");		
		if($users){
			foreach($users as $udata){
		           $deletedDate =get_deactive_usermeta($udata->ID,'deleted_date');
		           $is_facebook =get_deactive_usermeta($udata->ID,'_user_facebook_id');
		           $is_google =get_deactive_usermeta($udata->ID,'_user_google_id');
		           $is_insta =get_deactive_usermeta($udata->ID,'_user_instagram_id');
				   $email=$udata->user_email;
				   if(!$udata->user_email){
					  $email= '';
					   if($is_facebook){ $email= 'Facebook';  }	
					   if($is_google){ $email= 'Google';  }	
					   if($is_insta){ $email= 'Instagram';  }	
				   }
				   $dod = !empty($deletedDate) ? date('Y-m-d' , strtotime($deletedDate)) : false;
				
				$data[]= array(
                    'id'          => $udata->ID,
                    'name'       =>  $udata->display_name,
                    'email' =>      $email,
                    'doj'        => $udata->user_registered,
                    'dod'    => $dod,
					'action' =>'<a href="javascript:void(0);" class="delete_deactive_user" data-id="'.$udata->ID.'">Delete</a>'
                    );

		
			}
		}		


        

        return $data;
    }

    /**
     * Define what data to show on each column of the table
     *
     * @param  Array $item        Data
     * @param  String $column_name - Current column name
     *
     * @return Mixed
     */
    public function column_default( $item, $column_name )
    {
        switch( $column_name ) {
            case 'id':
            case 'name':
            case 'email':
            case 'doj':
            case 'dod':
            case 'action':
                return $item[ $column_name ];

            default:
                return print_r( $item, true ) ;
        }
    }

    /**
     * Allows you to sort the data by the variables set in the $_GET
     *
     * @return Mixed
     */
    private function sort_data( $a, $b )
    {
        // Set defaults
        $orderby = 'name';
        $order = 'asc';

        // If orderby is set, use this as the sort column
        if(!empty($_GET['orderby']))
        {
            $orderby = $_GET['orderby'];
        }

        // If order is set use this as the order
        if(!empty($_GET['order']))
        {
            $order = $_GET['order'];
        }


        $result = strcmp( $a[$orderby], $b[$orderby] );

        if($order === 'asc')
        {
            return $result;
        }

        return -$result;
    }
}


?>