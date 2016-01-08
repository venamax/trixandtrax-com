<?php

/**
 * Initialize the meta boxes. 
 */
add_action( 'admin_init', 'ct_page_meta_boxes' );

if ( ! function_exists( 'ct_page_meta_boxes' ) ){
	function ct_page_meta_boxes() {
	  $page_meta_box = array(
		'id'        => 'page_meta_box',
		'title'     => 'Page Settings',
		'desc'      => '',
		'pages'     => array( 'page' ),
		'context'   => 'normal',
		'priority'  => 'high',
		'fields'    => array(
			array(
			  'id'          => 'header_style',
			  'label'       => __('Header Stye','cactusthemes'),
			  'desc'        => __('Only use with Page template "Front Page"','cactusthemes'),
			  'std'         => '',
			  'type'        => 'select',
			  'class'       => '',
			  'choices'     => array(
			  	  array(
					'value'       => 0,
					'label'       => 'Default',
					'src'         => ''
				  ),
				  array(
					'value'       => 'carousel',
					'label'       => 'Big Carousel',
					'src'         => ''
				  ),
				  array(
					'value'       => 'metro',
					'label'       => 'Metro Carousel',
					'src'         => ''
				  ),
				  array(
					'value'       => 'classy',
					'label'       => 'Classy Slider',
					'src'         => ''
				  ),
				  array(
					'value'       => 'classy2',
					'label'       => 'Classy Slider 2',
					'src'         => ''
				  ),
				  array(
					'value'       => 'classy3',
					'label'       => 'Classy Slider 3',
					'src'         => ''
				  ),
				  array(
					'value'       => 'amazing',
					'label'       => 'Amazing Slider',
					'src'         => ''
				  ),
				  array(
					'value'       => 'video_slider',
					'label'       => 'Video Slider',
					'src'         => ''
				  ),
				  array(
					'value'       => 'sidebar',
					'label'       => 'Sidebar',
					'src'         => ''
				  )
			   )
			),
			array(
			  'id'          => 'sidebar',
			  'label'       => __('Sidebar','cactusthemes'),
			  'desc'        => __('Main Sidebar appearance','cactusthemes'),
			  'std'         => '',
			  'type'        => 'select',
			  'class'       => '',
			  'choices'     => array(
			  	  array(
					'value'       => '',
					'label'       => 'Default',
					'src'         => ''
				  ),
				  array(
					'value'       => 'left',
					'label'       => 'Left Sidebar',
					'src'         => ''
				  ),
				  array(
					'value'       => 'right',
					'label'       => 'Right Sidebar',
					'src'         => ''
				  ),
				  array(
					'value'       => 'full',
					'label'       => 'No Sidebar',
					'src'         => ''
				  )
			   )
			),
			array(	
					'id'          => 'background',
				  'label'       => __('Background','cactusthemes'),
				  'desc'        => __('If used with Page Template "Front Page" and Header Style is "Sidebar" or "Classy", this will be header background','cactusthemes'),
				  'std'         => '',
				  'type'        => 'background'
			)
		 )
	  );
	  ot_register_meta_box( $page_meta_box );

	}
}


