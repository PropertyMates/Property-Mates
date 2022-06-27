<?php

class CoOwner_Connections extends CoOwner_Model {

    protected $table;

    public function __construct()
    {
        global $wpdb;
        $table = $wpdb->prefix.CO_OWNER_CONNECTIONS_TABLE;
        $this->table = $table;
    }

    public static function count($array){
        global $wpdb;
        $table = $wpdb->prefix.CO_OWNER_CONNECTIONS_TABLE;
        $sql = self::make_query_from_array($table,$array);
        $result = $wpdb->get_row($sql);
        return (int) $result->count;
    }

    public static function create($array)
    {
        global $wpdb;
        $table = $wpdb->prefix.CO_OWNER_CONNECTIONS_TABLE;
        $array['created_at'] = wp_date('Y-m-d H:i:s');
        $array['updated_at'] = wp_date('Y-m-d H:i:s');
        $wpdb->insert($table,$array);
        return $wpdb->insert_id;
    }

    public static function connection_request($where = array(),$is_single = false)
    {
        global $wpdb;
        $table = $wpdb->prefix.CO_OWNER_CONNECTIONS_TABLE;
        $users_table = $wpdb->prefix.'users';
        $group_table = $wpdb->prefix.CO_OWNER_GROUP_TABLE;

        $columns = array(
            $table.'.*',
            'r_user.display_name as receiver_user_name',
            's_user.display_name as sender_user_name'
        );

        $join = "LEFT JOIN $users_table AS r_user ON r_user.ID = $table.receiver_user ";
        $join.= "LEFT JOIN $users_table AS s_user ON s_user.ID = $table.sender_user ";


        $sql = self::make_query_from_array($table,$where,$columns,$join);
        $result = $is_single ? $wpdb->get_row($sql) : $wpdb->get_results($sql);
        return $result;
    }

    public static function get_connected_users($user_id,$limit = null)
    {
        global $wpdb;
        $user_table = $wpdb->prefix.'users';
        $user_meta = $wpdb->usermeta;
        $connection_table = $wpdb->prefix.CO_OWNER_CONNECTIONS_TABLE;

        $columns = 'con.*';
        $columns.= ',r_user.display_name as receiver_user_name';
        $columns.= ',r_user.user_email as receiver_user_email';
        $columns.= ',s_user.display_name as sender_user_name';
        $columns.= ',s_user.user_email as sender_user_email';


        $join = "LEFT JOIN $user_table AS r_user ON r_user.ID = con.receiver_user ";
        $join.= "LEFT JOIN $user_table AS s_user ON s_user.ID = con.sender_user ";

        $sql = "SELECT $columns
                FROM {$connection_table} con {$join}
                WHERE (con.sender_user = {$user_id} OR con.receiver_user = {$user_id})
                AND con.status = 1  
                AND con.is_group = 0 order by con.id desc";
				
				
        if($limit){
            $sql .= " LIMIT {$limit}";
        }

        return $limit == 1 ? $wpdb->get_row($sql) : $wpdb->get_results($sql);
    }

    public static function get_chat_with_connection($id,$is_group,$without_data = false)
    {
        global $wpdb;
        $auth_user = get_current_user_id();

        $c_table = $wpdb->prefix.CO_OWNER_CONNECTIONS_TABLE;
        $g_table = $wpdb->prefix.CO_OWNER_GROUP_TABLE;
        $u_table = $wpdb->prefix.'users';

        $join = "";
        $where = "";

        if($is_group){
            $table = $g_table;
            //$p_table = $wpdb->prefix.'posts';
            $columns = array('t1.id,t1.name,t1.user_id,t1.property_id,u.display_name,u.ID as user_id','t2.status','t1.status as group_status');
            $join   .= " LEFT JOIN {$c_table} AS t2 ON t1.id = t2.group_id";
            $join   .= " LEFT JOIN {$u_table} AS u ON u.ID = t1.user_id";
            //$join   .= " LEFT JOIN {$p_table} AS p ON p.ID = t1.property_id";
            $where  .= " WHERE ((t2.sender_user = {$auth_user} OR t2.receiver_user = {$auth_user}) AND t2.is_group = 1 AND t2.group_id = {$id} AND t2.status = 1)";
            $where  .= " OR (t1.user_id = {$auth_user} AND t1.id = {$id})";
            $columns = implode(',',$columns);

            $query = "SELECT {$columns} FROM {$table} t1 {$join} {$where}";
            $result = $wpdb->get_row($query);

            if($result){
                $result->joind_date = null;
            }

            if($result && $without_data == false){
                if($result->user_id != $auth_user){
                    $connection = CoOwner_Connections::get_connection_between_sender_receiver_and_group($auth_user,$id);
                    $result->joind_date = $connection ? $connection->created_at : null;
                }
                $group = CoOwner_Groups::find(array('id'=>$id));
                $result->members = array();
                if($group){
                    $result->members = get_property_total_members($group->property_id);
                }
            }
        } else {
            $columns = array('t1.id,t1.sender_user,t1.status,t1.property_id,t1.receiver_user,u.display_name as sender_user_name,u2.display_name as receiver_user_name,t1.created_at as joind_date');
            $join   .= " LEFT JOIN {$u_table} AS u ON u.ID = t1.sender_user";
            $join   .= " LEFT JOIN {$u_table} AS u2 ON u2.ID = t1.receiver_user";
            $where  .= " WHERE ((t1.sender_user = {$auth_user} And t1.receiver_user = {$id})";
            $where  .= " OR (t1.sender_user = {$id} And t1.receiver_user = {$auth_user}))";
            $where  .= " AND t1.status = 1 AND t1.group_id is null";
            $columns = implode(',',$columns);
            $query = "SELECT {$columns} FROM {$c_table} t1 {$join} {$where}";
            $result = $wpdb->get_row($query);
            if($result && $without_data == false){
                $u_id = ($auth_user == $result->sender_user) ? $result->receiver_user : $result->sender_user;
                $result->user_info = get_person_detail_by_id($u_id);
//                if($result->user_info->user_status != 1){
//                    $result = null;
//                }
            }
        }
        return $result;
    }

    public static function get_connections_by_property_id($id)
    {
        global $wpdb;
        $table = $wpdb->prefix.CO_OWNER_CONNECTIONS_TABLE;

        $sql = "SELECT t1.* FROM {$table} AS t1 WHERE t1.is_group = 1 AND t1.property_id = {$id}";
        $result = $wpdb->get_results($sql);

        return new CoOwner_ArrayResponse($result);
    }

    public static function get_connection_between_sender_receiver($sender,$receiver){
        global $wpdb;
        $table = $wpdb->prefix.CO_OWNER_CONNECTIONS_TABLE;
        $where = "WHERE ((t1.receiver_user = {$sender} AND t1.sender_user = {$receiver})";
        $where.= " OR (t1.receiver_user = {$receiver} AND t1.sender_user = {$sender}))";
        $where.= " AND t1.group_id is null";
        $sql = "SELECT t1.* FROM {$table} AS t1 {$where}";
        return $wpdb->get_row($sql);
    }

    public static function get_connection_between_sender_receiver_and_group($sender,$group_id){
        global $wpdb;
        $table = $wpdb->prefix.CO_OWNER_CONNECTIONS_TABLE;
        $where = "WHERE (t1.receiver_user = {$sender} OR t1.sender_user = {$sender})";
        $where.= " AND t1.group_id = {$group_id}";
        $sql = "SELECT t1.* FROM {$table} AS t1 {$where}";
        return $wpdb->get_row($sql);
    }

    public static function update_connection_status($id,$status)
    {
        $user = get_current_user_id();
        global $wpdb;
        $table = $wpdb->prefix.CO_OWNER_CONNECTIONS_TABLE;
        $status = (int) $status;
        $updated_at = wp_date('Y-m-d H:i:s');
        $query = "UPDATE $table SET status = {$status},updated_at = '{$updated_at}',updated_by = {$user} WHERE id = {$id}";
        return $wpdb->query($query);
    }

    public static function get_total_column_by_property($property_id,$column = 'interested_in')
    {
        global $wpdb;
        $group = CoOwner_Groups::find(array('property_id'=>$property_id));
        if($group) {
            $table = $wpdb->prefix.CO_OWNER_CONNECTIONS_TABLE;
            $query = "SELECT SUM({$column}) as total FROM `{$table}` WHERE property_id = {$property_id} AND group_id = {$group->id} AND status = 1";
            $result = $wpdb->get_row($query);
            return $result ? (int) $result->total : 0;
        }
        return 0;
    }

    public static function get_people_requested_for_the_same_pool($property_id,$user_id)
    {
        global $wpdb;
        $table = $wpdb->prefix.CO_OWNER_CONNECTIONS_TABLE;
        $users = $wpdb->users;

        $sql = "
            SELECT
            t1.id,t1.sender_user,t1.receiver_user,t1.property_id,t1.receiver_user,sender.user_email          
            FROM {$table} AS t1
            LEFT JOIN {$users} AS sender ON sender.ID = t1.sender_user   
            WHERE (t1.property_id = {$property_id} AND t1.receiver_user = {$user_id} AND t1.group_id is null AND t1.status = 1)             
            ";

        $result = $wpdb->get_results($sql);
        $members = array();

        $ids = array();
        foreach ($result as $member){
            $user = new stdClass();
            $ids[] = $user->id = $member->sender_user;
            $user->email = $member->user_email;
            $user->name = get_user_full_name($member->sender_user);
            $user->mobile = get_user_meta($member->sender_user,'_mobile',true);
            $user->profile = get_avatar_url($member->sender_user);
            $members[] = $user;
        }

        $table2 = $wpdb->prefix.CO_OWNER_CONVERSATION_TABLE;
        $sql2 = "
            SELECT
                t1.id,
                t1.sender_user,
                t1.receiver_user,
                t1.property_id,
                sender.user_email          
            FROM {$table2} AS t1
            LEFT JOIN {$users} AS sender ON sender.ID = t1.sender_user   
            WHERE (t1.property_id = {$property_id} AND t1.receiver_user = {$user_id} AND t1.is_request = 1)             
            ";
        $result2 = $wpdb->get_results($sql2);

        foreach ($result2 as $member){
            if(!in_array($member->sender_user,$ids)){
                $connection = self::get_connection_between_sender_receiver($member->sender_user,$member->receiver_user);
                if($connection && $connection->status == 1) {
                    $user = new stdClass();
                    $ids[] = $user->id = $member->sender_user;
                    $user->email = $member->user_email;
                    $user->name = get_user_full_name($member->sender_user);
                    $user->mobile = get_user_meta($member->sender_user, '_mobile', true);
                    $user->profile = get_avatar_url($member->sender_user);
                    $members[] = $user;
                }
            }
        }
        return $members;
    }

    public static function check_user_has_already_requested_in_property($sender_user,$receiver_user,$property_id,$check_in_count = true)
    {
        $connection_request = self::get_connection_between_sender_receiver($sender_user,$receiver_user);
        if($connection_request) {
            if($connection_request->property_id == $property_id){
                if(!$check_in_count){
                    $request = new stdClass();
                    $request->id = $connection_request->id;
                    $request->sender_user = $connection_request->sender_user;
                    $request->receiver_user = $connection_request->receiver_user;
                    $request->property_id = $connection_request->property_id;
                    $request->status = $connection_request->status;
                    return $request;
                }
                return true;
            }
            global $wpdb;
            $table = $wpdb->prefix.CO_OWNER_CONVERSATION_TABLE;
            $column = $check_in_count ? "count(*) as count" : "*";

            $sql = "SELECT {$column} FROM {$table}";
            $sql .= " WHERE ((sender_user = {$sender_user} AND receiver_user = {$receiver_user}) ";
            $sql .= " OR (receiver_user = {$sender_user} AND sender_user = {$receiver_user})) ";
            $sql .= " AND is_request = 1";
            $sql .= " AND property_id = {$property_id}";

            $result = $wpdb->get_row($sql);
            if(!$check_in_count && $result){
                $request = new stdClass();
                $request->id = $connection_request->id;
                $request->sender_user = $result->sender_user;
                $request->receiver_user = $result->receiver_user;
                $request->property_id = $result->property_id;
                $request->status = $connection_request->status;
                return $request;
            }
            return $check_in_count ? ($result->count > 0 ? true : false) : null;
        }
        return false;
    }

    public static function check_user_has_already_connected_in_property($sender_user,$receiver_user,$property_id)
    {
        $connection_request = self::get_connection_between_sender_receiver($sender_user,$receiver_user);
        if($connection_request) {
            if($connection_request->property_id == $property_id){
                return true;
            }
            global $wpdb;
            $table = $wpdb->prefix.CO_OWNER_CONVERSATION_TABLE;
            $sql = "SELECT count(id) as count FROM {$table}";
            $sql .= " WHERE ((sender_user = {$sender_user} AND receiver_user = {$receiver_user}) ";
            $sql .= " OR (receiver_user = {$sender_user} AND sender_user = {$receiver_user})) ";
            $sql .= " AND is_request = 1";
            $sql .= " AND property_id = {$property_id}";

            $result = $wpdb->get_row($sql);
            return $result->count > 0 ? true : false;
        }
        return false;
    }
}




