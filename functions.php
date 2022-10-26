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
		echo '<a class="installation_btn" href="#"> Request Installation</a>';
	}
	
	echo '<div class="summary_description"><h4>Description</h4>'.apply_filters( 'the_content', get_the_content() ).'</div>';
	
	if( get_post_meta($post->ID, '_Landscape_Attributes_Para1', true) ) {
		echo '<p>'.get_post_meta($post->ID, '_Landscape_Attributes_Para1', true).'</p>';
	}
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
				$size = $product->get_attribute( 'pa_size' );
				if($size):
					echo '<li><strong>Width</strong><span>'.$size.'</span></li>';
				endif;
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
			
			jQuery(".request-quote-btn").on("click", function() {
				jQuery("#request-quote-submit").click();
			});
			
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
				jQuery(this).toggleClass("filter-active");
				jQuery(this).find(".filter-content").toggle();
			});
			
			jQuery(".filter-content input").on("click", function() {
				let meta_key = jQuery(this).attr("name");
				let meta_val = jQuery(this).val();
				//let currentUrl = window.location.href;
				let currentUrl = window.location.href.split('?')[0];
				let params = (new URL(document.location)).searchParams;
				let srchTerm = params.get("s");
				let filterUrl = currentUrl+"?s="+srchTerm+"&meta_k="+meta_key+"&meta_v="+meta_val;
				
				window.location = filterUrl;
			});
		});
	</script>
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			jQuery(function($) {
				var mywindow = $(window);
				var mypos = mywindow.scrollTop();
				let scrolling = false; /* For throlling scroll event */
				window.addEventListener('scroll', function() {
					scrolling = true;
				});
				setInterval(() => {
					if (scrolling) {
						scrolling = false;
						if (mypos > 55) {
							if (mywindow.scrollTop() > mypos) {
								$('#stickyheaders').addClass('headerup');
							} else {
								$('#stickyheaders').removeClass('headerup');
							}
						}
						mypos = mywindow.scrollTop();
					}
				}, 300);
			});
		});
	</script>
	<style>
		#stickyheaders{
			transition : transform 0.34s ease;
		}
		.headerup{
			transform: translateY(-110px); /*adjust this value to the height of your header*/
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
			max-width: 650px;
			padding-top: 20px;
		}
		
		<?php if( is_user_logged_in() && is_account_page() && !is_wc_endpoint_url() ) { ?>
		.woocommerce-MyAccount-content > p:first-of-type { display: none; }
		.woocommerce-MyAccount-content > p:nth-of-type(2) { display: none; }
		<?php } ?>
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
		<p><img src="https://trevorh36.sg-host.com/wp-content/uploads/2022/05/zones-map-1.jpg" alt="" class="alignnone size-full wp-image-10559" style="max-width: 650px;" /></p>
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
		's' => $s_keyword
	);
	if( (isset($_GET["meta_k"]) && $_GET["meta_k"] != "") && (isset($_GET["meta_v"]) && $_GET["meta_v"] != "") ) {
		$meta_key = $_GET["meta_k"];
		$meta_value = $_GET["meta_v"];
		$args["meta_query"] = array(
			array (
				'key' => $meta_key,
				'value' => $meta_value,
				'compare' => '=',
			),
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
			echo '<div style="font-size: 26px; text-align: center; font-weight: 600; color: #224121;">You have searched for "'.$_GET["s"].'"</div>';
		}
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
			echo '<td width="100"><span class="quentity">'.$cart_item['quantity'].'</span><a href="'.$product->get_permalink( $cart_item ).'">'.$getProductDetail->get_image('thumbnail', '').'</a></td>';
			echo '<td width="500">'.$product->get_title( $cart_item ).'</td>';
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
				<?php
				if( $installation_interest ) {
				?>
				<input type="checkbox" name="need_installation" id="need_installation" value="Want Installation" /><label for="need_installation">Installation or shipping?</label>
				<?php
				}
				if( $shipping_interest ) {
				?>
				<input type="checkbox" name="need_shipping" id="need_shipping" value="Want Shipping" /><label for="need_shipping">Installation or shipping?</label>
				<?php } ?>
			</div>
			<div class="gray_back shipping-fields-custom">
				<h2>Your Address</h2>
				<!--<div class="input_group">
					<div class="one_half"><input type="text" id="first_name" name="first_name" placeholder="First Name" /></div>
					<div class="one_half last"><input type="text" id="last_name" name="last_name" placeholder="Last Name" /></div>
				</div>-->
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
						<!--<input type="text" id="country" name="country" placeholder="Country/Region" />-->
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
				$message .= '<td width="80" style="text-align: center;"><a href="'.$product->get_permalink( $cart_item ).'">'.$getProductDetail->get_image('thumbnail', '').'</a></td>';
				$message .= '<td>'.$product->get_title( $cart_item ).'</td>';
				$message .= '<td>'.WC()->cart->get_product_price( $product ).'</td>';
				$message .= '<td><span class="quentity">'.$cart_item['quantity'].'</span></td>';
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

		if( isset($_POST["need_shipping"]) || isset($_POST["need_installation"]) ) {
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
	background-image: url(/wp-content/uploads/2022/05/login-page-logo.png);
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
    background-image: url(/wp-content/uploads/2022/05/user-icon.png) !important;
    background-repeat: no-repeat !important;
    background-position: center left 10px !important;
}

body.login #loginform #user_pass {
    background-image: url(/wp-content/uploads/2022/05/password-icon.png) !important;
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


function cvn_search_filter($query) {
    if ( ! is_admin() && $query->is_main_query() ) {
        if ( $query->is_search ) {
            $query->set( 'post_type', 'product' );
			//$meta_query = $query->get('meta_query');
			
			if( (isset($_GET["meta_k"]) && $_GET["meta_k"] != "") && (isset($_GET["meta_v"]) && $_GET["meta_v"] != "") ) {
				$meta_key = $_GET["meta_k"];
				$meta_value = $_GET["meta_v"];
				//$query->set( 'meta_key', $meta_key );
				//$query->set( 'meta_value', $meta_value );

				//$meta_query = ( is_array( $query->get('meta_query') ) ) ? $query->get('meta_query') : [];
				
				$meta_query = array(
					array (
					  'key' => $meta_key,
					  'value' => $meta_value,
					  'compare' => '=',
					),
				);
				$query->set('meta_query', $meta_query);
			}
        }
    }
}
//add_action( 'pre_get_posts', 'cvn_search_filter' );


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

add_filter( 'woocommerce_product_query_meta_query', 'cvn_shop_only_instock_products', 10, 2 );
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
							'post_type' => 'material',
							'posts_per_page' => -1
						);

						$mt_query = new WP_Query( $args );
						if ( $mt_query->have_posts() ) {
							while($mt_query->have_posts()) : $mt_query->the_post();
							$pid = get_the_ID();
							$sold_by = get_post_meta($pid, "_sold_by", true);
							$rprice = get_post_meta($pid, "_rprice", true);
							$wprice = get_post_meta($pid, "_wprice", true);
							$cprice = get_post_meta($pid, "_cprice", true);
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
			<div class="calc-result-title">Cost: </div>
			<div class="calc-result-cost"></div>
			<div class="calc-result-currency" style="border: none;"></div>
		</div>
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
					//var width = jQuery("#width-unit").val();
					var depthUnit = jQuery("#depth-unit").val();
					var materialLength = jQuery("#length-feet").val();
					var materialWidth = jQuery("#width-feet").val();
					var materialDepth = jQuery("#depth-inches").val();
					/*console.log("Cprice: "+calc_price);
					console.log("Sold in: "+sold_by);
					console.log("Unit: "+lengthUnit);*/
					
					if( sold_by == "Cubic Yard" && lengthUnit == "Feet" ) {
						//var materialRequired = (((materialLength*materialWidth*materialDepth)/12/27)*calc_price);
						var materialRequired = (materialLength*materialWidth*materialDepth/12)/27;
						//var materialRequired = ((materialLength*materialWidth*materialDepth/12)/27)*calc_price;
						jQuery(".calc-result-number").text(materialRequired.toLocaleString('en-US', {maximumFractionDigits:1}));
						jQuery(".calc-result-units-soldin").text("Cubic Yards");
						var materialCost = materialRequired*calc_price;
						jQuery(".calc-result-cost").text(materialCost.toLocaleString('en-US', {maximumFractionDigits:2}));
						//jQuery(".calc-result-currency").text("$");
					}
					if( sold_by == "Cubic Yard" && lengthUnit == "Meters" ) {
						//var materialRequired = ((((materialLength*materialWidth*materialDepth)/12/27)/1.36)*calc_price);
						var materialRequired = (materialLength*materialWidth*materialDepth/100)*1.307951;
						//var materialRequired = ((materialLength*materialWidth*materialDepth/100)*1.307951)*calc_price;
						jQuery(".calc-result-number").text(materialRequired.toLocaleString('en-US', {maximumFractionDigits:1}));
						jQuery(".calc-result-units-soldin").text("Cubic Yards");
						var materialCost = materialRequired*calc_price;
						jQuery(".calc-result-cost").text(materialCost.toLocaleString('en-US', {maximumFractionDigits:2}));
						//jQuery(".calc-result-currency").text("$");
					}
					if( sold_by == "Metric Tonne" && lengthUnit == "Feet" ) {
						//var materialRequired = ((((materialLength*materialWidth*materialDepth)/12/27)*1.36)*calc_price);
						var materialRequired = (((materialLength*materialWidth*materialDepth/12)/27)*0.764555)*1.307951;
						//var materialRequired = (((materialLength*materialWidth*materialDepth/100)*0.764555)*0.353146667214886)*calc_price;
						jQuery(".calc-result-number").text(materialRequired.toLocaleString('en-US', {maximumFractionDigits:1}));
						jQuery(".calc-result-units-soldin").text("Metric Tonnes");
						var materialCost = materialRequired*calc_price;
						jQuery(".calc-result-cost").text(materialCost.toLocaleString('en-US', {maximumFractionDigits:2}));
						//jQuery(".calc-result-currency").text("$");
					}
					if( sold_by == "Metric Tonne" && lengthUnit == "Meters" ) {
						//var materialRequired = (((materialLength*materialWidth*materialDepth)/12/27)*calc_price);
						var materialRequired = (materialLength*materialWidth*materialDepth/100)*1.307951;
						//var materialRequired = ((materialLength*materialWidth*materialDepth/100)*0.353146667214886)*calc_price;
						jQuery(".calc-result-number").text(materialRequired.toLocaleString('en-US', {maximumFractionDigits:1}));
						jQuery(".calc-result-units-soldin").text("Metric Tonnes");                    
						var materialCost = materialRequired*calc_price;
						jQuery(".calc-result-cost").text(materialCost.toLocaleString('en-US', {maximumFractionDigits:2}));
						//jQuery(".calc-result-currency").text("$");
					}

					/*let materialRequired = (((materialLength*materialWidth*materialDepth)/12/27)*1.36);
					jQuery(".calc-result-number").text(materialRequired.toFixed(1));
					jQuery(".calc-result-units-soldin").text("Metric Tonnes");*/
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
	$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
	
	$args = array(
		'post_type' => 'product',
		'posts_per_page' => 15,
		'paged' => $paged,
		's' => $s_keyword,
		'meta_query' => array(
            array(
                'key' => '_stock_status',
                'value' => 'instock'
        	)
        ),
	);
	if( (isset($_GET["meta_k"]) && $_GET["meta_k"] != "") && (isset($_GET["meta_v"]) && $_GET["meta_v"] != "") ) {
		$meta_key = $_GET["meta_k"];
		$meta_value = $_GET["meta_v"];
		$args["meta_query"] = array(
			array (
				'key' => $meta_key,
				'value' => $meta_value,
				'compare' => '=',
			),
		);
	}
	if( ! $s_keyword ) {
		$args["order"] = "ASC";
	}
	$the_query = new WP_Query( $args );
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
					<img src="/wp-content/uploads/woocommerce-placeholder-300x300.png" alt="<?php echo get_the_title(); ?>" />
					<?php } ?>
				</div>
				</a>
				<h3 class="product-title">
					<a href="<?php echo get_permalink(); ?>"><?php echo get_the_title(); ?></a>
				</h3>
				<?php
					$product = wc_get_product( get_the_ID() );
					//echo $product->get_price();
					if( ! $product->is_type('variable') ){
						echo $product->get_price_html();
						echo '<div class="src-pr-btns"><a href="'.$product->add_to_cart_url().'" value="'.esc_attr( $product->get_id() ).'" class="ajax_add_to_cart add_to_cart_button" data-product_id="'.get_the_ID().'" data-product_sku="'.esc_attr($sku).'" aria-label="Add “'.the_title_attribute("echo=0").'” to your cart">Add to Wish List</a></div>';
					} else {
						$min_price = $product->get_variation_price( 'min' );
        				$max_price = $product->get_variation_price( 'max' );
						$cr_symbol = get_woocommerce_currency_symbol();
						
						$variable_price = $cr_symbol.$min_price." - ".$cr_symbol.$max_price;
						echo $variable_price;
						echo '<div class="src-pr-btns"><a href="'.get_permalink().'">Add to Wish List</a></div>';
					}
				?>
			</li>
			<?php
		}
		echo '</ul>';
		
		wp_pagenavi();
	} else {
		// no posts found
		_e( 'Sorry, no posts matched your criteria.' );
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
	$items['orders'] = __("Shop Request", "woocommerce");
	$items['edit-address'] = __("Address", "woocommerce");
	$items['edit-account'] = __("Account Details", "woocommerce");
    return $items;
}

add_action( 'woocommerce_account_dashboard', 'cvn_my_account_endpoint_content' );
function cvn_my_account_endpoint_content() {

	// content here, one of the most useful functions here is get_current_user_id()
	echo '<p>As a registered Wholesale customer (or Contractor) of Clearview Nursery, you have access to Industry Pricing.  Your login provides you access to pricing not available to our retail customers.</p>
		<p>Please note that this system can be used for browsing, finding prices and submitting an order request.  This is not for ecommerce transactions.  We want to review any order requests before fulfilling them with you.</p>
		<p><a href="#" class="view-products-btn">View Our Products</a></p>';

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
    return __( 'Add to Wish List', 'woocommerce' );
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

	$query = new WC_Product_Query(array(
		'limit' => 15,
		'page'  => $paged,
		'paginate' => true,
		'stock_status' => 'instock',
		'category' => $cats
	));

	$results = $query->get_products();
	$products = $results->products;
	
	ob_start();
	//print_r($results);
	
	if ( !empty($products) ) {
		echo '<ul class="searched-products">';
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
						<img src="/wp-content/uploads/woocommerce-placeholder-300x300.png" alt="<?php echo get_the_title($product_id); ?>" />
						<?php } ?>
					</div>
				</a>
				<h3 class="product-title">
					<a href="<?php echo get_permalink($product_id); ?>"><?php echo get_the_title($product_id); ?></a>
				</h3>
				<?php
					if( ! $product->is_type('variable') ){
						echo $product->get_price_html();
						echo '<div class="src-pr-btns"><a href="'.$product->add_to_cart_url().'" value="'.esc_attr( $product_id ).'" class="ajax_add_to_cart add_to_cart_button" data-product_id="'.$product_id.'">Add to Wish List</a></div>';
					} else {
						$min_price = $product->get_variation_price( 'min' );
						$max_price = $product->get_variation_price( 'max' );
						$cr_symbol = get_woocommerce_currency_symbol();

						$variable_price = $cr_symbol.$min_price." - ".$cr_symbol.$max_price;
						echo $variable_price;
						echo '<div class="src-pr-btns"><a href="'.get_permalink($product_id).'">Add to Wish List</a></div>';
					}
				?>
			</li>
			<?php
		}
		echo '</div>';
		
		echo paginate_links( array(
                            'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                            'format' => '?paged=%#%',
                            'current' => max( 1, get_query_var('paged') ),
                            'total' => $results->max_num_pages
                            ) );
	}

	return ob_get_clean();
}
