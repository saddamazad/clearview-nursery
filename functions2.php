<?php
/**
 * astra-child Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package astra-child
 * @since 1.0.0
 */

/**
 * Define Constants
 */
define( 'CHILD_THEME_ASTRA_CHILD_VERSION', '1.0.0' );

/**
 * Enqueue styles
 */
function child_enqueue_styles() {

	wp_enqueue_style( 'astra-child-theme-css', get_stylesheet_directory_uri() . '/style.css', array('astra-theme-css'), CHILD_THEME_ASTRA_CHILD_VERSION, 'all' );
	
	$cvn_obj = array('logged_name' => '');
	$logged_name = '';
	if( is_user_logged_in() ) {
		$user = wp_get_current_user();
		if( $user->first_name ) {
			$name = $user->first_name;
		} else {
			$name = $user->display_name;
		}
		$cvn_obj['logged_name'] = $name;
	}
	wp_localize_script('jquery', 'cvn_object', $cvn_obj);

}

add_action( 'wp_enqueue_scripts', 'child_enqueue_styles', 15 );

if ( file_exists( dirname( __FILE__ ) . '/cmb2/init.php' ) ) {
	require_once dirname( __FILE__ ) . '/cmb2/init.php';
} elseif ( file_exists( dirname( __FILE__ ) . '/CMB2/init.php' ) ) {
	require_once dirname( __FILE__ ) . '/CMB2/init.php';
}


//add_action( 'cmb2_admin_init', 'register_product_faqs_metabox', 1 );
function register_product_faqs_metabox() {

   $cmb = new_cmb2_box( array(
            'id'            => '_faq_metabox',
            'title'         => 'Product FAQs',
            'object_types'  => array( 'product' )
        ) );


        // $group_field_id is the field id string, so in this case: $prefix . 'demo'
        $group_id = $cmb->add_field( array(
            'id'                => '_product_faq',
            'type'              => 'group',
            //'description'       => 'A Question with Answers',
            'options'           => array(
                'group_title'   => 'Question & Answer {#}',
                'add_button'    => 'Add Another FAQ',
                'remove_button' => 'Remove FAQ',
                'sortable'      => true
            )
        ) );

        $cmb->add_group_field( $group_id, array(
            'id'            => 'question',
            'name'          => __('Question', 'cmb2'),
            'type'          => 'text'
        ) );

        $cmb->add_group_field( $group_id, array(
            'id'            => 'answer',
            'name'          => __('Answers', 'cmb2'),
            'type'    => 'wysiwyg',
			'options' => array(
				'textarea_rows' => 5,
			),
        ) );
  
}


add_action( 'cmb2_admin_init', 'register_materials_metabox', 1 );
function register_materials_metabox() {

   $cmb = new_cmb2_box( array(
            'id'            => '_material_metabox',
            'title'         => 'Material Specification',
            'object_types'  => array( 'material' )
        ) );
  
	$cmb->add_field( array(
		'name'       => esc_html__( 'Price', 'cmb2' ),
		'id'         => '_rprice',
		'type'       => 'text_small'
	) );
	$cmb->add_field( array(
		'name'       => esc_html__( 'Wholesale Price', 'cmb2' ),
		'id'         => '_wprice',
		'type'       => 'text_small'
	) );
	$cmb->add_field( array(
		'name'       => esc_html__( 'Contractor Price', 'cmb2' ),
		'id'         => '_cprice',
		'type'       => 'text_small'
	) );
	$cmb->add_field( array(
		'name'             => esc_html__( 'Sold by', 'cmb2' ),
		'id'               => '_sold_by',
		'type'             => 'select',
		'show_option_none' => false,
		'options'          => array(
			'Cubic Yard' => esc_html__( 'Cubic Yard', 'cmb2' ),
			'Metric Tonne'   => esc_html__( 'Metric Tonne', 'cmb2' ),
		)
	) );

}


add_action( 'cmb2_admin_init', 'register_product_tabs_metabox', 2 );
function register_product_tabs_metabox() {
	$args = array('post_type' => 'product-faqs', 'posts_per_page' => -1);
	$loop = new WP_Query($args);
	$pageArray = array();
	if($loop->have_posts()) { 
		while($loop->have_posts()) : $loop->the_post();
			$faqID = get_the_id();
			$faqTitle = get_the_title();
			$pageArray[$faqID] = $faqTitle;
		endwhile;
	}
	
	$cmb = new_cmb2_box( array(
		'id'            => 'tab_product_metabox',
		'title'         => esc_html__( 'Tabs Metabox', 'cmb2' ),
		'object_types'  => array( 'product' ),
	) );
	
	$vgroup_id = $cmb->add_field( array(
		'id'                => '_cmb2_faq_video_resources',
		'type'              => 'group',
		//'description'       => 'A Question with Answers',
		'options'           => array(
			'group_title'   => 'FAQs',
			'add_button'    => 'Add Another FAQ',
			'remove_button' => 'Remove FAQ',
			'sortable'      => true
		)
	) );

	$cmb->add_group_field( $vgroup_id, array(
		'id'            => 'product_faq_video',
		'name'          => __('Select FAQ', 'cmb2'),
		'type'          => 'select',
		'show_option_none' => true,
		'options'          => $pageArray,
	) );

	$cmb->add_field( array(
		'name'       => esc_html__( 'Resources', 'cmb2' ),
		'id'         => '_cmb2_resources',
		'type'       => 'wysiwyg'
	) );
}


add_action( 'cmb2_admin_init', 'register_product_faqs_video_tab_metabox', 2 );
function register_product_faqs_video_tab_metabox() {
	$cmb = new_cmb2_box( array(
		'id'            => 'tab_product_faqs_metabox',
		'title'         => esc_html__( 'Video URL', 'cmb2' ),
		'object_types'  => array( 'product-faqs' ),
	) );
	
	/*$cmb->add_field( array(
		'name'       => esc_html__( 'FAQ Description', 'cmb2' ),
		'id'         => '_cmb2_faq_desc',
		'type'       => 'textarea_small'
	) );
	
	// $group_field_id is the field id string, so in this case: $prefix . 'demo'
	$group_id = $cmb->add_field( array(
		'id'                => '_product_faq',
		'type'              => 'group',
		//'description'       => 'A Question with Answers',
		'options'           => array(
			'group_title'   => 'Question & Answer {#}',
			'add_button'    => 'Add Another FAQ',
			'remove_button' => 'Remove FAQ',
			'sortable'      => true
		)
	) );

	$cmb->add_group_field( $group_id, array(
		'id'            => 'question',
		'name'          => __('Question', 'cmb2'),
		'type'          => 'text'
	) );

	$cmb->add_group_field( $group_id, array(
		'id'            => 'answer',
		'name'          => __('Answer', 'cmb2'),
		'type'    => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 5,
		),
	) );
	
	$vgroup_id = $cmb->add_field( array(
		'id'                => '_cmb2_video_resources',
		'type'              => 'group',
		//'description'       => 'A Question with Answers',
		'options'           => array(
			'group_title'   => 'Videos',
			'add_button'    => 'Add Another Video',
			'remove_button' => 'Remove Video',
			'sortable'      => true
		)
	) );

	$cmb->add_group_field( $vgroup_id, array(
		'id'            => 'video_url',
		'name'          => __('Video', 'cmb2'),
		'type'          => 'oembed'
	) );*/
	
	$cmb->add_field( array(
		'name'       => esc_html__( 'Video', 'cmb2' ),
		'id'         => 'video_url',
		'type'       => 'oembed'
	) );
}


//add_action( 'cmb2_admin_init', 'register_product_popup_metabox', 3 );
function register_product_popup_metabox() {

	$cmb = new_cmb2_box( array(
		'id'            => 'popup_product_metabox',
		'title'         => esc_html__( 'Popup Metabox', 'cmb2' ),
		'object_types'  => array( 'product' ),
	) );

	$cmb->add_field( array(
		'name'       => esc_html__( 'Hardiness Zone', 'cmb2' ),
		'id'         => '_cmb2_hardiness_zone',
		'type'       => 'wysiwyg'
	) );
	$cmb->add_field( array(
		'name'       => esc_html__( 'Growth Rate Popup', 'cmb2' ),
		'id'         => '_cmb2_growth_rate_zone',
		'type'       => 'wysiwyg'
	) );
	
}



add_action( 'cmb2_admin_init', 'register_product_metabox', 5 );
function register_product_metabox() {

  $cmb2 = new_cmb2_box( array(
    'id'            => 'product_metabox',
    'title'         => esc_html__( 'Product Specification', 'cmb2' ),
    'object_types'  => array( 'product' ),
  ) );
  
  $cmb2->add_field( array(
		'name'             => esc_html__( 'Show on Calculator?', 'cmb2' ),
		'id'               => '_calculator_item',
		'type'             => 'select',
		'show_option_none' => false,
		'options'          => array(
			'no'   => esc_html__( 'No', 'cmb2' ),
			'yes' => esc_html__( 'Yes', 'cmb2' ),
		),
	) );

  $cmb2->add_field( array(
	'name'       => esc_html__( 'Order for Calculator', 'cmb2' ),
	'id'         => '_calculator_order',
	'type'       => 'text_small'
  ) );

  $cmb2->add_field( array(
		'name'             => esc_html__( 'Sold by', 'cmb2' ),
		'id'               => '_sold_by',
		'type'             => 'select',
		'show_option_none' => true,
		'options'          => array(
			'Cubic Yard' => esc_html__( 'Cubic Yard', 'cmb2' ),
			'Metric Tonne'   => esc_html__( 'Metric Tonne', 'cmb2' ),
		)
	) );	
	
  $cmb2->add_field( array(
	'name'       => esc_html__( 'WB (Wire Basket)', 'cmb2' ),
	'id'         => '_wire_basket_info',
	'type'       => 'text'
  ) );
  $cmb2->add_field( array(
	'name'       => esc_html__( 'Wholesale Price', 'cmb2' ),
	'id'         => '_wholesale_price',
	'type'       => 'text_small'
  ) );
  $cmb2->add_field( array(
	'name'       => esc_html__( 'Contractor Pricing', 'cmb2' ),
	'id'         => '_contractor_pricing',
	'type'       => 'text_small'
  ) );
  
  $cmb2->add_field( array(
		'name'             => esc_html__( 'Installation Available', 'cmb2' ),
		'id'               => '_installation_available',
		'type'             => 'select',
		'show_option_none' => true,
		'options'          => array(
			'yes' => esc_html__( 'Yes', 'cmb2' ),
			'no'   => esc_html__( 'No', 'cmb2' ),
		),
	) );
  
  $cmb2->add_field( array(
	'name'       => esc_html__( 'Botanical Name', 'cmb2' ),
	'id'         => '_botanical_name',
	'type'       => 'text'
  ) );  
  
  $cmb2->add_field( array(
	'name'       => esc_html__( 'Botanic Class', 'cmb2' ),
	'id'         => '_Botanic_Class',
	'type'       => 'text'
  ) );
  $cmb2->add_field( array(
		'name'             => esc_html__( 'Evergreen', 'cmb2' ),
		'id'               => '_Evergreen',
		'type'             => 'select',
		'show_option_none' => true,
		'options'          => array(
			'TRUE' => esc_html__( 'Yes', 'cmb2' ),
			'FALSE'   => esc_html__( 'No', 'cmb2' ),
		),
	) );
  $cmb2->add_field( array(
		'name'             => esc_html__( 'Vine', 'cmb2' ),
		'id'               => '_Vine',
		'type'             => 'select',
		'show_option_none' => true,
		'options'          => array(
			'TRUE' => esc_html__( 'Yes', 'cmb2' ),
			'FALSE'   => esc_html__( 'No', 'cmb2' ),
		),
	) );
  $cmb2->add_field( array(
	'name'       => esc_html__( 'Landscape Attributes Para1', 'cmb2' ),
	'id'         => '_Landscape_Attributes_Para1',
	'type'       => 'textarea_small'
  ) );
  $cmb2->add_field( array(
		'name'             => esc_html__( 'Landscape Attributes Para2', 'cmb2' ),
		'id'               => 'landscape_attributes_para2',
		'type'       => 'textarea_small'
	));
  $cmb2->add_field( array(
		'name'             => esc_html__( 'Landscape Attributes Para3', 'cmb2' ),
		'id'               => 'landscape_attributes_para3',
		'type'       => 'textarea_small'
	));
  
  $cmb2->add_field( array(
		'name'             => esc_html__( 'Deer Resistant', 'cmb2' ),
		'id'               => '_Deer_Resistant',
		'type'             => 'select',
		'show_option_none' => true,
		'options'          => array(
			'TRUE' => esc_html__( 'Yes', 'cmb2' ),
			'FALSE'   => esc_html__( 'No', 'cmb2' ),
		),
	) );
  
  $cmb2->add_field( array(
	'name'       => esc_html__( 'Growth Rate', 'cmb2' ),
	'id'         => '_Growth_Rate',
	'type'       => 'text'
  ));
  
  $cmb2->add_field( array(
	'name'       => esc_html__( 'Hardiness Zone Whole', 'cmb2' ),
	'id'         => '_Hardiness_Zone_Whole',
	'type'       => 'text_small'
  ));
  
  $cmb2->add_field( array(
	'name'       => esc_html__( 'Height Descriptor', 'cmb2' ),
	'id'         => '_Height_Descriptor',
	'type'       => 'text'
  ));
  $cmb2->add_field( array(
	'name'       => esc_html__( 'Height Filter', 'cmb2' ),
	'id'         => '_Height_FILTER',
	'type'       => 'text_small'
  ));
  $cmb2->add_field( array(
	'name'       => esc_html__( 'Plant Form', 'cmb2' ),
	'id'         => '_Plant_Form',
	'type'       => 'text'
  ));
  $cmb2->add_field( array(
	'name'             => esc_html__( 'Plant Origin', 'cmb2' ),
	'id'               => '_Plant_Origin',
	'type'             => 'text_medium',
  ) );
  $cmb2->add_field( array(
	'name'       => esc_html__( 'Pruning', 'cmb2' ),
	'id'         => '_Pruning',
	'type'       => 'text'
  ));
  $cmb2->add_field( array(
	'name'       => esc_html__( 'Spread Descriptor', 'cmb2' ),
	'id'         => '_Spread_Descriptor',
	'type'       => 'text_medium'
  ));
  $cmb2->add_field( array(
	'name'       => esc_html__( 'Wildlife Attraction SCSV', 'cmb2' ),
	'id'         => '_Wildlife_Attraction_SCSV',
	'type'       => 'text'
  ));
  $cmb2->add_field( array(
		'name'             => esc_html__( 'Flower Color', 'cmb2' ),
		'id'               => '_Flower_Color',
		'type'             => 'text_medium',
  ) );
  
  $cmb2->add_field( array(
		'name'             => esc_html__( 'Flower Color Filter', 'cmb2' ),
		'id'               => '_Flower_Color_FILTER',
		'type'             => 'text_medium',
  ) );
  
  $cmb2->add_field( array(
		'name'             => esc_html__( 'Flower Effect', 'cmb2' ),
		'id'               => '_Flower_Effect',
		'type'       => 'text_medium'
	));
  $cmb2->add_field( array(
		'name'             => esc_html__( 'Flower Period', 'cmb2' ),
		'id'               => '_Flower_Period',
		'type'       => 'text_medium'
	));
	$cmb2->add_field( array(
		'name'             => esc_html__( 'Evergreen Deciduous', 'cmb2' ),
		'id'               => '_Evergreen_Deciduous',
		'type'       => 'text_medium'
	));
	$cmb2->add_field( array(
		'name'             => esc_html__( 'Summer Foliage Color', 'cmb2' ),
		'id'               => '_Summer_Foliage_Color',
		'type'       => 'text_medium'
	));
	$cmb2->add_field( array(
		'name'             => esc_html__( 'Fall Color', 'cmb2' ),
		'id'               => '_Fall_Color',
		'type'       => 'text_medium'
	));
	$cmb2->add_field( array(
		'name'             => esc_html__( 'Fall Color And', 'cmb2' ),
		'id'               => '_Fall_Color_And',
		'type'       => 'text_medium'
	));
	$cmb2->add_field( array(
		'name'             => esc_html__( 'Fall Color Filter', 'cmb2' ),
		'id'               => '_Fall_Color_FILTER',
		'type'       => 'text_medium'
	));
	$cmb2->add_field( array(
		'name'             => esc_html__( 'Fall Color To', 'cmb2' ),
		'id'               => '_Fall_Color_To',
		'type'       => 'text_medium'
	));
	$cmb2->add_field( array(
		'name'             => esc_html__( 'Fall Value', 'cmb2' ),
		'id'               => '_Fall_Value',
		'type'             => 'select',
		'show_option_none' => true,
		'options'          => array(
			'TRUE' => esc_html__( 'Yes', 'cmb2' ),
			'FALSE'   => esc_html__( 'No', 'cmb2' ),
		),
	) );
	$cmb2->add_field( array(
		'name'             => esc_html__( 'Edible', 'cmb2' ),
		'id'               => '_Edible',
		'type'             => 'select',
		'show_option_none' => true,
		'options'          => array(
			'TRUE' => esc_html__( 'Yes', 'cmb2' ),
			'FALSE'   => esc_html__( 'No', 'cmb2' ),
		),
	) );
	$cmb2->add_field( array(
		'name'             => esc_html__( 'Edible Component', 'cmb2' ),
		'id'               => '_Edible_Component',
		'type'       => 'text_medium'
	));	
	$cmb2->add_field( array(
		'name'             => esc_html__( 'Pollinator', 'cmb2' ),
		'id'               => '_Pollinator',
		'type'       => 'text_medium'
	));
	$cmb2->add_field( array(
		'name'             => esc_html__( 'Bark Color', 'cmb2' ),
		'id'               => '_Bark_Color',
		'type'       => 'text_medium'
	));
	$cmb2->add_field( array(
		'name'             => esc_html__( 'Bark Texture', 'cmb2' ),
		'id'               => '_Bark_Texture',
		'type'       => 'text_medium'
	));
	$cmb2->add_field( array(
		'name'             => esc_html__( 'Moisture Descriptor', 'cmb2' ),
		'id'               => '_Moisture_Descriptor',
		'type'       => 'text'
	));
	$cmb2->add_field( array(
		'name'             => esc_html__( 'Pollution Tolerance', 'cmb2' ),
		'id'               => '_Pollution_Tolerance',
		'type'       => 'text_medium'
	));
	$cmb2->add_field( array(
		'name'             => esc_html__( 'Salt Tolerance', 'cmb2' ),
		'id'               => '_Salt_Tolerance',
		'type'             => 'select',
		'show_option_none' => true,
		'options'          => array(
			'TRUE' => esc_html__( 'Yes', 'cmb2' ),
			'FALSE'   => esc_html__( 'No', 'cmb2' ),
		),
	) );
	$cmb2->add_field( array(
		'name'             => esc_html__( 'Soil PH Preference', 'cmb2' ),
		'id'               => '_Soil_pH_Preference',
		'type'       => 'text_medium'
	));
	$cmb2->add_field( array(
		'name'             => esc_html__( 'Soil Type Preference', 'cmb2' ),
		'id'               => '_Soil_Type_Preference',
		'type'       => 'text_medium'
	));
	$cmb2->add_field( array(
		'name'             => esc_html__( 'Sunlight Descriptor', 'cmb2' ),
		'id'               => '_Sunlight_Descriptor',
		'type'       => 'text'
	));
	$cmb2->add_field( array(
		'name'             => esc_html__( 'Well Drained', 'cmb2' ),
		'id'               => '_Well_Drained',
		'type'             => 'select',
		'show_option_none' => true,
		'options'          => array(
			'TRUE' => esc_html__( 'Yes', 'cmb2' ),
			'FALSE'   => esc_html__( 'No', 'cmb2' ),
		),
	) );
	$cmb2->add_field( array(
		'name'             => esc_html__( 'Is Selected In NetPS?', 'cmb2' ),
		'id'               => '_Is_Selected_In_NetPS',
		'type'             => 'select',
		'show_option_none' => true,
		'options'          => array(
			'TRUE' => esc_html__( 'Yes', 'cmb2' ),
			'FALSE'   => esc_html__( 'No', 'cmb2' ),
		),
	) );
	$cmb2->add_field( array(
		'name'             => esc_html__( 'NetPS Plant ID', 'cmb2' ),
		'id'               => '_NetPS_Plant_ID',
		'type'       => 'text_small'
	));
	$cmb2->add_field( array(
		'name'             => esc_html__( 'Planting and Growing Para1', 'cmb2' ),
		'id'               => 'planting_and_growing_para1',
		'type'       => 'textarea_small'
	));
	$cmb2->add_field( array(
		'name'             => esc_html__( 'Planting and Growing Para2', 'cmb2' ),
		'id'               => 'planting_and_growing_para2',
		'type'       => 'textarea_small'
	));
	$cmb2->add_field( array(
		'name'             => esc_html__( 'Planting and Growing Para3', 'cmb2' ),
		'id'               => 'planting_and_growing_para3',
		'type'       => 'textarea_small'
	));
	$cmb2->add_field( array(
		'name'             => esc_html__( 'Planting and Growing Para4', 'cmb2' ),
		'id'               => 'planting_and_growing_para4',
		'type'       => 'textarea_small'
	));
	$cmb2->add_field( array(
		'name'             => esc_html__( 'Comments', 'cmb2' ),
		'id'               => 'comments',
		'type'       => 'textarea_small'
	));
	
}

remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);

add_action('woocommerce_single_product_summary', 'get_product_summary_description', 35);
function get_product_summary_description(){
	global $post;
	if(get_post_meta($post->ID, '_installation_available', true) && get_post_meta($post->ID, '_installation_available', true) == "yes"){
		//echo '<a class="installation_btn" href="#"> Request Installation</a>';
		//get_post_meta($post->ID, '_wire_basket_info', true)
		echo '<a class="installation_btn"> Installation available on WB (Wire Basket) products*</a>';
	}
	
	echo '<div class="summary_description"><h4>Description</h4>'.apply_filters( 'the_content', get_the_content() ).'</div>';
	
	if( get_post_meta($post->ID, '_Landscape_Attributes_Para1', true) ) {
		echo '<p>'.get_post_meta($post->ID, '_Landscape_Attributes_Para1', true).'</p>';
	}
}

add_shortcode('get_top_bar_text', 'cvn_get_top_bar_text');
function cvn_get_top_bar_text() {
	ob_start();
	if( get_option('top_bar_msg') ) {
		echo wpautop( htmlspecialchars_decode( get_option('top_bar_msg') ) );
	}
	return ob_get_clean();
}

add_action('admin_init', 'cvn_general_section');
function cvn_general_section() {
    add_settings_section(  
        'cvn_options_section',
        'Highlight bar options',
        'cvn_section_options_callback',
        'general'
    );
	
    add_settings_field(
        'top_bar_msg',
        'Highlight bar text',
        'cvn_textbox_callback',
        'general',
        'cvn_options_section'
    );
	    
    register_setting('general','top_bar_msg', 'esc_attr');
}

function cvn_section_options_callback() {
    //echo '<p>A little message on editing info</p>';  
}

function cvn_textbox_callback( $args ) {
    $option = get_option('top_bar_msg');
	$content = isset( $option ) ?  htmlspecialchars_decode($option) : false;
    wp_editor( $content, 'top_bar_msg', array( 
        'textarea_name' => 'top_bar_msg',
        'media_buttons' => false,
		'textarea_rows' => 3
    ) );
}

add_action( 'woocommerce_single_product_summary', 'botanical_name_after_single_product_title', 6 );
function botanical_name_after_single_product_title() { 
    global $product;
	$product_id = $product->get_id();

	if( get_post_meta($product_id, '_hard_goods_product', true) && get_post_meta($product_id, '_hard_goods_product', true) == "Yes" ) {
		$botanical_name = get_post_meta($product_id, "_botanical_name", true);

		// Displaying the custom field under the title
		echo '<div class="botanical-name">' . $botanical_name . '</div>';
	}
}

add_action('woocommerce_after_single_product', 'prodcut_scpacification');
function prodcut_scpacification(){
	global $post, $product;

	echo '<div class="clear clearfix"></div>';
	if( get_post_meta($post->ID, '_hard_goods_product', true) != "Yes" ) {
		echo '<div class="product_specification">';
		echo '<ul>';
			//echo wc_display_product_attributes( $product );
			if( $product->is_type( 'simple' ) ) {
				/*$size = $product->get_attribute( 'pa_size' );
				if($size):
					echo '<li><strong>Width</strong><span>'.$size.'</span></li>';
				endif;*/
				if( get_post_meta($post->ID, '_Height_Descriptor', true) ) {
					echo '<li><strong>Height</strong><span>'.get_post_meta($post->ID, '_Height_Descriptor', true).'</span></li>';
				}
				if( get_post_meta($post->ID, '_Spread_Descriptor', true) ) {
					echo '<li><strong>Spread</strong><span>'.get_post_meta($post->ID, '_Spread_Descriptor', true).'</span></li>';
				}
			}
			if(get_post_meta($post->ID, '_Growth_Rate', true)):
				echo '<li><strong>Growth Rate</strong><span>'.get_post_meta($post->ID, '_Growth_Rate', true).'</span>';
				/*if (get_post_meta($post->ID, '_cmb2_growth_rate_zone', true)): 
				echo '<a class="zone_icon" href="#elementor-action%3Aaction%3Dpopup%3Aopen%26settings%3DeyJpZCI6Ijc4NjYiLCJ0b2dnbGUiOmZhbHNlfQ%3D%3D"><img src="/wp-content/uploads/2022/04/Growth-Rate-icon.png" /></a>';
				endif;*/
				'</li>';
			endif;

			if(get_post_meta($post->ID, '_Hardiness_Zone_Whole', true)):
				echo '<li><strong>Zone</strong><span>'.get_post_meta($post->ID, '_Hardiness_Zone_Whole', true).'</span>';
				//if (get_post_meta($post->ID, '_cmb2_hardiness_zone', true)): 
				echo '<a class="zone_icon" href="#elementor-action%3Aaction%3Dpopup%3Aopen%26settings%3DeyJpZCI6Ijc4NTQiLCJ0b2dnbGUiOmZhbHNlfQ%3D%3D"><i aria-hidden="true" class="fas fa-info"></i></a>';
				//<img src="/wp-content/uploads/2022/04/Zone-icon.png" />
				//endif;
				'</li>';
			endif;
			if(get_post_meta($post->ID, '_Exposure', true)):
				echo '<li><strong>Exposure</strong><span>'.get_post_meta($post->ID, '_Exposure', true).'</span></li>';
			endif;
			if(get_post_meta($post->ID, '_Soil_pH_Preference', true)):
				echo '<li><strong>Soil</strong><span>'.get_post_meta($post->ID, '_Soil_pH_Preference', true).'</span></li>';
			endif;
			if(get_post_meta($post->ID, '_Moisture_Descriptor', true)):
				echo '<li><strong>Moisture</strong><span>'.get_post_meta($post->ID, '_Moisture_Descriptor', true).'</span></li>';
			endif;	
			if(get_post_meta($post->ID, '_Plant_Origin', true)):
				echo '<li><strong>Plant Origin</strong><span>'.get_post_meta($post->ID, '_Plant_Origin', true).'</span></li>';
			endif;			
		echo '</ul>';
		echo '</div>';
	}
	echo do_shortcode('[elementor-template id="1980"]');
}


add_shortcode('display_addition_description', 'get_addition_description_init');
function get_addition_description_init(){
	ob_start();
	global $post;
		echo '<ul class="additional_description_tab">';
		if(get_post_meta($post->ID, '_Evergreen_Deciduous', true)):
			echo '<li><span>Evergreen or Deciduous</span> <span class="p-meta-val">'.get_post_meta($post->ID, '_Evergreen_Deciduous', true).'</span></li>';
		endif;
		if(get_post_meta($post->ID, '_Pruning', true)):
			echo '<li><span>Pruning</span> <span class="p-meta-val">'.get_post_meta($post->ID, '_Pruning', true).'</span></li>';
		endif;
		if(get_post_meta($post->ID, '_Shape', true)):
			echo '<li><span>Shape</span> <span class="p-meta-val">'.get_post_meta($post->ID, '_Shape', true).'</span></li>';
		endif;
		if(get_post_meta($post->ID, '_Flower_Color', true)):
			echo '<li><span>Flower Color</span> <span class="p-meta-val">'.get_post_meta($post->ID, '_Flower_Color', true).'</span></li>';
		endif;
		if(get_post_meta($post->ID, '_Flower_Timing', true)):
			echo '<li><span>Flower Timing</span> <span class="p-meta-val">'.get_post_meta($post->ID, '_Flower_Timing', true).'</span></li>';
		endif;
		if(get_post_meta($post->ID, '_Flower_Period', true)):
			echo '<li><span>Flower Period</span> <span class="p-meta-val">'.get_post_meta($post->ID, '_Flower_Period', true).'</span></li>';
		endif;
		if(get_post_meta($post->ID, '_Fall_Color', true)):
			echo '<li><span>Fall Color</span> <span class="p-meta-val">'.get_post_meta($post->ID, '_Fall_Color', true).'</span></li>';
		endif;
		if(get_post_meta($post->ID, '_Deer_Resistant', true)):
			$deer_res = '';
			if( get_post_meta($post->ID, '_Deer_Resistant', true) == "TRUE" ) {
				$deer_res = "Yes";
			}
			if( get_post_meta($post->ID, '_Deer_Resistant', true) == "FALSE" ) {
				$deer_res = "No";
			}
			echo '<li><span>Deer Resistance</span> <span class="p-meta-val">'.$deer_res.'</span></li>';
		endif;
		if(get_post_meta($post->ID, '_Wildlife_Attraction_SCSV', true)):
			echo '<li><span>Wildlife Attraction </span> <span class="p-meta-val">'.get_post_meta($post->ID, '_Wildlife_Attraction_SCSV', true).'</span></li>';
		endif;
		if(get_post_meta($post->ID, '_Botanic_Class', true)):
			echo '<li><span>Botanic Class</span> <span class="p-meta-val">'.get_post_meta($post->ID, '_Botanic_Class', true).'</span></li>';
		endif;
		if(get_post_meta($post->ID, '_Evergreen', true)):
			$evergreen = '';
			if( get_post_meta($post->ID, '_Evergreen', true) == "TRUE" ) {
				$evergreen = "Yes";
			}
			if( get_post_meta($post->ID, '_Evergreen', true) == "FALSE" ) {
				$evergreen = "No";
			}
			echo '<li><span>Evergreen</span> <span class="p-meta-val">'.$evergreen.'</span></li>';
		endif;
		
		echo '</ul>';
	return ob_get_clean();
}


//Hide “From:$X”
add_filter('woocommerce_get_price_html', 'hide_woo_variation_price', 10, 2);
function hide_woo_variation_price( $v_price, $v_product ) {
	$v_product_types = array( 'variable');
	if ( in_array ( $v_product->product_type, $v_product_types ) && !(is_shop()) ) {
		return '';
	}
	// return regular price
	return $v_price;
}

  
add_action( 'woocommerce_after_quantity_input_field', 'woo_display_quantity_plus' );  
function woo_display_quantity_plus() {
   echo '<button type="button" class="plus">+</button>';
}
  
add_action( 'woocommerce_before_quantity_input_field', 'woo_display_quantity_minus' );  
function woo_display_quantity_minus() {
   echo '<button type="button" class="minus">-</button>';
}
  
// -------------
// 2. Trigger update quantity script
  
add_action( 'wp_footer', 'woo_add_cart_quantity_plus_minus' );  
function woo_add_cart_quantity_plus_minus() { 
   if ( ! is_product() && ! is_cart() ) return;
    
   wc_enqueue_js( "   
           
      $(document).on( 'click', 'button.plus, button.minus', function() {
  
         var qty = $( this ).parent( '.quantity' ).find( '.qty' );
         var val = parseFloat(qty.val());
         var max = parseFloat(qty.attr( 'max' ));
         var min = parseFloat(qty.attr( 'min' ));
         var step = parseFloat(qty.attr( 'step' ));
 
         if ( $( this ).is( '.plus' ) ) {
            if ( max && ( max <= val ) ) {
               qty.val( max ).change();
            } else {
               qty.val( val + step ).change();
            }
         } else {
            if ( min && ( min >= val ) ) {
               qty.val( min ).change();
            } else if ( val > 1 ) {
               qty.val( val - step ).change();
            }
         }
 
      });
        
   " );
   
   ?>
   	<script>
	jQuery( document ).ready(function() {
		jQuery('.boxFaq button, .boxFaq h4').click(function() {
			var $parent = jQuery(this).closest('.boxFaq');
			var $btn = $parent.find('button');
			$parent.find('.toggle').slideToggle();
			$btn.text($btn.text() === '+' ? '-' : '+');
		});
		
		<?php
			global $post;
			if( get_post_meta($post->ID, '_hard_goods_product', true) && get_post_meta($post->ID, '_hard_goods_product', true) == "Yes" ) {
		?>

		/*setTimeout(function() {
			jQuery(".elementor-tabs-wrapper").hide();
			jQuery(".elementor-tab-content").hide();
			jQuery("#elementor-tab-content-1724").show();
		}, 1100);*/

		<?php } ?>
		
		/*jQuery('.boxFaq h4').on('click', function(e){
			var answer = jQuery(this).next('.boxContent');
			
			if(!$(answer).is(":visible")) {
			  jQuery(this).parent().addClass('open');
			} else {
			  jQuery(this).parent().removeClass('open');
			}
			jQuery(answer).slideToggle(300);
		  });*/
	});
	</script>
   <?php
}

function load_custom_js_scripts() {
	?>
	<script>
		jQuery(document).ready(function() {
			jQuery("#need_shipping").on("click", function() {
				if( jQuery(this).is(':checked') ) {
					jQuery(".shipping-fields-custom").show();
				} else {
					jQuery(".shipping-fields-custom").hide();
				}
			});
			jQuery("#need_installation").on("click", function() {
				if( jQuery(this).is(':checked') ) {
					jQuery(".shipping-fields-custom").show();
				} else {
					jQuery(".shipping-fields-custom").hide();
				}
			});
			jQuery("#all_tm_installation").on("click", function() {
				if( jQuery(this).is(':checked') ) {
					jQuery(".all-time-installation-fields").show();
					jQuery("#minimum-size").attr("required", "required");
					jQuery("#all-tm-quantity").attr("required", "required");
				} else {
					jQuery(".all-time-installation-fields").hide();
					jQuery("#minimum-size").removeAttr("required");
					jQuery("#all-tm-quantity").removeAttr("required");
				}
			});
			
			jQuery(".request-quote-btn").on("click", function() {
				jQuery("#request-quote-submit").click();
			});
			
			if( jQuery(".elementor-search-form__input").length ) {
				setTimeout(function(){
					var device_on = jQuery("body").attr("data-elementor-device-mode");
					if(device_on != "mobile") {
						jQuery(".elementor-search-form__input").attr("placeholder", "Common Name Search");
					}
				}, 50);
			}
			
			/*jQuery(".lb-map-close-btn").on("click", function() {
				jQuery(this).closest(".dialog-lightbox-widget-content").find(".dialog-lightbox-close-button").click();
			});*/
			jQuery( document ).on('click', '.lb-map-close-btn', function( event ) {
				//elementorProFrontend.modules.popup.closePopup( {id:7866}, event );
				elementorProFrontend.modules.popup.closePopup( {}, event );
				setTimeout(function(){
					jQuery(".zone_icon").trigger( "click" );
				}, 5);
			});
			
			jQuery(".filter-toggle-wrap").on("click", function() {
				//jQuery(".filter-toggle-wrap").not(this).removeClass("filter-active");
				//jQuery(".filter-toggle-wrap").not(this).find(".filter-content").hide(200);
				
				jQuery(this).toggleClass("filter-active");
				jQuery(this).find(".filter-content").toggle(200);
				
				var filter_metakey = jQuery(this).find(".filter-input:first input").attr("name").split("[]")[0];
				jQuery(this).find(".filter-input input").attr("type", "checkbox");
				jQuery(this).find(".filter-input input").attr("name", filter_metakey+"[]");
				
				//jQuery(".filter-inputs-btn").remove();
				/*if( !jQuery('button[data-filter-metakey="'+filter_metakey+'"]').length ) {
					jQuery('<button class="filter-inputs-btn" style="margin-top: 12px;" data-filter-metakey="'+filter_metakey+'">Update</button>').appendTo( jQuery(this).find(".filter-content") );
				}*/
			});
			
			if( jQuery(".filter-toggle-wrap").length ) {
				jQuery('<div class="src-filter-btn" style="padding-top: 12px;"><button class="filter-inputs-btn">Filter</button></div>').insertAfter( jQuery(".filter-toggle-wrap:last") );
				
				jQuery('<div class="src-filter-msg" style="background: #F1F1F1; padding: 10px; margin-bottom: 12px; font-size: 15px; line-height: 1.4em;">The search fields below will allow you to search for multiple categories. Please select and then use the “filter” button at the bottom of this row.</div>').insertBefore( jQuery(".filter-toggle-wrap:first") );
			}
			
			if( jQuery(".woocommerce-no-products-found").length ) {
				setTimeout(function() {
					var no_prod_src = jQuery('<div class="no-products-found-search-form"></div>');
					jQuery(no_prod_src).insertAfter( jQuery(".woocommerce-no-products-found") );
					jQuery(".search-filter:first").clone(true).appendTo(no_prod_src);
					jQuery(".elementor-search-form:first").clone(true).appendTo(no_prod_src);
				}, 75);
			}
			
			if( cvn_object.logged_name != "" ) {
				jQuery('.elementor-icon-list-item a[data-itemfor="profilename"] .elementor-icon-list-text').text("Hi "+cvn_object.logged_name);
				jQuery('.elementor-icon-list-item a[data-itemfor="profilename"]').removeAttr("href");
			} else {
				jQuery('a[data-itemfor="profilename"]').parent(".elementor-icon-list-item").hide();
			}

			jQuery(".filter-content").on("click", function(e) {
				e.stopPropagation();
			});
			
			jQuery(".filter-content input").on("click", function(e) {
				e.stopPropagation();
			});
			
			//jQuery(".filter-content input").on("click", function() {});
			//jQuery(".filter-content").on("click", ".filter-inputs-btn", function(e) {
			jQuery(".elementor-widget-html, .ast-woo-sidebar-widget").on("click", ".filter-inputs-btn", function(e) {
				e.stopPropagation();
				//let meta_key = jQuery(this).attr("name");
				//let meta_val = jQuery(this).val();
				
				/*let meta_key = jQuery(this).attr("data-filter-metakey");
				let meta_opts = [];
				jQuery('input[name="'+meta_key+'[]"]:checked').each(function() {
					meta_opts.push(this.value);
				});
				let meta_val = meta_opts.join();*/
				
				let meta_opts = [];
				let src_meta_keys = [];
				jQuery('.filter-content input:checked').each(function() {
					var src_meta_key = jQuery(this).attr("name").split("[]")[0];
					//var src_meta_vals = '';
					if( jQuery.inArray(src_meta_key, src_meta_keys) === -1 ) {
						src_meta_keys.push(src_meta_key);
						//var meta_keys_arr_length = src_meta_keys.push(src_meta_key);
						//var filter_item_index = meta_keys_arr_length - 1;
						//var src_meta_vals = this.value;
						meta_opts.push(this.value);
					} else {
						var filter_item_index = src_meta_keys.indexOf(src_meta_key);
						var curr_meta_vals = meta_opts[filter_item_index];
						meta_opts[filter_item_index] = curr_meta_vals+","+this.value;
					}
					//var filter_metakey = jQuery(this).find(".filter-input:first input").attr("name").split("[]")[0];
					//meta_opts.push(this.value);
				});
				let meta_keys_items = src_meta_keys.length;
				
				//let currentUrl = window.location.href;
				let currentUrl = window.location.href.split('?')[0];
				var curr_url_arr = currentUrl.split("/");
				
				// check if the URL has paging
				if( curr_url_arr.includes("page") ) {
					// get pagination part
					var pgnIndex = curr_url_arr.indexOf("page");
					if (pgnIndex > -1) {
						//remove the number part
						curr_url_arr.splice(pgnIndex+1, 1);
						// remove the "page" part
						curr_url_arr.splice(pgnIndex, 1);
						currentUrl = curr_url_arr.join("/");
					}
				}
				
				var filterUrl = currentUrl;
				var params = (new URL(document.location)).searchParams;
				var parseSign = '';
				
				if( jQuery("body").hasClass("search") ) {
					//let sortBy = params.get("sortby");
					let srchTerm = params.get("s");
					filterUrl = filterUrl+"?s="+srchTerm;					
					parseSign = "&";
				} else if( jQuery("body").hasClass("page-id-16296") || jQuery("body").hasClass("page-id-16449") || jQuery("body").hasClass("page-id-16477") || jQuery("body").hasClass("page-id-16489") || jQuery("body").hasClass("page-id-18134") ) {
					if( params.get("cat_term") ) {
						let catTerm = params.get("cat_term");
						filterUrl = filterUrl+"?cat_term="+catTerm;
						parseSign = "&";
					} else if( params.get("sterm") ) {
						// set params for the Botanical page
						let sterm = params.get("sterm");
						filterUrl = filterUrl+"?sterm="+sterm+"&sortby=BtncName";
						//filterUrl = filterUrl+"?sterm="+sterm;
						parseSign = "&";
					} else {
						parseSign = "?";
					}
				} else {
					parseSign = "?";
				}

				/*if( params.get("meta_k") && params.get("meta_v") ) {
					let metaKey = params.get("meta_k");
					let metaValue = params.get("meta_v");
					filterUrl = filterUrl+parseSign+"meta_k="+metaKey+"&meta_v="+metaValue;
				}*/
				
				
				//filterUrl = filterUrl+parseSign+"meta_k="+meta_key+"&meta_v="+meta_val;
				var multi_meta_params = '';
				jQuery.each(src_meta_keys, function(index, value) {
					if( index == 0 ) {
						var meta_key_num = '';
						var meta_params_parser = '';
					} else {
						var meta_key_num = index+1;
						var meta_params_parser = "&";
					}
					multi_meta_params += meta_params_parser+"meta_k"+meta_key_num+"="+value+"&meta_v"+meta_key_num+"="+meta_opts[index];
					if( index == (meta_keys_items - 1) ) {
						multi_meta_params += "&meta_keys_num="+meta_keys_items;
					}
				});
				filterUrl = filterUrl+parseSign+multi_meta_params;

				
				/*
				if( jQuery("body").hasClass("search") ) {
					let params = (new URL(document.location)).searchParams;
					let srchTerm = params.get("s");
					var filterUrl = currentUrl+"?s="+srchTerm+"&meta_k="+meta_key+"&meta_v="+meta_val;
				} else {
					var filterUrl = currentUrl+"?meta_k="+meta_key+"&meta_v="+meta_val;
				}
				*/
				
				window.location = filterUrl;
			});
			
			<?php if ( is_user_logged_in() ) { ?>
			jQuery('.elementor-icon-list-item a[data-user-item="log"]').attr("href", "/my-account/");
			jQuery('.elementor-icon-list-item a[data-user-item="log"] span').text('Account');
			<?php } ?>
			
			<?php if ( is_page(18) ) { ?>
			jQuery('<li class="woocommerce-MyAccount-navigation-link woocommerce-MyAccount-navigation-link--wishlist"><a href="/cart/">View Wishlist</a></li>').insertAfter( jQuery('.woocommerce-MyAccount-navigation-link--dashboard') );
			<?php } ?>
			
			jQuery(".sorting-opts").on("click", function() {
				let meta_val = jQuery(this).val();
				let currentUrl = window.location.href.split('?')[0];
				var curr_url_arr = currentUrl.split("/");
				
				// check if the URL has paging
				if( curr_url_arr.includes("page") ) {
					// get pagination part
					var pgnIndex = curr_url_arr.indexOf("page");
					if (pgnIndex > -1) {
						//remove the number part
						curr_url_arr.splice(pgnIndex+1, 1);
						// remove the "page" part
						curr_url_arr.splice(pgnIndex, 1);
						currentUrl = curr_url_arr.join("/");
					}
				}
				
				var filterUrl = currentUrl;
				var params = (new URL(document.location)).searchParams;
				var parseSign = '';
				
				if( jQuery("body").hasClass("search") ) {
					//let sortBy = params.get("sortby");
					let srchTerm = params.get("s");
					filterUrl = filterUrl+"?s="+srchTerm;					
					parseSign = "&";
				} else if( jQuery("body").hasClass("page-id-16296") || jQuery("body").hasClass("page-id-16449") || jQuery("body").hasClass("page-id-18134") ) {
					if( params.get("cat_term") ) {
						let catTerm = params.get("cat_term");
						filterUrl = filterUrl+"?cat_term="+catTerm;
						parseSign = "&";
					} else if( params.get("sterm") ) {
						let sterm = params.get("sterm");
						filterUrl = filterUrl+"?sterm="+sterm;
						parseSign = "&";
					} else {
						parseSign = "?";
					}
				} else {
					parseSign = "?";
				}

				/*if( params.get("meta_k") && params.get("meta_v") ) {
					let metaKey = params.get("meta_k");
					let metaValue = params.get("meta_v");
					filterUrl = filterUrl+parseSign+"meta_k="+metaKey+"&meta_v="+metaValue;
					parseSign = "&";
				}*/
				
				if( params.get("meta_keys_num") && params.get("meta_keys_num") != "" ) {
					var multi_meta_params = '';
					var srced_meta_items = parseInt( params.get("meta_keys_num") );
					for(var i=0; i<srced_meta_items; i++) {
						if( i == 0 ) {
							var meta_key_num = '';
							var meta_params_parser = '';
						} else {
							var meta_key_num = i+1;
							var meta_params_parser = "&";
						}
						var srced_meta_key = params.get("meta_k"+meta_key_num);
						var srced_meta_val = params.get("meta_v"+meta_key_num);
						multi_meta_params += meta_params_parser+"meta_k"+meta_key_num+"="+srced_meta_key+"&meta_v"+meta_key_num+"="+srced_meta_val;
						if( i == (srced_meta_items - 1) ) {
							multi_meta_params += "&meta_keys_num="+srced_meta_items;
						}
					}
					filterUrl = filterUrl+parseSign+multi_meta_params;
					parseSign = "&";
				}
				
				if(meta_val == "Botanicname") {
					if( jQuery("body").hasClass("page-id-16296") || jQuery("body").hasClass("page-id-16449") ) {
						filterUrl = filterUrl+parseSign+"sortnameby="+meta_val;
					} else if( jQuery("body").hasClass("page-id-18134") ) {
						filterUrl = filterUrl+parseSign+"sortby=BtncName";
					} else {
						filterUrl = filterUrl+parseSign+"sortby="+meta_val;
					}
					window.location = filterUrl;
				} else {
					//window.location = currentUrl;
					window.location = filterUrl;
				}
			});
			
			if( jQuery("body").hasClass("tax-product_cat") ) {
				jQuery(".woocommerce-products-header").clone().prependTo(".ast-container");
			}
			
			if( jQuery("body").hasClass("page-id-16296") || jQuery("body").hasClass("page-id-16449") || jQuery("body").hasClass("page-id-16477") || jQuery("body").hasClass("page-id-16489") ) {
				<?php if( isset($_GET["cat_term"]) && $_GET["cat_term"] != "" ) { ?>
				var targetCat = "<?php echo $_GET["cat_term"]; ?>";
				<?php } else { ?>
				var targetCat = "all";
				<?php } ?>
				jQuery('.special-cat-filters .elementor-icon-list-item a[data-cat="'+targetCat+'"] span').css("color", "#b79906");
			}
			
			if( jQuery("body").hasClass("woocommerce-cart") && jQuery(".material-calc-item").length ) {
				var material_qty = parseFloat(jQuery('.material-calc-item').attr('class').split('-').pop());
				jQuery(".material-calc-item .product-quantity .quantity").hide();
				jQuery(".material-calc-item .product-quantity").prepend( '<div class="mtl-qty">'+material_qty+'</div>' );
				
				/*jQuery(".material-calc-item .minus").hide();
				jQuery(".material-calc-item .plus").hide();
				jQuery(".material-calc-item .qty").attr("disabled", "disabled");*/
			}
			
			if( jQuery('input[name="s"]').length ) {
				jQuery('input[name="s"]').attr("autocomplete", "off");
			}
			
			jQuery('input[name="s"]').on("focus", function() {
				//jQuery(".search-filter").addClass("slideup");
			});
			jQuery(".search-filter").on("change", function(e) {
				var searchin = jQuery(this).val();
				if( searchin ) {
					jQuery('.searchin').remove();
					var device_on = jQuery("body").attr("data-elementor-device-mode");
					if( searchin == "BotName" ) {
						jQuery('<input type="hidden" name="searchin" class="searchin" value="'+searchin+'" />').insertAfter( jQuery('input[name="s"]') );
						if(device_on != "mobile") {
							jQuery(".elementor-search-form__input").attr("placeholder", "Botanical Name Search");
						}
					} else {
						if(device_on != "mobile") {
							jQuery(".elementor-search-form__input").attr("placeholder", "Common Name Search");
						}
					}
					//jQuery('input[name="s"]').focus();
					//jQuery(this).removeClass("slideup");
					//e.stopPropagation();
				}
			});
		});
		
		jQuery(document).on("updated_cart_totals", function() {
			if( jQuery("body").hasClass("woocommerce-cart") && jQuery(".material-calc-item").length ) {
				var material_qty = parseFloat(jQuery('.material-calc-item').attr('class').split('-').pop());
				jQuery(".material-calc-item .product-quantity .quantity").hide();
				jQuery(".material-calc-item .product-quantity").prepend( '<div class="mtl-qty">'+material_qty+'</div>' );
				
				/*jQuery(".material-calc-item .minus").hide();
				jQuery(".material-calc-item .plus").hide();
				jQuery(".material-calc-item .qty").attr("disabled", "disabled");*/
			}
		});
		
		// trigger when the mini cart is loaded/updated
		jQuery(document).on("wc_fragments_loaded wc_fragments_refreshed", function() {
			if( jQuery(".elementor-widget-woocommerce-menu-cart .material-calc-item").length ) {
				var material_qty = parseFloat(jQuery('.material-calc-item').attr('class').split('-').pop());
				jQuery(".elementor-widget-woocommerce-menu-cart .material-calc-item .product-quantity").text(material_qty+" ×");
			}
		});
	</script>
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			jQuery(function($) {
				var mywindow = $(window);
				var lastScrollTop = 0;
				window.addEventListener('scroll', function() {
					var mypos = mywindow.scrollTop();
					var topbar_height = $('.topbar-sect').outerHeight();
					var headerbar_height = $('.main-headerbar').outerHeight();
					var topbar_pos = 0;
					var headerbar_pos = topbar_height;
					var header_height = topbar_height + headerbar_height;
					if( jQuery(".limited-offer-banner").is(":visible") ) {
						var limited_offer_height = $('.limited-offer-banner').outerHeight();
						header_height += limited_offer_height;
					}
					if( jQuery("body").hasClass("admin-bar") ) {
						var adminbar_height = jQuery("#wpadminbar").outerHeight();
						topbar_pos += adminbar_height;
						headerbar_pos += adminbar_height;
						header_height += adminbar_height;
					}
					
					if (mypos > lastScrollTop) {
						// scrolling down
					} else {
						// scrolling up
						if( mypos > 110 ) {
							$('.topbar-sect').removeClass('headerup');
							$('.main-headerbar').removeClass('headerup');
							$('.elementor.elementor-location-header').css("min-height", header_height+"px");
							$('.topbar-sect').css({"position": "fixed", "top": topbar_pos+"px", "left": "0", "width": "100%", "z-index": "9"});
							$('.main-headerbar').css({"position": "fixed", "top": headerbar_pos+"px", "left": "0", "width": "100%", "z-index": "8"});
						} else {
							$('.topbar-sect').addClass('headerup');
							$('.main-headerbar').addClass('headerup');
							$('.topbar-sect').css({"position": "", "top": "", "left": "", "width": "", "z-index": ""});
							$('.main-headerbar').css({"position": "", "top": "", "left": "", "width": "", "z-index": ""});
							$('.elementor.elementor-location-header').css("min-height", "");
						}
					}
					lastScrollTop = mypos;
				});
			});
		});
	</script>
	<style>
		#stickyheaders{
			transition : transform 0.34s ease;
		}
		.headerup{
			/*transform: translateY(-110px);*/ /*adjust this value to the height of your header*/
		}
		.materail-measure-field select, .material-list-dropdown select {
			height: 45px;
			background-position-y: 17px;
		}
		.wp-pagenavi {
			margin-top: 40px;
		}
		.wp-pagenavi .pages, .wp-pagenavi .first, .wp-pagenavi .last, .src-pr-btns .added_to_cart, .archive.tax-product_cat .astra-shop-summary-wrap .button.add_to_cart_button, .return-to-shop, .elementor-menu-cart__product-image img:nth-child(2) {
			display: none;
		}
		.wp-pagenavi a, .wp-pagenavi .extend, .wp-pagenavi .current {
			width: 40px;
			height: 40px;
			display: inline-block;
			text-align: center;
			line-height: 34px;
		}
		.src-pr-btns a {
			background: #b79906;
			color: #fff;
			padding: 8px 25px;
			display: inline-block;
			margin-top: 10px;
		}
		.src-pr-btns a:hover {
			background: #224121;
		}
		.src-pr-btns a.ajax_add_to_cart.added::after {
			font-family: WooCommerce;
			content: '\e017';
			margin-left: .53em;
		}
		.shipping_form_area {
			max-width: 750px;
			padding-top: 20px;
		}
		.up-sells .astra-shop-summary-wrap .custom-btn-link, .cross-sells .astra-shop-summary-wrap .custom-btn-link {
			display: none;
		}
		
		<?php if( is_user_logged_in() && is_account_page() && !is_wc_endpoint_url() ) { ?>
		.woocommerce-MyAccount-content > p:first-of-type { display: none; }
		.woocommerce-MyAccount-content > p:nth-of-type(2) { display: none; }
		<?php }
			$lands_cats = array(427,417,418,410,414,398,396,402,409,401,397,400,416,403,408,404,405,412,413,395);
			$term_id = get_queried_object_id();
			if( in_array($term_id, $lands_cats) ) {
				?>
				#secondary, .sorting-options-list { display: none; }
				#primary { margin-left: auto; margin-right: auto; float: none; }
				
				@media (min-width: 922px) {
					#primary { width: 80%; }
				}
				<?php
			}
			
			if( has_term( $lands_cats, 'product_cat' ) ) {
			?>
				.elementor-section.product_tabs {
					display: none;
				}
			<?php
			}
		?>
	</style>
	<?php
}
add_action("wp_head", "load_custom_js_scripts");

add_shortcode('tabs_video_resources', 'get_tab_video_resource_shortcode');
function get_tab_video_resource_shortcode(){
	global $post;
	ob_start();
	
	//$embed_url = esc_url( get_post_meta( get_the_ID(), '_theme_embed', true ) );
	//echo wp_oembed_get( $embed_url );

	$entries = get_post_meta( get_the_ID(), '_cmb2_video_resources', true );
		if ($entries) {
		 echo '<div class="video_resources">';
                foreach ( (array) $entries as $key => $entry ):				
					 if ( isset( $entry['video_url'] ) ) {
						echo '<div class="video_item">'.wp_oembed_get($entry['video_url']).' </div>';
					 }             
              endforeach;
		echo '</div>'; 
		} else {
			//echo 'N/A';
			?>
			<script>
				jQuery(document).ready(function() {
					setTimeout(function(){
						jQuery("#elementor-tab-title-1722").hide();
					}, 1000);
				});
			</script>
			<?php
		}

	return ob_get_clean();
}

add_shortcode('tabs_resources', 'get_tab_resource_shortcode');
function get_tab_resource_shortcode(){
	global $post;
	ob_start();
		if(get_post_meta($post->ID, '_cmb2_resources', true)){
			echo wpautop(get_post_meta($post->ID, '_cmb2_resources', true));
		}else {
			//echo 'N/A';
			?>
			<script>
				jQuery(document).ready(function() {
					setTimeout(function(){
						jQuery("#elementor-tab-title-1723").hide();
					}, 1000);
				});
			</script>
			<?php
		}
	return ob_get_clean();
}


add_shortcode('tabs_faqs', 'get_tab_faqs_shortcode');
function get_tab_faqs_shortcode(){
	global $post;
	ob_start();
	if( get_post_meta( get_the_ID(), '_cmb2_faq_desc', true ) ) {
		echo '<div class="product-faq-desc">'.wpautop( get_post_meta( get_the_ID(), '_cmb2_faq_desc', true ) ).'</div>';
	}
	//$entries = get_post_meta( get_the_ID(), '_product_faq', true );
	$entries = get_post_meta( get_the_ID(), '_cmb2_faq_video_resources', true );
	if ($entries) {
		echo '<div class="section_faqs">';
		foreach ( (array) $entries as $key => $entry ):				
			echo '<div class="boxFaq"> ';
			/*if ( isset( $entry['question'] ) ) {
				echo '<div class="faq_header"><h4>'.esc_html($entry['question']).'</h4>  <button class="toggle_button">+</button></div>';
			}
			if ( isset( $entry['answer'] ) ) {
				echo '<div class="boxContent toggle">'.wpautop( $entry['answer']).'</div>';               
			}*/
			if ( isset( $entry['product_faq_video'] ) ) {
				$faq_id = $entry['product_faq_video'];
				if( get_post_meta($faq_id, "video_url", true) ) {
					echo '<div class="faq_header"><h4>'.esc_html(get_the_title($faq_id)).'</h4> <button class="toggle_button">+</button></div>';
					$content = apply_filters('the_content', get_post_field('post_content', $faq_id));
					echo '<div class="boxContent toggle">';
						if ( !empty($content) ) {
							echo $content;
						}
						echo '<div class="video_item">'.wp_oembed_get( get_post_meta($faq_id, "video_url", true) ).' </div>';
					echo '</div>';
				} else {
					echo '<div class="faq_header"><h4>'.esc_html(get_the_title($faq_id)).'</h4>  <button class="toggle_button">+</button></div>';
					$post = get_post( $faq_id );
					//$the_content = apply_filters('the_content', $post->post_content);
					$content = apply_filters('the_content', get_post_field('post_content', $faq_id));
					if ( !empty($content) ) {
						echo '<div class="boxContent toggle">'.$content.'</div>';
					}
					//echo '<div class="boxContent toggle">'.wpautop( $entry['answer']).'</div>';
				}
			}
			echo '</div>';
		endforeach;
		echo '</div>'; 
	} else {
		//echo 'N/A';
	?>
	<script>
		jQuery(document).ready(function() {
			setTimeout(function(){
				jQuery("#elementor-tab-title-1724").hide();
			}, 1000);
		});
	</script>
	<?php
	}
	return ob_get_clean();
}


//add_action("woocommerce_after_add_to_cart_form", "show_custom_product_meta");
function show_custom_product_meta() {
	global $product;
	echo 'Botanic Class: '.get_post_meta($product->id, '_Botanic_Class', true).'<br>'; 
	//echo 'Botanic: '.$botanic_meta;

	echo 'Botanic Name: '.get_post_meta($product->id, '_botanical_name', true).'<br>'; 
	echo 'Evergreen: '.get_post_meta($product->id, '_Evergreen', true);
	//echo get_post_meta($product->id, 'landscape_attributes_para2', true);
	//print_r( get_post_meta($product->id) );
}

add_filter( 'manage_edit-product_columns', 'remove_product_stock_column', 15 );
function remove_product_stock_column($columns){

   //remove column
   unset( $columns['is_in_stock'] );

   return $columns;
}


add_shortcode('display_hardiness_zone', 'get_hardiness_zone_popup');
function get_hardiness_zone_popup(){
	ob_start();
	//global $post;
	//echo '<div class="hardiness_zone">'.wpautop(get_post_meta($post->ID, '_cmb2_hardiness_zone', true)).'</div>';
	?>
	<div class="hardiness_zone">
		<h2>Hardiness Zone</h2>

		<p>A hardiness zone is a geographic area defined as having a certain range of annual minimum temperature, a factor relevant to the survival of many plants. The Canadian Plant Hardiness Zone map (PHZ Map) system defines 9 zones by long-term average annual extreme minimum temperatures. With 0 having the coldest and 9 the warmest. The zone number designated to each plant species corresponds to the extreme minimum temperatures that plant can withstand. Any plant located in a lower zone then its designation has the potential of not surviving the cold months.</p>
		<p><a href="#">Government of Canada Plant Hardiness of Canada</a> <a href="#">Government of Canada Map Link</a></p>
		<p><a href="#">Government of Canada Plant Hardiness Zone by Municipality</a></p>
		<a href="#elementor-action%3Aaction%3Dpopup%3Aopen%26settings%3DeyJpZCI6Ijc4NjYiLCJ0b2dnbGUiOmZhbHNlfQ%3D%3D" class="button">View Map</a>
	</div>
	<?php
	return ob_get_clean();
}

add_shortcode('display_growth_rate', 'get_growth_rate_zone_popup');
function get_growth_rate_zone_popup(){
	ob_start();
	//global $post;
	//echo '<div class="growth_rate_zone">'.wpautop(get_post_meta($post->ID, '_cmb2_growth_rate_zone', true)).'</div>';
	?>
	<div class="growth_rate_zone">
		<p><img src="https://clearviewnursery.com/wp-content/uploads/2022/05/zones-map-1.jpg" alt="" class="alignnone size-full wp-image-10559" style="max-width: 650px;" /></p>
		<p style="text-align: center;"><a href="javascript:void(0);" class="lb-map-close-btn button" style="font-size: 16px;">See Text Description</a></p>
 		<!--<a href="#elementor-action%3Aaction%3Dpopup%3Aopen%26settings%3DeyJpZCI6Ijc4NTQiLCJ0b2dnbGUiOmZhbHNlfQ%3D%3D" class="zone-text-lb">See Text Description</a>-->
	</div>
	<?php
	return ob_get_clean();
}

add_shortcode('get_search_results_counter', 'get_search_results_counter');
function get_search_results_counter() {
	/*global $wp_query;
	
	ob_start();
	if ( $wp_query->is_search ) {
		echo '<div style="font-size: 18px; color: #224121;">'.$wp_query->found_posts.' results found.</div>';
	}
	return ob_get_clean();*/
	
	$s_keyword = $_GET["s"];
	$args = array(
		'post_type' => 'product',
		'post__not_in' => array(18112),
		'meta_query' => array(),
	);

	// check if it is the default Search page
	if( is_search() ) {
		$args["s"] = $s_keyword;
	}
	
	// check if it is the Botanical search page
	if( is_page(18134) && isset($_GET["sterm"]) ) {
		$args["meta_query"][] = array(
				'key' => '_botanical_name',
				'value' => $_GET["sterm"],
				'compare' => 'LIKE'
			);
		$args["orderby"] = "meta_value";
	}
	
	/*if( (isset($_GET["meta_k"]) && $_GET["meta_k"] != "") && (isset($_GET["meta_v"]) && $_GET["meta_v"] != "") ) {
		$meta_key = $_GET["meta_k"];
		$meta_value = explode(",", $_GET["meta_v"]);
		$args["meta_query"][] = array (
				'key' => $meta_key,
				'value' => $meta_value,
				'compare' => 'IN',
			);
	}*/
	
	if( isset($_GET["meta_keys_num"]) && $_GET["meta_keys_num"] != "" ) {
		$meta_keys_num = $_GET["meta_keys_num"];
		for($i=1; $i<=$meta_keys_num; $i++) {
			if($i == 1) {
				$key_num = '';
			} else {
				$key_num = $i;
			}
			
			$meta_key = $_GET["meta_k".$key_num];
			$meta_value = explode(",", $_GET["meta_v".$key_num]);
			$args["meta_query"][] = array (
				'key' => $meta_key,
				'value' => $meta_value,
				'compare' => 'IN',
			);
		}
	}
	
	if( isset($_GET["sortby"]) && $_GET["sortby"] == "Botanicname" ) {
		//$args["meta_key"] = "_botanical_name";
		//$args["orderby"] = "meta_value";
		$args["meta_query"][] = array(
									'key'     => '_botanical_name',
									'compare' => 'EXISTS' // CHECK THE VALUE OF META KEY IF EXISTS?
								);
	}
	
	$the_query = new WP_Query( $args );
	
	ob_start();
	echo '<div style="font-size: 18px; color: #224121;">'.$the_query->found_posts.' results found.</div>';
	return ob_get_clean();
}

add_shortcode('get_search_term_info', 'get_search_term_info');
function get_search_term_info() {
	global $wp_query;
	
	ob_start();
	if ( $wp_query->is_search ) {
		if( $_GET["s"] != "" ) {
			echo '<div style="font-size: 26px; text-align: center; font-weight: 600; margin-bottom: 25px; color: #224121;">You have searched for "'.$_GET["s"].'"</div>';
		}
	}
	return ob_get_clean();
}

add_shortcode('get_request_call_form', 'get_request_call_form_shortcode');
function get_request_call_form_shortcode() {
	ob_start();
	
	if( is_page(11076) ) {
		echo do_shortcode('[gravityform id="3" title="false" ajax="true"]');
	}
	
	if( is_page(11068) ) {
		echo do_shortcode('[gravityform id="4" title="false" ajax="true"]');
	}
	
	if( is_page(11011) ) {
		echo do_shortcode('[gravityform id="5" title="false" ajax="true"]');
	}
	
	return ob_get_clean();
}

/**
Remove all possible fields
**/
add_filter( 'woocommerce_checkout_fields', 'remove_checkout_fields_init' );
function remove_checkout_fields_init( $fields ) {
	// Billing fields
	unset( $fields['billing']['billing_company'] );
	unset( $fields['billing']['billing_email'] );
	unset( $fields['billing']['billing_phone'] );
	unset( $fields['billing']['billing_state'] );
	unset( $fields['billing']['billing_first_name'] );
	unset( $fields['billing']['billing_last_name'] );
	unset( $fields['billing']['billing_address_1'] );
	unset( $fields['billing']['billing_address_2'] );
	unset( $fields['billing']['billing_city'] );
	unset( $fields['billing']['billing_postcode'] );
	unset( $fields['billing']['billing_country'] );
	
	// Shipping fields
	unset( $fields['shipping']['shipping_company'] );
	unset( $fields['shipping']['shipping_phone'] );
	unset( $fields['shipping']['shipping_state'] );
	unset( $fields['shipping']['shipping_first_name'] );
	unset( $fields['shipping']['shipping_last_name'] );
	unset( $fields['shipping']['shipping_address_1'] );
	unset( $fields['shipping']['shipping_address_2'] );
	unset( $fields['shipping']['shipping_city'] );
	unset( $fields['shipping']['shipping_postcode'] );
	
	// Order fields
	unset( $fields['order']['order_comments'] );
	return $fields;
}
remove_action( 'woocommerce_checkout_order_review', 'woocommerce_order_review', 10 );

add_filter( 'wp_mail_from_name', function( $name ) {
	return 'Clearview Nursery Ltd.';
});

/*add_filter( 'wp_mail_from', function( $email ) {
	return 'info@clearviewnursery.com';
});*/

add_action('woocommerce_after_checkout_form', 'get_checkout_custom_info');
function get_checkout_custom_info(){
	$installation_interest = false;
	$shipping_interest = false;
	
	global $woocommerce;
	if( isset($_GET["qreq"]) && $_GET["qreq"] == "sent" ) {
		echo '<div style="text-align: center; padding: 0 15px 30px; color: #b79906;">Quote Request Sent!</div>';
	}
	echo '<div class="order_details_area"><h2 style="border-bottom: 1px solid #E0E0E0;padding-bottom: 10px;color: #b79906;font-weight: 700;">Wish List Details</h2>';
	echo '<table>';
	foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
		echo '<tr>';
			$product = $cart_item['data'];
   			$product_id = $cart_item['product_id'];
			$getProductDetail = wc_get_product( $cart_item['product_id'] );
			if( isset($cart_item['_material_quantity']) ) {
				$item_qty = $cart_item['_material_quantity'];
				$item_url = 'javascript: void(0);';
			} else {
				$item_qty = $cart_item['quantity'];
				$item_url = $product->get_permalink( $cart_item );
			}
		
			/*if( isset($_GET["print_p"]) ) {
				echo "<pre>";
				print_r($cart_item['variation']);
				echo "</pre>";
				break;
			}*/
		
			$var_size = '';
			if( count($cart_item['variation']) > 0 ) {
				if( $product->get_attribute('pa_size') ) {
					$var_size = " - ".$product->get_attribute('pa_size');
				}
			}
		
			echo '<td width="100"><span class="quentity">'.$item_qty.'</span><a href="'.$item_url.'">'.$getProductDetail->get_image('thumbnail', '').'</a></td>';
			if( isset($cart_item['_meterial_name']) ) {
				echo '<td width="500">'.$cart_item['_meterial_name'].'</td>';
			} else {
				echo '<td width="500">'.$product->get_title( $cart_item ).$var_size.'</td>';
			}
			echo '<td>'.WC()->cart->get_product_price( $product ).'</td>';
			//echo '<td>'.WC()->cart->get_product_subtotal( $product, $cart_item['quantity'] ).'</td>';
		echo '</tr>'; 
   		if(get_post_meta($product_id, '_installation_available', true) && get_post_meta($product_id, '_installation_available', true) == "yes") {
			$installation_interest = true;
		}
		if(get_post_meta($product_id, '_shipping_eligible', true) && get_post_meta($product_id, '_shipping_eligible', true) == "yes") {
			$shipping_interest = true;
		}
	}
	echo '</table>';
	echo '<ul>';
	$subtotal = WC()->cart->get_subtotal();
	echo '<li><label>SUBTOTAL</label><span>'.number_format(floatval($subtotal), 2, '.', '').'</span></li>';
	//echo '<li><label>SHIPPING</label><span>Our team will review your requirement and provide you with a quote for shipping.</span></li>';
	$total_tax = WC()->cart->get_tax_totals();
	echo '<li><label>HST</label><span>'.number_format(floatval($total_tax["HST-1"]->amount), 2, '.', '').'</span></li>';
	echo '<li><label>TOTAL</label><span>'.WC()->cart->get_total().'</span></li>';
	echo '</ul>';
	echo '</div>';
?>
	<div class="shipping_form_area">
		<form action="" method="post">
			<div class="user-contact-info">
				<h3>Contact Information</h3>
				<div class="input_group">
					<div class="one_half"><input type="text" id="first_name" name="first_name" placeholder="First Name" required /></div>
					<div class="one_half last"><input type="text" id="last_name" name="last_name" placeholder="Last Name" required /></div>
				</div>
				<div class="input_group">
					<div class="one_half"><input type="text" id="email" name="email" placeholder="Email" required /></div>
					<div class="one_half last"><input type="text" id="phone" name="phone" placeholder="Phone" required /></div>
				</div>
			</div>
			<div class="gray_back checkbox_wrap">
				<input type="checkbox" name="need_shipping" id="need_shipping" value="1" autocomplete="off" /><label for="need_shipping">Would you like to request Installation or shipping? Minimum size and quantity required.</label>
				<?php
				if( $installation_interest ) {
				?>
				<!--<input type="checkbox" name="need_installation" id="need_installation" value="Want Installation" /><label for="need_installation">Installation or shipping?</label>-->
				<?php
				}
				if( $shipping_interest ) {
				?>
				<!--<input type="checkbox" name="need_shipping" id="need_shipping" value="Want Shipping" /><label for="need_shipping">Installation or shipping?</label>-->
				<?php } ?>
			</div>
			
			<div class="gray_back all-time-installation-fields" style="display: none; margin-top: 0; padding-top: 0;">
				<div class="input_group">
					<div class="one_half">
						<input type="text" id="minimum-size" name="minimum_size" placeholder="Minimum size" />
					</div>
					<div class="one_half last">
						<input type="text" id="all-tm-quantity" name="all_tm_quantity" placeholder="Quantity" />
					</div>
				</div>
			</div>
			
			<div class="gray_back shipping-fields-custom">
				<h2>Your Address</h2>
				<div class="input_group">
					<input type="text" id="street_address" name="street_address" placeholder="Street Address" />
				</div>
				<div class="input_group">
					<input type="text" id="apartment" name="apartment" placeholder="Apartment, Suit, Unit, etc." />
				</div>
				<div class="input_group">
					<input type="text" id="company_name" name="company_name" placeholder="Company Name (Optional)" />
				</div>
				<div class="input_group">
					<div class="one_half">
						<select name="country" id="country">
							<option value="">Country/Region</option>
							<?php
								$wc_countries = new WC_Countries();
								$countries = $wc_countries->get_countries();
								//print_r($countries);
								foreach($countries as $country) {
									echo '<option value="'.$country.'">'.$country.'</option>';
								}
							?>
						</select>
					</div>
					<div class="one_half last"><input type="text" id="city" name="city" placeholder="Town/City" /></div>
				</div><!--.input_group-->
			</div>
			<div class="button_wrap">
				<a class="button" href="/cart/">Return to Wish List</a>
				<input type="submit" name="request_quote_submit" id="request-quote-submit" value="Submit" style="display: none;" />
				<a class="button request-quote-btn" href="javascript: void(0);">Submit Request for Availability</a>
			</div>
		</form>
	</div>
<?php
}

function send_cart_details_to_priv_user() {
	if( isset($_POST["request_quote_submit"]) ) {
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

		$message = '';
		$message .= '<h2>Wish List Details</h2>';
		$message .= '<table cellpadding="10" cellspacing="0" style="width:100%;">';
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$message .= '<tr>';
				$product = $cart_item['data'];
	   			$product_id = $cart_item['product_id'];
				$getProductDetail = wc_get_product( $cart_item['product_id'] );
				if( isset($cart_item['_material_quantity']) ) {
					$item_qty = $cart_item['_material_quantity'];
					$item_url = '#';
				} else {
					$item_qty = $cart_item['quantity'];
					$item_url = $product->get_permalink( $cart_item );
				}
			
				$var_size = '';
				if( count($cart_item['variation']) > 0 ) {
					if( $product->get_attribute('pa_size') ) {
						$var_size = " - ".$product->get_attribute('pa_size');
					}
				}
			
				$message .= '<td width="80" style="text-align: center;"><a href="'.$item_url.'">'.$getProductDetail->get_image('thumbnail', '').'</a></td>';
				if( isset($cart_item['_meterial_name']) ) {
					$message .= '<td>'.$cart_item['_meterial_name'].'</td>';
				} else {
					$message .= '<td>'.$product->get_title( $cart_item ).$var_size.'</td>';
				}
				$message .= '<td>'.WC()->cart->get_product_price( $product ).'</td>';
				$message .= '<td><span class="quentity">'.$item_qty.'</span></td>';
			$message .= '</tr>';

		}   
		$message .= '</table>';
		
		$message .= '<table cellpadding="10" cellspacing="0" border="1" style="width:100%;">';
		$subtotal = WC()->cart->get_subtotal();		
		$message .= '<tr><td><label>SUB TOTAL:</label> <strong>'.number_format(floatval($subtotal), 2, '.', '').'</strong></td></tr>';
		//$message .= '<tr><td><label>SHIPPING</label><span>Our team will review your requirement and provide you with a quote for shipping.</span></td></tr>';
		$total_tax = WC()->cart->get_tax_totals();
		$message .= '<tr><td><label>HST:</label> <strong>'.number_format(floatval($total_tax["HST-1"]->amount), 2, '.', '').'</strong></td></tr>';
		$message .= '<tr><td><label>TOTAL:</label> <strong>'.WC()->cart->get_total().'</strong></td></tr>';
		$message .= '</table>';

		$first_name = $_POST["first_name"];
		$last_name = $_POST["last_name"];
		$email = $_POST["email"];
		$phone = $_POST["phone"];
		
		$message .= '<h2>Contact Information</h2>';
		$message .= '<table style="width:100%;">';
		$message .= '<tr>
							<td><strong>First Name:</strong> '.$first_name.'</td>
							<td><strong>Last Name:</strong> '.$last_name.'</td>
						</tr>
						<tr>
							<td><strong>Email:</strong> '.$email.'</td>
							<td><strong>Phone:</strong> '.$phone.'</td>
						</tr>';
		$message .= '</table>';

		if( isset($_POST["all_tm_installation"]) ) {
			$message .= '<h3>Would like to request Installation or shipping</h3>';
			
			$minimum_size = $_POST["minimum_size"];
			$all_tm_quantity = $_POST["all_tm_quantity"];
			
			$message .= '<table style="width:100%;">';
			$message .= '<tr>
							<td><strong>Minimum size:</strong> '.$minimum_size.'</td>
							<td><strong>Quantity:</strong> '.$all_tm_quantity.'</td>
						</tr>';
			$message .= '</table>';
		}
		
		if( isset($_POST["need_shipping"]) || isset($_POST["need_installation"]) ) {
			$message .= '<p>Would like to request Installation or shipping</p>';
			$message .= '<h3>Address</h3>';
			
			$street_address = $_POST["street_address"];
			$apartment = $_POST["apartment"];
			$company_name = $_POST["company_name"];
			$country = $_POST["country"];
			$city = $_POST["city"];
			
			$message .= '<table style="width:100%;">';
			$message .= '<tr>
							<td><strong>Street Address:</strong> '.$street_address.'</td>
							<td><strong>Apartment, Suit, Unit, etc.:</strong> '.$apartment.'</td>
						</tr>
						<tr>
							<td><strong>Company Name:</strong> '.$company_name.'</td>
							<td><strong>Country/Region:</strong> '.$country.'</td>
						</tr>
						<tr>
							<td><strong>Town/City:</strong> '.$city.'</td>
							<td>&nbsp;</td>
						</tr>';
			$message .= '</table>';
		}
		
		$mailto = "sales@clearviewnursery.com";
		if( wp_mail( $mailto, "Request for Availability", $message, $headers ) ) {
			wp_redirect("/thank-you-checkout/");
			exit;
		}
	}
}
add_action("init", "send_cart_details_to_priv_user");

/*add_action( 'elementor_pro/search_form/after_input', function( $form ) {
    echo '<input type="hidden" name="post_type" value="product" />';
}, 10, 1 );*/

add_action( 'login_enqueue_scripts', 'my_login_logo_one' );
function my_login_logo_one() { 
?> 
<style type="text/css"> 
body.login div#login h1 a {
	background-image: url(https://clearviewnursery.com/wp-content/uploads/2022/05/login-page-logo.png);
	padding-bottom: 30px; 
} 
.wp-core-ui .button-primary {
    background: #b79906 !important;
    border-color: #b79906 !important;
}
#login {
    width: 100% !important;
    max-width: 500px !important;
	position: relative;
    padding-bottom: 180px !important;
}
.login .privacy-policy-page-link{ display:none !important; }
.login form {
    border: none !important;
    border-radius: 10px;
}
.login input[type=text],
.login input[type=password] {
    background: #fbfbfb !important;
    border-color: #ebebeb !important;
    border-radius: 0 !important;
    padding: 8px 8px 8px 30px !important;
}
body.login #loginform #user_login {
    background-image: url(https://clearviewnursery.com/wp-content/uploads/2022/05/user-icon.png) !important;
    background-repeat: no-repeat !important;
    background-position: center left 10px !important;
}

body.login #loginform #user_pass {
    background-image: url(https://clearviewnursery.com/wp-content/uploads/2022/05/password-icon.png) !important;
    background-repeat: no-repeat !important;
    background-position: center left 10px !important;
}
/*div#login:after {
    content: "This login is for pre-registered business owners. To apply to become a preferred supplier with Clearview Nursery, please go to Trade Registration.";
    display: block;
    clear: both;
    text-align: center;
	font-size: 15px;
    line-height: 1.5em;
}*/
p.loginpage_note {
    position: absolute;
    bottom: 40px;
    left: 0;
    width: 100%;
    text-align: center;
	font-size: 20px;
	line-height: 24px;
}

@media (min-width: 768px){
	.login form { padding: 40px !important; }
	.login #login #nav { display: inline-block; float: left; padding-left: 0; }
	.login #login #backtoblog {
		float: right;
		padding-top: 7px;
		padding-right: 0;
	}
	#login p a { color: #b79906 !important; }
	.login #login h1 { background-color: #fff; margin-bottom: -50px; padding-top:30px; border-top-left-radius:10px; border-top-right-radius:10px;}
}
</style>
 <?php 
 
} 

add_action( 'login_form', 'login_extra_note' );
function login_extra_note() {
    ?>
    <p class="loginpage_note">This login is for pre-registered business owners. To apply to become a preferred supplier with Clearview Nursery, please go to <a href="/trade-registration/">Trade Registration</a>.</p> 
    <?php
}

function cvn_search_filter( $query ) {
    if ( ! is_admin() && $query->is_main_query() ) {
		if( isset($_GET["sortby"]) && $_GET["sortby"] == "Botanicname" ) {
			$query->set( 'meta_key', '_botanical_name' );
			$query->set( 'orderby', 'meta_value');
		}
		
		if( is_product_category() ) {
			/*if( (isset($_GET["meta_k"]) && $_GET["meta_k"] != "") && (isset($_GET["meta_v"]) && $_GET["meta_v"] != "") ) {
				$meta_key = $_GET["meta_k"];
				$meta_value = explode(",", $_GET["meta_v"]);
				//$meta_value = $_GET["meta_v"];
				$query->set( 'meta_key', $meta_key );
				$query->set( 'meta_value', $meta_value );
				$query->set( 'meta_compare', 'IN' );
			}*/
			
			if( isset($_GET["meta_keys_num"]) && $_GET["meta_keys_num"] != "" ) {
				$meta_keys_num = $_GET["meta_keys_num"];
				$meta_query_arr = array();
				for($i=1; $i<=$meta_keys_num; $i++) {
					if($i == 1) {
						$key_num = '';
					} else {
						$key_num = $i;
					}

					$meta_key = $_GET["meta_k".$key_num];
					$meta_value = explode(",", $_GET["meta_v".$key_num]);
					$meta_query_arr[] = array(
							'key'     => $meta_key,
							'value'   => $meta_value,
							'compare' => 'IN',
						);
				}
				$query->set( 'meta_query', $meta_query_arr );
			}
		}
    }
}
add_action( 'pre_get_posts', 'cvn_search_filter' );

//add_filter('posts_request', 'cvn_supress_main_search_query', 10, 2);
function cvn_supress_main_search_query( $request, $query ){
    if( $query->is_main_query() && $query->is_search() && ! is_admin() ) {
        return false;
	}
	return $request;
}

// Hooks for simple, grouped, external and variation products
add_filter('woocommerce_product_get_price', 'cvn_custom_price_role', 99, 2 );
add_filter('woocommerce_product_get_regular_price', 'cvn_custom_price_role', 99, 2 );
add_filter('woocommerce_product_variation_get_regular_price', 'cvn_custom_price_role', 99, 2 );
add_filter('woocommerce_product_variation_get_price', 'cvn_custom_price_role', 99, 2 );
function cvn_custom_price_role( $price, $product ) {
	$price = cvn_custom_price_handling( $price, $product );  
	return $price;
}
 
// Variable (price range)
add_filter('woocommerce_variation_prices_price', 'cvn_custom_variable_price', 99, 3 );
add_filter('woocommerce_variation_prices_regular_price', 'cvn_custom_variable_price', 99, 3 );
function cvn_custom_variable_price( $price, $variation, $product ) {
	$price = cvn_custom_price_handling( $price, $product );  
	return $price;
}
 
function cvn_custom_price_handling($price, $product) {
	// Delete product cached price, remove comment if needed
	//wc_delete_product_transients($variation->get_id());

	//get our current user
	$current_user = wp_get_current_user();

	//check if the user role is the role we want
	if ( isset( $current_user->roles[0] ) && '' != $current_user->roles[0] && in_array( 'wholesale_customer',  $current_user->roles ) ) {

		//load the custom price for our product
		$wholesale_price = get_post_meta( $product->get_id(), '_wholesale_price', true );
		if( "$" == substr($wholesale_price, 0, 1) ) {
			$custom_price = substr($wholesale_price, 1);
		} else {
			$custom_price = $wholesale_price;
		}

		//if there is a custom price, apply it
		if ( ! empty($custom_price) ) {
			$price = $custom_price;
		}
	}
	
	if ( isset( $current_user->roles[0] ) && '' != $current_user->roles[0] && in_array( 'contractor_customer',  $current_user->roles ) ) {

		//load the custom price for our product
		$landscape_price = get_post_meta( $product->get_id(), '_contractor_pricing', true );
		if( "$" == substr($landscape_price, 0, 1) ) {
			$custom_price = substr($landscape_price, 1);
		} else {
			$custom_price = $landscape_price;
		}

		//if there is a custom price, apply it
		if ( ! empty($custom_price) ) {
			$price = $custom_price;
		}
	}

	return $price;
}

// 1. Add custom field input @ Product Data > Variations > Single Variation
add_action( 'woocommerce_variation_options_pricing', 'cvn_add_custom_field_to_variations', 10, 3 );
function cvn_add_custom_field_to_variations( $loop, $variation_data, $variation ) {
	woocommerce_wp_text_input( array(
		'id' => '_wholesale_price[' . $loop . ']',
		'class' => 'short',
		'label' => __( 'Wholesale Price', 'woocommerce' ),
		'value' => get_post_meta( $variation->ID, '_wholesale_price', true )
	) );
	woocommerce_wp_text_input( array(
		'id' => '_contractor_pricing[' . $loop . ']',
		'class' => 'short',
		'label' => __( 'Contractor Price', 'woocommerce' ),
		'value' => get_post_meta( $variation->ID, '_contractor_pricing', true )
	) );
}
 
// 2. Save custom field on product variation save
add_action( 'woocommerce_save_product_variation', 'cvn_save_custom_field_variations', 10, 2 );
function cvn_save_custom_field_variations( $variation_id, $i ) {
	$wholesale_price = $_POST['_wholesale_price'][$i];
	if ( isset( $wholesale_price ) ) update_post_meta( $variation_id, '_wholesale_price', esc_attr( $wholesale_price ) );
	
	$contractor_pricing = $_POST['_contractor_pricing'][$i];
	if ( isset( $contractor_pricing ) ) update_post_meta( $variation_id, '_contractor_pricing', esc_attr( $contractor_pricing ) );
}
 
// 3. Store custom field value into variation data
add_filter( 'woocommerce_available_variation', 'cvn_add_custom_field_variation_data' );
function cvn_add_custom_field_variation_data( $variations ) {
	$variations['_wholesale_price'] = '<div class="woocommerce_wholesale_price">Wholesale Price: <span>' . get_post_meta( $variations[ 'variation_id' ], '_wholesale_price', true ) . '</span></div>';
	
	$variations['_contractor_pricing'] = '<div class="woocommerce_contractor_pricing">Contractor Price: <span>' . get_post_meta( $variations[ 'variation_id' ], '_contractor_pricing', true ) . '</span></div>';
	
	return $variations;
}

//add_action("init", "get_cvn_product_variations");
function get_cvn_product_variations() {
	/*$args =  array(
		'post_type'      => 'product_variation',
		'post_status'    => 'publish',
		'posts_per_page' => 10,
		'post_parent'	   => 7959
	);

	$query = new WP_Query( $args );

	while( $query->have_posts() ) {
		$query->the_post();

		//var_dump( get_post_meta( get_the_id(), '_wholesale_price', true ) );

		//$product = wc_get_product( get_the_id() );
		$product = new WC_Product_Variation( get_the_id() );
		//echo $product->get_price();
		//echo get_post_meta( get_the_id(), '_wholesale_price', true );
		print_r( $product->get_meta_data() );

	}

	wp_reset_postdata();*/
	
	//global $wp_query;
	//echo $wp_query->request;
}

//add_filter( 'woocommerce_product_query_meta_query', 'cvn_shop_only_instock_products', 10, 2 );
function cvn_shop_only_instock_products( $meta_query, $query ) {
    // Only on shop archive pages
    if( is_admin() || is_search() || ( !is_shop() && !is_product_category() ) ) return $meta_query;

    $meta_query[] = array(
        'key'     => '_stock_status',
        'value'   => 'outofstock',
        'compare' => '!='
    );
    return $meta_query;
}

function generate_bulk_material_calculator() {
	ob_start();
	?>
	<div class="bulk-material-calculator-wrap">
		<form id="mulk-material-calculator" action="" method="post">
			<div class="material-heading-row">
				<div class="material-heading-title">Material</div>
				<div class="material-list-dropdown">
					<select name="material_list" id="material-list">
						<option value="">Select</option>
						<?php
						$args = array(
							'post_type' => 'product',
							'posts_per_page' => -1,
							'meta_key' => '_calculator_order',
							'orderby' => 'meta_value_num',
							'order' => 'ASC',
							'meta_query' => array(
								array(
									'key' => '_calculator_item',
									'value' => 'yes',
									'compare' => '=',
								),
							),
						);

						$mt_query = new WP_Query( $args );
						if ( $mt_query->have_posts() ) {
							while($mt_query->have_posts()) : $mt_query->the_post();
							$pid = get_the_ID();
							$sold_by = get_post_meta($pid, "_sold_by", true);
							$rprice = get_post_meta($pid, "_regular_price", true);
							
							$wholesale_price = get_post_meta($pid, "_wholesale_price", true);
							if( "$" == substr($wholesale_price, 0, 1) ) {
								$wprice = substr($wholesale_price, 1);
							} else {
								$wprice = $wholesale_price;
							}
							
							$contractor_price = get_post_meta($pid, "_contractor_pricing", true);
							if( "$" == substr($contractor_price, 0, 1) ) {
								$cprice = substr($contractor_price, 1);
							} else {
								$cprice = $contractor_price;
							}
						?>
						<option value="<?php the_title(); ?>" data-sold-by="<?php echo $sold_by; ?>" data-rprice="<?php echo $rprice; ?>" data-wprice="<?php echo $wprice; ?>" data-cprice="<?php echo $cprice; ?>"><?php the_title(); ?></option>
						<?php
							endwhile;
						}
						wp_reset_postdata();
						?>
					</select>
				</div>
			</div><!-- .material-heading-row -->
			
			<div class="material-fields-row">
				<div class="materail-measure-title">
					Length: 
				</div>
				<div class="materail-measure-field">
					<input type="text" name="length_feet" id="length-feet" placeholder="Feet" />
				</div>
				<div class="materail-measure-field">
					<!-- <input type="text" name="length_meters" id="length-meters" placeholder="meters" /> -->
					<select name="length_unit" id="length-unit">
						<!-- <option value="">Select Unit</option> -->
						<option value="Feet">Feet</option>
						<option value="Meters">Meters</option>
					</select>
				</div>
			</div><!-- .material-fields-row -->
			
			<div class="material-fields-row">
				<div class="materail-measure-title">
					Width: 
				</div>
				<div class="materail-measure-field">
					<input type="text" name="width_feet" id="width-feet" placeholder="Feet" />
				</div>
				<div class="materail-measure-field">
					<!-- <input type="text" name="width_meters" id="width-meters" placeholder="meters" /> -->
					<select name="width_unit" id="width-unit">
						<!-- <option value="">Select Unit</option> -->
						<option value="Feet">Feet</option>
						<option value="Meters">Meters</option>
					</select>
				</div>
			</div><!-- .material-fields-row -->
			
			<div class="material-fields-row">
				<div class="materail-measure-title">
					Depth: 
				</div>
				<div class="materail-measure-field">
					<input type="text" name="depth_inches" id="depth-inches" placeholder="Inches" />
				</div>
				<div class="materail-measure-field">
					<!-- <input type="text" name="depth_centimeters" id="depth-centimeters" placeholder="centimeters" /> -->
					<select name="depth_unit" id="depth-unit">
						<!--<option value="">Select Unit</option> -->
						<option value="Inches">Inches</option>
						<option value="Centimeters">Centimeters</option>
					</select>
				</div>
			</div><!-- .material-fields-row -->
			
			<button type="button" id="calculate-btn">Calculate</button>
		</form>
		
		<div class="calculation-results-wrap">
			<div class="calc-result-title">Material Required: </div>
			<div class="calc-result-number"></div>
			<div class="calc-result-units-soldin"></div>
		</div>
		<div class="calculation-results-wrap" style="margin-top: 10px;">
			<div class="calc-result-title">Cost Per Unit: </div>
			<div class="calc-cost-unit"></div>
			<div class="calc-result-sell-units"></div>
		</div>
		<div class="calculation-results-wrap" style="margin-top: 10px;">
			<div class="calc-result-title">Total Cost: </div>
			<div class="calc-result-cost"></div>
			<div class="calc-result-currency" style="border: none;"></div>
		</div>
		<div class="calculation-formula"></div>
		
		<button type="button" id="add-calc-cart-btn">Add Material To Wishlist</button>
	</div>

	<script>
		jQuery(document).ready(function() {
			jQuery("#calculate-btn").on("click", function() {
				let calcError = false;

				if( jQuery("#material-list").val() == "" ) {
					jQuery('<div class="calc-req-field">This field is required.</div>').insertAfter( jQuery("#material-list") );
					calcError = true;
				}

				if( jQuery("#length-unit").val() == "" ) {
					jQuery('<div class="calc-req-field">This field is required.</div>').insertAfter( jQuery("#length-unit") );
					calcError = true;
				}
				if( jQuery("#width-unit").val() == "" ) {
					jQuery('<div class="calc-req-field">This field is required.</div>').insertAfter( jQuery("#width-unit") );
					calcError = true;
				}
				if( jQuery("#depth-unit").val() == "" ) {
					jQuery('<div class="calc-req-field">This field is required.</div>').insertAfter( jQuery("#depth-unit") );
					calcError = true;
				}

				if( jQuery("#length-feet").val() == "" ) {
					jQuery('<div class="calc-req-field">This field is required.</div>').insertAfter( jQuery("#length-feet") );
					calcError = true;
				}
				if( jQuery("#width-feet").val() == "" ) {
					jQuery('<div class="calc-req-field">This field is required.</div>').insertAfter( jQuery("#width-feet") );
					calcError = true;
				}
				if( jQuery("#depth-inches").val() == "" ) {
					jQuery('<div class="calc-req-field">This field is required.</div>').insertAfter( jQuery("#depth-inches") );
					calcError = true;
				}

				if( !calcError ) {
					var reg_price = jQuery("#material-list").find(':selected').attr('data-rprice');
					var ws_price = jQuery("#material-list").find(':selected').attr('data-wprice');
					var c_price = jQuery("#material-list").find(':selected').attr('data-cprice');
					var sold_by = jQuery("#material-list").find(':selected').attr('data-sold-by');
					<?php
					if( is_user_logged_in() ) {
						$current_user = wp_get_current_user();
						if ( isset( $current_user->roles[0] ) && '' != $current_user->roles[0] && in_array( 'wholesale_customer',  $current_user->roles ) ) {
					?>
							var calc_price = ws_price;
					<?php
						} else if ( isset( $current_user->roles[0] ) && '' != $current_user->roles[0] && in_array( 'landscape_contractors',  $current_user->roles ) ) {
					?>
							var calc_price = c_price;
					<?php
						} else {
					?>
							var calc_price = reg_price;
					<?php
						}
					} else {
					?>
						var calc_price = reg_price;
					<?php
					}
					?>
					var lengthUnit = jQuery("#length-unit").val();
					var depthUnit = jQuery("#depth-unit").val();
					var materialLength = jQuery("#length-feet").val();
					var materialWidth = jQuery("#width-feet").val();
					var materialDepth = jQuery("#depth-inches").val();
					
					if( sold_by == "Cubic Yard" && lengthUnit == "Feet" ) {
						var materialRequired = parseFloat(((materialLength*materialWidth*materialDepth/12)/27).toFixed(1));
						var materialCost = parseFloat((materialRequired*calc_price).toFixed(2));
						// round to nearest .25
						materialCost = parseFloat((Math.round(materialCost * 4) / 4).toFixed(2));
						jQuery(".calc-result-number").text(materialRequired.toLocaleString('en-US', {maximumFractionDigits:1}));
						jQuery(".calc-result-units-soldin").text("Cubic Yards");
						jQuery(".calc-cost-unit").text(calc_price);
						jQuery(".calc-result-sell-units").text("Cubic Yard");
						jQuery(".calc-result-cost").text( materialCost.toLocaleString('en-US', {maximumFractionDigits:2}) );
						jQuery(".calculation-formula").html("<strong>Calculation:</strong> "+materialRequired+" X "+calc_price+" = "+materialCost.toLocaleString('en-US', {maximumFractionDigits:2}));
					}
					if( sold_by == "Cubic Yard" && lengthUnit == "Meters" ) {
						var materialRequired = parseFloat(((materialLength*materialWidth*materialDepth/100)*1.307951).toFixed(1));
						var materialCost = parseFloat((materialRequired*calc_price).toFixed(2));
						// round to nearest .25
						materialCost = parseFloat((Math.round(materialCost * 4) / 4).toFixed(2));
						jQuery(".calc-result-number").text(materialRequired.toLocaleString('en-US', {maximumFractionDigits:1}));
						jQuery(".calc-result-units-soldin").text("Cubic Yards");
						jQuery(".calc-cost-unit").text(calc_price);
						jQuery(".calc-result-sell-units").text("Cubic Yard");
						jQuery(".calc-result-cost").text( materialCost.toLocaleString('en-US', {maximumFractionDigits:2}) );
						jQuery(".calculation-formula").html("<strong>Calculation:</strong> "+materialRequired+" X "+calc_price+" = "+materialCost.toLocaleString('en-US', {maximumFractionDigits:2}));
					}
					if( sold_by == "Metric Tonne" && lengthUnit == "Feet" ) {
						var materialRequired = parseFloat(((((materialLength*materialWidth*materialDepth/12)/27)*0.764555)*1.307951).toFixed(1));
						var materialCost = parseFloat((materialRequired*calc_price).toFixed(2));
						// round to nearest .25
						materialCost = parseFloat((Math.round(materialCost * 4) / 4).toFixed(2));
						jQuery(".calc-result-number").text(materialRequired.toLocaleString('en-US', {maximumFractionDigits:1}));
						jQuery(".calc-result-units-soldin").text("Metric Tonnes");
						jQuery(".calc-cost-unit").text(calc_price);
						jQuery(".calc-result-sell-units").text("Metric Tonne");
						jQuery(".calc-result-cost").text( materialCost.toLocaleString('en-US', {maximumFractionDigits:2}) );
						jQuery(".calculation-formula").html("<strong>Calculation:</strong> "+materialRequired+" X "+calc_price+" = "+materialCost.toLocaleString('en-US', {maximumFractionDigits:2}));
					}
					if( sold_by == "Metric Tonne" && lengthUnit == "Meters" ) {
						var materialRequired = parseFloat(((materialLength*materialWidth*materialDepth/100)*1.307951).toFixed(1));
						var materialCost = parseFloat((materialRequired*calc_price).toFixed(2));
						// round to nearest .25
						materialCost = parseFloat((Math.round(materialCost * 4) / 4).toFixed(2));
						jQuery(".calc-result-number").text(materialRequired.toLocaleString('en-US', {maximumFractionDigits:1}));
						jQuery(".calc-result-units-soldin").text("Metric Tonnes");
						jQuery(".calc-cost-unit").text(calc_price);
						jQuery(".calc-result-sell-units").text("Metric Tonne");
						jQuery(".calc-result-cost").text( materialCost.toLocaleString('en-US', {maximumFractionDigits:2}) );
						jQuery(".calculation-formula").html("<strong>Calculation:</strong> "+materialRequired+" X "+calc_price+" = "+materialCost.toLocaleString('en-US', {maximumFractionDigits:2}));
					}
					
					jQuery("#add-calc-cart-btn").css("display", "block");
				}
			});

			jQuery("#length-unit").on("change", function() {
				if( jQuery("#length-unit").val() == "Feet" ) {
					jQuery("#width-unit>option:eq(0)").prop("selected", true);
					jQuery("#depth-unit>option:eq(0)").prop("selected", true);
					jQuery("#length-feet").attr("placeholder", "Feet");
					jQuery("#width-feet").attr("placeholder", "Feet");
					jQuery("#depth-inches").attr("placeholder", "Inches");
				}
				if( jQuery("#length-unit").val() == "Meters" ) {
					jQuery("#width-unit>option:eq(1)").prop("selected", true);
					jQuery("#depth-unit>option:eq(1)").prop("selected", true);
					jQuery("#length-feet").attr("placeholder", "Meters");
					jQuery("#width-feet").attr("placeholder", "Meters");
					jQuery("#depth-inches").attr("placeholder", "Centimeters");
				}
			});
			jQuery("#width-unit").on("change", function() {
				if( jQuery("#width-unit").val() == "Feet" ) {
					jQuery("#length-unit>option:eq(0)").prop("selected", true);
					jQuery("#depth-unit>option:eq(0)").prop("selected", true);
					jQuery("#length-feet").attr("placeholder", "Feet");
					jQuery("#width-feet").attr("placeholder", "Feet");
					jQuery("#depth-inches").attr("placeholder", "Inches");
				}
				if( jQuery("#width-unit").val() == "Meters" ) {
					jQuery("#length-unit>option:eq(1)").prop("selected", true);
					jQuery("#depth-unit>option:eq(1)").prop("selected", true);
					jQuery("#length-feet").attr("placeholder", "Meters");
					jQuery("#width-feet").attr("placeholder", "Meters");
					jQuery("#depth-inches").attr("placeholder", "Centimeters");
				}
			});
			jQuery("#depth-unit").on("change", function() {
				if( jQuery("#depth-unit").val() == "Inches" ) {
					jQuery("#length-unit>option:eq(0)").prop("selected", true);
					jQuery("#width-unit>option:eq(0)").prop("selected", true);
					jQuery("#length-feet").attr("placeholder", "Feet");
					jQuery("#width-feet").attr("placeholder", "Feet");
					jQuery("#depth-inches").attr("placeholder", "Inches");
				}
				if( jQuery("#depth-unit").val() == "Centimeters" ) {
					jQuery("#length-unit>option:eq(1)").prop("selected", true);
					jQuery("#width-unit>option:eq(1)").prop("selected", true);
					jQuery("#length-feet").attr("placeholder", "Meters");
					jQuery("#width-feet").attr("placeholder", "Meters");
					jQuery("#depth-inches").attr("placeholder", "Centimeters");
				}
			});
			jQuery("#material-list").on("change", function() {
				if( jQuery("#material-list").val() != "" ) {
					var sold_by = jQuery(this).find(':selected').attr('data-sold-by');
					if( sold_by == "Cubic Yard" ) {
						jQuery("#length-unit>option:eq(0)").prop("selected", true);
						jQuery("#width-unit>option:eq(0)").prop("selected", true);
						jQuery("#depth-unit>option:eq(0)").prop("selected", true);
						jQuery("#length-feet").attr("placeholder", "Feet");
						jQuery("#width-feet").attr("placeholder", "Feet");
						jQuery("#depth-inches").attr("placeholder", "Inches");
					}
					if( sold_by == "Metric Tonne" ) {
						jQuery("#length-unit>option:eq(1)").prop("selected", true);
						jQuery("#width-unit>option:eq(1)").prop("selected", true);
						jQuery("#depth-unit>option:eq(1)").prop("selected", true);
						jQuery("#length-feet").attr("placeholder", "Meters");
						jQuery("#width-feet").attr("placeholder", "Meters");
						jQuery("#depth-inches").attr("placeholder", "Centimeters");
					}
				}
			});
			
			jQuery("#add-calc-cart-btn").on("click", function() {
				var material = jQuery("#material-list").val();
				if( material ) {
					var quantity = jQuery(".calc-result-number").text();
					var unit_cost = jQuery(".calc-cost-unit").text();
					if( quantity && unit_cost ) {
						var curr_url = window.location.href.split('?')[0];
						var redirect_url = curr_url+"?add_material=1&matprod="+material+"&qty="+quantity+"&pr="+unit_cost;
						window.location = redirect_url;
					}
				}
			});
		});
	</script>
	<?php
	return ob_get_clean();
}
add_shortcode("get_bulk_material_calculator", "generate_bulk_material_calculator");

add_shortcode('get_search_term_products', 'get_search_term_products');
function get_search_term_products() {
	ob_start();
	$s_keyword = $_GET["s"];
	//$search_in = $_GET["searchin"];
	$big = 999999999;
	$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
	
	$args = array(
		'post_type' => 'product',
		'posts_per_page' => 15,
		'paged' => $paged,
		'post__not_in' => array(18112),
		'meta_query' => array(
            array(
                'key' => '_stock_status',
                'value' => 'instock'
        	)
        ),
	);
	
	// check if it is the default Search page
	if( is_search() ) {
		$args["s"] = $s_keyword;
	}
	
	if( ! $s_keyword ) {
		$args["order"] = "ASC";
	}
	
	// check if it is the Botanical search page
	if( is_page(18134) && isset($_GET["sterm"]) ) {
		$args["meta_query"][] = array(
			'key' => '_botanical_name',
			'value' => $_GET["sterm"],
			'compare' => 'LIKE'
		);
		
		if( isset($_GET["sortby"]) && $_GET["sortby"] == "BtncName" ) {
			$args["orderby"] = "meta_value";
		} else {
			$args["orderby"] = "title";
		}
	}
	
	/*if( (isset($_GET["meta_k"]) && $_GET["meta_k"] != "") && (isset($_GET["meta_v"]) && $_GET["meta_v"] != "") ) {
		$meta_key = $_GET["meta_k"];
		$meta_value = explode(",", $_GET["meta_v"]);
		$args["meta_query"][] = array (
				'key' => $meta_key,
				'value' => $meta_value,
				'compare' => 'IN',
			);
	}*/
	
	if( isset($_GET["meta_keys_num"]) && $_GET["meta_keys_num"] != "" ) {
		$meta_keys_num = $_GET["meta_keys_num"];
		for($i=1; $i<=$meta_keys_num; $i++) {
			if($i == 1) {
				$key_num = '';
			} else {
				$key_num = $i;
			}
			
			$meta_key = $_GET["meta_k".$key_num];
			$meta_value = explode(",", $_GET["meta_v".$key_num]);
			$args["meta_query"][] = array (
				'key' => $meta_key,
				'value' => $meta_value,
				'compare' => 'IN',
			);
		}
	}
	
	if( isset($_GET["sortby"]) && $_GET["sortby"] == "Botanicname" ) {
		//$args["meta_key"] = "_botanical_name";
		//$args["orderby"] = "meta_value";
		$args["meta_query"][] = array(
									'key'     => '_botanical_name',
									'compare' => 'EXISTS' // CHECK THE VALUE OF META KEY IF EXISTS?
								);
	}

	$the_query = new WP_Query( $args );
	//echo $the_query->request;
	
	if ( $the_query->have_posts() ) {
		echo '<ul class="searched-products">';
		while ( $the_query->have_posts() ) {
			$the_query->the_post();
			//echo '<li>' . get_the_title() . '</li>';
			?>
			<li class="searched-post">
				<?php $featured_img_url = get_the_post_thumbnail_url(get_the_ID(), 'medium'); ?>
				<a href="<?php echo get_permalink(); ?>" class="sp-thumbnail-link">
				<div class="product-ft-image">
					<?php if ( has_post_thumbnail( get_the_ID() ) ) { ?>
					<img src="<?php echo $featured_img_url; ?>" alt="<?php echo get_the_title(); ?>" />
					<?php } else { ?>
					<img src="https://clearviewnursery.com/wp-content/uploads/woocommerce-placeholder-300x300.jpg" alt="<?php echo get_the_title(); ?>" />
					<?php } ?>
				</div>
				</a>
				<h3 class="product-title">
					<?php
						if( isset($_GET["sortby"]) && ($_GET["sortby"] == "Botanicname" || $_GET["sortby"] == "BtncName") ) {
							if( get_post_meta(get_the_ID(), '_botanical_name', true) ) {
								echo '<a href="'.get_permalink().'">'.get_post_meta(get_the_ID(), '_botanical_name', true).'</a>';
							}
						} else {
					?>
						<a href="<?php echo get_permalink(); ?>"><?php echo get_the_title(); ?></a>
					<?php } ?>
				</h3>
				<?php
					$product = wc_get_product( get_the_ID() );
					//echo $product->get_price();
					if( ! $product->is_type('variable') ){
						echo $product->get_price_html();
						//echo '<div class="src-pr-btns"><a href="'.$product->add_to_cart_url().'" value="'.esc_attr( $product->get_id() ).'" class="ajax_add_to_cart add_to_cart_button" data-product_id="'.get_the_ID().'" data-product_sku="'.esc_attr($sku).'" aria-label="Add “'.the_title_attribute("echo=0").'” to your cart">Add to Wish List</a></div>';
						echo '<div class="src-pr-btns"><a href="'.get_permalink().'" class="add_to_cart_button" aria-label="Add “'.the_title_attribute("echo=0").'” to your cart">View Product</a></div>';
					} else {
						$min_price = $product->get_variation_price( 'min' );
        				$max_price = $product->get_variation_price( 'max' );
						$cr_symbol = get_woocommerce_currency_symbol();
						
						$variable_price = $cr_symbol.$min_price." - ".$cr_symbol.$max_price;
						echo $variable_price;
						echo '<div class="src-pr-btns"><a href="'.get_permalink().'">View Product</a></div>';
					}
				?>
			</li>
			<?php
		}
		echo '</ul>';
		
		//wp_pagenavi();
		echo '<div class="pagination_nav">';		
		// This works and removes #038; from the URL of the pagination when query string exists in the URL
		echo paginate_links( array(
			'base' => str_replace( $big, '%#%', get_pagenum_link( $big, false ) ),
			'format' => '?paged=%#%',
			'current' => max( 1, get_query_var('paged') ),
			'total' => $the_query->max_num_pages
			) );
		echo '</div>';
	} else {
		// no posts found
		echo '<div class="woocommerce-no-products-found" style="margin-bottom: 15px;">No products were found matching your selection.</div>';
	}
	/* Restore original Post Data */
	wp_reset_postdata();
	
	return ob_get_clean();
}

function cvn_template_loop_price() {
	global $product;
	if( $product->is_type('variable') ) {
		$min_price = $product->get_variation_price( 'min' );
		$max_price = $product->get_variation_price( 'max' );
		$cr_symbol = get_woocommerce_currency_symbol();

		$variable_price = $cr_symbol.$min_price." - ".$cr_symbol.$max_price;
		echo '<strong>'.$variable_price.'</strong>';
	}
}
add_action( 'woocommerce_after_shop_loop_item', 'cvn_template_loop_price', 11 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 12 );

function cvn_get_botanical_name() {
	if( isset($_GET["sortby"]) && $_GET["sortby"] == "Botanicname" ) {
		global $post;
		if( get_post_meta($post->ID, '_botanical_name', true) ) {
			echo '<h2 class="woocommerce-loop-product__title">'.get_post_meta($post->ID, '_botanical_name', true).'</h2>';
			echo '<style type="text/css">.products li.post-'.$post->ID.' .astra-shop-summary-wrap .ast-loop-product__link { display: none; }</style>';
		}
	}
}
add_action("woocommerce_shop_loop_item_title", "cvn_get_botanical_name");

add_shortcode('get_product_faqs', 'get_product_faqs');
function get_product_faqs($atts) {
	extract(shortcode_atts(array(
		'cat' => '',
	), $atts));
	
	$args = array(
		'post_type' => 'product-faqs',
		'posts_per_page' => -1
	);
	
	if( $cat ) {
		$args["tax_query"] = array(
			array(
				'taxonomy' => 'faq_category',
				'field'    => 'slug',
				'terms'    => $cat,
			),
		);
	}
	
	ob_start();
	$the_query = new WP_Query( $args );
	if ( $the_query->have_posts() ) {
		echo '<div class="product-faqs-grid">';
		while ( $the_query->have_posts() ) {
			$the_query->the_post();
			?>
			<div class="faq-post">
				<div class="faq-wrap">
					<?php
						$featured_img_url = get_the_post_thumbnail_url(get_the_ID(), 'medium');
						$faq_url = get_permalink();
						if( get_post_meta(get_the_ID(), "video_url", true) ) {
							$faq_url = get_post_meta(get_the_ID(), "video_url", true);
						}
					?>
					<a href="<?php echo $faq_url; ?>" class="sp-thumbnail-link">
					<div class="product-ft-image">
						<img src="<?php echo $featured_img_url; ?>" alt="<?php echo get_the_title(); ?>" />
						<?php
							if( get_post_meta(get_the_ID(), "video_url", true) ) {
								echo '<span class="play-video"><i aria-hidden="true" class="fas fa-play"></i></span>';
							}
						?>
					</div>
					</a>
					<div class="faq-info">
						<?php
							$terms = get_the_terms( get_the_ID(), 'faq_category' );
							if ( $terms && ! is_wp_error( $terms ) ) {
								echo '<div class="faq-category">'.$terms[0]->name.'</div>';
							}
						?>
						<h3 class="product-title">
							<a href="<?php echo $faq_url; ?>"><?php echo get_the_title(); ?></a>
						</h3>
					</div>
				</div>
			</div>
			<?php
		}
		echo '</div>';
	} else {
		// no posts found
		_e( 'Sorry, no faq matched your criteria.' );
	}
	/* Restore original Post Data */
	wp_reset_postdata();
	
	return ob_get_clean();
}

add_filter( 'woocommerce_account_menu_items', 'cvn_my_account_menu_items', 22, 1 );
function cvn_my_account_menu_items( $items ) {
    $items['dashboard'] = __("Welcome", "woocommerce");
	//$items['orders'] = __("Shop Request", "woocommerce");
	$items['edit-address'] = __("Address", "woocommerce");
	$items['edit-account'] = __("Account Details", "woocommerce");
	
	unset($items['orders']);
	
    return $items;
}

add_action( 'woocommerce_account_dashboard', 'cvn_my_account_endpoint_content' );
function cvn_my_account_endpoint_content() {

	// content here, one of the most useful functions here is get_current_user_id()
	echo '<p>As a registered Wholesale customer (or Contractor) of Clearview Nursery, you have access to Industry Pricing.  Your login provides you access to pricing not available to our retail customers.</p>
		<p>Please note that this system can be used for browsing, finding prices and submitting an order request.  This is not for ecommerce transactions.  We want to review any order requests before fulfilling them with you.</p>
		<p><a href="https://clearviewnursery.com/?s=" class="view-products-btn">View Our Products</a></p>';

}

function woocommerce_button_proceed_to_checkout() { ?>
	<a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="checkout-button button alt wc-forward">
		<?php esc_html_e( 'Proceed to Request Availability', 'woocommerce' ); ?>
	</a>
 <?php
}

add_filter( 'woocommerce_product_single_add_to_cart_text', 'cvn_add_to_cart_button_text_single' ); 
function cvn_add_to_cart_button_text_single() {
    return __( 'Add to Wish List', 'woocommerce' );
}

add_filter( 'woocommerce_product_add_to_cart_text', 'cvn_add_to_cart_button_text_archives' );  
function cvn_add_to_cart_button_text_archives() {
    return __( 'View Product', 'woocommerce' );
}

add_filter( 'gettext', 'cvn_cart_text_strings', 20, 3 );
function cvn_cart_text_strings( $translated_text, $text, $domain ) {
    switch ( strtolower( $translated_text ) ) {
        case 'view cart' :
            $translated_text = __( 'View Wish List', 'woocommerce' );
            break;
    }
    return $translated_text;
}

add_filter( 'gettext', 'cvn_change_update_cart_text', 20, 3 );
function cvn_change_update_cart_text( $translated, $text, $domain ) {
    if( is_cart() && $translated == 'Update cart' ){
        $translated = 'Update Wishlist';
    }
	if( is_cart() && $translated == 'Cart totals' ){
        $translated = 'Wish List Totals';
    }
	/*if( is_checkout() && $translated == 'Order Details' ){
        $translated = 'Wish List Details';
    }*/
    return $translated;
}


add_shortcode( 'display_collection_product', 'get_collection_product_shortcode_init' );
function get_collection_product_shortcode_init( $atts ) {
	extract(shortcode_atts(array('category' => ''), $atts));
	
	$big = 999999999;
	$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
	
	// categories by slug
	$cats = explode(",", $category);
	if( isset($_GET["cat_term"]) ) {
		$cats = explode(",", $_GET["cat_term"]);
	}
	
	$args = array(
		'limit' => 15,
		'page'  => $paged,
		'paginate' => true,
		'stock_status' => 'instock',
		'category' => $cats
	);
	
	if( (isset($_GET["meta_k"]) && $_GET["meta_k"] != "") && (isset($_GET["meta_v"]) && $_GET["meta_v"] != "") ) {
		$meta_key = $_GET["meta_k"];
		$meta_value = $_GET["meta_v"];
		$args[$meta_key] = $meta_value;
	}
	
	/*if( isset($_GET["sortnameby"]) && $_GET["sortnameby"] == "Botanicname" ) {
		$metakey = "_botanical_name";
		$meta_val = $_GET["sortnameby"];
		$args[$metakey] = $meta_val;
	}*/
	
	$query = new WC_Product_Query($args);

	$results = $query->get_products();
	$products = $results->products;
	
	ob_start();
	//print_r($results);
	
	if ( !empty($products) ) {
		echo '<ul class="searched-products category_prodcut_grid">';
		foreach ($products as $product) {
			$product_id = $product->get_id();
			?>
			<li class="searched-post">
				<?php $featured_img_url = get_the_post_thumbnail_url($product_id, 'medium'); ?>
				<a href="<?php echo get_permalink($product_id); ?>" class="sp-thumbnail-link">
					<div class="product-ft-image">
						<?php if ( has_post_thumbnail( $product_id ) ) { ?>
						<img src="<?php echo $featured_img_url; ?>" alt="<?php echo get_the_title($product_id); ?>" />
						<?php } else { ?>
						<img src="https://clearviewnursery.com/wp-content/uploads/woocommerce-placeholder-300x300.jpg" alt="<?php echo get_the_title($product_id); ?>" />
						<?php } ?>
					</div>
				</a>
				<h3 class="product-title">
					<a href="<?php echo get_permalink($product_id); ?>"><?php echo get_the_title($product_id); ?></a>
				</h3>
				<?php
					if( ! $product->is_type('variable') ){
						echo $product->get_price_html();
						//echo '<div class="src-pr-btns"><a href="'.$product->add_to_cart_url().'" value="'.esc_attr( $product_id ).'" class="ajax_add_to_cart add_to_cart_button" data-product_id="'.$product_id.'">View Product</a></div>';
						echo '<div class="src-pr-btns"><a href="'.get_permalink($product_id).'" class="add_to_cart_button">View Product</a></div>';
					} else {
						$min_price = $product->get_variation_price( 'min' );
						$max_price = $product->get_variation_price( 'max' );
						$cr_symbol = get_woocommerce_currency_symbol();

						$variable_price = $cr_symbol.$min_price." - ".$cr_symbol.$max_price;
						echo $variable_price;
						echo '<div class="src-pr-btns"><a href="'.get_permalink($product_id).'">View Product</a></div>';
					}
				?>
			</li>
			<?php
		}
		echo '</ul>';
		echo '<div class="pagination_nav">';
		/*echo paginate_links( array(
			'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
			'format' => '?paged=%#%',
			'current' => max( 1, get_query_var('paged') ),
			'total' => $results->max_num_pages
			) );*/
		
		// This works and removes #038; from the URL of the pagination when query string exists in the URL
		echo paginate_links( array(
			'base' => str_replace( $big, '%#%', get_pagenum_link( $big, false ) ),
			'format' => '?paged=%#%',
			'current' => max( 1, get_query_var('paged') ),
			'total' => $results->max_num_pages
			) );
		echo '</div>';
	}

	return ob_get_clean();
}
function cvn_handle_custom_query_var( $query, $query_vars ) {
	/*if ( ! empty( $query_vars['meta_k'] ) && ! empty( $query_vars['meta_v'] ) ) {
		$query['meta_query'][] = array(
			'key' => esc_attr( $query_vars['meta_k'] ),
			'value' => esc_attr( $query_vars['meta_v'] ),
		);
	}*/
	if( (isset($_GET["meta_k"]) && $_GET["meta_k"] != "") && (isset($_GET["meta_v"]) && $_GET["meta_v"] != "") ) {
		$meta_key = $_GET["meta_k"];
		$meta_value = $_GET["meta_v"];
		$query['meta_query'][] = array(
			'key' => $meta_key,
			'value' => esc_attr( $meta_value ),
		);
	}

	return $query;
}
add_filter('woocommerce_product_data_store_cpt_get_products_query', 'cvn_handle_custom_query_var', 10, 2);


remove_action( 'woocommerce_cart_is_empty', 'wc_empty_cart_message', 10 );
add_action( 'woocommerce_cart_is_empty', 'cvn_custom_empty_cart_message', 10 );
function cvn_custom_empty_cart_message() {
    $html  = '<div class="col-12 offset-md-1 col-md-10"><p class="cart-empty" style="text-align: center;">';
    $html .= wp_kses_post( apply_filters( 'wc_empty_cart_message', __( 'Your wishlist is currently empty.', 'woocommerce' ) ) );
    echo $html . '</p></div>';
}

add_action("woocommerce_before_shop_loop", "flava_add_sorting_dropdown");
function flava_add_sorting_dropdown() {
	echo do_shortcode('[name_sort_switch]');
}

add_shortcode("name_sort_switch", "show_name_filter_switcher");
function show_name_filter_switcher() {
	ob_start();
	?>
	<style type="text/css">
		.sorting-options-list span:first-child {
			/*margin-right: 10px;*/
		}
		.sorting-options-list input {
			appearance: none;
			position: absolute;
			opacity: 0;
			height: 0;
			visibility: hidden;
		}
		.sorting-options-list input[type="radio"]:checked {
			border-color: #b79906;
			background-color: #b79906;
			box-shadow: none;
		}
		.sorting-options-list label {
			padding: 6px 20px;
			display: inline-block;
			border-radius: 5px;
			text-align: center;
			cursor: pointer;
			color: #5b5b5b;
		}
		.sorting-options-list input[type="radio"]:checked + label {
			background: #b79906;
			/*box-shadow: 0px 24px 38px 0px rgb(230 133 11 / 20%);*/
			color: #fff;
		}
		.woocommerce .woocommerce-result-count {
			margin-bottom: 40px;
		}

		@media (min-width: 980px) {
			.sorting-options-list { float: left; }
			.woocommerce .woocommerce-result-count {
				float: right;
				margin-top: 13px;
				margin-bottom: 50px;
			}
		}
	</style>
	<div class="sorting-options-list">
		<strong>Sort Name By:</strong>
		<span class="sorts-wrap">
			<?php if( is_page(16296) || is_page(16449) ) { ?>
				<span>
					<input type="radio" name="sorting_opts" class="sorting-opts" id="sorting1" value="ProductName" <?php if( !isset($_GET["sortnameby"]) ) { echo 'checked="checked"'; } ?> />
					<label for="sorting1">Common</label>
				</span>
				<span>
					<input type="radio" name="sorting_opts" class="sorting-opts" id="sorting2" value="Botanicname" <?php if( isset($_GET["sortnameby"]) && $_GET["sortnameby"] == "Botanicname" ) { echo 'checked="checked"'; } ?> />
					<label for="sorting2">Botanical</label>
				</span>
			<?php } elseif( is_page(18134) ) { ?>
				<span>
					<input type="radio" name="sorting_opts" class="sorting-opts" id="sorting1" value="ProductName" <?php if( !isset($_GET["sortby"]) ) { echo 'checked="checked"'; } ?> />
					<label for="sorting1">Common</label>
				</span>
				<span>
					<input type="radio" name="sorting_opts" class="sorting-opts" id="sorting2" value="Botanicname" <?php if( isset($_GET["sortby"]) && $_GET["sortby"] == "BtncName" ) { echo 'checked="checked"'; } ?> />
					<label for="sorting2">Botanical</label>
				</span>
			<?php } else { ?>
				<span>
					<input type="radio" name="sorting_opts" class="sorting-opts" id="sorting1" value="ProductName" <?php if( !isset($_GET["sortby"]) ) { echo 'checked="checked"'; } ?> />
					<label for="sorting1">Common</label>
				</span>
				<span>
					<input type="radio" name="sorting_opts" class="sorting-opts" id="sorting2" value="Botanicname" <?php if( isset($_GET["sortby"]) && $_GET["sortby"] == "Botanicname" ) { echo 'checked="checked"'; } ?> />
					<label for="sorting2">Botanical</label>
				</span>
			<?php } ?>
		</span>
	</div>
	<?php
	return ob_get_clean();
}

add_filter('woocommerce_loop_add_to_cart_link', 'flava_loop_add_to_cart_link', 10, 2);
function flava_loop_add_to_cart_link( $link, $product ){
    $link = sprintf( '<a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" data-quantity="%s" class="button custom-btn-link product_type_%s">%s</a>',
        esc_url( get_permalink( $product->id ) ),
        esc_attr( $product->id ),
        esc_attr( $product->get_sku() ),
        esc_attr( isset( $quantity ) ? $quantity : 1 ),
        esc_attr( $product->product_type ),
        esc_html( $product->add_to_cart_text() )
    );
    return $link;
}

function cvn_get_profile_name() {
	if( is_user_logged_in() ) {
		$user = wp_get_current_user();
		if( $user->first_name ) {
			$name = $user->first_name;
		} else {
			$name = $user->display_name;
		}
		return '<div style="color: #8e8d8d;">Hi '.$name.'</div>';
	}
	
	return false;
}
add_shortcode('get_profile_name', 'cvn_get_profile_name');

// https://wordpress.stackexchange.com/questions/196453/displaying-logged-in-user-name-in-wordpress-menu
//add_filter( 'wp_nav_menu_objects', 'cvn_dynamic_menu_items' );
function cvn_dynamic_menu_items( $menu_items ) {
    foreach ( $menu_items as $menu_item ) {
        if ( '#profile_name#' == $menu_item->title ) {
            global $shortcode_tags;
            if ( isset( $shortcode_tags['get_profile_name'] ) ) {
                // Or do_shortcode(), if you must.
                $menu_item->title = call_user_func( $shortcode_tags['get_profile_name'] );
            }    
        }
    }

    return $menu_items;
}


// Allow decimal values in the product's quantity field
add_filter('woocommerce_quantity_input_args', 'cvn_woocommerce_decimal_quantity_input_args', 10, 2);
function cvn_woocommerce_decimal_quantity_input_args( $args, $product ) {
	if( $product->get_id() == 18112 ) {
		$args['min_value'] 	= 0.1;
		//$args['input_value'] = 0.1;
    	$args['step'] = 0.1;
	}
	
    return $args;
}

add_action("template_redirect", 'cvn_redirect_decimal_product');
function cvn_redirect_decimal_product() {
	if( is_singular( 'product' ) ) {
		global $post;
		if( $post->ID == 18112 ) {
			wp_redirect( home_url(), 301 );
			exit;
		}
	}
	
	if( is_search() ) {
		if( isset($_GET["searchin"]) && (isset($_GET["s"]) && $_GET["s"] != "") ) {
			$s_term = $_GET["s"];
			$search_in = $_GET["searchin"];
			if( $search_in == "BotName" ) {
				wp_redirect( home_url("/botanical-search/?sterm=".$s_term."&sortby=BtncName"), 301 );
				exit;
			}
		}
	}
}

add_action("template_redirect", "add_materials_to_cart");
function add_materials_to_cart() {
	if( isset($_GET["add_material"]) && $_GET["add_material"] == 1 ) {
		$product_id = 18112;
		$quantity = $_GET["qty"];
		$material_name = $_GET["matprod"];
		$unit_cost = $_GET["pr"];
		//WC()->cart->add_to_cart( $product_id, $quantity, 0, array(), array( '_meterial_name' => $material_name, '_meterial_unit_cost' => $unit_cost ) );
		WC()->cart->add_to_cart( $product_id, 1, 0, array(), array( '_meterial_name' => $material_name, '_meterial_unit_cost' => $unit_cost, '_material_quantity' => $quantity ) );
		
		wp_redirect("/cart/");
		exit;
	}
}

add_filter( 'woocommerce_cart_item_class', 'cvn_additional_class_to_cart_item_classes', 10, 3 );
function cvn_additional_class_to_cart_item_classes ( $class, $cart_item, $cart_item_key ) {
    if( isset( $cart_item['_meterial_unit_cost'] ) ) {
        $class .= ' material-calc-item materialqty-'.$cart_item['_material_quantity'];
    }

    return $class;
}

// Removes the WooCommerce filter, that is validating the quantity to be an int
//remove_filter('woocommerce_stock_amount', 'intval');
 
// Add a filter, that validates the quantity to be a float
//add_filter('woocommerce_stock_amount', 'floatval');

add_action( 'woocommerce_before_calculate_totals', 'cvn_update_custom_price', 1, 1 );
function cvn_update_custom_price( $cart_object ) {
	if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
        return;
	}
	
    foreach ( $cart_object->cart_contents as $cart_item_key => $cart_item ) {
		if( isset($cart_item['_meterial_unit_cost']) ) {
			//$cart_item['data']->set_price( $cart_item['_meterial_unit_cost'] );
			$material_price = $cart_item['_meterial_unit_cost'] * $cart_item['_material_quantity'];
			$cart_item['data']->set_price( quarter_round($material_price, 4) );			
		}
    }
}

//add_filter( 'woocommerce_cart_item_subtotal', 'cvn_filter_cart_item_subtotal', 10, 3 );
function cvn_filter_cart_item_subtotal( $subtotal, $cart_item, $cart_item_key ) {
	if( isset($cart_item['_meterial_unit_cost']) ) {
		$sub_calc = $cart_item['_meterial_unit_cost'] * $cart_item['quantity'];
		$subtotal_round = quarter_round($sub_calc, 4);
		$subtotal = wc_price( $subtotal_round );
	}
	
	return $subtotal;
}

// Display the default product price (instead of the calculated one)
add_filter( 'woocommerce_cart_item_price', 'cvn_filter_woocommerce_cart_item_price', 10, 3 );
function cvn_filter_woocommerce_cart_item_price( $product_price, $cart_item, $cart_item_key  ) {
    if( isset($cart_item['_meterial_unit_cost']) ) {
        $product_price = wc_price( wc_get_price_to_display( $cart_item['data'], array('price' => $cart_item['_meterial_unit_cost']) ) );
    }
	
    return $product_price;
}

// show the meta data within the cart line item
add_filter('woocommerce_cart_item_name', 'cvn_add_materials_custom_session', 1, 3);
function cvn_add_materials_custom_session($product_name, $cart_item, $cart_item_key ) {
	if( isset($cart_item['_meterial_name']) ) {
		return $cart_item['_meterial_name'];
		//return $cart_item['_meterial_name']." <small>x</small> ".$cart_item['_material_quantity'];
	}

    return $product_name;
}

add_filter( 'woocommerce_cart_item_removed_title', 'cvn_removed_from_cart_title', 12, 2);
function cvn_removed_from_cart_title( $message, $cart_item ) {
	if( isset($cart_item['_meterial_name']) ) {
		$message = sprintf( __('"%s" has been'), $cart_item['_meterial_name'] );
	}

    return $message;
}

add_filter('woocommerce_cart_item_permalink', 'cvn_cart_item_permalink' , 10, 3 );
function cvn_cart_item_permalink( $permalink, $cart_item, $cart_item_key ) {
	if( isset($cart_item['_meterial_name']) ) {
		$permalink = '';
	}
	
    return $permalink;
}

function quarter_round($num, $parts) {
    $res = $num * $parts;
    $res = round($res);
    return $res /$parts;
}

//add_filter( 'woocommerce_cart_subtotal', 'cvn_filter_woocommerce_cart_subtotal', 10, 3 );
function cvn_filter_woocommerce_cart_subtotal( $subtotal, $compound, $cart ) {
	foreach ( $cart->get_cart() as $cart_item ) {
		if( $cart_item["product_id"] == 18112 ) {
			// Get cart subtotal
			$round = quarter_round( $cart->subtotal, 4 );

			// Use wc_price(), for the correct HTML output
			$subtotal = wc_price( $round );
		}
	}

    return $subtotal;
}

//add_filter( 'woocommerce_calculated_total', 'cvn_filter_woocommerce_calculated_total', 10, 2 );
function cvn_filter_woocommerce_calculated_total( $total, $cart ) {    
    return quarter_round( $total, 4 );
}


add_action("woocommerce_archive_description", "cvn_woo_taxonomy_title");
function cvn_woo_taxonomy_title() {
	if( isset($_GET["meta_k"]) ) {
		$curr_cat = get_queried_object();
		$parent_name = '';

		if( $curr_cat->parent > 0 ) {
			$parent_cats = get_ancestors($curr_cat->term_id, 'product_cat');
			/*foreach($parent_cats as $parent) {
				$cat = get_term($parent,'product_cat');
				$cat_name = $cat->name;
			}*/
			$parent_name = get_term($parent_cats[0],'product_cat')->name.' / ';
		}

		$meta_keys_num = $_GET["meta_keys_num"];
		$filter_item = '';
		for($i=1; $i<=$meta_keys_num; $i++) {
			if($i==1) {
				$key_num = '';
				$key_join = ' / ';
			} else {
				$key_num = $i;
				$key_join = ' + ';
			}
			
			if( $_GET["meta_k".$key_num] == '_Evergreen_Deciduous' ) {
				$ed_item_filter = trim(str_replace("_", " ", $_GET["meta_k".$key_num]));
				$filter_item .= $key_join.str_replace(" ", " or ", $ed_item_filter);
			} elseif( $_GET["meta_k".$key_num] == '_Moisture_Descriptor' ) {
				$md_item_filter = trim(str_replace("_", " ", $_GET["meta_k".$key_num]));
				$filter_item .= $key_join.explode(" ", $md_item_filter)[0];
			} elseif( $_GET["meta_k".$key_num] == '_Hardiness_Zone_Whole' ) {
				$hz_item_filter = trim(str_replace("_", " ", $_GET["meta_k".$key_num]));
				$filter_item .= $key_join.explode(" ", $hz_item_filter)[1];
			} elseif( $_GET["meta_k".$key_num] == '_Wildlife_Attraction_SCSV' ) {
				$wla_item_filter = trim(str_replace("_", " ", $_GET["meta_k".$key_num]));
				$filter_item .= $key_join.str_replace(" SCSV", "", $wla_item_filter);
			} else {
				$filter_item .= $key_join.trim(str_replace("_", " ", $_GET["meta_k".$key_num]));
			}

			$meta_value = $_GET["meta_v".$key_num];
			if( strtolower($_GET["meta_v".$key_num]) == "true" ) {
				$meta_value = "Yes";
			}
			if( strtolower($_GET["meta_v".$key_num]) == "false" ) {
				$meta_value = "No";
			}
			
			$filter_item .= ': '.$meta_value;
		}
		
		//$title = $curr_cat->name.$filter_item;
		$title = $parent_name.$curr_cat->name.$filter_item;
		echo '<h2 class="woocommerce-products-header__title page-title title-brcm">'.ucwords($title).'</h2>';
		echo '<div style="text-align: center;"><a href="'.parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH).'" id="clear-search">Clear Search</a></div>';
		echo '<style type="text/css">
				.woocommerce-products-header__title:not(.title-brcm) { display: none; }
				.woocommerce-products-header__title.title-brcm { color: #224121; font-size: 18px; text-transform: uppercase; font-weight: 600; }
			</style>';
	}
}

add_shortcode("search_breadcrumbs", "generate_search_breadcrumbs");
function generate_search_breadcrumbs() {
	ob_start();
	$brdcrm_base = '';
	$uri_query = '';
	$src_clear = false;
	$filter_item = '';
	$meta_conct = '';
	$display = true;
	if( is_search() ) {
		$uri_query = '?s=';
	}
	if( is_page(18134) ) {
		$uri_query = '?sterm=';
	}
	
	if( (is_search() && $_GET["s"] == "") && !isset($_GET["meta_k"]) ) {
		$display = false;
	}
	if( is_search() && $_GET["s"] != "" ) {
		$brdcrm_base .= $_GET["s"];
		$meta_conct .= ' / ';
	}
	if( is_page(18134) && isset($_GET["sterm"]) && $_GET["sterm"] != "" ) {
		$brdcrm_base .= $_GET["sterm"];
		$meta_conct .= ' / ';		
	}
	
	if( isset($_GET["meta_k"]) ) {
		$meta_keys_num = $_GET["meta_keys_num"];
		$filter_item = '';
		for($i=1; $i<=$meta_keys_num; $i++) {
			if($i==1) {
				$key_num = '';
				$meta_conct = $meta_conct;
			} else {
				$key_num = $i;
				$meta_conct = ' + ';
			}
			
			if( $_GET["meta_k".$key_num] == '_Evergreen_Deciduous' ) {
				$ed_item_filter = trim(str_replace("_", " ", $_GET["meta_k".$key_num]));
				$filter_item .= $meta_conct.str_replace(" ", " or ", $ed_item_filter);
			} elseif( $_GET["meta_k".$key_num] == '_Moisture_Descriptor' ) {
				$md_item_filter = trim(str_replace("_", " ", $_GET["meta_k".$key_num]));
				$filter_item .= $meta_conct.explode(" ", $md_item_filter)[0];
			} elseif( $_GET["meta_k".$key_num] == '_Hardiness_Zone_Whole' ) {
				$hz_item_filter = trim(str_replace("_", " ", $_GET["meta_k".$key_num]));
				$filter_item .= $meta_conct.explode(" ", $hz_item_filter)[1];
			} elseif( $_GET["meta_k".$key_num] == '_Wildlife_Attraction_SCSV' ) {
				$wla_item_filter = trim(str_replace("_", " ", $_GET["meta_k".$key_num]));
				$filter_item .= $meta_conct.str_replace(" SCSV", "", $wla_item_filter);
			} else {
				$filter_item .= $meta_conct.trim(str_replace("_", " ", $_GET["meta_k".$key_num]));				
			}

			$meta_value = $_GET["meta_v".$key_num];
			if( strtolower($_GET["meta_v".$key_num]) == "true" ) {
				$meta_value = "Yes";
			}
			if( strtolower($_GET["meta_v".$key_num]) == "false" ) {
				$meta_value = "No";
			}

			$filter_item .= ': '.$meta_value;
		}
		//$title = $curr_cat->name.$filter_item;
		$src_clear = true;
	}
	
	$title = $brdcrm_base.$filter_item;
	if( $display ) {
		echo '<h2 class="woocommerce-products-header__title page-title title-brcm">'.ucwords($title).'</h2>';
		if( $src_clear ) {
			echo '<div style="text-align: center;"><a href="'.$uri_query.$brdcrm_base.'" id="clear-search" style="margin-top: 0; margin-bottom: 40px;">Clear Search</a></div>';
		}
		echo '<style type="text/css">
					.woocommerce-products-header__title.title-brcm { color: #224121; font-size: 18px; text-transform: uppercase; font-weight: 600; text-align: center; }
				</style>';
	}
	
	return ob_get_clean();
}

add_filter( 'wc_add_to_cart_message', 'cvn_add_to_cart_function', 10, 2 );
function cvn_add_to_cart_function( $message, $product_id ) { 
    $message = sprintf('<a href="/cart/" tabindex="1" class="button wc-forward wp-element-button">View Wish List</a> "%s" has been added to your wish list.', get_the_title( $product_id ) );
    return $message;
}

add_filter( 'woocommerce_show_variation_price', '__return_true' );

add_action("woocommerce_after_add_to_cart_button", "cvn_add_calculator");
function cvn_add_calculator() {
	if( is_single(20680) || is_single(20355) || is_single(20350) || is_single(20356) || is_single(20354) || is_single(20353) || is_single(20470) || is_single(20471) || is_single(20472) || is_single(20474) || is_single(20475) || is_single(20476) || is_single(20327) || is_single(20473) ) {
		echo '<div class="elementor-button-wrapper"><a href="#elementor-action%3Aaction%3Dpopup%3Aopen%26settings%3DeyJpZCI6IjEwODI0IiwidG9nZ2xlIjpmYWxzZX0%3D" class="elementor-button-link elementor-button elementor-size-md" role="button" style="background: #b79906; margin-left: 5px; font-weight: 600;"><span class="elementor-button-content-wrapper"><span class="elementor-button-text">Calculate</span></span></a></div>';
	}
}

add_action("woocommerce_share", "cvn_load_calculator_template");
function cvn_load_calculator_template() {
	//if( is_product() )
	echo do_shortcode('[elementor-template id="10824"]');
}

//add_action("init", "cvn_update_sold_indv_products");
function cvn_update_sold_indv_products() {
	if( isset($_GET['pd_chk']) ) {
		$args = array(
			'post_type' => 'product',
			'posts_per_page' => -1,
			'meta_query' => array(
					array(
						'key'     => '_sold_individually',
						'value'   => 'yes',
						'compare' => '=',
					),
				),
		);
	
		/*if( $cat ) {
			$args["tax_query"] = array(
				array(
					'taxonomy' => 'faq_category',
					'field'    => 'slug',
					'terms'    => $cat,
				),
			);
		}*/

		$the_query = new WP_Query( $args );
		if ( $the_query->have_posts() ) {
			$pd_arr = array();
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$product = wc_get_product( get_the_ID() );
				$product_id = $product->get_id();
				$pd_arr[] = $product_id;
				
				//$product->set_sold_individually( false );
				//$product->save();
			}
			
			//echo "Done";
			echo count($pd_arr)."<br>";
			//print_r($pd_arr);
		} else {
			echo "No Result";
		}
	}
	
	if( isset($_GET['pd_meta_info']) ) {
		$product_id = 17375; //17379
		$price_arr = get_post_meta($product_id, '_price', false);
		//print_r( $price_arr );
		if( count($price_arr) > 1 ) {
			echo "Variable";
		} else {
			echo "Simple";
		}
	}
	
	if( isset($_GET['product_instock']) && isset($_GET['product_id']) ) {
		$product_id = $_GET['product_id'];
		//update_post_meta($product_id, '_stock_status', 'instock');
		//update_post_meta($product_id, '_stock', NULL);
	}
}
