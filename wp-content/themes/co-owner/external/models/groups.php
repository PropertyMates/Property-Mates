<?php
class CoOwner_Groups  extends CoOwner_Model
{
    public static function get_count($array = array())
    {
        global $wpdb;
        $table = $wpdb->prefix . CO_OWNER_GROUP_TABLE;
        $sql = self::make_query_from_array($table,$array);
        $result = $wpdb->get_row($sql);
        return $result->count;
    }

    public static function create($array)
    {
        $result = self::get_count(array('property_id' => $array['property_id']));
        if($result == 0){
            global $wpdb;
            $table = $wpdb->prefix.CO_OWNER_GROUP_TABLE;
            $wpdb->insert($table,$array);
            return $wpdb->insert_id;
        }
        return true;
    }

    public static function find($array = array())
    {
        global $wpdb;
        $table = $wpdb->prefix . CO_OWNER_GROUP_TABLE;
        $sql = self::make_query_from_array($table,$array,array('*'));
        return $wpdb->get_row($sql);
    }
	
	public static function getConversationGroupTime($groud_id){
		/* When group true */
		global $wpdb;
		$sql="SELECT cv1.created_at, cv1.id
					   FROM  wp_co_owner_conversation cv1
					   WHERE ";
	   $sql.= "cv1.groud_id='$groud_id' ";

	  $sql.= " ORDER BY cv1.id DESC limit 1";
			// echo  $sql;
		return $wpdb->get_row($sql);
		
	}	

    public static function get_joined_groups($user_id = null)
    {
        global $wpdb;
        $table = $wpdb->prefix . CO_OWNER_GROUP_TABLE;
        $table2 = $wpdb->prefix . CO_OWNER_CONNECTIONS_TABLE;
        $co_owner_conversation = $wpdb->prefix.'co_owner_conversation';
        if($user_id == null){
            $user_id = get_current_user_id();
        }

        $total_members = "(select count(*) + 1 from {$table2} where group_id = u_g.id) as members_count";
        $sql = "
            SELECT u_g.*,
			{$total_members},
			(select t1.created_at from {$co_owner_conversation} t1 where t1.group_id=u_g.id order by t1.id desc limit 1) as message_date,
			(select t2.id from {$co_owner_conversation} t2 where t2.group_id=u_g.id order by t2.id desc limit 1) as message_id 
            FROM {$table} AS u_g
            LEFT JOIN {$table2} AS u_c ON u_c.group_id = u_g.id
			LEFT JOIN {$co_owner_conversation} as u_cv ON u_cv.group_id = u_g.id
            WHERE (u_g.user_id = {$user_id} OR (u_c.receiver_user = {$user_id} AND u_c.status = 1))
            GROUP BY u_g.id";
			
			//echo $sql;
		
        return new CoOwner_ArrayResponse($wpdb->get_results($sql)) ;
    }

    public static function get_group_members($property_id)
    {
        $group = CoOwner_Groups::find(array('property_id'=>$property_id));
        $properties_members = array();
        if(empty($group)){
            $admin_id = get_post($property_id)->post_author;
        } else {
            $admin_id = $group->user_id;
        }
        $user_info = get_person_detail_by_id($admin_id);
        if($user_info){
            $admin = (object)array(
                'id' => $user_info->ID,
                'display_name' => $user_info->first_name.' '.$user_info->last_name,
                'user_email' => $user_info->user_email,
                'interested_in' => get_property_available_share($property_id),
                'calculated_price' => get_property_available_price($property_id),
                'is_admin' => 1,
                'status' => 1,
                'mobile' => get_user_meta($user_info->ID, '_mobile', true),
                'group_id' => $group ? $group->id : null
            );
            $properties_members[] = $admin;
        }

        if($group) {
            global $wpdb;
            $c_table = $wpdb->prefix . CO_OWNER_CONNECTIONS_TABLE;
            $u_table = $wpdb->prefix . 'users';

            $columns2 = "t1.*";
            $columns2 .= ",r_user.display_name as receiver_user_name";
            $columns2 .= ",r_user.user_email as receiver_user_email";
            $join = "LEFT JOIN {$u_table} AS r_user ON r_user.ID = t1.receiver_user ";
            $query = "SELECT {$columns2} FROM {$c_table} as t1 {$join} WHERE t1.group_id = {$group->id} AND (t1.status = 1 OR t1.status = 3)";
            $members = $wpdb->get_results($query);

            foreach ($members as $key => $member) {
                $members[$key]->is_admin = 0;
                $properties_members[] = (object)array(
                    'id' => $member->receiver_user,
                    'display_name' => get_user_full_name($member->receiver_user),
                    'user_email' => $member->receiver_user_email,
                    'interested_in' => $member->interested_in,
                    'calculated_price' => $member->calculated_price,
                    'status' => $member->status,
                    'is_admin' => 0,
                    'mobile' => get_user_meta($member->receiver_user, '_mobile', true),
                    'group_id' => $group->id
                );
            }
        }
        return $properties_members;
    }

    public static function update($id,$key,$value)
    {
        global $wpdb;
        $table = $wpdb->prefix.CO_OWNER_GROUP_TABLE;
        $value = ( is_string($value) && !is_numeric($value) ? "'".$value."' " : $value);
        $query = "UPDATE {$table} SET {$key} = {$value} WHERE id = {$id}";
        return $wpdb->query($query);
    }
}
