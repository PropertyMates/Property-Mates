<?php
/**
 * Adding WP List table class if it's not available.
 */
if ( ! class_exists( \WP_List_Table::class ) ) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * Class CoOwner_connection_request_tbl.
 *
 * @since 0.1.0
 * @package Admin_Table_Tut
 * @see WP_List_Table
 */
class CoOwner_connection_request_tbl extends \WP_List_Table {

    const POSTS_PER_PAGE = 10;

    public function __construct() {
        parent::__construct(
            array(
                'singular' => 'Draft',
                'plural'   => 'Drafts',
                'ajax'     => false,
            )
        );
    }

    public function get_columns()
    {
        return array(
            'id' => "#ID",
            'sender_user' => 'Sender user',
            'receiver_user' => 'Receiver user',
            'property_id' => 'Property',
            'address' => 'Address',
            'status' => 'status',
            'comment' => 'Comment',
            'interested_in' => 'Interested in',
            'calculated_price' => 'Calculated price',
            'created_at' => 'Requested Date',
        );
    }

    public function column_default($item, $column_name)
    {
        switch ($column_name){
            case 'id':
                return $item->id;
            break;
            case 'property_id':
                $property = get_post_field('post_title',$item->property_id);
                return "<a target='_blanck' href='".home_url(CO_OWNER_PROPERTY_DETAILS_PAGE)."?id={$item->property_id}'>{$property}</a>";
            break;
            case 'address':
                return get_property_full_address($item->property_id);
                break;
            case 'status':
                return ($item->status == 0 ? 'Pending' : ($item->status == 2 ? 'Rejected' : ( $item->status == 3 ? 'Blocked' : '' )));
                break;
            case 'interested_in':
                return $item->interested_in.'%';
                break;
            case 'calculated_price':
                return CO_OWNER_CURRENCY_SYMBOL.number_format($item->calculated_price);
                break;
            case 'created_at':
                return date('M d Y h:i: A',strtotime($item->created_at));
                break;

            default:
                return '-';

        }
    }

    public function column_sender_user($item)
    {
        $sender_user = get_user_full_name($item->sender_user);

        $view = home_url(CO_OWNER_PERSON_DETAILS_PAGE."?id={$item->sender_user}");
        $edit = admin_url("user-edit.php?user_id={$item->sender_user}");

        $action = array(
            'view' => '<a target="_blank" href="'.$view.'">View</a>',
            'edit' => '<a target="_blank" href="'.$edit.'">Edit</a>',
        );
        return sprintf('%1$s %2$s',$sender_user,$this->row_actions($action));
    }

    public function column_receiver_user($item)
    {
        $receiver_user = get_user_full_name($item->receiver_user);

        $view = home_url(CO_OWNER_PERSON_DETAILS_PAGE."?id={$item->receiver_user}");
        $edit = admin_url("user-edit.php?user_id={$item->receiver_user}");

        $action = array(
            'view' => '<a target="_blank" href="'.$view.'">View</a>',
            'edit' => '<a target="_blank" href="'.$edit.'">Edit</a>',
        );
        return sprintf('%1$s %2$s',$receiver_user,$this->row_actions($action));
    }

    public function column_property_id($item)
    {
        $property = get_post_field('post_title',$item->property_id);

        $view = home_url(CO_OWNER_PROPERTY_DETAILS_PAGE."?id={$item->property_id}");
        $edit = admin_url("post.php?post={$item->property_id}&action=edit");

        $action = array(
            'view' => '<a target="_blank" href="'.$view.'">View</a>',
            'edit' => '<a target="_blank" href="'.$edit.'">Edit</a>',
        );
        return sprintf('%1$s %2$s',$property,$this->row_actions($action));
    }

    public function get_hidden_columns()
    {
        return array();
    }

    public function get_sortable_columns()
    {
        return array(

        );
    }

    public function get_connection_requests($for_pagination_count_total = false)
    {
        $records_per_page = self::POSTS_PER_PAGE;
        $current_page = !empty($this->get_pagenum()) ? $this->get_pagenum() : 1;
        $offset = (($current_page-1)*$records_per_page);

        //$search = isset($_POST['s']) ? $_POST['s'] : null;

        global $wpdb;
        $table1 = $wpdb->prefix.CO_OWNER_CONNECTIONS_TABLE;

        $where = "is_group = 0 and status != 1";

        $column = $for_pagination_count_total ? 'count(*) as count' : '*';
        $sql = "SELECT {$column} FROM {$table1} WHERE {$where}";

        if(!$for_pagination_count_total){
            $sql .= " LIMIT ".$records_per_page." OFFSET ".$offset." ";
        }

        return $for_pagination_count_total ? $wpdb->get_row($sql) : $wpdb->get_results($sql);
    }

    public function prepare_items()
    {
        $result = $this->get_connection_requests(true);
        $this->items = $this->get_connection_requests();

        $this->set_pagination_args(array(
            "total_items" => $result->count,
            "per_page" => self::POSTS_PER_PAGE,
        ));
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($this->get_columns(),$hidden,$sortable);
    }

    public function print_page_table_headers()
    {
        echo "<h1 class='wp-heading-inline'>Connections Requests</h1>";
    }
}


function CoOwner_connection_request_tbl_display_table()
{
    $wp_list_table = new CoOwner_connection_request_tbl();
    $wp_list_table->prepare_items();
    ?><div class='wrap'>
        <?php $wp_list_table->print_page_table_headers(); ?>
        <hr class="wp-header-end">
        <div>
<!--            <form method="post" name="search_requests_form" action="--><?php //echo $_SERVER['PHP_SELF']."?page=".$_GET['page']; ?><!--">-->
<!--                --><?php //$wp_list_table->search_box("Search","search_requests_id"); ?>
<!--            </form>-->
            <?php
                $wp_list_table->display();
            ?>
        </div>
    <?php
    echo "</div>";
}




CoOwner_connection_request_tbl_display_table();
