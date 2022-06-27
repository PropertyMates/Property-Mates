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
class CoOwner_user_reports_tbl extends \WP_List_Table {

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
            'report_by' => 'Report By',
            'sender_user' => 'Message Sender',
            'receiver_user' => 'Message Receiver',
            'message' => 'Message',
            'created_at' => 'Reported Date',
        );
    }

    public function column_default($item, $column_name)
    {
        switch ($column_name){
            case 'id':
                return $item->id;
            case 'message':
                return $item->message;
            case 'created_at':
                return date('M d Y h:i: A',strtotime($item->created_at));
            default:
                return '-';

        }
    }

    public function column_report_by($item)
    {
        $report_by = get_user_full_name($item->user_id);

        $view = home_url(CO_OWNER_PERSON_DETAILS_PAGE."?id={$item->user_id}");
        $edit = admin_url("user-edit.php?user_id={$item->user_id}");

        $action = array(
            'view' => '<a target="_blank" href="'.$view.'">View</a>',
            'edit' => '<a target="_blank" href="'.$edit.'">Edit</a>',
        );
        return sprintf('%1$s %2$s',$report_by,$this->row_actions($action));
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
        if(empty($item->group_id)){
            $receiver_user = get_user_full_name($item->receiver_user);

            $view = home_url(CO_OWNER_PERSON_DETAILS_PAGE."?id={$item->receiver_user}");
            $edit = admin_url("user-edit.php?user_id={$item->receiver_user}");

            $action = array(
                'view' => '<a target="_blank" href="'.$view.'">View</a>',
                'edit' => '<a target="_blank" href="'.$edit.'">Edit</a>',
            );
            return sprintf('%1$s %2$s',$receiver_user,$this->row_actions($action));
        } else {
            $group = CoOwner_Groups::find(array('id'=>$item->group_id));
            if($group){
                return "<strong>Is POOL :-</strong> {$group->name}";
            } else {
                return '-';
            }
        }
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

        global $wpdb;
        $table1 = $wpdb->prefix.CO_OWNER_REPORTS_TABLE;
        $table2 = $wpdb->prefix.CO_OWNER_CONVERSATION_TABLE;

        $column = $for_pagination_count_total ? 'count(*) as count' : 't1.*,t2.sender_user,t2.receiver_user,t2.group_id,t2.message';

        $join = " LEFT JOIN {$table2} as t2 ON t1.message_id = t2.id";
        $sql = "SELECT {$column} FROM {$table1} as t1 $join";

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
        echo "<h1 class='wp-heading-inline'>Chat Abuse</h1>";
    }
}


function CoOwner_user_reports_tbl_display_table()
{
    $wp_list_table = new CoOwner_user_reports_tbl();
    $wp_list_table->prepare_items();
    ?><div class='wrap'>
        <?php $wp_list_table->print_page_table_headers(); ?>
        <hr class="wp-header-end">
        <div><?php $wp_list_table->display(); ?>
        </div>
    <?php
    echo "</div>";
}




CoOwner_user_reports_tbl_display_table();
