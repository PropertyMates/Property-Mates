<?php
class CoOwner_Favourite extends CoOwner_Model {

    public static function get_count($array){
        global $wpdb;
        $table = $wpdb->prefix.CO_OWNER_FAVOURITE_TABLE;
        $sql = self::make_query_from_array($table,$array);
        return $wpdb->get_row($sql);
    }

    public static function like($array)
    {
        $result = self::get_count($array);
        if($result->count == 0){
            global $wpdb;
            $table = $wpdb->prefix.CO_OWNER_FAVOURITE_TABLE;
            $wpdb->insert($table,$array);

            if($array['favourite_type'] == 'post'){
                $post = get_post($array['favourite_id']);
                if($post){
                    CoOwner_Notifications::create($array['user_id'],$post->post_author,' added your own property to the wishlist.',6,$post->ID);
                }
            }

            if($array['favourite_type'] == 'user'){
                CoOwner_Notifications::create($array['user_id'],$array['favourite_id'],'added your profile to wishlist',7,$array['favourite_id']);
            }
        }
        return true;
    }

    public static function dislike($array)
    {
        global $wpdb;
        $table = $wpdb->prefix.CO_OWNER_FAVOURITE_TABLE;
        $is_delete = $wpdb->delete( $table,$array);

        if ($is_delete) {
            if ($array['favourite_type'] == 'post') {
                $post = get_post($array['favourite_id']);
                if ($post) {
                    CoOwner_Notifications::delete_row(CO_OWNER_NOTIFICATIONS_TABLE,array(
                        'sender_user' => $array['user_id'],
                        'receiver_user' => $post->post_author,
                        'notify_type' => 6,
                        'notify_id' => $post->ID,
                    ));
                }
            }

            if ($array['favourite_type'] == 'user') {
                CoOwner_Notifications::delete_row(CO_OWNER_NOTIFICATIONS_TABLE,array(
                    'sender_user' => $array['user_id'],
                    'receiver_user' => $array['favourite_id'],
                    'notify_type' => 7,
                    'notify_id' => $array['favourite_id'],
                ));
            }
        }

        return $is_delete;
    }

}
