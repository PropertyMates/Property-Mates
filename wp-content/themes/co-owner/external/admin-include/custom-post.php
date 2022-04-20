<?php

class CoOwner_custom_post_tyoes {

    public static function status()
    {
        register_post_status( 'completed', array(
            'label'                     => _x( 'Completed', 'post' ),
            'public'                    => true,
            'exclude_from_search'       => false,
            'show_in_admin_all_list'    => true,
            'show_in_admin_status_list' => true,
            'label_count'               => _n_noop( 'Completed <span class="count">(%s)</span>','Completed <span class="count">(%s)</span>' ),
        ) );
    }

    public static function property()
    {   $type = 'property';
        $uc_type = ucfirst('property');
        $labels = array(
            "name"               => _x( "Properties", "post type general name" ),
            "singular_name"      => _x( "{$uc_type}", "post type singular name" ),
            "add_new"            => __( "Add New"),
            "add_new_item"       => __( "Add New {$uc_type}" ),
            "edit_item"          => __( "Edit {$uc_type}" ),
            "new_item"           => __( "New {$uc_type}" ),
            "all_items"          => __( "All Properties" ),
            "view_item"          => __( "View {$uc_type}" ),
            "search_items"       => __( "Search {$uc_type}" ),
            "not_found"          => __( "No faq found" ),
            "not_found_in_trash" => __( "No faq found in the Trash" ),
            "menu_name"          => "Properties",

        );
        $args = array(
            "labels"        => $labels,
            "description"   => "Holds our {$type} specific data",
            "public"        => true,
            "menu_position" => 6,
            "supports"      => array( "title","editor",'author','comments'),
            "has_archive"   => true,
            "rewrite" => array("slug" => "{$type}"),
        );
        register_post_type( "{$type}",$args );
    }

    public static function faq()
    {
        $labels = array(
            'name'               => _x( 'Faq', 'post type general name' ),
            'singular_name'      => _x( 'Faq', 'post type singular name' ),
            'add_new'            => __( 'Add New'),
            'add_new_item'       => __( 'Add New Faq' ),
            'edit_item'          => __( 'Edit Faq' ),
            'new_item'           => __( 'New Faq' ),
            'all_items'          => __( 'All Faqs' ),
            'view_item'          => __( 'View Faq' ),
            'search_items'       => __( 'Search Faq' ),
            'not_found'          => __( 'No faq found' ),
            'not_found_in_trash' => __( 'No faq found in the Trash' ),
            'menu_name'          => 'Faq'
        );
        $args = array(
            'labels'        => $labels,
            'description'   => 'Holds our Faq specific data',
            'public'        => true,
            'menu_position' => 6,
            //'show_in_rest' => true,
            'supports'      => array( 'title','editor'),
            'has_archive'   => true,
            'rewrite'       => array('slug' => 'faqs'),
            'menu_icon'         => 'dashicons-format-aside'
        );
        register_post_type( 'faqs',$args );
    }

    public static function install_post($type)
    {
        $uc_type = ucfirst($type);
        $labels = array(
            "name"               => _x( "{$uc_type}", "post type general name" ),
            "singular_name"      => _x( "{$uc_type}", "post type singular name" ),
            "add_new"            => __( "Add New"),
            "add_new_item"       => __( "Add New {$uc_type}" ),
            "edit_item"          => __( "Edit {$uc_type}" ),
            "new_item"           => __( "New {$uc_type}" ),
            "all_items"          => __( "All {$uc_type}s" ),
            "view_item"          => __( "View {$uc_type}" ),
            "search_items"       => __( "Search {$uc_type}" ),
            "not_found"          => __( "No faq found" ),
            "not_found_in_trash" => __( "No faq found in the Trash" ),
            "menu_name"          => "{$uc_type}s",

        );
        $args = array(
            "labels"        => $labels,
            "description"   => "Holds our {$type} specific data",
            "public"        => true,
            "menu_position" => 6,
            "supports"      => array( "title","editor",'author'),
            "has_archive"   => true,
            "rewrite" => array("slug" => "{$type}"),
        );

        if($type == 'feedback'){
            $args['menu_icon'] = 'dashicons-feedback';
        }
        elseif($type == 'property'){
            $args['menu_icon'] = 'dashicons-store';
        }
        register_post_type( "{$type}",$args );
    }

    public static function feedback()
    {
        self::install_post('feedback');
    }

    public static function wpb_append_post_status_list()
    {
        global $post;
        $complete = '';
        $label = '';
        if($post && $post->post_type == 'property'){
            if($post->post_status == 'completed'){
                $complete = ' selected=\"selected\"';
                $label = 'Completed';
            }
            echo '<script>
            jQuery(document).ready(function($){
                $("select#post_status").append( "<option value=\"completed\" '.$complete.'>Completed</option>" );
                $("#post-status-display").append("'.$label.'");
            });
            </script>
            ';
            echo "<script>
            jQuery(document).ready( function() {
                jQuery( 'select[name=\"_status\"]' ).append( '<option value=\"completed\">Completed</option>' );
            });
            </script>";

        }
    }
}

add_action( 'init', function (){
    CoOwner_custom_post_tyoes::property();
    CoOwner_custom_post_tyoes::faq();
    CoOwner_custom_post_tyoes::feedback();
    CoOwner_custom_post_tyoes::status();
});
add_action('admin_footer', array('CoOwner_custom_post_tyoes','wpb_append_post_status_list'));


if (is_admin()) {

    function set_custom_edit_feedback_columns($columns)
    {
        $columns['author'] = __('Author', 'your_text_domain');
        return $columns;
    }
    function custom_feedback_column($column, $post_id)
    {
        switch ($column) {
            case 'author' :
                echo get_user_full_name(get_post_field('post_author', $post_id));
            break;
        }
    }
    add_filter('manage_feedback_posts_columns', 'set_custom_edit_feedback_columns');
    add_action('manage_feedback_posts_custom_column', 'custom_feedback_column', 10, 2);

    add_filter('manage_faqs_posts_columns', 'set_custom_edit_feedback_columns');
    add_action('manage_faqs_posts_custom_column', 'custom_feedback_column', 10, 2);

    function co_owner_manage_property_table_columns($columns)
    {
        $columns['author'] = __('Author', 'your_text_domain');
        if (isset($_GET['enable_pool']) && $_GET['enable_pool'] == 1) {
            $columns['members'] = 'Members';
        }
        $columns['market_price'] = 'Market Price';
        $columns['i_want_to_sell'] = 'I Want To Sell';
        $columns['selling_price'] = 'Selling Price';
        if (isset($_GET['enable_pool']) && $_GET['enable_pool'] == 1) {
            $columns['available_share'] = 'Available Portion';
            $columns['available_price'] = 'Available Price';
        }
        unset($columns['ws_plugin__s2member_pro_lock_icons']);
        return $columns;
    }
    add_filter('manage_property_posts_columns', 'co_owner_manage_property_table_columns', 12, 1);

    function co_owner_manage_property_posts_custom_column($column, $post_id)
    {
        switch ($column) {
            case 'author' :
                echo get_user_full_name(get_post_field('post_author', $post_id));
            break;
            case 'members' :
                $members = get_property_total_members($post_id);
                echo count($members);
            break;
            case 'i_want_to_sell' :
                $selling = get_post_meta($post_id,'_pl_interested_in_selling',true);
                if($selling == 'portion_of_it'){
                    $i_want_to_sell = get_post_meta($post_id,'_pl_i_want_to_sell',true);
                    echo $i_want_to_sell."%";
                } else {
                    echo '-';
                }
            break;
            case 'available_share' :
                $shares = get_property_available_share($post_id);
                echo $shares > 0 ? "{$shares}%" : '-';
            break;
            case 'available_price' :
                $price = get_property_available_price($post_id);
                echo $price > 0 ? CO_OWNER_CURRENCY_SYMBOL.number_format($price) : '-';
            break;
            case 'market_price' :
                $price = get_post_meta($post_id,'_pl_property_original_price',true);
                echo $price > 0 ? CO_OWNER_CURRENCY_SYMBOL.' '.number_format($price) : '-';
            break;
            case 'selling_price' :
                $price = get_post_meta($post_id,'_pl_calculated',true);
                echo $price > 0 ? CO_OWNER_CURRENCY_SYMBOL.' '.number_format($price) : '-';
            break;
        }
    }
    add_action('manage_property_posts_custom_column', 'co_owner_manage_property_posts_custom_column', 10, 2);

    function co_owner_admin_apply_custom_posttype_filters($query)
    {
        $type = 'post';
        if (isset($_GET['post_type'])) {
            $type = $_GET['post_type'];
        }
        if($type == 'property'){
            if(isset($_GET['enable_pool']) && in_array($_GET['enable_pool'],[0,1]) == true ){
                $meta_filter = array();
                $meta_filter[] = array(
                    'key' => '_pl_enable_pool',
                    'value' => 1,
                    'type' => 'BOOLEAN',
                    'compare' => (isset($_GET['enable_pool']) && $_GET['enable_pool'] == 1) ? '=' : '!='
                );
                $query->set('meta_query',$meta_filter);
            }
        }
    }
    add_filter( 'parse_query', 'co_owner_admin_apply_custom_posttype_filters',10,1);

    add_filter( 'post_row_actions', 'remove_row_actions', 10, 2 );
    function remove_row_actions( $unset_actions, $post ) {
        global $current_screen;
        if ( in_array($current_screen->post_type,['property','faqs','feedback'])){
            unset( $unset_actions[ 'inline hide-if-no-js' ] );
        }
        return $unset_actions;
    }




    function co_owner_property_custom_meta_inputs($post_type,$post)
    {
        if($post_type == 'property') {
            add_meta_box(
                "heavy-inputs",
                "Images",
                "co_owner_property_heavy_inputs",
                "property"
            );
        }
    }
    add_action("add_meta_boxes", "co_owner_property_custom_meta_inputs",10,2);
    function co_owner_property_heavy_inputs($post,$meta_box){
        $features = $post ? get_post_meta($post->ID,'_pl_property_features',true ) : (isset($_POST['_pl_property_features']) ? $_POST['_pl_property_features'] : array());
        $property_features = (empty($features) || $features == null) ? array() : $features;

        $m_features = $post ? get_post_meta(  $post->ID,'_pl_manually_features',true ) : (isset($_POST['_pl_manually_features']) ? $_POST['_pl_manually_features'] : array());
        $manually_features = (empty($m_features) || $m_features == null) ? array() : $m_features;
        $i_want_to_sell = $post ? get_post_meta(  $post->ID,'_pl_i_want_to_sell',true ) : (isset($_POST['_pl_i_want_to_sell']) ? $_POST['_pl_i_want_to_sell'] : null);
        $calculated = $post ? get_post_meta(  $post->ID,'_pl_calculated',true ) : (isset($_POST['_pl_calculated']) ? $_POST['_pl_calculated'] : null);
        $old_images = $post ? get_post_meta($post->ID,'_pl_images',true) : null;
        $address = $post ? get_property_full_address($post->ID,true) : null;
        $address_manually = $post ? get_post_meta($post->ID,'_pl_address_manually',true) : null;
        $address_manually = $address_manually ? true : false;
        $images = is_array($old_images) ? $old_images : array();
        ?>
        <div class="cf-field cf-text cf-field--has-width residential-inputs pl-property-features-box">
            <table class="form-table pl-property-features">
                <tr>
                    <th class="p-0">
                        <label for="_user_property_features"><?php _e("Property Features"); ?>
                            <span class="cf-field__asterisk">*</span>
                        </label>
                    </th>
                </tr>
                <tr>
                    <td class="check-list p-0">
                        <table style="width: 50%;">
                            <tr>
                                <td><label><input id="_user_property_features" name="_pl_property_features[]" <?php echo in_array('Air Conditioning',$property_features) ? 'checked' : null; ?> value="Air Conditioning" type="checkbox" >Air Conditioning</label></td>
                                <td><label><input name="_pl_property_features[]" <?php echo in_array('Build in wardrobes',$property_features) ? 'checked' : null; ?> value="Build in wardrobes" type="checkbox" >Build in wardrobes</label></td>
                                <td><label><input name="_pl_property_features[]" <?php echo in_array('Floorboards',$property_features) ? 'checked' : null; ?> value="Floorboards" type="checkbox" >Floorboards</label></td>
                            </tr>
                            <tr>
                                <td><label><input name="_pl_property_features[]" <?php echo in_array('Gas',$property_features) ? 'checked' : null; ?> value="Gas" type="checkbox" >Gas</label></td>
                                <td><label><input name="_pl_property_features[]" <?php echo in_array('Swimming Pool',$property_features) ? 'checked' : null; ?> value="Swimming Pool" type="checkbox" >Swimming Pool</label></td>
                                <td><label><input name="_pl_property_features[]" <?php echo in_array('Furnished',$property_features) ? 'checked' : null; ?> value="Furnished" type="checkbox" >Furnished</label></td>
                            </tr>
                            <tr>
                                <td><label><input name="_pl_property_features[]" <?php echo in_array('Indoor Gym',$property_features) ? 'checked' : null; ?> value="Indoor Gym" type="checkbox" >Indoor Gym</label></td>
                                <td><label><input name="_pl_property_features[]" <?php echo in_array('Alarm System',$property_features) ? 'checked' : null; ?> value="Alarm System" type="checkbox" >Alarm System</label></td>
                                <td><label><input name="_pl_property_features[]" <?php echo in_array('Dishwasher',$property_features) ? 'checked' : null; ?> value="Dishwasher" type="checkbox" >Dishwasher</label></td>
                            </tr>
                        </table>
                        <label id="_pl_property_features[]-error" class="cf-field__error" for="_pl_property_features[]">
                    </td>
                </tr>
            </table>
        </div>
        <div class="cf-field cf-text cf-field--has-width residential-inputs pl-property-manually-features-box">
            <table class="form-table">
                <tr>
                    <th class="p-0">
                        <label for="_pl_manually_features"><?php _e("Property Manully Features"); ?></label>
                    </th>
                </tr>
                <tr>
                    <td class="p-0">
                        <select id="_pl_manually_features" name="_pl_manually_features[]" class="select2-tags" multiple style="width: 100% !important;">
                            <?php foreach ($manually_features as $value) : ?>
                                <option selected value="<?php echo $value; ?>"><?php echo $value; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
            </table>
        </div>
        <div class="cf-field cf-text cf-field--has-width portion-of-it-box pl-shares" style="flex-basis: 10%;">
            <table class="form-table">
                <tr>
                    <th class="p-0">
                        <label for="_pl_i_want_to_sell"><?php _e("I Want To Sell %"); ?></label>
                    </th>
                </tr>
                <tr>
                    <td class="p-0">
                        <select
                            id="_pl_i_want_to_sell"
                            name="_pl_i_want_to_sell"
                            class="single-pr-select2"
                            data-property-value-input='[name="carbon_fields_compact_input[_pl_property_market_price]"]'
                            data-calculated-value-input="#_pl_calculated"
                        >
                            <option value="">Select I Want To Sell</option>
                            <?php
                            $array_ = array();
                            for ($i = 1; $i <= 99; $i++):
                                $array_[] = $i;
                                ?>
                                <option <?php echo ($i_want_to_sell == $i ) ? 'selected' : null; ?> value="<?php echo $i; ?>"><?php echo $i; ?>%</option>
                            <?php endfor; ?>
                            <?php if(!in_array($i_want_to_sell,$array_) && $i_want_to_sell != null): ?>
                                <option selected value="<?php echo $i_want_to_sell; ?>"><?php echo $i_want_to_sell; ?>%</option>
                            <?php endif; ?>
                        </select>
                        <label id="_pl_i_want_to_sell-error" class="error cf-field__error" for="_pl_i_want_to_sell"></label>
                    </td>
                </tr>
            </table>
        </div>
        <div class="cf-field cf-text cf-field--has-width portion-of-it-box pl-calculated" style="flex-basis: 10%;">
            <table class="form-table">
                <tr>
                    <th class="p-0">
                        <label for="_pl_calculated"><?php _e("Calculated"); ?></label>
                    </th>
                </tr>
                <tr>
                    <td class="p-0">
                        <input type="text" readonly id="_pl_calculated" name="_pl_calculated" class="cf-text__input" value="<?php echo $calculated; ?>">
                        <label id="_pl_calculated-error" class="error cf-field__error" for="_pl_calculated"></label>
                    </td>
                </tr>
            </table>
        </div>
        <div class="co-owner-row media-images">
            <?php foreach ($images as $key => $image): ?>
                <div class="co-owner-col co-owner-col-md-2 img-preview-box">
                    <img src="<?php echo $image['url']; ?>" alt="">
                    <a href="#" data-index="<?php echo $key; ?>" class="text-error remove-old-image">Remove</a>
                </div>
            <?php endforeach; ?>
            <div class="co-owner-col co-owner-col-md-2">
                <div class="media-select"><button type="button" id="open-wp-media-library" class="button">Select Image</button></div>
            </div>
        </div>

        <div class="cf-field cf-text pl-address-api-select">
            <table class="form-table">
                <tr>
                    <th class="p-0">
                        <label for="_pl_address"><?php _e("Address"); ?></label>
                        <a href="#" class="add-manually-property-address button button-small" style="margin-bottom: 5px;">
                            <?php echo $address_manually ? 'Add By Suggestion' : 'Add Manually'; ?>
                        </a>
                        <input type="hidden" name="_pl_address_manually" value="<?php echo $address_manually ? 'true' : 'false'; ?>">
                    </th>
                </tr>
                <tr>
                    <td class="p-0 address-by-suggest" style="display:<?php echo $address_manually ? 'none' : 'block'; ?>;">
                        <select class="select2-property-address-api" name="_pl_address" id="_pl_address" style="width: 100%;">
                            <?php if($address): ?>
                                <option selected value="<?php echo $address; ?>"><?php echo $address; ?></option>
                            <?php endif; ?>
                        </select>
                        <label id="_pl_address-error" class="cf-field__error" for="_pl_address"></label>
                    </td>
                </tr>
            </table>
        </div>
        <?php
    }

    function co_owner_property_members_box($post_type,$post)
    {
        if($post_type == 'property' && get_post_meta($post->ID,'_pl_enable_pool',true)) {
            add_meta_box(
                "property-members-inputs",
                "Members",
                "co_owner_property_members_inputs",
                "property"
            );
        }
    }
    add_action("add_meta_boxes", "co_owner_property_members_box",10,2);
    function co_owner_property_members_inputs($post,$meta_box)
    {
        global $wp;
        $members = get_property_total_members($post->ID);
        ?>
        <div class="co-owner-row">
            <?php foreach ($members as $key => $member){
                $remove_action_url = home_url(add_query_arg($wp->request,array('member'=>$member->id,'co_owner_action'=>'remove_member_from_group')));?>
                <div class="co-owner-col-md-3 member-box">
                    <div class="card member-card <?php echo $member->is_admin ? 'green' : ( ($key % 2) ? 'red' : 'yellow'); ?>">
                        <div class="card-body">
                            <div class="mbr-title d-flex w-100">
                                <?php if(!$member->is_admin): ?>
                                    <a class="alignright" href="<?php echo $remove_action_url; ?>">Remove ?</a>
                                <?php endif; ?>
                                <h6>Member <?php echo ($key)+1; ?></h6>
                            </div>
                            <div class="mbr-detail-area">
                                <div class="<?php echo get_user_shield_status($member->id) == 1 ? 'mbr-icon-onthumb user-shield-tooltip' : ''?> mt--46px">
                                    <div class="mbr-thumb mx-auto">
                                        <img src="<?php  echo esc_url( get_avatar_url($member->id));  ?>" alt="">
                                    </div>
                                </div>
                                <a target="_blank" href="<?php echo home_url('/'.CO_OWNER_PERSON_DETAILS_PAGE).'?id='.$member->id; ?>">
                                    <h4 class="text-center"><?php echo $member->display_name; ?></h4>
                                </a>
                                <div class="property-own text-center">
                                    <?php echo ($member->is_admin) ? 'Property Owner | Admin ' : ( $member->interested_in.'% Portion' ) ?>
                                </div>

                                <span class="title text-center">Email id</span>
                                <span class="cnt text-center">
                                    <a target="_blank" href="mailto:<?php echo $member->user_email; ?>">
                                        <?php echo $member->user_email; ?>
                                    </a>
                                </span>
                                <span class="title text-center">Phone No</span>
                                <span class="cnt text-center"><?php echo $member->mobile; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
        <?php
    }


    function co_owner_on_save_property( $post_id, $post, $update)
    {
        $meta_value1 = isset($_POST["_pl_property_features"]) ?  $_POST["_pl_property_features"]  : array();
        update_post_meta($post_id,"_pl_property_features", $meta_value1);

        $meta_value2 = isset($_POST["_pl_manually_features"]) ?  $_POST["_pl_manually_features"]  : array();
        update_post_meta($post_id,"_pl_manually_features", $meta_value2);

        if(isset($_POST['ID'])){
            $total_member = count(get_property_total_members($_POST['ID'],true));
            if($total_member <= 0){
                $pl_i_want_to_sell = isset($_POST['_pl_i_want_to_sell']) ? (float) $_POST['_pl_i_want_to_sell'] : null;
                $pl_calculated = isset($_POST['_pl_calculated']) ? (float) $_POST['_pl_calculated'] : null;
                update_post_meta($post_id,"_pl_i_want_to_sell", $pl_i_want_to_sell);
                update_post_meta($post_id,"_pl_calculated", $pl_calculated);
            }
        }

        $images = isset($_POST['_pl_property_new_image']) ? $_POST['_pl_property_new_image'] : array();

        $removed_old_images = isset($_POST['remove_old_image']) ? $_POST['remove_old_image'] : array();
        foreach ($removed_old_images as $image_index){
            remove_property_image($post_id,$image_index);
        }

        if(count($images) > 0){
            $uploads = wp_upload_dir();
            $old_images = get_post_meta($post_id,'_pl_images',true);
            $new_images = (is_array($old_images) && count($old_images) > 0) ? $old_images : array();
            foreach ($images as $image) {
                $post = get_post($image);
                $extension = pathinfo($post->guid, PATHINFO_EXTENSION);
                $filename = mt_rand() . '.' . $extension;
                $new_file = $uploads['path'] . "/$filename";
                if (@copy($post->guid, $new_file)) {
                    $stat = stat(dirname($new_file));
                    $perms = $stat['mode'] & 0000666;
                    chmod($new_file, $perms);
                    $url = $uploads['url'] . "/$filename";
                    $type = wp_check_filetype($filename);
                    $new_images[] = array(
                        'file' => $new_file,
                        'url' => $url,
                        'type' => $type['type'],
                    );
                }
            }
            update_post_meta($post_id,'_pl_images',$new_images);
        }

        $address_manually = isset($_POST['_pl_address_manually']) && $_POST['_pl_address_manually'] == 'true' ? true : false;
        update_post_meta($post_id,"_pl_address_manually",$address_manually);
    }
    add_action("save_post_property", "co_owner_on_save_property",10,3);

    function co_owner_save_after_property($post_id, $post)
    {
        check_and_create_property_group($post_id);
        $market_price = get_post_meta($post_id,"_pl_property_market_price",true);
        update_post_meta($post_id,"_pl_property_original_price", $market_price);
        update_property_price_for_search($post_id);
    }
    add_action("save_post_property", "co_owner_save_after_property",10,2);

    add_filter('wp_dropdown_users', 'co_owner_post_author');
    function co_owner_post_author($output)
    {
        global $post;
        $users = get_users(array('role__not_in'=>'administrator'));
        $output = "<select id=\"post_author_override\" name=\"post_author_override\" class=\"\" style='width: 100%;'>";

        $output .= '<option value="">Please select author</option>';
        foreach($users as $user) {
            $sel = ($post && $post->post_author == $user->ID)?"selected='selected'":'';
            $output .= '<option value="'.$user->ID.'"'.$sel.'>'.(ucfirst($user->first_name)." ".$user->last_name).'</option>';
        }
        $output .= "</select>";
        return $output;
    }


    function co_owner_field_is_valid_for_save($field,$value = null)
    {
        if(
            isset($_POST['post_type']) &&
            isset($_POST['ID']) &&
            $_POST['post_type'] == 'property'
        ) {
            $fields = array(
                '_pl_property_original_price',
                '_pl_interested_in_selling',
                '_pl_this_property_is',
                '_pl_enable_pool',
                '_pl_property_market_price',
                '_pl_i_want_to_sell',
                '_pl_calculated',
            );
            if(in_array($field->get_name(),$fields)){
                $total_member = count(get_property_total_members($_POST['ID'],true));
                if($total_member > 0){
                    return false;
                }
            }
        }
        return true;
    }
    function co_owner_update_carbon_inputs($save,$value,$field)
    {
        return co_owner_field_is_valid_for_save($field,$value);
    }
    function co_owner_delete_carbon_inputs($save,$field)
    {
        return co_owner_field_is_valid_for_save($field);
    }

    add_filter('carbon_fields_should_save_field_value','co_owner_update_carbon_inputs',10,3);
    add_filter('carbon_fields_should_delete_field_value_on_save','co_owner_delete_carbon_inputs',10,2);


    add_filter( 'post_updated_messages', 'co_owner_post_updated_messages' );


    function co_owner_post_updated_messages( $messages ) {
        $messages['property'] = array(
            0  => '',
            1  => __( 'Property updated.' ),
            4  => __( 'Property updated.' ),
            6  => __( 'Property published.' ),
            7  => __( 'Property saved.' ),
            8  => __( 'Property submitted.' ),
            10 => __( 'Property draft updated.' )
        );
        return $messages;
    }

    add_filter('views_edit-property','co_owner_property_quicklinks');
    function co_owner_property_quicklinks($views)
    {
        $enable = isset($_GET['enable_pool']) ? $_GET['enable_pool'] == 1 ? 'enable' : 'disabled' : '';
        $views['enabled_pool'] = "<a class='".( $enable == 'enable' ? 'current' : '')."' href='".(admin_url('edit.php?post_type=property&enable_pool=1'))."'>Enabled Pool</a>";
        $views['disabled_pool'] = "<a class='".( $enable == 'disabled' ? 'current' : '')."' href='".(admin_url('edit.php?post_type=property&enable_pool=0'))."'>Disabled Pool</a>";

        return $views;
    }


    add_filter( 'bulk_actions-edit-property', 'remove_from_bulk_actions' );
    add_filter( 'bulk_actions-edit-feedback', 'remove_from_bulk_actions' );
    add_filter( 'bulk_actions-edit-forum', 'remove_from_bulk_actions' );
    add_filter( 'bulk_actions-edit-topic', 'remove_from_bulk_actions' );
    add_filter( 'bulk_actions-edit-reply', 'remove_from_bulk_actions' );
    function remove_from_bulk_actions( $actions ){
        unset( $actions[ 'edit' ] );
        return $actions;
    }


    function co_owner_admin_add_custom_posttype_filters($post_type = 'post',$which)
    {
        if (in_array($post_type,['property','faqs','feedback'])  && $which == 'top') {
            if(isset($_GET['enable_pool']) && $post_type == 'property'){
                echo "<input type='hidden' name='enable_pool' value='{$_GET['enable_pool']}'>";
            }

            $args = array(
                'role__not_in' => 'administrator',
                'fields' => array(
                    'ID',
                    'display_name',
                )
            );
            $users = get_users($args);
            $shorted_by = isset($_GET['shorted_by']) ? $_GET['shorted_by'] : null;
            $author = isset($_GET['author']) ? $_GET['author'] : null;
            ?>
            <select name="author">
                <option value="">Select Author</option>
                <?php foreach ($users as $user): ?>
                    <option <?php echo $author == $user->ID ? 'selected' : ''; ?> value="<?php echo $user->ID; ?>"><?php echo $user->display_name; ?></option>
                <?php endforeach; ?>
            </select>


            <?php if($post_type == 'property'): ?>
            <select name="shorted_by">
                <option value="">Shortlisted By User</option>
                <?php foreach ($users as $user): ?>
                    <option <?php echo $shorted_by == $user->ID ? 'selected' : ''; ?> value="<?php echo $user->ID; ?>"><?php echo $user->display_name; ?></option>
                <?php endforeach; ?>
            </select>
            <?php endif;

        }
    }
    add_action( 'restrict_manage_posts','co_owner_admin_add_custom_posttype_filters',10,2);

    function co_owner_property_join_query($query,$type){
        if(
            isset($type->query_vars) &&
            isset($_GET['shorted_by']) &&
            !empty($_GET['shorted_by']) &&
            isset($type->query_vars['post_type']) &&
            $type->query_vars['post_type'] == 'property'
        ){
            global $wpdb;
            $table = $wpdb->prefix.CO_OWNER_FAVOURITE_TABLE;
            $sql = " LEFT JOIN {$table} ON {$table}.favourite_id = {$wpdb->posts}.ID";
            return $query.$sql;
        }
        return $query;
    }
    add_filter('posts_join_paged','co_owner_property_join_query',10,2);

    function co_owner_property_where_query($query,$type){
        if(
            isset($type->query_vars) &&
            isset($_GET['shorted_by']) &&
            !empty($_GET['shorted_by']) &&
            isset($type->query_vars['post_type']) &&
            $type->query_vars['post_type'] == 'property'
        ){
            global $wpdb;
            $shorted_by = $_GET['shorted_by'];
            $table = $wpdb->prefix.CO_OWNER_FAVOURITE_TABLE;
            $sql = " AND {$table}.user_id = {$shorted_by} AND {$table}.favourite_type = 'post'";
            return $query.$sql;
        }
        return $query;
    }
    add_filter('posts_where_paged','co_owner_property_where_query',10,2);

    add_filter('views_users','co_owner_views_users',10,1);
    function co_owner_views_users($view){
        if(isset($view['s2member_level0'])){
            $view['s2member_level0'] = str_replace("s2Member Level Trial","Trial Plan ",$view['s2member_level0']);
        }

        if(isset($view['s2member_level1'])){
            $view['s2member_level1'] = str_replace("s2Member Level 1","Standard Plan",$view['s2member_level1']);
        }
        if(isset($view['s2member_level2'])){
            $view['s2member_level2'] = str_replace("s2Member Level 2","Professional Plan",$view['s2member_level2']);
        }
        return $view;
    }


    add_filter('post_row_actions','my_action_row', 10, 2);

    function my_action_row($actions, $post){
        if (in_array($post->post_type,["feedback","faqs"])){
            unset($actions['view']);
        } elseif($post->post_type == 'property'){
           // $url = home_url(CO_OWNER_PROPERTY_DETAILS_PAGE."?id=$post->ID");
           // $actions['view'] = "<a href='{$url}'>View</a>";
        }
        return $actions;
    }

    add_action('admin_print_footer_scripts', 'wp_561_window_unload_error_final_fix');
    function wp_561_window_unload_error_final_fix(){
        global $post_type;
        if($post_type == 'property') {
            ?>
            <script>
                jQuery(document).ready(function($){
                    if(typeof window.wp.autosave === 'undefined')
                        return;

                    window.wp.autosave.server.postChanged = function(){
                        return false;
                    }
                });
            </script>
            <?php
        }
    }

}
