<?php
/**
 * Initialize the meta boxes. 
 */


add_filter( 'rwmb_meta_boxes', 'post_register_meta_boxes' );

/**
 * Register meta boxes
 *
 * @return void
 */
function post_register_meta_boxes( $meta_boxes )
{
	/**
	 * Prefix of meta keys (optional)
	 * Use underscore (_) at the beginning to make keys hidden
	 * Alt.: You also can make prefix empty to disable it
	 */
	// Better has an underscore as last sign
	$prefix = 'YOUR_PREFIX_';

	// 1st meta box
	$meta_boxes[] = array(
		// Meta box id, UNIQUE per meta box. Optional since 4.1.5
		'id' => 'standard',

		// Meta box title - Will appear at the drag and drop handle bar. Required.
		'title' => __( 'Post Settings', 'rwmb' ),

		// Post types, accept custom post types as well - DEFAULT is array('post'). Optional.
		'pages' => array( 'post'),

		// Where the meta box appear: normal (default), advanced, side. Optional.
		'context' => 'normal',

		// Order of meta box: high (default), low. Optional.
		'priority' => 'high',

		// Auto save: true, false (default). Optional.
		'autosave' => true,

		// List of meta fields
		'fields' => array(
			array(
				'name'  => __( 'Video URL','cactusthemes'),
				'id'    => "tm_video_url",
				'desc'  => __( 'Paste the url from popular video sites like YouTube or Vimeo. For example: <br/><code>http://www.youtube.com/watch?v=nTDNLUzjkpg</code><br/>or<br/><code>http://vimeo.com/23079092</code>','cactusthemes'),
				'type'  => 'text',
				'std'   => __( '','cactusthemes'),
				'clone' => false,
			),
			array(
				'name' => __( 'Video File','cactusthemes'),
				'desc' => __( 'Paste your video file url to here. Supported Video Formats: mp4, m4v, webmv, webm, ogv and flv.<br/><b>About Cross-platform and Cross-browser Support</b><br/>If you want your video works in all platforms and browsers(HTML5 and Flash), you should provide various video formats for same video, if the video files are ready, enter one url per line.<br/> For Example:<br/> <code>http://yousite.com/sample-video.m4v</code><br/><code>http://yousite.com/sample-video.ogv</code><br/> <b>Recommended Format Solution:</b> webmv + m4v + ogv. ','cactusthemes'),
				'id'   => "tm_video_file",
				'type' => 'textarea',
				'cols' => 20,
				'rows' => 7,
			),
			array(
				'name' => __( 'Video Code','cactusthemes'),
				'desc' => __('Paste the raw video code to here, such as <code>&lt;</code><code>object</code><code>&gt;</code>,<code>&lt;</code><code>embed</code><code>&gt;</code> or <code>&lt;</code><code>iframe</code><code>&gt;</code> code.','cactusthemes'),
				'id'   => "tm_video_code",
				'type' => 'textarea',
				'cols' => 20,
				'rows' => 7,
			),
			array(
				'name'  => __( 'Duration','cactusthemes'),
				'id'    => "time_video",
				'desc'  => __( ''),
				'type'  => 'text',
				'std'   => __( '','cactusthemes'),
				'clone' => false,
			),
			array(
				'name'     => __( 'Show Hide Feature Image', 'cactusthemes' ),
				'id'       => "show_feature_image",
				'type'     => 'select',
				// Array of 'value' => 'Label' pairs for select box
				'options'  => array(
					'3' => __( 'Default','cactusthemes'),
					'2' => __( 'Show','cactusthemes'),
					'1' => __( 'Hide','cactusthemes'),
				),
				'multiple'    => false,
				'std'         => '',
				'placeholder' => false,
			),
			array(
				'name'     => __('Video Layout/Feature Image','cactusthemes'),
				'id'       => "page_layout",
				'type'     => 'select',
				// Array of 'value' => 'Label' pairs for select box
				'options'  => array(
					'def' => __( 'Default','cactusthemes'),
					'full_width' => __( 'Full Width','cactusthemes'),
					'inbox' => __( 'Inboxed','cactusthemes'),
				),
				// Select multiple values, optional. Default is false.
				'multiple'    => false,
				'std'         => '',
				'placeholder' => false,
			),
			array(
				'name'     => __('Page Layout','cactusthemes'),
				'id'       => "single_ly_ct_video",
				'type'     => 'select',
				// Array of 'value' => 'Label' pairs for select box
				'options'  => array(
					'def' => __( 'Default','cactusthemes'),
					'full' => __( 'Full Width','cactusthemes'),
					'right' => __( 'Sidebar Right','cactusthemes'),
					'left' => __( 'Sidebar Left','cactusthemes'),
				),
				'multiple'    => false,
				'std'         => '',
				'placeholder' => false,
			),
			array(
				'name'             => __( 'Background Image', 'cactusthemes' ),
				'id'               => "ct_bg_image",
				'type'             => 'image_advanced',
				'max_file_uploads' => 4,
			),
			array(
				'name'     => __('Background repeat','cactusthemes'),
				'id'       => "ct_bg_repeat",
				'type'     => 'select',
				// Array of 'value' => 'Label' pairs for select box
				'options'  => array(
					'no-repeat' => __( 'No Repeat','cactusthemes'),
					'repeat' => __( 'Repeat All','cactusthemes'),
					'repeat-x' => __( 'Repeat Horizontally','cactusthemes'),
					'repeat-y' => __( 'Repeat Vertically','cactusthemes'),
					'inherit' => __( 'Inherit','cactusthemes'),
				),
				'multiple'    => false,
				'std'         => '',
				'placeholder' => false,
			),
			array(
				'name'     => __('Background attachment','cactusthemes'),
				'id'       => "ct_bg_attachment",
				'type'     => 'select',
				// Array of 'value' => 'Label' pairs for select box
				'options'  => array(
					'' => __( '','cactusthemes'),
					'fixed' => __( 'Fixed','cactusthemes'),
					'scroll' => __( 'Scroll','cactusthemes'),
					'inherit' => __( 'Inherit','cactusthemes'),
				),
				'multiple'    => false,
				'std'         => '',
				'placeholder' => false,
			),
			array(
				'name'     => __('Background position','cactusthemes'),
				'id'       => "ct_bg_position",
				'type'     => 'select',
				// Array of 'value' => 'Label' pairs for select box
				'options'  => array(
					'' => __( '','cactusthemes'),
					'left top' => __( 'Left Top','cactusthemes'),
					'left center' => __( 'Left Center','cactusthemes'),
					'left bottom' => __( 'Left Bottom','cactusthemes'),
					'center top' => __( 'Center Top','cactusthemes'),
					'center center' => __( 'Center Center','cactusthemes'),
					'center bottom' => __( 'Center Bottom','cactusthemes'),
					'right top' => __( 'Right Top','cactusthemes'),
					'right center' => __( 'Right center','cactusthemes'),
					'right bottom' => __( 'Right bottom','cactusthemes'),
				),
				'multiple'    => false,
				'std'         => '',
				'placeholder' => false,
			),
		),
	);

	// 2nd meta box
	$meta_boxes[] = array(
		'title' => __( 'Videos Auto Fetch Data', 'cactusthemes' ),
		'context' => 'side',
		'fields' => array(
			// HEADING
			array(
				'name' => __( '', 'cactusthemes' ),
				'id'   => "fetch_info",
				'type' => 'checkbox_list',
				'desc' => __('Check above checkbox if you do not want to auto-fetch video data after save/edit. To chose which fields to fetch, go to appearance > Theme option > General ','cactusthemes'),
				// Options of checkboxes, in format 'value' => 'Label'
				'options' => array(
					'1' => __( '<strong>DO NOT FETCH</strong>', 'cactusthemes' ),
				),
			),
		)
	);
    
    // meta box para welcu
    $meta_boxes[] = array(
        'title' => 'Url Invitaci&oacute;n por Welcu',
        'context' => 'normal',
        'fields' => array(
            array(
                'name' => 'Welcu Url',
                'id' => 'welcu_url', 
                'type' => 'text',
                'desc' => 'Ingrese el Url de la invitaci&oacute;n al evento proporcionada por Welcu',
            )
        ),
        'pages' => array( 'post'),

		// Order of meta box: high (default), low. Optional.
		'priority' => 'high',

		// Auto save: true, false (default). Optional.
		'autosave' => true,
    );
    
    // meta box para external URL
    $meta_boxes[] = array(
        'title' => 'Url externa al hacer click en el carousel',
        'context' => 'normal',
        'fields' => array(
            array(
                'name' => 'Url Externo',
                'id' => 'external_url', 
                'type' => 'text',
                'desc' => 'Ingrese el Url al que el post debe redigir al hacer click en el carousel',
            )
        ),
        'pages' => array( 'post'),

		// Order of meta box: high (default), low. Optional.
		'priority' => 'high',

		// Auto save: true, false (default). Optional.
		'autosave' => true,
    );
	return $meta_boxes;
}


