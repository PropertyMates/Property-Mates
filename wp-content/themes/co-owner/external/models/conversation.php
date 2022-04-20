<?php

class CoOwner_Conversation extends CoOwner_Model
{
    const PER_PAGE = 5;

    public static function send_message(
        $sender,
        $receiver,
        $message,
        $is_group,
        $calculated_price = 0,
        $interested_in = 0,
        $is_request = 0,
        $property_id = null,
        $group_id = null
    )
    {
        $data = array(
            'sender_user' => (int) $sender,
            'receiver_user' => ($group_id ? $receiver : (!$is_group ? (int) $receiver : null)),
            'group_id' => ($group_id ? $group_id : ($is_group ? (int) $receiver : null)),
            'is_group' => (int) $is_group,
            'message' => $message ? $message : null,
            'calculated_price' => $calculated_price ? $calculated_price : 0,
            'interested_in' => $interested_in ? $interested_in : 0,
            'is_request' => $is_request,
            'property_id' => $property_id,
            'created_at' => wp_date('Y-m-d H:i:s')
        );
        global $wpdb;
        $table = $wpdb->prefix.CO_OWNER_CONVERSATION_TABLE;
        $wpdb->insert($table,$data);
        return $wpdb->insert_id;
    }

    public static function get_conversations($sender,$receiver,$is_group = false,$page = 1,$is_single = false,$after_created = null)
    {
        global $wpdb;
        $table =  $wpdb->prefix.CO_OWNER_CONVERSATION_TABLE;
        $table2 =  $wpdb->prefix.CO_OWNER_CONNECTIONS_TABLE;
        $users_table = $wpdb->prefix.'users';

        $columns = array(
            "chat.*",
            "u.display_name as display_name",
        );

        $where = "";
        $join = "";

        $receiver_column = $is_group ? 'group_id' : 'receiver_user';

        if(!$is_group){
            $where.= " WHERE ((chat.sender_user = {$sender} AND chat.receiver_user = {$receiver})";
            $where.= " OR (chat.sender_user = {$receiver} AND chat.receiver_user = {$sender}))";
            $where.= " AND chat.group_id is NULL";
        } else {
            $where.= " WHERE (chat.group_id = {$receiver})";
        }

        $join .= " LEFT JOIN {$users_table} AS u ON u.ID = chat.sender_user";

        $select_columns = implode(',',$columns);

        $userId = get_current_user_id();
        $clear_chat_user = $is_group ? $receiver : ($userId == $sender ? $receiver : $sender);

        $clear_chat_key = $is_group ? '_user_clear_chat_group_' : '_user_clear_chat_with_';
        $clear_chat = get_user_meta($userId,"{$clear_chat_key}{$clear_chat_user}",true);
        if(!empty($clear_chat)){
            $where .= " AND chat.created_at > '$clear_chat'";
        }

        if($after_created){
            $where .= " AND chat.created_at >= '$after_created'";
        }

        $query = "SELECT {$select_columns} FROM {$table} chat {$join} {$where} ORDER BY chat.id DESC";

        if($is_single){
            $message = $wpdb->get_row($query);
            if($message){
                $message->display_name = get_user_full_name($message->sender_user);
            }
            return $message;
        }

        $total_messages = $wpdb->get_results($query);
        $max_page = ceil (count($total_messages) / self::PER_PAGE);

        $limit = self::PER_PAGE;
        $offset = ($page - 1 ) * $limit;
        $query .= " LIMIT {$limit} OFFSET {$offset}";

        return (object) array(
            'messages' => $wpdb->get_results($query),
            'max_page' => $max_page,
            $where
        );
    }

    public static function get_interested_properties($sender_user,$receiver_user)
    {
        global $wpdb;
        $table = $wpdb->prefix.CO_OWNER_CONVERSATION_TABLE;
        $sql = "SELECT id,sender_user,receiver_user,is_request,property_id FROM {$table}";
        $sql .= " WHERE ((sender_user = {$sender_user} AND receiver_user = {$receiver_user})";
        $sql .= " OR (receiver_user = {$sender_user} AND sender_user = {$receiver_user}))";
        $sql .= " AND is_request = 1";
        $sql .= " AND property_id IS NOT NULL ORDER BY id DESC";
        $result = $wpdb->get_results($sql);
        $response = array();
        foreach ($result as $message) {
            $property = new stdClass();
            $property->id = $message->property_id;
            $property->address = get_property_full_address($message->property_id);
            $response[] = $property;
        }
        return $response;
    }
}


class CoOwner_Conversation_Files extends CoOwner_Model
{

    public static function count($array = array())
    {
        global $wpdb;
        $table = $wpdb->prefix . CO_OWNER_CONVERSATION_FILES_TABLE;
        $sql = self::make_query_from_array($table,$array);
        $result = $wpdb->get_row($sql);
        return $result->count;
    }

    public static function create($array)
    {
        global $wpdb;
        $table = $wpdb->prefix.CO_OWNER_CONVERSATION_FILES_TABLE;
        $array['created_at'] = wp_date('Y-m-d H:i:s');
        return $wpdb->insert($table,$array);
    }

    public static function get_files($where = array(),$is_single = false,$pagination = false,$page = 1){
        global $wpdb;
        $table = $wpdb->prefix.CO_OWNER_CONVERSATION_FILES_TABLE;

        if(count($where) == 0){
            $where = array('is_link' => 1);
        }

        $sql = CoOwner_Conversation_Files::make_query_from_array($table,$where,array('*'));
        $sql.= " ORDER BY {$table}.id DESC";
        if($is_single){
            $response = $wpdb->get_row($sql);
            if($response){
                if($response->is_link == 0){
                    $response->file = (object) unserialize($response->file);
                    $response->file->name = basename($response->file->url);
                }
            }
        } else {
            if($pagination && $page > 0){
                $limit = 5;
                $offset = ($page - 1 ) * $limit;
                $sql .= " LIMIT {$limit} OFFSET {$offset}";
            }
            $response = $wpdb->get_results($sql);
            foreach ($response as $key => $file){
                if($file->is_link == 0) {
                    $response[$key]->file = (object)unserialize($file->file);
                    $response[$key]->file->name = basename($file->file->url);
                }
            }
        }
        return $response;
    }

    public static function download_file($id){
        $result  = self::get_files(array('id'=>$id),true);
        if (file_exists($result->file->file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($result->file->file).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($result->file->file));
            readfile($result->file->file);
            exit;
        } else {
            wp_redirect(home_url());
            die;
        }
    }

}




