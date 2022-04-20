<?php
use Carbon_Fields\Container;
use Carbon_Fields\Field;

add_action( 'carbon_fields_register_fields', 'crb_attach_theme_options' );

function crb_attach_theme_options() {
    $pages_options = array();

    foreach (get_pages() as $page) {
        if(in_array($page->post_name,['login','register']) == false){
            $pages_options[$page->ID] = $page->post_title;
        }
    }

    Container::make( 'theme_options', __( 'Property Mates Settings' ) )
    ->add_tab( __( 'Common Settings' ), array(
        Field::make( 'multiselect', 'crb_protect_pages', __( 'Do you want to protect pages?' ) )
        ->add_options($pages_options),

        Field::make( 'text', 'how_its_works_button_title', __( 'How it Works Button Title' ) ),
        Field::make( 'text', 'how_its_works_button_link', __( 'How it Works Link' ) )
        ->set_classes('how-its-works-input-link')
        ->set_help_text("Please put video url only. Use <a href='#' class='select-how-its-works'>Gallery</a>"),

        Field::make( 'text', 'copyright_disclaimer', __( 'Copyright Disclaimer' ) ),
    ) )
    ->add_tab( __( 'API Keys' ), array(
        Field::make( 'text', 'crb_api_domain_com', __( 'Api Domain Com Address Api Key' ) ),
        Field::make( 'text', 'crb_google_map_api_key', __( 'Google Map Api Key' ) ),
    ))
    ->add_tab( __( 'Social Accounts Links' ), array(

        Field::make( 'text', 'crb_facebook_account', __( 'Facebook Account' ) ),
        Field::make( 'text', 'crb_instagram_account', __( 'Instagram Account' ) ),
        Field::make( 'text', 'crb_linkedin_account', __( 'Linkedin Account' ) ),
        Field::make( 'text', 'crb_twitter_account', __( 'Twitter Account' ) ),

    ) )
    ->add_tab( __( 'Social Accounts Credentials' ), array(
        Field::make( 'separator', 'twilio_account_credentials', __( 'Twilio Account Credentials' ) ),
        Field::make( 'text', 'crb_twilio_account_sid', __( 'Account Sid' ) ),
        Field::make( 'text', 'crb_twilio_auth_token', __( 'Auth Token' ) ),
        Field::make( 'text', 'crb_twilio_from_number', __( 'From Number' ) ),

        Field::make( 'separator', 'google_account_credentials', __( 'Google Account Credentials' ) ),
        Field::make( 'text', 'crb_google_client_id', __( 'Google client id' ) ),
        Field::make( 'text', 'crb_google_client_secret', __( 'Google client secret' ) ),
        Field::make( 'text', 'crb_google_redirect', __( 'Google redirect' ) ),

        Field::make( 'separator', 'facebook_account_credentials', __( 'Facebook Account Credentials' ) ),
        Field::make( 'text', 'crb_facebook_client_id', __( 'Facebook client id' ) ),
        Field::make( 'text', 'crb_facebook_client_secret', __( 'Facebook client secret' ) ),
        Field::make( 'text', 'crb_facebook_redirect', __( 'Facebook redirect' ) ),

        //Field::make( 'separator', 'instagram_account_credentials', __( 'Instagram Account Credentials' ) ),
        //Field::make( 'text', 'crb_instagram_client_id', __( 'Instagram client id' ) ),
        //Field::make( 'text', 'crb_instagram_client_secret', __( 'Instagram client secret' ) ),
        //Field::make( 'text', 'crb_instagram_redirect', __( 'Instagram redirect' ) ),

        Field::make( 'separator', 'linkedin_account_credentials', __( 'Linkedin Account Credentials' ) ),
        Field::make( 'text', 'crb_linkedin_client_id', __( 'Linkedin client id' ) ),
        Field::make( 'text', 'crb_linkedin_client_secret', __( 'Linkedin client secret' ) ),
        Field::make( 'text', 'crb_linkedin_redirect', __( 'Linkedin redirect' ) ),

        Field::make( 'separator', 'pusher_account_credentials', __( 'Pusher Account Credentials' ) ),
        Field::make( 'text', 'crb_pusher_cluster', __( 'Cluster' ) ),
        Field::make( 'text', 'crb_pusher_app_id', __( 'App id' ) ),
        Field::make( 'text', 'crb_pusher_instance_id', __( 'Instance id' ) ),
        Field::make( 'text', 'crb_pusher_secret_key', __( 'Secret key' ) ),
    ) )
    ->add_tab( __( 'Account Verification Code Format' ), array(

        Field::make( 'textarea', 'crb_account_verification_code_format', __( 'Account Verification Code Format.' ) )
            ->set_help_text('Please dont remove {{account-verification-code}}'),

    ));



    $build_years = array();
    for($i = 0; $i <100; $i++){
        $build_years[$i] = $i;
    }

    $shares = array();
    for($i = 1; $i <100; $i++){
        $shares[$i] = "{$i}%";
    }

    Container::make( 'post_meta', __( 'Additional Details' ) )
    ->where('post_type','property')
    ->add_tab( __( 'Step 1' ), array(
        Field::make( 'radio', 'pl_property_category',__('Select Property Category'))
        ->add_options( array(
            'commercial' => 'Commercial',
            'residential' => 'Residential',
        ))
        ->set_classes('step-1-inputs')
        ->set_required( true ),
        Field::make( 'radio', 'pl_property_type', __( 'Select Property Type' ) )
        ->set_options( array(
            'house' => 'House',
            'apartment' => 'Apartment',
            'townhouse' => 'Townhouse',
            'land' => 'Land',
            'retirement' => 'Retirement',
            'office' => 'Office - Office buildings,serviced offices',
            'leisure' => 'Leisure - hotels,public houses, restaurants, cafes, sports facilities',
            'retails' => 'Retails - retail stores, shopping malls, shops',
            'healthcare' => 'Healthcare - medical centers, hospitals, nursing homes',
            'multifamily' => 'Multifamily - multifamily housing buildings (apartments)',
        ))
        ->set_required( true ),
        Field::make( 'select', 'pl_posted_by',__('Select Property Posted By'))
        ->add_options( array(
            'Owner' => 'Owner',
            'Agent' => 'Agent/Non Owner',
        ))
        ->set_required( true )

    ))
    ->add_tab( __( 'Step 2' ), array(
        Field::make( 'text', 'pl_unit_no',__('Unit No'))->set_width(50)
        ->set_classes('step-2-inputs'),
        Field::make( 'text', 'pl_suburb',__('Suburb'))->set_width(50)
        ->set_classes(''),
        Field::make( 'text', 'pl_street_no',__('Street No'))->set_width(50)
        ->set_classes(''),
        Field::make( 'text', 'pl_postcode',__('Postcode'))->set_width(50)
        ->set_classes(''),
        Field::make( 'text', 'pl_street_name',__('Street Name'))->set_width(50)
        ->set_classes(''),
        Field::make( 'select', 'pl_state',__('State'))->set_width(50)
        ->set_classes('')
        ->add_options(get_all_states()),
    ))
    ->add_tab( __( 'Step 3' ), array(
        Field::make( 'radio', 'pl_only_display_suburb_in_my_ad', __( 'Only display suburb in my address.' ))
            ->add_options( array(
                1 => 'Yes',
                0 => 'No',
            ))
            ->set_classes('step-3-inputs'),
        Field::make( 'text', 'pl_building_area', __( 'Building area' ))
            ->set_required(true)
            ->set_width(30),
        Field::make( 'text', 'pl_land_area', __( 'Land area' ))
            ->set_required(true)
            ->set_width(30),
        Field::make( 'select', 'pl_age_year_built', __( 'Age year built' ))
            ->set_required(true)
            ->set_width(30)
            ->set_options($build_years),
        Field::make( 'text', 'pl_bathroom', __( 'Bathroom' ))
            ->set_required(false)
            ->set_classes('room-counter-input residential-inputs')
            ->set_width(30)->set_default_value(0),
        Field::make( 'text', 'pl_bedroom', __( 'Bedroom' ))
            ->set_required(false)
            ->set_classes('room-counter-input residential-inputs')
            ->set_width(30)->set_default_value(0),
        Field::make( 'text', 'pl_parking', __( 'Parking' ))
            ->set_required(false)
            ->set_classes('room-counter-input residential-inputs')
            ->set_width(30)->set_default_value(0),
    ))
    ->add_tab( __( 'Step 4' ), array(
        Field::make( 'radio', 'pl_interested_in_selling', __( 'Interested in selling' ) )
        ->set_options( array(
            'full_property' => 'Full Property',
            'portion_of_it' => 'Portion of it [% Portion]',
        ) )
        ->set_required( true )
        ->set_classes('step-4-inputs'),
        Field::make( 'radio', 'pl_this_property_is', __( 'This Property is' ) )
        ->set_options( array(
            'Investment' => 'Investment',
            'Currently occupied by owner' => 'Currently occupied by owner',
        ) )

        ->set_required( true ),
        Field::make( 'radio', 'pl_currently_on_leased', __( 'Currently Leased' ) )
        ->set_options( array(
            'Yes' => 'Yes',
            'No' => 'No',
        ) )
        ->set_required( true ),
        Field::make( 'text', 'pl_rent_per_month', __( 'Rent Per month' ) )
        ->set_classes('input-only-number pl-currently-on-leased'),

        Field::make( 'radio', 'pl_enable_pool', __( 'Enable pool' ))
        ->add_options(array(
            0 => 'Off',
            1 => 'On'
        ))
        ->set_required( true )
        ->set_help_text('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.'),

        Field::make( 'radio', 'pl_negotiable', __( 'Negotiable' ))
            ->add_options(array(
                0 => 'Off',
                1 => 'On'
            ))
            ->set_required( true ),

        Field::make( 'text', 'pl_property_market_price', __( 'Property Market price' ) )
            ->set_required( true )
            ->set_width(10)
            ->set_classes('property-market-price input-only-number'),

    ));

    Container::make( 'post_meta', __( 'Front Page Settings' ) )
    ->where('post_type','page')
    ->where( 'post_id', '=', get_option( 'page_on_front' ) )
    ->add_tab( __('Banner Settings'), array(
        Field::make( 'image', 'front_page_banner', __( 'Home Page Banner Image' ) ),
        Field::make( 'text', 'front_page_banner_title', __( 'Home Page Banner Title' ) ),
        Field::make( 'textarea', 'front_page_banner_description', __( 'Home Page Banner Description' ) ),
        Field::make( 'checkbox', 'front_page_banner_whow_how_its_works', __( 'Home Show Banner How its Works Button?' ) )->set_option_value( 'yes' )
    ))
    ->add_tab( __( 'After Content Button' ), array(
        Field::make( 'separator', 'front_page_block_1', __( 'Button 1' ) ),
        Field::make( 'text', 'front_page_block_1_label',__('Title'))->set_default_value('I have a Property'),
        Field::make( 'text', 'front_page_block_1_title',__('Description'))->set_default_value('Create a Property Listing'),
        Field::make( 'text', 'front_page_block_1_link',__('Link')),

        Field::make( 'separator', 'front_page_block_2', __( 'Button 2' ) ),
        Field::make( 'text', 'front_page_block_2_label',__('Title'))->set_default_value('I Need a Property'),
        Field::make( 'text', 'front_page_block_2_title',__('Description'))->set_default_value('Create a Property Listing'),
        Field::make( 'text', 'front_page_block_2_link',__('Link')),
    ))
    ->add_tab( __( 'Properties Need Co-owners' ), array(
        Field::make( 'checkbox', 'front_page_show_need_co_owners', __( 'Show Need Co-owners' ) )->set_option_value( 'yes' ),
        Field::make( 'text', 'front_page_need_co_owners_count',__('Property Count'))->set_default_value(5),
        Field::make( 'text', 'front_page_need_co_owners_link',__('View All Link')),
        Field::make( 'text', 'front_page_need_co_owners_title',__('Co Owners Title')),
        Field::make( 'rich_text', 'front_page_need_co_owners_description',__('Co Owners Description'))
    ))
    ->add_tab( __( 'People Looking for Properties' ), array(
        Field::make( 'checkbox', 'front_page_show_people_looking_for_properties', __( 'Show People Looking for Properties' ) )->set_option_value( 'yes' ),
        Field::make( 'text', 'front_page_people_looking_for_properties_count',__('People Count'))->set_default_value(5),
        Field::make( 'text', 'front_page_people_looking_for_properties_link',__('View All Link')),
        Field::make( 'text', 'front_page_people_looking_for_properties_title',__('People Looking for Properties Title')),
        Field::make( 'rich_text', 'front_page_people_looking_for_properties_description',__('People Looking for Properties Description'))
    ))
    ->add_tab( __( 'Checkout the Pools Already Created' ), array(
        Field::make( 'checkbox', 'front_page_show_pools_already_created', __( 'Show Pools Already Created' ) )->set_option_value( 'yes' ),
        Field::make( 'text', 'front_page_pools_already_created_count',__('Property Count'))->set_default_value(5),
        Field::make( 'text', 'front_page_pools_already_created_link',__('View All Link')),
        Field::make( 'text', 'front_page_pools_already_created_title',__('Pools Already Created Title')),
        Field::make( 'rich_text', 'front_page_pools_already_created_description',__('Pools Already Created Description'))
    ))
//    ->add_tab( __( 'Why choose us?' ) , array(
//        Field::make( 'checkbox', 'front_page_show_why_choose_us', __( 'Show Why Choose Us' ) )->set_option_value( 'yes' ),
//        Field::make( 'text', 'front_page_why_choose_us_title',__('Why choose Us Title')),
//        Field::make( 'rich_text', 'front_page_why_choose_us_description',__('Why choose Us Description')),
//
//        Field::make( 'separator', 'front_page_why_choose_us_block_1', __( 'Block 1' ) ),
//        Field::make( 'text', 'front_page_why_choose_us_block_1_title',__('Block 1 Title')),
//        Field::make( 'rich_text', 'front_page_why_choose_us_block_1_description',__('Block 1 Description')),
//
//        Field::make( 'separator', 'front_page_why_choose_us_block_2', __( 'Block 2' ) ),
//        Field::make( 'text', 'front_page_why_choose_us_block_2_title',__('Block 2 Title')),
//        Field::make( 'rich_text', 'front_page_why_choose_us_block_2_description',__('Block 2 Description')),
//
//        Field::make( 'separator', 'front_page_why_choose_us_block_3', __( 'Block 3' ) ),
//        Field::make( 'text', 'front_page_why_choose_us_block_3_title',__('Block 3 Title')),
//        Field::make( 'rich_text', 'front_page_why_choose_us_block_3_description',__('Block 3 Description')),
//    ))
    ->add_tab( __( 'Property portion(s) under' ) , array(
        Field::make( 'checkbox', 'front_page_show_property_shares_under', __( 'Show Property Portion(s) under' ) )->set_option_value( 'yes' ),
        Field::make( 'text', 'front_page_property_shares_under_count',__('Property Count'))->set_default_value(5),
        Field::make( 'text', 'front_page_property_shares_under_title',__('Property Portions Under Title')),
        Field::make( 'multiselect', 'front_page_property_shares_under_states',__('Property Portions Under State'))
        ->add_options(get_all_states()),
    ));


    $rating_stars =  array(
        '1' => 1,
        '2' => 2,
        '3' => 3,
        '4' => 4,
        '5' => 5,
    );

    Container::make( 'post_meta', __( 'Additional Details' ) )
    ->where('post_type','feedback')
    ->add_tab( __( 'Rating Stars' ), array(
        Field::make( 'radio', 'feedback_rating_1',__(CO_OWNER_FEEDBACK_Q_1))->set_options( $rating_stars),
        Field::make( 'radio', 'feedback_rating_2',__(CO_OWNER_FEEDBACK_Q_2))->set_options( $rating_stars),
        Field::make( 'radio', 'feedback_rating_3',__(CO_OWNER_FEEDBACK_Q_3))->set_options( $rating_stars),
    ));
}

