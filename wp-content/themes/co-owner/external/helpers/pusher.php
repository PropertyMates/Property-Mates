<?php
use Pusher\Pusher;

class CoOwner_pusher{

    public $pusher;

    public function __construct()
    {
        $cluster   = get_option('_crb_pusher_cluster');
        $auth_key   = get_option('_crb_pusher_instance_id');
        $secret     = get_option('_crb_pusher_secret_key');
        $app_id     = get_option('_crb_pusher_app_id');
        $options = array(
            'cluster' => $cluster,
            'useTLS' => true
        );
        $this->pusher = new Pusher($auth_key,$secret,$app_id,$options);
    }
}

