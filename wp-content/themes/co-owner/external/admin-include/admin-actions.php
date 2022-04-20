<?php
if(is_admin()){

    function admin_reset_listing_status_and_meta($id)
    {
        $user_meta = array(
            '_user_property_category' => array(),
            '_user_property_type' => array(),
            '_user_descriptions' => null,
            '_user_preferred_location' => array(),
            '_user_land_area' => null,
            '_user_building_area' => null,
            '_user_age_year_built' => null,
            '_user_bedroom' => (integer) 0,
            '_user_bathroom' => (integer) 0,
            '_user_parking' => (integer) 0,
            '_user_property_features' => array(),
            '_user_manually_features' => array(),
            '_user_budget' => 0,
            '_user_enable_pool' => false,
            '_user_listing_status' => 0,
        );
        co_owner_update_user_meta($id,$user_meta);
    }

    function co_owner_admin_actions()
    {
        if(isset($_GET['action'])){
            switch ($_GET['action']) {

                case 'admin_update_user_listing_status':
                    if(isset($_GET['status']) && $_GET['id']){
                        $status = $_GET['status'];
                        $id = $_GET['id'];
                        update_user_meta($id,'_user_listing_status',$status);
                        if($status == 0){
                            admin_reset_listing_status_and_meta($id);
                        }
                        wp_redirect( admin_url( '/users.php?co_owner_alert=Updated user listing status successfully.&co_owner_alert_type=success' ) );
                        exit();
                    }
                break;

                case 'admin_update_user_status':
                    if(isset($_GET['status']) && $_GET['id']) {
                        $status = $_GET['status'];
                        $id = $_GET['id'];
                        update_user_meta($id, '_user_status', $status);
                        if($status==3){
                            admin_reset_listing_status_and_meta($id);
                            $sessions = WP_Session_Tokens::get_instance( $id );
                            if($sessions){
                                $sessions->destroy_all();
                            }
                        }
                        wp_redirect(admin_url('/users.php?co_owner_alert=Updated user status successfully.&co_owner_alert_type=success'));
                        exit();
                    }
                break;

                case 'admin_make_user_email_verified':
                    if(isset($_GET['status']) && $_GET['id']) {
                        $status = $_GET['status'];
                        $id = $_GET['id'];
                        update_user_meta($id, '_user_is_email_verified', $status);
                        $status = $status == 1 ? 'Verified' : 'Unverified';
                        wp_redirect(admin_url("/users.php?co_owner_alert=User email {$status} successfully.&co_owner_alert_type=success"));
                        exit();
                    }
                break;

                case 'admin_make_user_mobile_verified':
                    if(isset($_GET['status']) && $_GET['id']) {
                        $status = $_GET['status'];
                        $id = $_GET['id'];
                        update_user_meta($id, '_user_is_mobile_verified', $status);
                        $status = $status == 1 ? 'Verified' : 'Unverified';
                        wp_redirect(admin_url("/users.php?co_owner_alert=User mobile {$status} successfully.&co_owner_alert_type=success"));
                        exit();
                    }
                break;

                case 'download_user_document':
                    if(
                        isset($_GET['user']) && !empty($_GET['user']) &&
                        isset($_GET['key']) && $_GET['key'] >= 0
                    ){
                        $uploadFiles = get_user_meta( $_GET['user'], '_user_profile_documents', true );
                        if(is_array($uploadFiles) && isset($uploadFiles[$_GET['key']])){
                            $file = (object) $uploadFiles[$_GET['key']];
                            if (file_exists($file->file)) {
                                header('Content-Description: File Transfer');
                                header('Content-Type: application/octet-stream');
                                header('Content-Disposition: attachment; filename="'.basename($file->file).'"');
                                header('Expires: 0');
                                header('Cache-Control: must-revalidate');
                                header('Pragma: public');
                                header('Content-Length: ' . filesize($file->file));
                                readfile($file->file);
                                exit;
                            } else {
                                wp_redirect(admin_url("admin.php?page=user_shield_requests"));
                                die;
                            }
                        } else {
                            wp_redirect(admin_url("admin.php?page=user_shield_requests"));
                            die;
                        }
                    } else {
                        wp_redirect(admin_url("admin.php?page=user_shield_requests"));
                        die;
                    }
                break;

                case 'user_document_status':
                    if(
                        isset($_GET['user']) && !empty($_GET['user']) &&
                        isset($_GET['status']) && $_GET['status'] >= 0
                    ){
                        if($_GET['status'] == 1){
                            send_user_mail_for_shield_approved($_GET['user']);
                            send_user_message_for_shield_approved($_GET['user']);
                        }
                        update_user_meta($_GET['user'],'_document_shield_status',$_GET['status']);
                        wp_redirect(admin_url("admin.php?page=user_shield_requests&co_owner_alert=Status Update Successfully."));
                        die;
                    } else {
                        wp_redirect(admin_url("admin.php?page=user_shield_requests"));
                        die;
                    }
                break;
            }
        }

        if(isset($_GET['co_owner_action'])){
            switch ($_GET['co_owner_action']) {
                case 'remove_member_from_group':
                    if(
                        isset($_GET['post']) &&
                        isset($_GET['member']) &&
                        is_numeric($_GET['post']) &&
                        is_numeric($_GET['member'])
                    ) {
                        $post = $_GET['post'];
                        $member = $_GET['member'];
                        $group = CoOwner_Groups::find(array('property_id'=>$post));
                        if($group){
                            $connection = CoOwner_Connections::delete_row(CO_OWNER_CONNECTIONS_TABLE,array(
                                'property_id' => $post,
                                'is_group' => 1,
                                'group_id' => $group->id,
                                'receiver_user' => $member
                            ));
                            wp_redirect(admin_url("/post.php?post=178&action=edit&co_owner_toastr=Member removed successfully.&co_owner_toastr_type=success"));
                            exit();
                        }
                    }
                    wp_redirect(admin_url("/post.php?post=178&action=edit&co_owner_toastr=Something went wrong please try again.&co_owner_toastr_type=error"));
                    exit();
                break;
            }
        }


        if(isset($_POST['co_owner_action']) && !empty($_POST['co_owner_action'])){
            $action = $_POST['co_owner_action'];
            if(
                $action == 'reject_user_shield' &&
                isset($_POST['_document_shield_reject_reason']) && !empty($_POST['_document_shield_reject_reason']) &&
                isset($_POST['user_id']) && !empty($_POST['user_id'])
            ){
                update_user_meta($_POST['user_id'],'_document_shield_status',2);
                update_user_meta($_POST['user_id'],'_document_shield_reject_reason',$_POST['_document_shield_reject_reason']);
                send_user_mail_for_shield_approved($_POST['user_id'],2);
                send_user_message_for_shield_approved($_POST['user_id'],2);

                wp_redirect(admin_url("admin.php?page=user_shield_requests&co_owner_alert=Status Update Successfully."));
                die;
            }
        }
    }

    add_action('init','co_owner_admin_actions');


    if(isset($_GET['co_owner_alert'])){
        add_action('admin_notices', 'author_admin_notice');
    }

    function author_admin_notice(){
        $alert = isset($_GET['co_owner_alert']) ? $_GET['co_owner_alert'] : null;
        $type = isset($_GET['co_owner_alert_type']) ? $_GET['co_owner_alert_type'] : 'success';
        echo "<div class='notice notice-{$type} is-dismissible'><p>{$alert}</p></div>";
    }

}
