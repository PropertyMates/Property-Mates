<?php
get_header();
$is_group = isset($_GET['is_pool']) ? filter_var($_GET['is_pool'], FILTER_VALIDATE_BOOLEAN)  : false;
$is_received = isset($_GET['is_received']) ? filter_var($_GET['is_received'], FILTER_VALIDATE_BOOLEAN)  : true;
$request_id = isset($_GET['request']) ? $_GET['request'] : null;
$chat_with = isset($_GET['with']) ? $_GET['with'] : null;

$user_id = get_current_user_id();
echo 'User id'.$user_id;
$wpdb;
$tbaleName = $wpdb->prefix."co_owner_conversation";
$sql="select * from $tbaleName where receiver_user='$user_id' order by id desc limit 1 ";
$activePoolData= $wpdb->get_row($sql);

function getGroupById($user_id,$uid){
	global $wpdb;
$tbaleName = $wpdb->prefix."co_owner_groups";
$tbaleMessage = $wpdb->prefix."co_owner_conversation";
$sql="select group_id from $tbaleMessage t2 where ((sender_user='$user_id' OR receiver_user='$user_id') AND (sender_user='$uid' OR receiver_user='$uid'))  AND group_id is not null  order by t2.id desc limit 1";
return  $wpdb->get_row($sql);	
}

function getMessageById($user_id){
	global $wpdb;
$tbaleName = $wpdb->prefix."co_owner_conversation";
	$sql="SELECT *
                   FROM  $tbaleName cv1
                   WHERE ";				   
					$sql.= " (cv1.sender_user='$user_id' OR cv1.receiver_user='$user_id') AND group_id is null ";			   
				   
				   $sql.= " ORDER BY cv1.id DESC limit 1";
		// echo  $sql;
	return $wpdb->get_row($sql);
}




//    $user_status = get_user_status($user_id);
//    if($user_status == 1){
//        $first_connection = CoOwner_Connections::get_connected_users($user_id,1);
//        if(!$first_connection){
//            $first_connection = get_connection_requests(true,false,true);
//            if(!$first_connection){
//                $first_connection = get_connection_requests(false,false,true);
//            }
//        }
//
//        if($first_connection) {
//
//            if($first_connection->status == 0){
//                $request_id = $first_connection->id;
//            } else {
//                $chat_with = $first_connection->sender_user == $user_id ? $first_connection->receiver_user : $first_connection->sender_user;
//                $is_group = false;
//            }
//        }
//    }


$connection_requests = $is_group ? array() : get_connection_requests($is_received,$is_group);
$request = get_connection_request($request_id,$is_group,$is_received);

$connected_users = get_connected_connections();
$group_connections = get_connected_connections(true);

$connected_connections = $is_group ? $group_connections : $connected_users;

$chat_with_connected = get_chat_with_connection($chat_with,$is_group);

$chat_files = array();
$clear_chat_date = null;
if($chat_with_connected){
    $filter = array(
        ($is_group ? 'group_id' : 'connection_id') => $chat_with_connected->id
    );
    $clear_chat_key = $is_group ? '_user_clear_chat_group_' : '_user_clear_chat_with_';
    $clear_chat = get_user_meta($user_id,"{$clear_chat_key}{$chat_with}",true);
    if(!empty($clear_chat)){
        $clear_chat_date = $clear_chat;
        $filter['created_at'] = array('>=',$clear_chat);
    }
    if($is_group && isset($chat_with_connected->joind_date) && $chat_with_connected->joind_date){
        $clear_chat_date = $chat_with_connected->joind_date;
        $filter['created_at'] = array('>=',$chat_with_connected->joind_date);
    }
    $count = CoOwner_Conversation_Files::count($filter);
    $chat_files = CoOwner_Conversation_Files::get_files($filter,false,true,1);
}
?>

<?php if($is_group && $chat_with_connected && $chat_with_connected->user_id == $user_id): ?>
    <?php if($chat_with_connected->group_status == 1): ?>
    <div class="modal fade member-modal-custom" id="members-modal" tabindex="-1" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Select from your connections</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="input-group custom-contact-search mb-3">
                        <span class="input-group-text search"><?php echo co_owner_get_svg('search'); ?></span>
                        <input type="text" class="form-control search-input" aria-label="Amount (to the nearest dollar)" placeholder="Search by name">
                        <span class="input-group-text close"><?php echo co_owner_get_svg('close-round'); ?></span>
                    </div>
                    <?php $people_requested = get_people_requested_for_the_same_pool($chat_with_connected->property_id,$user_id);
                         


					?>
					
                    <div class="row <?php echo count($people_requested)>0 ? '' : 'd-none'; ?>">
                        <div class="col-sm-12 col-12 list-title">
                            <h6>People requested for the same pool</h6>
                        </div>
                        <?php foreach ($people_requested as $user_): ?>
                            <div class="col-xxl-2 col-xl-3 col-lg-3 col-md-4 col-sm-4 col-12 pb-3 search-filter-member">
                                <div class="card member-card">
                                    <div class="card-body">
                                        <div class="mbr-title d-flex w-100">
                                            <div class="form-check custom-check">
                                                <label class="form-check-label" for="flex-radio-default-<?php echo $user_->id;?>">
                                                    <input value="<?php echo $user_->id;?>" class="form-check-input" type="radio" name="selected_member" id="flex-radio-default-<?php echo $user_->id;?>">
                                                </label>
                                            </div>
                                        </div>
                                        <div class="mbr-detail-area">
                                            <div class="<?php echo get_user_shield_status($user_->id) == 1 ? 'mbr-icon-onthumb user-shield-tooltip' : ''; ?> mt--46px">
                                                <div class="mbr-thumb mx-auto">
                                                    <img src="<?php echo $user_->profile ?>" alt="">
                                                </div>
                                            </div>
                                            <h5 class="text-center"><?php echo $user_->name ?></h5>
                                            <span class="cnt text-center">
                                                <a href="mailto:<?php echo $user_->email ?>"><?php echo $user_->email ?></a>
                                            </span>
                                            <span class="cnt text-center"><?php echo $user_->mobile ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-12 list-title">
                            <h6>Other Connections</h6>
                        </div>
                        <?php foreach ($connected_users as $connected_user): ?>
                        <div class="col-xxl-2 col-xl-3 col-lg-3 col-md-4 col-sm-4 col-12 pb-3 search-filter-member">
                            <div class="card member-card">
                                <div class="card-body">
                                    <div class="mbr-title d-flex w-100">
                                        <div class="form-check custom-check">
                                            <label class="form-check-label" for="flex-radio-default-<?php echo $connected_user->id;?>">
                                                <input value="<?php echo $connected_user->id;?>" class="form-check-input" type="radio" name="selected_member" id="flex-radio-default-<?php echo $connected_user->id;?>">
                                            </label>
                                        </div>
                                    </div>
                                    <div class="mbr-detail-area">
                                        <div class="<?php echo get_user_shield_status($connected_user->id) == 1 ? 'mbr-icon-onthumb user-shield-tooltip' : ''; ?> mt--46px">
                                            <div class="mbr-thumb mx-auto">
                                                <img src="<?php echo $connected_user->profile ?>" alt="">
                                            </div>
                                        </div>
                                        <h5 class="text-center"><?php echo $connected_user->name ?></h5>
                                        <span class="cnt text-center">
                                                <a href="mailto:<?php echo $connected_user->email ?>"><?php echo $connected_user->email ?></a>
                                            </span>
                                        <span class="cnt text-center"><?php echo $connected_user->mobile ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-orange-text" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-orange add-member-to-pool rounded-pill">Add to Pool</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade default-modal-custom" id="add-member-to-pool" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">

                        <div class="col-sm-12 col-12 d-flex">
                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="col-sm-12 col-12 pb-4">
                            <h6>Add New Member</h6>
                        </div>

                        <?php
                        $available_share = get_property_available_share($chat_with_connected->property_id);
                        $available_price = get_property_available_price($chat_with_connected->property_id);
                        ?>

                        <div class="col-sm-12 col-12 pb-3">
                            <h6 class="pt-2 bb-1 pb-3">
                                Pool: <?php echo $chat_with_connected->name; ?>
                                <span class="coman-orange-sub d-block pt-1">
                                    <?php echo 'Pool Member(s): '.count($chat_with_connected->members); ?> |
                                     <?php if($available_price > 0): ?>
                                        Available Share: <?php echo $available_share."% at";   ?>
                                        <?php echo CO_OWNER_CURRENCY_SYMBOL." ".number_format( (float) $available_price); ?>
                                     <?php else: ?>
                                         Portions of the property are not available
                                     <?php endif; ?>
                                </span>
                            </h6>
                        </div>

                        <form action="" id="add-new-member-form">
                            <input type="hidden" name="property_id" value="<?php echo $chat_with_connected->property_id; ?>">
                            <div class="col-12">
                                <div class="row property-share-inputs">
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-12 mb-3">
                                        <label for="property-share-options" class="form-label">I am interested in %</label>
                                        <div class="w-100 custom-select">
                                            <select
                                                data-calculated-input="#member-calculated-price"
                                                data-property-available-share="<?php echo $available_share; ?>"
                                                data-property-available-price="<?php echo $available_price; ?>"
                                                class="form-select single-select2 property-share-selection"
                                                name="interested_in"
                                            >
                                                <?php echo get_property_share_options_by_id($chat_with_connected->property_id); ?>
                                            </select>
                                        </div>
                                        <label id="interested_in-error" class="text-error" for="interested_in"></label>
                                        <label id="property-share-options-error" class="text-error" for="property-share-options" style=""></label>
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-12 mb-3">
                                        <label for="price" class="form-label">Calculated Price</label>
                                        <input name="calculated_price" type="text" class="form-control" readonly id="member-calculated-price">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-12 mb-4">
                                <label for="add-comment" class="form-label">Welcome message for user (visible in the pool chat)</label>
                                <textarea name="description" class="form-control" id="add-comment" rows="3" placeholder="Comment"></textarea>
                            </div>
                            <div class="col-sm-12 col-12 mb-3 text-end bottom-btns">
                                <a href="#" class="btn btn-orange-text rounded-pill" data-bs-dismiss="modal">Cancel</a>
                                <button type="submit" class="btn btn-orange rounded-pill">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="modal fade default-modal-custom" id="add-member-to-another-pool" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">

                        <div class="col-sm-12 col-12 d-flex">
                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="col-sm-12 col-12 pb-4">
                            <h6>Assign another pool</h6>
                        </div>
                        <form action="" id="assign-member-to-another-group">
                            <input type="hidden" name="member_id" value="">
                            <div class="col-sm-12 col-12 pb-3" id="show-property-info-group"></div>
                            <div class="col-sm-12 col-12 pb-3">
                                <label for="property-share-options" class="form-label">Select a Pool</label>
                                <div class="w-100 custom-select">
                                    <select class="form-select single-select2 select-another-group" name="property_id">
                                        <option value=""> Select Pool</option>
                                        <?php foreach ($connected_connections as $c_group): ?>
                                            <?php if($user_id == $c_group->user_id && $c_group->id != $chat_with): ?>
                                                <option data-property-id="<?php echo $c_group->property_id; ?>" value="<?php echo $c_group->property_id; ?>"><?php echo $c_group->name; ?></option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                    <label id="property_id-error" class="text-error" for="property_id"></label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="row property-share-inputs">
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-12 mb-3">
                                        <label for="property-share-options" class="form-label">Select Share %</label>
                                        <div class="w-100 custom-select">
                                            <select
                                                    data-calculated-input="#member-calculated-price-group"
                                                    data-property-available-share="0"
                                                    data-property-available-price="0"
                                                    id="property-share-select2-group"
                                                    class="form-select single-select2 property-share-selection"
                                                    name="interested_in">
                                            </select>
                                            <label id="property-share-select2-group-error" class="text-error" for="property-share-select2-group"></label>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-12 mb-3">
                                        <label for="price" class="form-label">Calculated Price</label>
                                        <input name="calculated_price" type="text" class="form-control" readonly
                                               id="member-calculated-price-group">
                                        <label id="member-calculated-price-group-error" class="text-error" for="member-calculated-price-group"></label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-12 mb-4">
                                <label for="add-comment" class="form-label">Add Comment</label>
                                <textarea name="description" class="form-control" id="add-comment" rows="3" placeholder="Comment"></textarea>
                            </div>
                            <div class="col-sm-12 col-12 mb-3 text-end bottom-btns">
                                <a href="#" class="btn btn-orange-text rounded-pill" data-bs-dismiss="modal">Cancel</a>
                                <button type="submit" class="btn btn-orange rounded-pill">Assign</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="modal fade member-modal-custom" id="all-connections-modal" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" >All connections</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="input-group custom-contact-search mb-3">
                    <span class="input-group-text search"><?php echo co_owner_get_svg('search'); ?></span>
                    <input type="text" class="form-control search-input all-connections-contact-search" aria-label="Amount (to the nearest dollar)" placeholder="Search by name">
                    <span class="all-connections-contact-search-close input-group-text close"><?php echo co_owner_get_svg('close-round'); ?></span>
                </div>
                <div class="row">
                    <div class="col-sm12 col-12 list-title">
                        <h6>Connected Members</h6>
                    </div>
                    <?php foreach ($connected_users as $c_user): ?>
                    <div class="all-connection-search-filter-connection col-xxl-2 col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12 pb-30px">
                        <div class="card member-card">
                            <div class="card-body pb-20px">
                                <div class="mbr-title d-flex w-100"></div>
                                <div class="mbr-detail-area">
                                    <div class="<?php echo get_user_shield_status($c_user->id) == 1 ? 'mbr-icon-onthumb user-shield-tooltip' : ''; ?> mt--46px">
                                        <div class="mbr-thumb mx-auto">
                                            <img src="<?php echo $c_user->profile; ?>" alt="">
                                        </div>
                                    </div>
                                    <h5 class="text-center">
                                        <a href="<?php echo home_url(CO_OWNER_PERSON_DETAILS_PAGE.'/?id='.$c_user->id); ?>" class="text-orange">
                                            <?php echo $c_user->name; ?>
                                        </a>
                                    </h5>
                                    <?php if($c_user->user_status == 1): ?>
                                        <span class="cnt text-center">
                                            <a href="mailto:<?php echo $c_user->email; ?>"><?php echo $c_user->email; ?></a>
                                        </span>
                                        <span class="cnt text-center"><?php echo $c_user->mobile; ?></span>
                                    <?php endif; ?>
                                    <div class="btn-area d-flex justify-content-center">
                                        <a href="<?php echo home_url(CO_OWNER_MESSAGE_PAGE."?is_pool=false&with={$c_user->id}"); ?>" class="btn btn-orange btn-sm rounded-pill me-1">Message</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>

                    <div class="col-sm12 col-12 list-title">
                        <h6>Connected Pools</h6>
                    </div>
                    <?php foreach ($group_connections as $c_pool): ?>
                        <div class="all-connection-search-filter-connection col-xxl-2 col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12 pb-30px">
                            <div class="card member-card">
                                <div class="card-body pb-20px">
                                    <div class="mbr-title d-flex w-100"></div>
                                    <div class="mbr-detail-area">
                                        <div class="mt--46px">
                                            <div class="mbr-thumb mx-auto">
                                                <img src="<?php echo get_property_first_image($c_pool->property_id); ?>" alt="">
                                            </div>
                                        </div>
                                        <h5 class="text-center">
                                            <a href="<?php echo home_url(CO_OWNER_PROPERTY_DETAILS_PAGE.'/?id='.$c_pool->property_id); ?>" class="text-orange">
                                                <?php echo $c_pool->name; ?>
                                            </a>
                                        </h5>
                                        <span class="cnt text-center">
                                            Total Members: <?php echo $c_pool->members_count; ?>
                                        </span>
                                        <span class="cnt text-center">
                                            Admin
                                            <a href="<?php echo home_url(CO_OWNER_PERSON_DETAILS_PAGE.'/?id='.$c_pool->user_id); ?>" class="text-orange">
                                                <?php echo get_user_full_name($c_pool->user_id); ?>
                                            </a>
                                        </span>
                                        <div class="btn-area d-flex justify-content-center">
                                            <a href="<?php echo home_url(CO_OWNER_MESSAGE_PAGE."?is_pool=true&with={$c_pool->id}"); ?>" class="btn btn-orange btn-sm rounded-pill me-1">Message</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade default-modal-custom default-small-modal" id="compose-message-modal" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-12 d-flex">
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="col-sm-12 col-12 pb-4">
                        <h6>New Message</h6>
                    </div>
                    <form id="compose-message-form">
                        <div class="col-sm-12 col-12 mb-3">
                            <label class="form-label">To</label>
                            <div class="w-100 custom-select">
                                <select name="contact_for_compose_message" class="form-control w-100 js-select2-with-image">
                                    <option value="">Select contact</option>
                                    <?php foreach ($connected_users as $compose_message_user): ?>
                                        <option data-type="connection" data-profile="<?php echo $compose_message_user->profile; ?>" value="<?php echo $compose_message_user->id; ?>"><?php echo $compose_message_user->name; ?></option>
                                    <?php endforeach; ?>
                                    <?php foreach ($group_connections as $compose_message_pool): ?>
                                        <option data-type="group" value="<?php echo $compose_message_pool->id; ?>"><?php echo $compose_message_pool->name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <label id="contact_for_compose_message-error" class="text-error" for="contact_for_compose_message"></label>
                            </div>
                        </div>
                        <div class="col-sm-12 col-12 mb-3">
<!--                            <label for="compose_message" class="form-label">Textarea</label>-->
                            <textarea name="compose_message" class="form-control" id="compose_message" rows="7" placeholder="Type here "></textarea>
                        </div>
                        <div class="col-sm-12 col-12 text-end bottom-btns">
                        <a href="#" data-bs-dismiss="modal" aria-label="Close" class="btn btn-orange-text rounded-pill">Cancel</a>
                        <button type="submit" class="btn btn-orange rounded-pill">Post</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade property-modal-custom" id="preview-images-modal" tabindex="-1" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-12 d-flex">
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="col-sm-12 col-12">
                        <div class="custom-flx-slider">
                            <div id="slider">
                                <div class="flex-viewport" >
                                    <ul class="slides" >
                                        <li class="flex-active-slide" >
                                            <img src="" id="preview_image" alt="" draggable="false">
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="center-area">
    <div class="main-section bg-grey public-title for-my-list py-20px">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 inner-section-grey list-view-title">
                    <div class="title-area pb-0">
                        <h3 class="d-flex align-items-center">
                            <span class="pe-2">
                                Messages
                            </span>

                            <p class="ms-auto text-orange d-none d-md-block">Remember: You can anytime add a member to your pool either from pool chat or from your listing.</p>

                            <div class="ms-auto d-flex align-items-center">
                                <a title="Create new message" class="custom-btn-area d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#compose-message-modal">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                        <g fill="none" fill-rule="evenodd">
                                            <g>
                                                <g transform="translate(-1286 -108) translate(1286 108)">
                                                    <rect width="24" height="24" fill="#FE7400" rx="7"></rect>
                                                    <g>
                                                        <path d="M0 0H16V16H0z" transform="translate(4 4)"></path>
                                                        <path fill="#FFF" d="M10.451.389l2.598 1.5c.478.276.642.888.366 1.366l-5.75 9.96c-.014.023-.03.046-.047.067l.047-.068c-.02.034-.043.065-.069.093l-.039.037-.02.016-.03.021L5.056 15H12.5c.276 0 .5.224.5.5s-.224.5-.5.5h-9c-.276 0-.5-.224-.5-.5l.002-.02c-.002-.027-.003-.054-.001-.082l.268-4.464.002-.027.006-.038-.008.065c.004-.077.027-.153.066-.22l5.75-9.96C9.36.278 9.973.114 10.45.39zM4.219 11.802l-.16 2.658 2.223-1.467-2.063-1.191zm4.232-7.95l-4 6.929 2.598 1.5 4-6.928-2.598-1.5zm1.5-2.597l-1 1.732 2.598 1.5 1-1.732-2.598-1.5z" transform="translate(4 4)"></path>
                                                    </g>
                                                </g>
                                            </g>
                                        </g>
                                    </svg>
                                    <h5 class="ps-2">Compose</h5>
                                </a>
                                <button class="navbar-toggler openbtn" type="button" id="open-nav-on-mobile" onclick="openNav()">
                                    <span class="navbar-toggler-icon"></span>
                                </button>
                            </div>
                        </h3>
                    </div>

                    <p class="d-md-none m-0 ms-auto text-orange mt-3">Remember: You can anytime add a member to your pool either from pool chat or from your listing.</p>

                </div>
            </div>
        </div>
    </div>
    <div class="d-md-none d-md-block mobile-side-panel h-83vh" id="mySidebar">
        <div class="d-flex h-100">
            <div class="chat-body px-3">
                <div class="text-end pt-1">
                    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                            <g fill="none" fill-rule="evenodd">
                                <g>
                                    <g>
                                        <path d="M24 0L0 0 0 24 24 24z" transform="translate(-1356 -18) translate(1356 18)"></path>
                                        <path fill="#262626" fill-opacity=".65" fill-rule="nonzero" d="M3.214 3.213c.285-.284.746-.284 1.031 0l7.752 7.758 7.753-7.757c.258-.26.664-.283.95-.071l.08.07c.286.285.286.747.001 1.032l-7.753 7.758 7.753 7.759c.26.259.282.664.07.95l-.07.081c-.285.285-.747.285-1.031 0l-7.753-7.759-7.752 7.759c-.259.259-.664.282-.95.07l-.081-.07c-.285-.284-.285-.746 0-1.031l7.752-7.759-7.753-7.758c-.258-.26-.282-.664-.07-.95z" transform="translate(-1356 -18) translate(1356 18)"></path>
                                    </g>
                                </g>
                            </g>
                        </svg>
                    </a>
                </div>
                <div class="w-100">
                    <div class="message-list-menu h-100 pt-20px">
                        <ul>
                            <li class="<?php echo !$is_group ? 'active' : ''; ?>"><a class="messages" href="<?php echo home_url(CO_OWNER_MESSAGE_PAGE.'/?is_pool=false') ?>" >Direct Messages</a></li>
                            <li class="<?php echo $is_group ? 'active' : ''; ?>"><a class="pools" href="<?php echo home_url(CO_OWNER_MESSAGE_PAGE.'/?is_pool=true') ?>" >Active Pools</a></li>
                        </ul>
                    </div>
                </div>
                <div class="chat-content w-100">
                    <div class="h-83vh">
                        <div class="pnl-title bb-1px d-flex ps-3 pt-10px pb-10px align-items-center min-h-50px">
                            <h6>All Messages</h6>
                            <div class="dropdown member-drop ms-auto">
                                <button class="btn dropdown-toggle" type="button" id="dropdownMenu1" data-bs-toggle="dropdown" aria-expanded="false">
                                    <!--?xml version="1.0" encoding="utf-8"?-->
                                    <!-- Generator: Adobe Illustrator 25.2.2, SVG Export Plug-In . SVG Version: 6.00 Build 0)  -->
                                    <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 32 18" style="enable-background:new 0 0 32 18;" xml:space="preserve">
                                                <style type="text/css">
                                                    .st0{opacity:0.65;fill:#262626;}
                                                </style>
                                        <g>
                                            <circle class="st0" cx="3.4" cy="9" r="3.2"></circle>
                                            <circle class="st0" cx="16.1" cy="9" r="3.2"></circle>
                                            <circle class="st0" cx="28.6" cy="9" r="3.2"></circle>
                                        </g>
                                            </svg>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                    <li><a class="dropdown-item" data-bs-target="#all-connections-modal" data-bs-toggle="modal" href="#">All Connections</a></li>
                                    <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#compose-message-modal" href="#">Compose Message</a></li>
                                </ul>

                            </div>
                        </div>

                        <div class="side-tab-links bb-1px pt-10px pb-2">
                            <ul>
                                <li class="<?php echo $is_received ? 'active' : ''; ?>"><a class="nav-link <?php echo $is_received ? 'active' : ''; ?>" href="<?php echo home_url(CO_OWNER_MESSAGE_PAGE.'/?is_pool=false&is_received=true') ?>">Received</a></li>
                                <li class="<?php echo !$is_received ? 'active' : ''; ?>"><a class="nav-link <?php echo !$is_received ? 'active' : ''; ?>" href="<?php echo home_url(CO_OWNER_MESSAGE_PAGE.'/?is_pool=false&is_received=false') ?>">Request Sent</a></li>
                            </ul>
                        </div>

                        <div class="side-search pt-3 pb-3">
                            <div class="input-group custom-contact-search">
                                <span class="input-group-text search">
                                            <!--?xml version="1.0" encoding="utf-8"?-->
                                            <!-- Generator: Adobe Illustrator 25.2.2, SVG Export Plug-In . SVG Version: 6.00 Build 0)  -->
                                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 16 16" style="enable-background:new 0 0 16 16;" xml:space="preserve">
                                                <style type="text/css">
                                                    .st0{opacity:0.65;fill:#262626;}
                                                    .st1{display:none;}
                                                    .st2{display:inline;}
                                                    .st3{opacity:0.65;}
                                                    .st4{fill:#262626;}
                                                </style>
                                                <g id="Layer_1">
                                                    <path class="st0" d="M15.8,15.1l-4.2-4.2c1-1.1,1.6-2.6,1.6-4.2c0-3.6-2.9-6.5-6.5-6.5C3,0.1,0.1,3,0.1,6.7s2.9,6.5,6.5,6.5
                                                        c1.7,0,3.2-0.6,4.3-1.6l4.2,4.2c0.1,0.1,0.2,0.1,0.3,0.1c0.1,0,0.2,0,0.3-0.1C16,15.6,16,15.3,15.8,15.1z M1,6.7C1,3.6,3.5,1,6.6,1
                                                        s5.6,2.5,5.6,5.6c0,1.5-0.6,2.9-1.6,3.9c0,0,0,0,0,0c0,0,0,0,0,0c-1,1-2.4,1.7-4,1.7C3.5,12.3,1,9.7,1,6.7z"></path>
                                                </g>
                                                <g id="Layer_2" class="st1">
                                                    <g class="st2">
                                                        <g class="st3">
                                                            <g>
                                                                <path class="st4" d="M8,1.1c3.8,0,6.9,3.1,6.9,6.9s-3.1,6.9-6.9,6.9c-3.8,0-6.9-3.1-6.9-6.9S4.2,1.1,8,1.1 M8,0.1
                                                                    C3.6,0.1,0.1,3.6,0.1,8s3.5,7.9,7.9,7.9c4.4,0,7.9-3.5,7.9-7.9S12.4,0.1,8,0.1L8,0.1z"></path>
                                                            </g>
                                                        </g>
                                                        <path class="st3" d="M12,11.2L8.7,7.9L12,4.6c0.2-0.2,0.2-0.5,0-0.7s-0.5-0.2-0.7,0L8,7.2L4.8,4C4.6,3.8,4.3,3.8,4.1,4
                                                            C4,4.2,4,4.5,4.1,4.7l3.2,3.2l-3,3c-0.2,0.2-0.2,0.5,0,0.7c0.1,0.1,0.2,0.1,0.3,0.1c0.1,0,0.3,0,0.3-0.1l3-3l3.3,3.3
                                                            c0.1,0.1,0.2,0.1,0.3,0.1c0.1,0,0.3,0,0.3-0.1C12.2,11.7,12.2,11.4,12,11.2z"></path>
                                                    </g>
                                                </g>
                                            </svg>
                                        </span>
                                <input type="text" class="form-control contact-search" aria-label="Amount (to the nearest dollar)" placeholder="Search User">
                                <a title="Delete" href="#" class=" input-group-text contact-search-close close">
                                    <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 12 12" style="enable-background:new 0 0 12 12;" xml:space="preserve">
                                        <style type="text/css">.st0{opacity:0.65;fill:#727272;enable-background:new    ;}</style>
                                        <g>
                                            <path class="st0" d="M6,0.1C2.7,0.1,0.1,2.7,0.1,6s2.6,5.9,5.9,5.9c3.3,0,5.9-2.6,5.9-5.9S9.3,0.1,6,0.1z M6,11.2
                                                c-2.9,0-5.2-2.3-5.2-5.2S3.1,0.8,6,0.8s5.2,2.3,5.2,5.2S8.9,11.2,6,11.2z"></path>
                                            <path class="st0" d="M8.9,3C8.8,2.9,8.6,2.9,8.4,3L5.9,5.5L3.5,3.1C3.4,3,3.1,3,3,3.1C2.9,3.3,2.9,3.5,3,3.6L5.4,6L3.1,8.3
                                                C3,8.4,3,8.7,3.1,8.8c0.1,0.1,0.2,0.1,0.2,0.1c0.1,0,0.2,0,0.2-0.1l2.3-2.3L8.3,9c0.1,0.1,0.2,0.1,0.2,0.1c0.1,0,0.2,0,0.2-0.1
                                                c0.3-0.2,0.3-0.4,0.2-0.5L6.5,6l2.5-2.5C9.1,3.4,9.1,3.2,8.9,3z"></path>
                                        </g>
                                    </svg>
                                </a>
                            </div>
                        </div>
                        <div class="side-list-main">
						<?php $sellectclass=""; if(isset($_GET['with'])){ ?>
						
						<?php }else{ ?>
						<?php $sellectclass = 'select'; ?>
						<?php } ?>
						
                            <ul>
                                <?php if(!$is_group && count($connection_requests) > 0): ?>
                                    <?php foreach ($connection_requests as $rq): ?>
                                        <li class="search-filter-connection <?php echo $request_id == $rq->id ? 'select' : '' ?>">
                                            <a href="<?php echo home_url(CO_OWNER_MESSAGE_PAGE."/?is_pool=false&request={$rq->id}&is_received=".($is_received?'true':'false')); ?>" class="d-flex align-items-center">
                                                <div class="list-thumb">
                                                    <img src="<?php echo get_avatar_url($rq->requested_user_id); ?>" alt="">
                                                </div>
                                                <div class="side-lst-cnt">
                                                    <h6 class="<?php echo $rq->requested_user_shield_status ? 'user-shield-tooltip' : ''; ?>">
                                                        <?php echo $rq->requested_user_shield_status ? co_owner_get_svg('shield') : ''; ?>
                                                        <?php echo $rq->requested_user_full_name; ?>
                                                    </h6>
                                                    <p><?php echo $rq->requested_property; ?></p>
                                                </div>
                                                <span class="orange-circle ms-auto"></span>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                <?php endif; ?>

                                <?php if($is_received): ?>
                                    <?php $i = 1; foreach($connected_connections as $connected_connection): ?>
                                        <li class="search-filter-connection <?php echo ($is_group ? 'group-' : 'user-').$connected_connection->id; ?> <?php echo $chat_with == $connected_connection->id ? 'select' : '' ?> <?php  if($i == 1){ echo $sellectclass; }?>">
                                            <a href="<?php echo home_url(CO_OWNER_MESSAGE_PAGE."/?is_pool=".($is_group?'true':'false')."&with=".( $connected_connection->id)); ?>" class="d-flex align-items-center">
                                                <?php if(!$is_group): ?>
                                                    <div class="list-thumb">
                                                        <img src="<?php echo $connected_connection->profile; ?>" alt="">
                                                    </div>
                                                <?php else: ?>
                                                    <div class="list-icon">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30">
                                                            <defs>
                                                                <linearGradient id="t3e2xyb3sa" x1="100%" x2="0%" y1="50%" y2="50%">
                                                                    <stop offset="0%" stop-color="#FE7400"></stop>
                                                                    <stop offset="100%" stop-color="#DC5151"></stop>
                                                                </linearGradient>
                                                            </defs>
                                                            <g fill="none" fill-rule="evenodd">
                                                                <g>
                                                                    <g>
                                                                        <g>
                                                                            <path fill="url(#t3e2xyb3sa)" stroke="#FE7400" stroke-width="2.25" d="M15 1.299l11.865 6.85V21.85L15 28.701l-11.865-6.85V8.15L15 1.299z" transform="translate(-70 -2447) translate(0 2293) translate(70 154)"></path>
                                                                            <text fill="#FFF" font-family="OpenSans-SemiBold, Open Sans" font-size="15" font-weight="500" transform="translate(-70 -2447) translate(0 2293) translate(70 154)">
                                                                                <tspan x="10.5" y="21.25">P</tspan>
                                                                            </text>
                                                                        </g>
                                                                    </g>
                                                                </g>
                                                            </g>
                                                        </svg>
                                                    </div>
                                                <?php endif; ?>
                                                <div class="side-lst-cnt">
                                                    <?php if(!$is_group): ?>
                                                        <h6 class="<?php echo $connected_connection->user_shield_status ? 'user-shield-tooltip' : ''; ?>">
                                                            <?php echo $connected_connection->user_shield_status ? co_owner_get_svg('shield') : ''; ?>
                                                            <?php echo $connected_connection->name; ?>
                                                        </h6>
                                                    <?php else: ?>
                                                        <h6><?php echo $connected_connection->name; ?></h6>
                                                    <?php endif; ?>
                                                </div>
                                                <?php if($connected_connection->unread > 0): ?>
                                                    <span class="ms-auto orange-circle"></span>
                                                <?php endif; ?>
                                            </a>
                                        </li>
                                    <?php $i++; endforeach ?>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="main-section bg-white my-listings-main">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-2 col-lg-2 col-md-2 pe-0 d-none d-md-block">
                    <div class="message-list-menu h-100 py-40px br-1px">
					<?php /* Active link changed by Neeraj*/

							/*$getFirstGroupConnection = !empty($connected_connections) ? $connected_connections[0] : '';
							$directMessageParam='';
							$activePoolParam='';
							 if($getFirstGroupConnection->connection_id){
								  $groupTableData= getGroupById($getFirstGroupConnection->id,get_current_user_id());
								 
								 $directMessageParam='&with='.$activePoolData->sender_user;	
								 $activePoolParam='&with='.$groupTableData->group_id;
							 }else{
								 $groupTableData= getGroupById($getFirstGroupConnection->user_id,get_current_user_id());
								 $groupTableNullData= getMessageById($getFirstGroupConnection->user_id,get_current_user_id());
								 
							  //  $directMessageParam='&with='.$groupTableNullData->sender_user;	
							   $directMessageParam='&with='.$getFirstGroupConnection->user_id;	
								 $activePoolParam='&with='.$groupTableData->group_id;
							
							 }
					*/
							
					?>
                        <ul>
                            <li title="See all your direct messages" class="<?php echo !$is_group ? 'active' : ''; ?> direct_message"><a class="messages" href="<?php echo home_url(CO_OWNER_MESSAGE_PAGE.'/?is_pool=false') ?>" >Direct Messages</a></li>
                            <li title="View all of the active pools you're a member of. " class="<?php echo $is_group ? 'active' : ''; ?> direct_group"><a class="pools" href="<?php echo home_url(CO_OWNER_MESSAGE_PAGE.'/?is_pool=true') ?>" >Active Pools</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-3 d-none d-md-block ps-0 pe-0">
                    <div class="h-83vh br-1px">
                        <div class="pnl-title bb-1px d-flex ps-3 pt-10px pb-10px align-items-center min-h-50px">
                            <h6>All Messages</h6>
                            <div class="dropdown member-drop ms-auto">
                                <button title="Open more actions" class="btn dropdown-toggle" type="button" id="dropdownMenu1" data-bs-toggle="dropdown" aria-expanded="false">
                                    <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 32 18" style="enable-background:new 0 0 32 18;" xml:space="preserve">
                                        <style type="text/css">
                                            .st0{opacity:0.65;fill:#262626;}
                                        </style>
                                        <g>
                                            <circle class="st0" cx="3.4" cy="9" r="3.2"></circle>
                                            <circle class="st0" cx="16.1" cy="9" r="3.2"></circle>
                                            <circle class="st0" cx="28.6" cy="9" r="3.2"></circle>
                                        </g>
                                    </svg>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                    <li><a title="See all connections" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#all-connections-modal" href="#">All Connections</a></li>
                                    <li><a title="Start a conversation" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#compose-message-modal" href="#">Compose Message</a></li>
                                </ul>
                            </div>
                        </div>
                        <?php if(!$is_group): ?>
                        <div class="side-tab-links bb-1px pt-10px pb-2">
                            <ul>
                                <li class="<?php echo $is_received ? 'active' : ''; ?>"><a title="See all your current messages" class="nav-link <?php echo $is_received ? 'active' : ''; ?>" href="<?php echo home_url(CO_OWNER_MESSAGE_PAGE.'/?is_pool=false&is_received=true') ?>">Received</a></li>
                                <li class="<?php echo !$is_received ? 'active' : ''; ?>"><a title="See all your sent connection request" class="nav-link <?php echo !$is_received ? 'active' : ''; ?>" href="<?php echo home_url(CO_OWNER_MESSAGE_PAGE.'/?is_pool=false&is_received=false') ?>">Request Sent</a></li>
                            </ul>
                        </div>
                        <?php endif; ?>
                        <div class="side-search pt-3 pb-3">
                            <div class="input-group custom-contact-search">
                                <span class="input-group-text search">
                                    <!--?xml version="1.0" encoding="utf-8"?-->
                                    <!-- Generator: Adobe Illustrator 25.2.2, SVG Export Plug-In . SVG Version: 6.00 Build 0)  -->
                                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 16 16" style="enable-background:new 0 0 16 16;" xml:space="preserve">
                                        <style type="text/css">
                                            .st0{opacity:0.65;fill:#262626;}
                                            .st1{display:none;}
                                            .st2{display:inline;}
                                            .st3{opacity:0.65;}
                                            .st4{fill:#262626;}
                                        </style>
                                        <g id="Layer_1">
                                            <path class="st0" d="M15.8,15.1l-4.2-4.2c1-1.1,1.6-2.6,1.6-4.2c0-3.6-2.9-6.5-6.5-6.5C3,0.1,0.1,3,0.1,6.7s2.9,6.5,6.5,6.5
                                                c1.7,0,3.2-0.6,4.3-1.6l4.2,4.2c0.1,0.1,0.2,0.1,0.3,0.1c0.1,0,0.2,0,0.3-0.1C16,15.6,16,15.3,15.8,15.1z M1,6.7C1,3.6,3.5,1,6.6,1
                                                s5.6,2.5,5.6,5.6c0,1.5-0.6,2.9-1.6,3.9c0,0,0,0,0,0c0,0,0,0,0,0c-1,1-2.4,1.7-4,1.7C3.5,12.3,1,9.7,1,6.7z"></path>
                                        </g>
                                        <g id="Layer_2" class="st1">
                                            <g class="st2">
                                                <g class="st3">
                                                    <g>
                                                        <path class="st4" d="M8,1.1c3.8,0,6.9,3.1,6.9,6.9s-3.1,6.9-6.9,6.9c-3.8,0-6.9-3.1-6.9-6.9S4.2,1.1,8,1.1 M8,0.1
                                                            C3.6,0.1,0.1,3.6,0.1,8s3.5,7.9,7.9,7.9c4.4,0,7.9-3.5,7.9-7.9S12.4,0.1,8,0.1L8,0.1z"></path>
                                                    </g>
                                                </g>
                                                <path class="st3" d="M12,11.2L8.7,7.9L12,4.6c0.2-0.2,0.2-0.5,0-0.7s-0.5-0.2-0.7,0L8,7.2L4.8,4C4.6,3.8,4.3,3.8,4.1,4
                                                    C4,4.2,4,4.5,4.1,4.7l3.2,3.2l-3,3c-0.2,0.2-0.2,0.5,0,0.7c0.1,0.1,0.2,0.1,0.3,0.1c0.1,0,0.3,0,0.3-0.1l3-3l3.3,3.3
                                                    c0.1,0.1,0.2,0.1,0.3,0.1c0.1,0,0.3,0,0.3-0.1C12.2,11.7,12.2,11.4,12,11.2z"></path>
                                            </g>
                                        </g>
                                    </svg>
                                </span>
                                <input type="text" class="form-control contact-search" aria-label="Amount (to the nearest dollar)" placeholder="Search User">
                                <a title="Delete" href="#" class="input-group-text contact-search-close close">
                                    <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 12 12" style="enable-background:new 0 0 12 12;" xml:space="preserve">
                                        <style type="text/css">.st0{opacity:0.65;fill:#727272;enable-background:new    ;}</style>
                                        <g>
                                            <path class="st0" d="M6,0.1C2.7,0.1,0.1,2.7,0.1,6s2.6,5.9,5.9,5.9c3.3,0,5.9-2.6,5.9-5.9S9.3,0.1,6,0.1z M6,11.2
                                                c-2.9,0-5.2-2.3-5.2-5.2S3.1,0.8,6,0.8s5.2,2.3,5.2,5.2S8.9,11.2,6,11.2z"></path>
                                            <path class="st0" d="M8.9,3C8.8,2.9,8.6,2.9,8.4,3L5.9,5.5L3.5,3.1C3.4,3,3.1,3,3,3.1C2.9,3.3,2.9,3.5,3,3.6L5.4,6L3.1,8.3
                                                C3,8.4,3,8.7,3.1,8.8c0.1,0.1,0.2,0.1,0.2,0.1c0.1,0,0.2,0,0.2-0.1l2.3-2.3L8.3,9c0.1,0.1,0.2,0.1,0.2,0.1c0.1,0,0.2,0,0.2-0.1
                                                c0.3-0.2,0.3-0.4,0.2-0.5L6.5,6l2.5-2.5C9.1,3.4,9.1,3.2,8.9,3z"></path>
                                        </g>
                                        </svg>
                                </a>
                            </div>
                        </div>
                        <div class="side-list-main">
						<?php $sellectclass = ""; if(isset($_GET['with'])){ ?>
						
						<?php }else{ ?>
						<?php $sellectclass = 'select'; ?>
						<?php } ?>
                            <ul>
                                <?php if(!$is_group && count($connection_requests) > 0): ?>
								
							
                                    <?php $i = 1; foreach ($connection_requests as $rq): ?>
                                        <li class="search-filter-connection <?php echo $request_id == $rq->id ? 'select' : '' ?> <?php  if($i == 1){ echo $sellectclass; }?>">
                                            <a href="<?php echo home_url(CO_OWNER_MESSAGE_PAGE."/?is_pool=false&request={$rq->id}&is_received=".($is_received?'true':'false')); ?>" class="d-flex align-items-center">
                                                <div class="list-thumb">
                                                    <img src="<?php echo get_avatar_url($rq->requested_user_id); ?>" alt="">
                                                </div>
                                                <div class="side-lst-cnt">
                                                    <h6 class="<?php echo $rq->requested_user_shield_status ? 'user-shield-tooltip' : ''; ?>">
                                                        <?php echo $rq->requested_user_shield_status ? co_owner_get_svg('shield') : ''; ?>
                                                        <?php echo $rq->requested_user_full_name; ?>
                                                    </h6>
                                                    <p><?php echo $rq->requested_property; ?></p>
                                                </div>
                                                <span class="orange-circle ms-auto"></span>
                                            </a>
                                        </li>
                                    <?php $i++; endforeach; ?>
                                <?php endif; ?>

                                <?php if($is_received): ?>
									<?php 

									/*echo '<pre>';
									   print_r($connected_connections);
									 echo '</pre>';
									 */
									 
									 
									 /* message time  Added By Neeraj */
								?>
                                    <?php $i = 1; foreach($connected_connections as $connected_connection): 
									
									 $sender_reciever_id= get_current_user_id();
									   $col_name='sender_user';
									  if(empty($connected_connection->group_id)){
									     $col_name='receiver_user';
										 
									  }
									  
									//  $messagedata =  getConversationTime($sender_reciever_id,$col_name,$uid=$connected_connection->id);
									  
									  
									?>
                                        <li class="search-filter-connection <?php echo ($is_group ? 'group-' : 'user-').$connected_connection->id; ?> <?php echo $chat_with == $connected_connection->id ? 'select' : '' ?> <?php  if($i == 1){ echo $sellectclass; }?>">
                                            
											
											<?php //echo 'ID '.$connected_connection->se; 
											
											  //var_dump($messagedata);
											
											?>
											<a href="<?php echo home_url(CO_OWNER_MESSAGE_PAGE."/?is_pool=".($is_group?'true':'false')."&with=".( $connected_connection->id)); ?>" class="d-flex align-items-center">
                                                <?php if(!$is_group): ?>
                                                    <div class="list-thumb">
                                                        <img src="<?php echo $connected_connection->profile; ?>" alt="">
                                                    </div>
                                                <?php else: ?>
                                                    <div class="list-icon">
                                                        <?php echo co_owner_get_svg('enable_pool'); ?>
                                                    </div>
                                                <?php endif; ?>
                                                <div class="side-lst-cnt">
                                                    <?php if(!$is_group): ?>
                                                        <h6 class="<?php echo $connected_connection->user_shield_status ? 'user-shield-tooltip' : ''; ?>">
                                                            <?php echo $connected_connection->user_shield_status ? co_owner_get_svg('shield') : ''; ?>
                                                            <?php echo $connected_connection->name; ?>
                                                            <p><?php echo $connected_connection->property_name; ?></p>
                                                        </h6>
                                                    <?php else: ?>
                                                        <h6><?php echo $connected_connection->name; ?></h6>
                                                    <?php endif; ?>
                                                </div>
												
												<div class="ms-auto message-time d-flex"><?php 
												
												//echo $messagedata->created_at;
												echo date('h:i a',strtotime($connected_connection->message_date));
												if(empty($connected_connection->message_date)){
													//echo date('h:i a',strtotime($messagedata->created_at)); 
												}else{
													//echo date('h:i a',strtotime($connected_connection->message_date)); 
												}
												
												
												?></div>
                                                <?php if($connected_connection->unread > 0): ?>
                                                    <span class="ms-auto orange-circle"></span>
                                                <?php endif; ?>
                                            </a>
                                        </li>
                                    <?php $i++; endforeach ?>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl-5 col-lg-4 col-md-6 col-12 ps-0 pe-0 chat-one-col">
                    <div class="h-83vh br-1px">
                        <div class="d-flex h-100">
                            <div class="chat-body">
                                <div class="pnl-title bb-1px d-flex align-items-center pt-2 pb-2 ps-3 min-h-50px">
                                    <?php
                                    $connected_chat_title = null;
                                    if($chat_with_connected):
                                        $connected_chat_title = $is_group ? get_property_full_address($chat_with_connected->property_id) : ucfirst(get_user_full_name($chat_with_connected->user_info->ID));
                                    ?>
                                    <div class="side-list-main">
                                        <div class="d-flex align-items-center">
                                            <?php if($is_group): ?>
                                                <div class="list-icon">
                                                    <?php echo co_owner_get_svg('enable_pool'); ?>
                                                </div>
                                            <?php else: ?>
                                                <div class="list-thumb">
                                                    <img src="<?php echo get_avatar_url($chat_with_connected->user_info->ID)?>" alt="">
                                                </div>
                                            <?php endif; ?>
                                            <div class="side-lst-cnt">
                                                <h6><?php echo $connected_chat_title;?></h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="dropdown member-drop ms-auto">
                                        <button title="Open more actions" class="btn dropdown-toggle" type="button" id="dropdownMenu2" data-bs-toggle="dropdown" aria-expanded="false">
                                            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 32 18" style="enable-background:new 0 0 32 18;" xml:space="preserve">
                                                <style type="text/css">.st0{opacity:0.65;fill:#262626;}</style>
                                                <g>
                                                    <circle class="st0" cx="3.4" cy="9" r="3.2"></circle>
                                                    <circle class="st0" cx="16.1" cy="9" r="3.2"></circle>
                                                    <circle class="st0" cx="28.6" cy="9" r="3.2"></circle>
                                                </g>
                                            </svg>
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                            <?php if(!$is_group): ?>
                                                <li><a title="Block user" data-id="<?php echo $chat_with_connected->user_info->ID; ?>" class="user-block-action dropdown-item" href="#">Block</a></li>
                                                <li><a title="Remove user and its messages" data-connection-id="<?php echo $chat_with_connected->id; ?>" data-id="<?php echo $chat_with_connected->user_info->ID; ?>" class="user-remove-action dropdown-item" href="#">Remove</a></li>
                                                <li><a data-id="<?php echo $chat_with_connected->user_info->ID; ?>" class="dropdown-item clear-connection-chat" href="#">Clear Chats</a></li>
                                            <?php elseif($is_group && $chat_with_connected->user_id == $user_id && $chat_with_connected->group_status == 1 && $available_price > 0): ?>
                                                <li><a class="dropdown-item add-group-members" href="#" data-is-group="true">Add Member</a></li>
                                            <?php endif; ?>

                                            <?php if($is_group && $chat_with_connected->user_id == $user_id): ?>
                                                <li><a data-id="<?php echo $chat_with_connected->id; ?>" class="dropdown-item delete-group-action" href="#">Delete</a></li>
                                            <?php elseif($is_group && $chat_with_connected->user_id != $user_id): ?>
                                                <li><a data-id="<?php echo $chat_with_connected->id; ?>" data-user-id="<?php echo $user_id; ?>" class="dropdown-item leave-group-action" href="#">Leave</a></li>
                                            <?php endif; ?>

                                            <?php if($is_group): ?>
                                                <li><a title="Delete all the conversation" class="dropdown-item clear-group-chat" href="#" data-id="<?php echo ($chat_with_connected->id);?>">Clear Chat</a></li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <div class="chat-content pt-30px pb-20px">
                                    <?php if($request): ?>
                                        <?php $request_user_id = ($is_received && $user_id != $request->sender_user) ? $request->sender_user : $request->receiver_user; ?>
                                        <div class="connect-person-main px-3 max-w-432px mx-auto">
                                            <div class="<?php echo get_user_shield_status($request_user_id) == 1 ? 'mbr-icon-onthumb user-shield-tooltip' : ''; ?>">
                                                <div class="mbr-thumb mx-auto">
                                                    <img src="<?php echo get_avatar_url($request_user_id); ?>" alt="">
                                                </div>
                                            </div>

                                            <h4 class="text-center pt-2">
                                                <a href="<?php echo home_url(CO_OWNER_PERSON_DETAILS_PAGE.'/?id='.$request_user_id); ?>" class="text-orange">
                                                    <?php echo get_user_full_name($request_user_id); ?>
                                                </a>
                                                <span class="d-block black-text pt-2">
                                                    <?php echo $is_received ? 'Wants to connect with you' : 'You sent a connection request.'; ?>
                                                </span>
                                            </h4>

                                            <div class="white-bx-main mt-4">
                                                <?php if($request->property_id > 0): ?>
                                                    <a href="<?php echo home_url(CO_OWNER_PROPERTY_DETAILS_PAGE.'/?id='.$request->property_id); ?>" class="text-orange">
                                                        <span class="small-org">Listing: <?php echo get_property_full_address($request->property_id); ?></span>
                                                    </a>

                                                    <table class="table table-sm table-striped border font-size-14px mt-2">
                                                        <tr>
                                                            <th colspan="2" class="text-center">
                                                                Property Information
                                                            </th>
                                                        </tr>
                                                        <tr>
                                                            <td>Property Market Price</td>
                                                            <td class="text-end"><?php echo get_pl_property_original_price($request->property_id,true); ?></td>
                                                        </tr>

                                                        <?php if(get_pl_interested_in_selling($request->property_id) == 'portion_of_it'): ?>
                                                            <tr>
                                                                <td>User wishes to sell</td>
                                                                <td class="text-end"><?php echo get_pl_i_want_to_sell($request->property_id,true); ?> %</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Selling Price</td>
                                                                <td class="text-end"><?php echo get_pl_calculated($request->property_id,true); ?></td>
                                                            </tr>
                                                        <?php endif; ?>

                                                        <?php if(get_pl_enable_pool($request->property_id)): ?>
                                                            <?php $property_share = get_property_available_share($request->property_id); ?>
                                                            <?php $property_price = get_property_available_price($request->property_id); ?>
                                                            <?php if($property_share > 0): ?>
                                                                <tr>
                                                                    <td>Available Portion</td>
                                                                    <td class="text-end"><?php echo $property_share; ?> %</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Will Cost</td>
                                                                    <td class="text-end"><?php echo CO_OWNER_CURRENCY_SYMBOL.' '.number_format($property_price) ; ?></td>
                                                                </tr>
                                                            <?php else: ?>
                                                                <tr>
                                                                    <th colspan="2" class="text-center">
                                                                        <span class="text-error">Portions of the property are not available</span>
                                                                    </th>
                                                                </tr>
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                    </table>

                                                <?php endif; ?>
                                                <?php if(!empty($request->interested_in)) : ?>
                                                    <span class="gry-cnt mt-2">
                                                        Interested in <?php echo $request->interested_in; ?>% Portions @  <?php echo $request->calculated_price ? CO_OWNER_CURRENCY_SYMBOL.number_format($request->calculated_price) : ''; ?>
                                                    </span>
                                                <?php endif; ?>

                                                <p class="pt-2 mb-0">
                                                    <?php echo $request->comment; ?>
                                                    <span class="d-block pt-3">
                                                        <a href="<?php echo home_url(CO_OWNER_PERSON_DETAILS_PAGE.'/?id='.$request_user_id); ?>">Profile Link</a>
                                                    </span>
                                                </p>
                                                <?php if($request->receiver_user != $user_id && $request->receiver_user == $request->updated_by): ?>
                                                    <?php if($request->status == 2): ?>
                                                        <p class="text-danger">Receiver reject your request.</p>
                                                    <?php elseif($request->status == 3): ?>
                                                        <p class="text-danger">Receiver blocked you.</p>
                                                    <?php endif; ?>
                                                <?php endif; ?>

                                            </div>


                                            <?php if($is_received && $request && ($request->status == 0 || $request->status == 3)): ?>
                                                <div class="connect-btn d-flex pt-3">
                                                    <?php if($request->status == 0): ?>
                                                        <a href="#" data-id="<?php echo $request->id; ?>" class="connection-block-action btn btn-red rounded-pill">Block</a>
                                                        <div class="ms-auto">
                                                            <a href="#" data-id="<?php echo $request->id; ?>"  class="connection-reject-action btn btn-orange-bordered rounded-pill">Reject</a>
                                                            <a href="#" data-id="<?php echo $request->id; ?>" data-with-id="<?php echo $request_user_id; ?>" class="connection-accept-action btn btn-orange rounded-pill">Accept</a>
                                                        </div>
                                                    <?php elseif($request->updated_by == $user_id): ?>
                                                        <a href="#" data-id="<?php echo $request->id; ?>" data-with-id="<?php echo $request_user_id; ?>" class="connection-unblock-action btn btn-red rounded-pill">UnBlock</a>
                                                    <?php else: ?>
                                                        <a href="#" class="btn btn-red rounded-pill">Connection Blocked</a>
                                                    <?php endif; ?>
                                                </div>
                                            <?php elseif($request->status == 3): ?>
                                                <?php if($request->updated_by == $user_id): ?>
                                                    <div class="connect-btn d-flex pt-3">
                                                        <a href="#" data-id="<?php echo $request->id; ?>" data-with-id="<?php echo $request_user_id; ?>" class="connection-unblock-action btn btn-red rounded-pill">UnBlock</a>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="connect-btn d-flex pt-3">
                                                        <a href="#" class="btn btn-red rounded-pill">Connection Blocked</a>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif ?>
                                    <div class="row" id="message-display-box"></div>
                                </div>

                                <?php if(
                                    ($chat_with_connected && $is_group && $chat_with_connected->group_status == 1) ||
                                    ($chat_with_connected && !$is_group && $chat_with_connected->status == 1)
                                ): ?>
                                <div class="chat-footer pt-20px pb-20px ps-3 pe-3">
                                    <div class="col-12 display-input-files"></div>
                                    <div class="d-flex align-items-center">
                                        <div class="input-group">
                                            <textarea type="text" class="form-control message-input" data-id="<?php echo $chat_with; ?>" data-is-group="<?php echo $is_group?'true':'false';?>" placeholder="Type here..." aria-label="Type here..." aria-describedby="button-addon2"></textarea>
                                            <div class="input-group-append">
                                                <div class="file-field-btn">
                                                    <div class="btn-floating text-center">
                                                        <div title="Attach a file" class="file-cnt text-center sand-file-input">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20">
                                                                <g fill="none" fill-rule="evenodd">
                                                                    <g fill="#FE7400" fill-rule="nonzero">
                                                                        <g>
                                                                            <g>
                                                                                <path d="M10 0C4.477 0 0 4.477 0 10c0 5.522 4.477 10 10 10 5.522 0 10-4.478 10-10 0-5.523-4.478-10-10-10zm4.8 8.907l-6.571 6.57-.007.007-.04.04-.002-.002c-.37.345-.851.517-1.348.517-.593 0-1.209-.242-1.685-.718-.423-.423-.677-.969-.714-1.538-.037-.563.14-1.083.499-1.476l-.004-.003 6.494-6.494c.549-.549 1.48-.509 2.079.09.28.28.448.643.474 1.022.026.4-.114.785-.386 1.055l-5.286 5.287-.734-.733 5.287-5.287c.078-.078.088-.18.083-.253-.008-.126-.071-.257-.172-.358-.19-.19-.47-.23-.611-.09l-6.43 6.43c-.186.186-.278.45-.258.741.02.318.168.629.413.874.487.487 1.21.558 1.612.158l6.574-6.573c.306-.306.457-.737.426-1.212-.033-.502-.263-.989-.646-1.372-.773-.773-1.932-.872-2.584-.22l-5.439 5.438-.733-.733 5.438-5.439c1.056-1.056 2.874-.957 4.051.22.562.561.898 1.284.948 2.037.052.778-.207 1.493-.728 2.015z" transform="translate(-1028 -966) translate(603 926) translate(425 40)"></path>
                                                                            </g>
                                                                        </g>
                                                                    </g>
                                                                </g>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ps-2">
                                            <a href="#" class="btn btn-orange rounded-pill px-4 message-send-action">Send</a>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <?php if($chat_with_connected && $is_group && $chat_with_connected->group_status == 2): ?>
                                <div class="chat-footer pt-20px pb-20px ps-3 pe-3 text-center">
                                    Closed a pool deal are completed
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-3 col-md-12  d-sm-block px-0 chat-two-col">
                    <div class="h-83vh">
                        <div class="d-flex h-100">
                            <div class="msg-right-section">
                                <div class="pnl-title bb-1px d-flex align-items-center pt-2 pb-2 ps-3 pe-3 min-h-50px">
                                    <h5>Details</h5>
                                    <div class="ms-auto">
                                        <a href="#">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                                <g fill="none" fill-rule="evenodd">
                                                    <g>
                                                        <g>
                                                            <path d="M16 0L0 0 0 16 16 16z" transform="translate(-1406 -178) translate(1406 178)"></path>
                                                            <path fill="#262626" fill-opacity=".65" fill-rule="nonzero" d="M2.47 2.47c.293-.293.768-.293 1.06 0l4.718 4.718 4.718-4.718c.266-.267.683-.29.976-.073l.085.073c.292.292.293.767 0 1.06L9.308 8.25l4.719 4.72c.266.266.29.683.072.976l-.072.085c-.293.292-.768.292-1.061 0L8.248 9.31l-4.718 4.72c-.266.266-.682.29-.976.072l-.084-.072c-.293-.293-.293-.768 0-1.061l4.717-4.72L2.47 3.53c-.267-.266-.29-.683-.073-.976z" transform="translate(-1406 -178) translate(1406 178)"></path>
                                                        </g>
                                                    </g>
                                                </g>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                                <?php if($chat_with_connected): ?>
                                    <div class="usr-detail text-center pt-20px pb-20px">
                                        <?php if(!$is_group): ?>
                                            <div class="list-thumb mx-auto">
                                                <img src="<?php echo get_avatar_url($chat_with_connected->user_info->ID)?>" alt="">
                                            </div>
                                            <h6>
                                                <a class="text-orange" href="<?php echo home_url(CO_OWNER_PERSON_DETAILS_PAGE.'?id='.$chat_with_connected->user_info->ID); ?>">
                                                    <?php echo $connected_chat_title; ?>
                                                </a>
                                                <span>Connected</span>
                                            </h6>
                                        <?php else: ?>
                                            <div class="list-icon mx-auto">
                                                <?php echo co_owner_get_svg('enable_pool'); ?>
                                            </div>
                                            <h6>
                                                <a class="text-orange" href="<?php echo home_url(CO_OWNER_PROPERTY_DETAILS_PAGE."?id={$chat_with_connected->property_id}"); ?>"><?php echo $connected_chat_title; ?></a>
                                                <span><?php echo count($chat_with_connected->members); ?> members</span>
                                            </h6>
                                        <?php endif; ?>
                                    </div>
                                    <div class="msg-accord">
                                    <div class="accordion" id="accordionPanelsStayOpenExample">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="panelsStayOpen-headingOne">
                                                <button title="All about your friend" class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                                                    About
                                                </button>
                                            </h2>
                                            <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingOne">
                                                <div class="accordion-body">
                                                    <div class="aco-inner-cnt">
                                                        <?php if(!$is_group && $chat_with_connected): ?>
                                                            <h6>Budget
                                                                <span><?php echo $chat_with_connected->user_info->budget ? (CO_OWNER_CURRENCY_SYMBOL).number_format($chat_with_connected->user_info->budget) : null;?></span>
                                                            </h6>
                                                            <?php if($chat_with_connected->user_info->user_status == 1): ?>
                                                                <?php if(!empty($chat_with_connected->user_info->mobile)): ?>
                                                                <h6>Phone Number
                                                                    <span><?php echo $chat_with_connected->user_info->mobile; ?></span>
                                                                </h6>
                                                                <?php endif; ?>
                                                                <h6>Email ID
                                                                    <span><a href="mailto:<?php echo $chat_with_connected->user_info->user_email; ?>"><?php echo $chat_with_connected->user_info->user_email; ?></a></span>
                                                                </h6>
                                                            <?php endif; ?>
                                                            <h6>
                                                                Interested In
                                                                <?php $interested_in_properties = CoOwner_Conversation::get_interested_properties($user_id,$chat_with); ?>
<!--                                                                --><?php //$interested_in_properties = array_merge($interested_in_properties,$interested_in_properties); ?>
                                                                <?php foreach ($interested_in_properties as $interested_in_property): ?>
                                                                    <span class="d-flex">
                                                                        <a class="text-orange" href="<?php echo home_url(CO_OWNER_PROPERTY_DETAILS_PAGE."?id={$interested_in_property->id}"); ?>">
                                                                            <div class="text-truncate" data-toggle="tooltip" data-placement="top" title="<?php echo $interested_in_property->address; ?>">
                                                                                Listing: <?php echo $interested_in_property->address; ?>
                                                                            </div>
                                                                        </a>
                                                                    </span>
                                                                <?php endforeach; ?>

                                                            </h6>
                                                        <?php else: ?>
                                                            <h6>Created By
                                                                <span><?php echo get_user_full_name($chat_with_connected->user_id); ?></span>
                                                            </h6>
                                                            <?php $property_share = get_property_available_share($chat_with_connected->property_id);?>
                                                            <?php if($property_share > 0): ?>
                                                                <h6>Available portions
                                                                    <span><?php echo get_property_available_share($chat_with_connected->property_id); ?> %</span>
                                                                </h6>
                                                                <h6>Cost
                                                                    <span><?php echo CO_OWNER_CURRENCY_SYMBOL .number_format(get_property_available_price($chat_with_connected->property_id));?></span>
                                                                </h6>
                                                            <?php else: ?>
                                                                <h6>
                                                                    Available Portions
                                                                    <span class="text-error">Portions of the property are not available</span>
                                                                </h6>
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <?php if($is_group): ?>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="accor-members">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#members-box" aria-expanded="false" aria-controls="members-box">
                                                    Member (<?php echo count($chat_with_connected->members); ?>)
                                                </button>
                                            </h2>
                                            <div id="members-box" class="accordion-collapse collapse" aria-labelledby="accor-members">
                                                <div class="accordion-body">
                                                    <div class="aco-inner-cnt">
                                                        <?php foreach($chat_with_connected->members as $member): ?>
                                                            <div class="pnl-title d-flex align-items-center pt-2 pb-2">
                                                                <div class="side-list-main">
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="list-thumb">
                                                                            <img src="<?php echo get_avatar_url($member->id); ?>" alt="">
                                                                        </div>
                                                                        <div class="side-lst-cnt">
                                                                            <a href="<?php echo home_url(CO_OWNER_PERSON_DETAILS_PAGE."?id={$member->id}"); ?>">
                                                                                <h6>
                                                                                    <?php echo $member->display_name; ?><?php echo $member->id == $user_id ? ' (You)' : ''; ?>
                                                                                    <?php echo $member->is_admin ? '<span>Admin</span>' : ''; ?>
                                                                                </h6>
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <?php if(!$member->is_admin && $chat_with_connected->user_id == $user_id): ?>
                                                                <div class="dropdown member-drop dropstart ms-auto">
                                                                    <button class="btn dropdown-toggle" type="button" id="dropdownMenu0" data-bs-toggle="dropdown" aria-expanded="false">
                                                                        <!--?xml version="1.0" encoding="utf-8"?-->
                                                                        <!-- Generator: Adobe Illustrator 25.2.2, SVG Export Plug-In . SVG Version: 6.00 Build 0)  -->
                                                                        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 32 18" style="enable-background:new 0 0 32 18;" xml:space="preserve">
                                                                                    <style type="text/css">
                                                                                        .st0{opacity:0.65;fill:#262626;}
                                                                                    </style>
                                                                            <g>
                                                                                <circle class="st0" cx="3.4" cy="9" r="3.2"></circle>
                                                                                <circle class="st0" cx="16.1" cy="9" r="3.2"></circle>
                                                                                <circle class="st0" cx="28.6" cy="9" r="3.2"></circle>
                                                                            </g>
                                                                                </svg>
                                                                    </button>
                                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenu0">
                                                                        <?php if($chat_with_connected->group_status == 1): ?>
                                                                        <li>
                                                                            <a data-connection-id="<?php echo $chat_with_connected->id; ?>"
                                                                               data-id="<?php echo $member->id; ?>"
                                                                               class="remove-group-connection dropdown-item" href="#">Remove
                                                                            </a>
                                                                        </li>
                                                                        <li>
                                                                            <a data-connection-id="<?php echo $chat_with_connected->id; ?>"
                                                                               data-id="<?php echo $member->id; ?>"
                                                                               class="dropdown-item <?php echo $member->status != 1 ? 'unblock-group-connection' : 'block-group-connection';?>" href="#">
                                                                                <?php echo $member->status != 1 ? 'Unblock' : 'Block';?>
                                                                            </a>
                                                                        </li>
                                                                        <?php endif; ?>
                                                                        <li><a
                                                                                data-interested-in="<?php echo $member->interested_in; ?>"
                                                                                data-calculated-price="<?php echo $member->calculated_price; ?>"
                                                                                data-id="<?php echo $member->id; ?>"
                                                                                class="dropdown-item assign-another-pool" href="#">Assign another pool</a></li>
                                                                    </ul>
                                                                </div>
                                                                <?php endif; ?>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endif; ?>

                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="panelsStayOpen-headingThree">
                                                <button title="See all your files and links" class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#files_and_links" aria-expanded="false" aria-controls="files_and_links">
                                                    Files &amp; Links
                                                </button>
                                            </h2>
                                            <div id="files_and_links" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingThree">
                                                <div class="accordion-body">
                                                    <div class="aco-inner-cnt">
                                                        <ul>
                                                            <?php
                                                            foreach($chat_files as $file_attachment):
                                                                include CO_OWNER_THEME_DIR.'/parts/files.php';
                                                            endforeach;
                                                            ?>
                                                            <?php if($count > count($chat_files)): ?>
                                                                <li>
                                                                    <a
                                                                        data-is-group="<?php echo $is_group ? 'true' : 'false' ?>"
                                                                        data-chat-with-connected="<?php echo $chat_with_connected->id ?>"
                                                                        data-current-page="1"
                                                                        data-chat-with="<?php echo $chat_with; ?>"
                                                                        data-clear-chat-date="<?php echo $clear_chat_date; ?>"
                                                                        href="#" class="load-more-files notification-item text-center text-orange form-text">Load More</a>
                                                                </li>
                                                            <?php endif; ?>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if(isset($_GET['with'])){ ?>
	
<?php }else{ ?>
<?php if(!isset($_GET['is_received'])){ ?>
<script type="text/javascript">
    jQuery(document).ready(function($) {
	var redirecturl = $(".search-filter-connection.select a.d-flex.align-items-center").attr('href');
	var totalconnect = '<?php echo count($connected_connections); ?>';
	if(totalconnect > 0){
	location.href = redirecturl;
	}

});
     
</script>
<?php } } ?>
<?php get_no_footer(); ?>
