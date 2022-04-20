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

    const POSTS_PER_PAGE = 5;

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
            'ID' => "#ID",
            'first_name' => 'User',
            'email' => 'Email',
            'documents' => 'Documents',
            'status' => 'shield Status',
            'action' => 'Action',
        );
    }

    public function column_default($item, $column_name)
    {
        switch ($column_name){
            case 'ID':
                return $item->ID;
            break;
            case 'email':
                return "<a href='mailto:{$item->user_email}'>{$item->user_email}</a>";
                break;
            case 'documents':
                $documents = get_user_meta($item->ID,'_user_profile_documents',true);
                $html = "<ul>";
                foreach (is_array($documents) ? $documents : array() as $key => $document){
                    $file_name = wp_basename($document['url']);
                    $index = $key +1;
                    $link = "<a download='".$file_name."' class='button button-small' href='".$document['url']."'>Download</a>";
                    $html .= "<li>{$index} - {$file_name} {$link}</li>";
                }
                return $html."</ul>";
                break;
            case 'status':
                $status = get_user_meta($item->ID,'_document_shield_status',true);
                $html = "";
                if($status == 0){
                    $html = "<button class='button' disabled>Pending</button>";
                }elseif($status == 1){
                    $html = "<button class='button text-vr-center' >".co_owner_get_svg('shield')."Approved</button>";
                }elseif($status == 2){
                    $html = "<button class='button' disabled>Rejected</button>";
                    $reason = get_user_meta($item->ID,'_document_shield_reject_reason',true);
                    $html .= "<p>Reason :- {$reason}</p>";
                }
                return $html;
                break;
            case 'action':
                $status = get_user_meta($item->ID,'_document_shield_status',true);
                $html = "";
                $url = wp_nonce_url(admin_url("admin.php?page=user_shield_requests&action=user_document_status&user={$item->ID}"));
                if($status == 0){
                    $html = "<a href='{$url}&status=1' class='button button-small' >Approve</a>";
                    $html .= "<a href='#' data-user='{$item->ID}' class='reject-user-shield button button-small' >Reject</a>";
                }elseif($status == 1){
                    $html .= "<a href='#' data-user='{$item->ID}' class='reject-user-shield button button-small' >Reject</a>";
                }elseif($status == 2){
                    $html = "<a href='{$url}&status=1' class='button button-small' >Approve</a>";
                }

                return $html;
                break;
            default:
                return '-';

        }
    }

    public function column_first_name($item)
    {
        $status = get_user_meta($item->ID,'_document_shield_status',true);
            $html = "<span class='text-vr-center'>".($status == 1 ? co_owner_get_svg('shield') : "").get_user_full_name($item->ID)."</span>";


            $view = home_url(CO_OWNER_PERSON_DETAILS_PAGE."?id={$item->ID}");
            $edit = admin_url("user-edit.php?user_id={$item->ID}");

            $action = array(
                'view' => '<a target="_blank" href="'.$view.'">View</a>',
                'edit' => '<a target="_blank" href="'.$edit.'">Edit</a>',
            );
            return sprintf('%1$s %2$s',$html,$this->row_actions($action));

    }

    public function get_hidden_columns()
    {
        return array();
    }

    public function get_sortable_columns()
    {
        $s_columns = array (
            'ID' => [ 'ID', true],
        );
        return $s_columns;
    }

    public function get_connection_requests($for_pagination_count_total = false)
    {
        $records_per_page = self::POSTS_PER_PAGE;
        $current_page = !empty($this->get_pagenum()) ? $this->get_pagenum() : 1;
        $offset = (($current_page-1)*$records_per_page);
        $order_by = isset($_GET['orderby']) ? $_GET['orderby'] : 'ID';
        $order = isset($_GET['order']) ? $_GET['order'] : 'DESC';

        $meta_query = array();
        $meta_query[] = array(
            array('relation' => 'AND'),
            array(
                'key'     => '_user_profile_documents',
                'value'   => null,
                'compare' => '!=',
            )
        );

        $args = array(
            "orderby"          => $order_by,
            "order"            => $order,
            "meta_query"       => $meta_query,
        );

        if($for_pagination_count_total == false){
            $args["number"]   = $records_per_page;
            $args["offset"]   = $offset;
        }

        $results = get_users($args);
        return $for_pagination_count_total ? count($results) : $results;
    }

    public function prepare_items()
    {
        $result = $this->get_connection_requests(true);
        $this->items = $this->get_connection_requests();

        $this->set_pagination_args(array(
            "total_items" => $result,
            "per_page" => self::POSTS_PER_PAGE,
        ));
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($this->get_columns(),$hidden,$sortable);
    }

    public function print_page_table_headers()
    {
        echo "<h1 class='wp-heading-inline'>User shield Requests</h1>";
    }
}


function CoOwner_connection_request_tbl_display_table()
{
    add_thickbox();

    $wp_list_table = new CoOwner_connection_request_tbl();
    $wp_list_table->prepare_items();
    ?><div class='wrap'>
        <?php $wp_list_table->print_page_table_headers(); ?>
        <hr class="wp-header-end">
        <div>
            <?php $wp_list_table->display(); ?>

            <div id="reject-user-shield" title='Reject User Shield' >
                <form id="reject-user-shield-form" action="<?php echo admin_url('admin.php?page=user_shield_requests'); ?>" method="post">
                    <table style="width: 100%;">
                        <tr>
                            <td colspan="2"><label for="_document_shield_reject_reason">Reason</label></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <input type="hidden" name="co_owner_action" value="reject_user_shield">
                                <input type="hidden" name="user_id" value="" id="user_id">
                                <textarea id="_document_shield_reject_reason" style="width: 100%;" rows="7" class="form-control" name="_document_shield_reject_reason"></textarea>
                                <label for="_document_shield_reject_reason" id="_document_shield_reject_reason_error" class="is-invalid"></label>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    <?php
    echo "</div>";
}




CoOwner_connection_request_tbl_display_table();
