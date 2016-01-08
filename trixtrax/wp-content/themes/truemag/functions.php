<?php

if(!defined('PARENT_THEME')){
	define('PARENT_THEME','truemag');
}
if ( ! isset( $content_width ) ) $content_width = 900;
global $_theme_required_plugins;

/* Define list of recommended and required plugins */
$_theme_required_plugins = array(
        array(
            'name'      => 'WP Pagenavi',
            'slug'      => 'wp-pagenavi',
            'required'  => false
        ),
        array(
            'name'      => 'BAW Post Views Count',
            'slug'      => 'baw-post-views-count',
            'required'  => false
        ),
        array(
            'name'      => 'Truemag - Member',
            'slug'      => 'ct-member',
            'required'  => false
        ),
        array(
            'name'      => 'TrueMAG - Movie',
            'slug'      => 'truemag-movie',
            'required'  => false
        ),
        array(
            'name'      => 'TrueMAG Rating',
            'slug'      => 'truemag-rating',
            'required'  => false
        ),
        array(
            'name'      => 'TrueMAG - Shortcodes',
            'slug'      => 'truemag-shortcodes',
            'required'  => false
        ),
		array(
            'name'      => 'Video Thumbnails',
            'slug'      => 'video-thumbnails',
            'required'  => false
        ),
		array(
            'name'      => 'WTI Like Post',
            'slug'      => 'wti-like-post',
            'required'  => false
        ),
		array(
            'name'      => 'JW Player for WordPress',
            'slug'      => 'jw-player-plugin-for-wordpress',
            'required'  => false
        ),
		array(
            'name'      => 'Categories Images',
            'slug'      => 'categories-images',
            'required'  => false
        ),
		array(
            'name'      => 'Black Studio TinyMCE Widget',
            'slug'      => 'black-studio-tinymce-widget',
            'required'  => false
        ),
		array(
            'name'      => 'Contact Form 7',
            'slug'      => 'contact-form-7',
            'required'  => false
        ),
		array(
            'name'      => 'Simple Twitter Tweets',
            'slug'      => 'simple-twitter-tweets',
            'required'  => false
        ),
    );
	
include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); //for check plugin status
/**
 * Load core framework
 */
require_once 'inc/core/skeleton-core.php';
require_once 'inc/videos-functions.php';

/**
 * Load Theme Options settings
 */ 
require_once 'inc/theme-options.php';

/**
 * Load Theme Core Functions, Hooks & Filter
 */
require_once 'inc/core/theme-core.php';

require_once 'inc/videos-functions.php';

require_once 'sample-data/tm_importer.php';

/*//////////////////////////////////////////////True-Mag////////////////////////////////////////////////*/

/*Remove filter*/
function remove_like_view_widget() {
	unregister_widget('MostLikedPostsWidget');
	unregister_widget('WP_Widget_Most_Viewed_Posts');
}
add_action( 'widgets_init', 'remove_like_view_widget' );

remove_filter('the_content', 'PutWtiLikePost');

/* Add filter to modify markup */
add_filter( 'video_thumbnail_markup', 'tm_video_thumbnail_markup', 10, 2 );

add_filter('widget_text', 'do_shortcode');
if(!function_exists('tm_get_default_image')){
	function tm_get_default_image(){
		return get_template_directory_uri().'/images/nothumb.jpg';
	}
}
//add prev and next link rel on head
add_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );

//add author social link meta
add_action( 'show_user_profile', 'tm_show_extra_profile_fields' );
add_action( 'edit_user_profile', 'tm_show_extra_profile_fields' );
function tm_show_extra_profile_fields( $user ) { ?>
	<h3><?php _e('Social informations','cactusthemes') ?></h3>
	<table class="form-table">
		<tr>
			<th><label for="twitter">Twitter</label></th>
			<td>
				<input type="text" name="twitter" id="twitter" value="<?php echo esc_attr( get_the_author_meta( 'twitter', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description"><?php _e('Enter your Twitter profile url.','cactusthemes')?></span>
			</td>
		</tr>
        <tr>
			<th><label for="facebook">Facebook</label></th>
			<td>
				<input type="text" name="facebook" id="facebook" value="<?php echo esc_attr( get_the_author_meta( 'facebook', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description"><?php _e('Enter your Facebook profile url.','cactusthemes')?></span>
			</td>
		</tr>
        <tr>
			<th><label for="flickr">Flickr</label></th>
			<td>
				<input type="text" name="flickr" id="flickr" value="<?php echo esc_attr( get_the_author_meta( 'flickr', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description"><?php _e('Enter your Flickr profile url.','cactusthemes')?></span>
			</td>
		</tr>
        <tr>
			<th><label for="google-plus">Google+</label></th>
			<td>
				<input type="text" name="google" id="google" value="<?php echo esc_attr( get_the_author_meta( 'google', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description"><?php _e('Enter your Google+ profile url.','cactusthemes')?></span>
			</td>
		</tr>
	</table>
<?php }
add_action( 'personal_options_update', 'tm_save_extra_profile_fields' );
add_action( 'edit_user_profile_update', 'tm_save_extra_profile_fields' );
function tm_save_extra_profile_fields( $user_id ) {
	if ( !current_user_can( 'edit_user', $user_id ) )
		return false;
	/* Copy and paste this line for additional fields. Make sure to change 'twitter' to the field ID. */
	update_user_meta( $user_id, 'twitter', $_POST['twitter'] );
	update_user_meta( $user_id, 'facebook', $_POST['facebook'] );
	update_user_meta( $user_id, 'flickr', $_POST['flickr'] );
	update_user_meta( $user_id, 'google', $_POST['google'] );
}
//get video meta count
if(!function_exists('tm_html_video_meta')){
	function tm_html_video_meta($single = false, $label = false, $break = false, $listing_page = false, $post_id = false){
		global $post;
		$post_id = $post_id?$post_id:get_the_ID();
		ob_start();
		$view_count = get_post_meta($post_id, '_count-views_all', true);
		if($single=='view'){
			echo '<span class="pp-icon"><i class="fa fa-eye"></i> '.($view_count?$view_count:0).'</span>';
		}elseif($single=='like'){
			if(function_exists('GetWtiLikeCount')){
			echo '<span class="pp-icon iclike"><i class="fa fa-thumbs-up"></i> '.str_replace('+','',GetWtiLikeCount($post_id)).'</span>';
			}
		}elseif($single=='comment'){
			echo '<span class="pp-icon"><i class="fa fa-comment"></i> '.get_comments_number($post_id).'</span>';			
		}elseif($listing_page){
			if(ot_get_option('blog_show_meta_view',1)){?>
        	<span><i class="fa fa-eye"></i> <?php echo ($view_count?$view_count:0).($label?__('  Views'):'') ?></span><?php echo $break?'<br>':'' ?>
            <?php }
			if(ot_get_option('blog_show_meta_comment',1)){?>
            <span><i class="fa fa-comment"></i> <?php echo get_comments_number($post_id).($label?__('  Comments'):''); ?></span><?php echo $break?'<br>':'' ?>
            <?php }
			if(ot_get_option('blog_show_meta_like',1)&&function_exists('GetWtiLikeCount')){?>
            <span><i class="fa fa-thumbs-up"></i> <?php echo str_replace('+','',GetWtiLikeCount($post_id)).($label?__('  Likes'):''); ?></span>
		<?php
			}
		}else{?>
            <span><i class="fa fa-eye"></i> <?php echo ($view_count?$view_count:0).($label?__('  Views'):'') ?></span>
            <?php echo $break?'<br>':'' ?>
            <span><i class="fa fa-comment"></i> <?php echo get_comments_number($post_id).($label?__('  Comments'):''); ?></span>
            <?php echo $break?'<br>':'' ?>
            <?php if(function_exists('GetWtiLikeCount')){?>
            <span><i class="fa fa-thumbs-up"></i> <?php echo str_replace('+','',GetWtiLikeCount($post_id)).($label?__('  Likes'):''); ?></span>
            <?php }
		}
		$html = ob_get_clean();
		return $html;
	}
}
//quick view
if(!function_exists('quick_view_tm')){
	function quick_view_tm(){
		  $html = '';
		  $title = get_the_title();
		  $title = strip_tags($title);
		  $link_q = get_post_meta(get_the_id(),'tm_video_url',true);
		  $link_q = str_replace('http://vimeo.com/','http://player.vimeo.com/video/',$link_q);
		  if((strpos($link_q, 'wistia.com')) !== false){$link_q ='';}
		  if($link_q==''){
			  $file = get_post_meta(get_the_id(), 'tm_video_file', true);
			  $files = !empty($file) ? explode("\n", $file) : array();
			  $link_q = $files['0'];
		  }
		  if($link_q!='' ){
		  $html .='<div><a href='.$link_q.' class=\'html5lightbox\' data-width=\'480\' data-height=\'320\' title=\''.$title.'\' >
				'.__('Quick View','cactusthemes').'
			</a></div>';
		  }
		  return $html;
	}
}
if(!function_exists('tm_post_rating')){
	function tm_post_rating($post_id,$get=false){
		$rating = round(get_post_meta($post_id, 'taq_review_score', true)/10,1);
		if($rating){
			$rating = number_format($rating,1,'.','');
		}
		if($get){
			return $rating;
		}elseif($rating){
			$html='<span class="rating-bar bgcolor2">'.$rating.'</span>';
			return $html;
		}
	}
}

/**
 * Sets up theme defaults and registers the various WordPress features that
 * theme supports.
 *
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_editor_style() To add a Visual Editor stylesheet.
 * @uses add_theme_support() To add support for post thumbnails, automatic feed links,
 * 	custom background, and post formats.
 * @uses register_nav_menu() To add support for navigation menus.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 */
function cactusthemes_setup() {
	/*
	 * Makes theme available for translation.
	 *
	 * Translations can be added to the /languages/ directory.
	 */
	load_theme_textdomain( 'cactusthemes', get_template_directory() . '/languages' );

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// Adds RSS feed links to <head> for posts and comments.
	add_theme_support( 'automatic-feed-links' );

	// This theme supports a variety of post formats.
	
	add_theme_support( 'post-formats', array( 'gallery', 'video', 'audio' ) );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menu( 'main-navigation', __( 'Main Navigation', 'cactusthemes' ) );
	register_nav_menu( 'footer-navigation', __( 'Footer Navigation', 'cactusthemes' ) );

	// This theme uses a custom image size for featured images, displayed on "standard" posts.
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 624, 9999 ); // Unlimited height, soft crop
}
add_action( 'after_setup_theme', 'cactusthemes_setup' );

/**
 * Enqueues scripts and styles for front-end.
 */
function cactusthemes_scripts_styles() {
	global $wp_styles;
	
	/*
	 * Loads our main javascript.
	 */	
	
	wp_enqueue_script( 'jquery');
	wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/js/bootstrap.min.js', array(), '', true );
	wp_enqueue_script( 'caroufredsel', get_template_directory_uri() . '/js/jquery.caroufredsel-6.2.1.min.js', array('jquery'), '', true );
	wp_enqueue_script( 'touchswipe', get_template_directory_uri() . '/js/helper-plugins/jquery.touchSwipe.min.js', array('caroufredsel'), '', true );
	wp_enqueue_script( 'hammer', get_template_directory_uri() . '/js/jquery.hammer.js', array('jquery'), '', true );		
	wp_enqueue_script( 'template', get_template_directory_uri() . '/js/template.js', array('jquery'), '', true );
	
	wp_enqueue_script( 'html5lightbox', get_template_directory_uri() . '/js/html5lightbox.js', array('jquery'), '', true );		
	wp_register_script( 'js-scrollbox', get_template_directory_uri() . '/js/jquery.scrollbox.js', array(), '', true );
	
	wp_enqueue_script( 'tooltipster', get_template_directory_uri() . '/js/jquery.tooltipster.js', array(), '', true );
	//wp_enqueue_script( 'waypoints' );
	/*
	 * videojs.
	 */
	wp_register_script( 'videojs', get_template_directory_uri() . '/js/videojs/video.js' , array('jquery'), '', true );
	wp_enqueue_script( 'videojs' );
	wp_register_style( 'videojs', get_template_directory_uri() . '/js/videojs/video-js.css');
	wp_enqueue_style( 'videojs' );
	/*
	 * Loads our main stylesheet.
	 */
	$tm_all_font = array();
	if(ot_get_option('text_font', 'Open Sans')!='Custom Font'){
		$tm_all_font[] = ot_get_option( 'text_font', 'Open Sans');
	}
	$all_font=implode('|',$tm_all_font);

	wp_enqueue_style( 'google-font', 'http://fonts.googleapis.com/css?family='.$all_font );
	wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css');
	wp_enqueue_style( 'tooltipster', get_template_directory_uri() . '/css/tooltipster.css');
	wp_enqueue_style( 'style', get_stylesheet_directory_uri() . '/style.css');
	if(ot_get_option( 'flat-style')){
		wp_enqueue_style( 'flat-style', get_template_directory_uri() . '/css/flat-style.css');
	}
	wp_enqueue_style( 'font-awesome', get_template_directory_uri() .'/fonts/css/font-awesome.min.css');
	wp_enqueue_style( 'custom-css', get_template_directory_uri() . '/css/custom.css.php');
	if(ot_get_option( 'right_to_left', 0)){
		wp_enqueue_style( 'rtl', get_template_directory_uri() . '/css/rtl.css');
	}
	if(ot_get_option( 'responsive', 1)!=1){
		wp_enqueue_style( 'no-responsive', get_template_directory_uri() . '/css/no-responsive.css');
	}
	if(is_singular() ) wp_enqueue_script( 'comment-reply' );
	if(is_plugin_active( 'buddypress/bp-loader.php' )){
		wp_enqueue_style( 'truemag-bp', get_template_directory_uri() . '/css/tm-buddypress.css');
	}
	if(is_plugin_active( 'bbpress/bbpress.php' )){
		wp_enqueue_style( 'truemag-bb', get_template_directory_uri() . '/css/tm-bbpress.css');
	}
}
add_action( 'wp_enqueue_scripts', 'cactusthemes_scripts_styles' );

/**
 * Registers our main widget area and the front page widget areas.
 *
 * @since Twenty Twelve 1.0
 */
function cactusthemes_widgets_init() {
	$rtl = ot_get_option( 'righttoleft', 0);
	
	register_sidebar( array(
		'name' => __( 'Main Sidebar', 'cactusthemes' ),
		'id' => 'main_sidebar',
		'description' => __( 'Appears on posts and pages except the optional Front Page template, which has its own widgets', 'cactusthemes' ),
		'before_widget' => '<div id="%1$s" class="widget widget-border %2$s">',
		'after_widget' => '</div>',
		'before_title' => $rtl ? '<h2 class="widget-title maincolor2">' : '<h2 class="widget-title maincolor2">',
		'after_title' => $rtl ? '</h2>' : '</h2>',
	));
	
	register_sidebar( array(
		'name' => __( 'Home Sidebar', 'cactusthemes' ),
		'id' => 'home_sidebar',
		'description' => __('Sidebar in home page. If empty, main sidebar will be used', 'cactusthemes' ),
		'before_widget' => '<div id="%1$s" class="widget widget-border %2$s">',
		'after_widget' => '</div>',
		'before_title' => $rtl ? '<h2 class="widget-title maincolor2">' : '<h2 class="widget-title maincolor2">',
		'after_title' => $rtl ? '</h2>' : '</h2>',
	));
	
	register_sidebar( array(
		'name' => __( 'Main Top Sidebar', 'cactusthemes' ),
		'id' => 'maintop_sidebar',
		'description' => __( 'Sidebar in top of site, be used if there are no slider ', 'cactusthemes' ),
		'before_widget' => '<div id="%1$s" class="%2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h2 class="widget-title maincolor1">',
		'after_title' => '</h2>',
	));
	register_sidebar( array(
		'name' => __( 'Headline Sidebar', 'cactusthemes' ),
		'id' => 'headline_sidebar',
		'description' => __( '', 'cactusthemes' ),
		'before_widget' => '<div id="%1$s" class="headline-widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));
	register_sidebar( array(
		'name' => __( 'Pathway Sidebar', 'cactusthemes' ),
		'id' => 'pathway_sidebar',
		'description' => __( 'Replace Pathway (Breadcrumbs) with your widgets', 'cactusthemes' ),
		'before_widget' => '<div id="%1$s" class="pathway-widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));
	register_sidebar( array(
		'name' => __( 'Search Box Sidebar', 'cactusthemes' ),
		'id' => 'search_sidebar',
		'description' => __( '', 'cactusthemes' ),
		'before_widget' => '<div id="%1$s" class="heading-search-widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));
	register_sidebar( array(
		'name' => __( 'User Submit Video Sidebar', 'cactusthemes' ),
		'id' => 'user_submit_sidebar',
		'description' => __( 'Sidebar in popup User submit video', 'cactusthemes' ),
		'before_widget' => '<div id="%1$s" class="%2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h2 class="widget-title maincolor2">',
		'after_title' => '</h2>',
	));
    register_sidebar( array(
		'name' => __( 'Organiza tu Evento Sidebar', 'cactusthemes' ),
		'id' => 'submit_event_sidebar',
		'description' => __( 'Sidebar in popup User submit event', 'cactusthemes' ),
		'before_widget' => '<div id="%1$s" class="%2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h2 class="widget-title maincolor2">',
		'after_title' => '</h2>',
	));
	register_sidebar( array(
		'name' => __( 'Footer Sidebar', 'cactusthemes' ),
		'id' => 'footer_sidebar',
		'description' => __( '', 'cactusthemes' ),
		'before_widget' => '<div id="%1$s" class="widget col-md-3 col-sm-6 %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h2 class="widget-title maincolor1">',
		'after_title' => '</h2>',
	));
	register_sidebar( array(
		'name' => __( 'Footer 404 page Sidebar', 'cactusthemes' ),
		'id' => 'footer_404_sidebar',
		'description' => __( '', 'cactusthemes' ),
		'before_widget' => '<div id="%1$s" class="widget col-md-3 col-sm-6 %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h2 class="widget-title maincolor1">',
		'after_title' => '</h2>',
	));
	register_sidebar( array(
		'name' => __( 'Blog Sidebar', 'cactusthemes' ),
		'id' => 'blog_sidebar',
		'description' => __( 'Sidebar in blog, category (blog) page. If there is no widgets, Main Sidebar will be used ', 'cactusthemes' ),
		'before_widget' => '<div id="%1$s" class="widget widget-border %2$s">',
		'after_widget' => '</div>',
		'before_title' => $rtl ? '<h2 class="widget-title maincolor2">' : '<h2 class="widget-title maincolor2">',
		'after_title' => $rtl ? '</h2>' : '</h2>',
	));
	register_sidebar( array(
		'name' => __( 'Video listing Sidebar', 'cactusthemes' ),
		'id' => 'video_listing_sidebar',
		'description' => __( 'Sidebar in blog, category (video) page. If there is no widgets, Main Sidebar will be used ', 'cactusthemes' ),
		'before_widget' => '<div id="%1$s" class="widget widget-border %2$s">',
		'after_widget' => '</div>',
		'before_title' => $rtl ? '<h2 class="widget-title maincolor2">' : '<h2 class="widget-title maincolor2">',
		'after_title' => $rtl ? '</h2>' : '</h2>',
	));
	register_sidebar( array(
		'name' => __( 'Single Blog Sidebar', 'cactusthemes' ),
		'id' => 'single_blog_sidebar',
		'description' => __( 'Sidebar in single post page. If there is no widgets, Main Sidebar will be used ', 'cactusthemes' ),
		'before_widget' => '<div id="%1$s" class="widget widget-border %2$s">',
		'after_widget' => '</div>',
		'before_title' => $rtl ? '<h2 class="widget-title maincolor2">' : '<h2 class="widget-title maincolor2">',
		'after_title' => $rtl ? '</h2>' : '</h2>',
	));
	register_sidebar( array(
		'name' => __( 'Single Video Sidebar', 'cactusthemes' ),
		'id' => 'single_video_sidebar',
		'description' => __( 'Sidebar in single Video post page. If there is no widgets, Main Sidebar will be used ', 'cactusthemes' ),
		'before_widget' => '<div id="%1$s" class="widget widget-border %2$s">',
		'after_widget' => '</div>',
		'before_title' => $rtl ? '<h2 class="widget-title maincolor2">' : '<h2 class="widget-title maincolor2">',
		'after_title' => $rtl ? '</h2>' : '</h2>',
	));
	register_sidebar( array(
		'name' => __( 'Search page Sidebar', 'cactusthemes' ),
		'id' => 'search_page_sidebar',
		'description' => __( 'Appears on Search result page', 'cactusthemes' ),
		'before_widget' => '<div id="%1$s" class="widget widget-border %2$s">',
		'after_widget' => '</div>',
		'before_title' => $rtl ? '<h2 class="widget-title maincolor2">' : '<h2 class="widget-title maincolor2">',
		'after_title' => $rtl ? '</h2>' : '</h2>',
	));
	register_sidebar( array(
		'name' => __( 'Single Page Sidebar', 'cactusthemes' ),
		'id' => 'single_page_sidebar',
		'description' => __( 'Sidebar in single page. If there is no widgets, Main Sidebar will be used ', 'cactusthemes' ),
		'before_widget' => '<div id="%1$s" class="widget widget-border %2$s">',
		'after_widget' => '</div>',
		'before_title' => $rtl ? '<h2 class="widget-title maincolor2">' : '<h2 class="widget-title maincolor2">',
		'after_title' => $rtl ? '</h2>' : '</h2>',
	));
if ( is_plugin_active( 'buddypress/bp-loader.php' ) ) {
	//buddyPress
	register_sidebar( array(
		'name' => __( 'BuddyPress Sidebar', 'cactusthemes' ),
		'id' => 'bp_sidebar',
		'description' => __( 'Sidebar in BuddyPress Page.', 'cactusthemes' ),
		'before_widget' => '<div id="%1$s" class="widget widget-border %2$s">',
		'after_widget' => '</div>',
		'before_title' => $rtl ? '<h2 class="widget-title maincolor2">' : '<h2 class="widget-title maincolor2">',
		'after_title' => $rtl ? '</h2>' : '</h2>',
	));
	register_sidebar( array(
		'name' => __( 'BuddyPress Sitewide Activity Sidebar', 'cactusthemes' ),
		'id' => 'bp_activity_sidebar',
		'description' => __( 'Sidebar in BuddyPress Sitewide Activity Page. If there is no widgets, BuddyPress Sidebar will be used', 'cactusthemes' ),
		'before_widget' => '<div id="%1$s" class="widget widget-border %2$s">',
		'after_widget' => '</div>',
		'before_title' => $rtl ? '<h2 class="widget-title maincolor2">' : '<h2 class="widget-title maincolor2">',
		'after_title' => $rtl ? '</h2>' : '</h2>',
	));
	register_sidebar( array(
		'name' => __( 'BuddyPress Sitewide Members Sidebar', 'cactusthemes' ),
		'id' => 'bp_member_sidebar',
		'description' => __( 'Sidebar in BuddyPress Sitewide Member Page. If there is no widgets, BuddyPress Sidebar will be used', 'cactusthemes' ),
		'before_widget' => '<div id="%1$s" class="widget widget-border %2$s">',
		'after_widget' => '</div>',
		'before_title' => $rtl ? '<h2 class="widget-title maincolor2">' : '<h2 class="widget-title maincolor2">',
		'after_title' => $rtl ? '</h2>' : '</h2>',
	));
	register_sidebar( array(
		'name' => __( 'BuddyPress Sitewide Groups Sidebar', 'cactusthemes' ),
		'id' => 'bp_group_sidebar',
		'description' => __( 'Sidebar in BuddyPress Sitewide Groups Page. If there is no widgets, BuddyPress Sidebar will be used', 'cactusthemes' ),
		'before_widget' => '<div id="%1$s" class="widget widget-border %2$s">',
		'after_widget' => '</div>',
		'before_title' => $rtl ? '<h2 class="widget-title maincolor2">' : '<h2 class="widget-title maincolor2">',
		'after_title' => $rtl ? '</h2>' : '</h2>',
	));
	register_sidebar( array(
		'name' => __( 'BuddyPress Single Members Sidebar', 'cactusthemes' ),
		'id' => 'bp_single_member_sidebar',
		'description' => __( 'Sidebar in BuddyPress Single Member Page. If there is no widgets, BuddyPress Sidebar will be used', 'cactusthemes' ),
		'before_widget' => '<div id="%1$s" class="widget widget-border %2$s">',
		'after_widget' => '</div>',
		'before_title' => $rtl ? '<h2 class="widget-title maincolor2">' : '<h2 class="widget-title maincolor2">',
		'after_title' => $rtl ? '</h2>' : '</h2>',
	));
	register_sidebar( array(
		'name' => __( 'BuddyPress Single Groups Sidebar', 'cactusthemes' ),
		'id' => 'bp_single_group_sidebar',
		'description' => __( 'Sidebar in BuddyPress Single Groups Page. If there is no widgets, BuddyPress Sidebar will be used', 'cactusthemes' ),
		'before_widget' => '<div id="%1$s" class="widget widget-border %2$s">',
		'after_widget' => '</div>',
		'before_title' => $rtl ? '<h2 class="widget-title maincolor2">' : '<h2 class="widget-title maincolor2">',
		'after_title' => $rtl ? '</h2>' : '</h2>',
	));
	register_sidebar( array(
		'name' => __( 'BuddyPress Register Sidebar', 'cactusthemes' ),
		'id' => 'bp_register_sidebar',
		'description' => __( 'Sidebar in BuddyPress Register Page. If there is no widgets, BuddyPress Sidebar will be used', 'cactusthemes' ),
		'before_widget' => '<div id="%1$s" class="widget widget-border %2$s">',
		'after_widget' => '</div>',
		'before_title' => $rtl ? '<h2 class="widget-title maincolor2">' : '<h2 class="widget-title maincolor2">',
		'after_title' => $rtl ? '</h2>' : '</h2>',
	));
}
}
add_action( 'widgets_init', 'cactusthemes_widgets_init' );

add_image_size('thumb_139x89',139,89, true); //widget
add_image_size('thumb_365x235',365,235, true); //blog
add_image_size('thumb_196x126',196,126, true); //cat carousel, related
add_image_size('thumb_520x293',520,293, true); //big carousel 16:9
add_image_size('thumb_260x146',260,146, true); //metro carousel 16:9
add_image_size('thumb_356x200',356,200, true); //metro carousel 16:9 bigger
add_image_size('thumb_130x73',130,73, true); //mobile
add_image_size('thumb_748x421',748,421, true); //classy big
add_image_size('thumb_72x72',72,72, true); //classy thumb

// Hook widget 'SEARCH'
add_filter('get_search_form', 'cactus_search_form'); 
function cactus_search_form($text) {
	$text = str_replace('value=""', 'placeholder="'.__("SEARCH").'"', $text);
    return $text;
}

/* Display Facebook and Google Plus button */
function gp_social_share($post_ID){
if(ot_get_option('social_like',1)){	
    $nonce = wp_create_nonce("wti_like_post_vote_nonce");
    $ajax_like_link = admin_url('admin-ajax.php?action=wti_like_post_process_vote&task=like&post_id=' . $post_ID . '&nonce=' . $nonce);
    
?>
<div class="video-toolbar-item">
    <div id="fb-root"></div>
    <script>(function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/es_LA/sdk.js#xfbml=1&appId=<?php echo ot_get_option('facebook_appId'); ?>&version=v2.0";
        fjs.parentNode.insertBefore(js, fjs);
        
        window.fbAsyncInit = function()
        {
            FB.Event.subscribe('edge.create', function(url, html_element){
                var task = 'like';
                var post_id = jQuery(html_element).attr("data-post_id");
                var nonce = jQuery(html_element).attr("data-nonce");
                jQuery.ajax({
                    type : "post",
                    dataType : "json",
                    url : wtilp.ajax_url,
                    data : {action: "wti_like_post_process_vote", task : task, post_id : post_id, nonce: nonce},
                    success: function(response) {
                        
                    }
                });
            });

            FB.Event.subscribe('edge.remove', function(url, html_element){
                var task = 'unlike';
                var post_id = jQuery(html_element).attr("data-post_id");
                var nonce = jQuery(html_element).attr("data-nonce");
                jQuery.ajax({
                    type : "post",
                    dataType : "json",
                    url : wtilp.ajax_url,
                    data : {action: "wti_like_post_process_vote", task : task, post_id : post_id, nonce: nonce},
                    success: function(response) {
                        
                    }
                });
            });
        };
    }(document, 'script', 'facebook-jssdk'));</script>
    &nbsp;
    <div class="fb-like" data-href="<?php echo get_permalink($post_ID); ?>" data-layout="box_count" 
        data-show-faces="false" data-share="false" 
        data-task="like" data-post_id="<?php echo $post_ID; ?>" data-nonce="<?php echo $nonce; ?>" 
        style="border:none; overflow:hidden; width:120px;padding-top:5px;"></div>
    &nbsp;
</div>
<?php }
}

/* Display Icon Links to some social networks */
function tm_social_share(){ ?>
<div class="tm-social-share">
	<?php if(ot_get_option('share_facebook')){ ?>
	<a class="social-icon maincolor2 bordercolor2hover bgcolor2hover" title="Share on Facebook" href="#" target="_blank" rel="nofollow" onclick="window.open('https://www.facebook.com/sharer/sharer.php?u='+encodeURIComponent(location.href),'facebook-share-dialog','width=626,height=436');return false;"><i class="fa fa-facebook"></i></a>
    <?php } ?>
    <?php if(ot_get_option('share_twitter')){ ?>
    <a class="social-icon maincolor2 bordercolor2hover bgcolor2hover" href="#" title="Share on Twitter" rel="nofollow" target="_blank" onclick="window.open('http://twitter.com/share?text=<?php echo urlencode(get_the_title(get_the_ID())); ?>&url=<?php echo urlencode(get_permalink(get_the_ID())); ?>','twitter-share-dialog','width=626,height=436');return false;"><i class="fa fa-twitter"></i></a>
    <?php } ?>
    <?php if(ot_get_option('share_linkedin')){ ?>
    <a class="social-icon maincolor2 bordercolor2hover bgcolor2hover" href="#" title="Share on LinkedIn" rel="nofollow" target="_blank" onclick="window.open('http://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode(get_permalink(get_the_ID())); ?>&title=<?php echo urlencode(get_the_title(get_the_ID())); ?>&source=<?php echo urlencode(get_bloginfo('name')); ?>','linkedin-share-dialog','width=626,height=436');return false;"><i class="fa fa-linkedin"></i></a>
    <?php } ?>
    <?php if(ot_get_option('share_tumblr')){ ?>
    <a class="social-icon maincolor2 bordercolor2hover bgcolor2hover" href="#" title="Share on Tumblr" rel="nofollow" target="_blank" onclick="window.open('http://www.tumblr.com/share/link?url=<?php echo urlencode(get_permalink(get_the_ID())); ?>&name=<?php echo urlencode(get_the_title(get_the_ID())); ?>','tumblr-share-dialog','width=626,height=436');return false;"><i class="fa fa-tumblr"></i></a>
    <?php } ?>
    <?php if(ot_get_option('share_google-plus')){ ?>
    <a class="social-icon maincolor2 bordercolor2hover bgcolor2hover" href="#" title="Share on Google Plus" rel="nofollow" target="_blank" onclick="window.open('https://plus.google.com/share?url=<?php echo urlencode(get_permalink(get_the_ID())); ?>','googleplus-share-dialog','width=626,height=436');return false;"><i class="fa fa-google-plus"></i></a>
    <?php } ?>
    <?php if(ot_get_option('share_pinterest')){ ?>
    <a class="social-icon maincolor2 bordercolor2hover bgcolor2hover" href="#" title="Pin this" rel="nofollow" target="_blank" onclick="window.open('//pinterest.com/pin/create/button/?url=<?php echo urlencode(get_permalink(get_the_ID())) ?>&media=<?php echo urlencode(wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()))); ?>&description=<?php echo urlencode(get_the_title(get_the_ID())) ?>','pin-share-dialog','width=626,height=436');return false;"><i class="fa fa-pinterest"></i></a>
    <?php } ?>
    <?php if(ot_get_option('share_email')){ ?>
    <a class="social-icon maincolor2 bordercolor2hover bgcolor2hover" href="mailto:?subject=<?php echo get_the_title(get_the_ID()) ?>&body=<?php echo urlencode(get_permalink(get_the_ID())) ?>" title="Email this"><i class="fa fa-envelope"></i></a>
    <?php } ?>
</div>
<?php }

require_once 'inc/category-metadata.php';
require_once 'inc/google-adsense-responsive.php';

/*facebook comment*/
if(!function_exists('tm_update_fb_comment')){
	function tm_update_fb_comment(){
		if(is_plugin_active('facebook/facebook.php')&&get_option('facebook_comments_enabled')&&is_single()){
			global $post;
			//$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			if(class_exists('Facebook_Comments')){
				//$comment_count = Facebook_Comments::get_comments_number_filter(0,$post->ID);
				$comment_count = get_comments_number($post->ID);
			}else{
				$actual_link = get_permalink($post->ID);
				$fql  = "SELECT url, normalized_url, like_count, comment_count, ";
				$fql .= "total_count, commentsbox_count, comments_fbid FROM ";
				$fql .= "link_stat WHERE url = '".$actual_link."'";
				$apifql = "https://api.facebook.com/method/fql.query?format=json&query=".urlencode($fql);
				$json = file_get_contents($apifql);
				//print_r( json_decode($json));
				$link_fb_stat = json_decode($json);
				$comment_count = $link_fb_stat[0]->commentsbox_count?$link_fb_stat[0]->commentsbox_count:0;
			}
			update_post_meta($post->ID, 'custom_comment_count', $comment_count);
		}elseif(is_plugin_active('disqus-comment-system/disqus.php')&&is_single()){
			global $post;
			echo '<a href="'.get_permalink($post->ID).'#disqus_thread" id="disqus_count" class="hidden">comment_count</a>';
			?>
            <script type="text/javascript">
			/* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
			var disqus_shortname = '<?php echo get_option('disqus_forum_url','testtruemag') ?>'; // required: replace example with your forum shortname
			/* * * DON'T EDIT BELOW THIS LINE * * */
			(function () {
			var s = document.createElement('script'); s.async = true;
			s.type = 'text/javascript';
			s.src = 'http://' + disqus_shortname + '.disqus.com/count.js';
			(document.getElementsByTagName('HEAD')[0] || document.getElementsByTagName('BODY')[0]).appendChild(s);
			}());
			//get comments number
			jQuery(window).load(function(e) {
                var str = jQuery('#disqus_count').html();
				var pattern = /[0-9]+/;
				var matches = str.match(pattern);
				matches = (matches)?matches[0]:0;
				if(!isNaN(parseFloat(matches)) && isFinite(matches)){ //is numberic
					var param = {
						action: 'tm_disqus_update',
						post_id:<?php echo $post->ID ?>,
						count:matches,
					};
					jQuery.ajax({
						type: "GET",
						url: "<?php echo home_url('/'); ?>wp-admin/admin-ajax.php",
						dataType: 'html',
						data: (param),
						success: function(data){
							//
						}
					});
				}//if numberic
			});
			</script>
            <?php
		}
	}
}

add_action('wp_footer', 'tm_update_fb_comment', 100);
//ajax update disqus count
if(!function_exists('tm_disqus_update')){
	function tm_disqus_update(){
		if(isset($_GET['post_id'])){
			update_post_meta($_GET['post_id'], 'custom_comment_count', $_GET['count']?$_GET['count']:0);
		}
	}
}
add_action("wp_ajax_tm_disqus_update", "tm_disqus_update");
add_action("wp_ajax_nopriv_tm_disqus_update", "tm_disqus_update");

//hook for get disqus count
if(!function_exists('tm_get_disqus_count')){
	function tm_get_disqus_count($count, $post_id){
		if(is_plugin_active('disqus-comment-system/disqus.php')){
			$return = get_post_meta($post_id,'custom_comment_count',true);
			return $return?$return:0;
		}else{
			return $count;
		}
	}
}
add_filter( 'get_comments_number', 'tm_get_disqus_count', 10, 2 );

if(!function_exists('tm_breadcrumbs')){
	function tm_breadcrumbs(){
		/* === OPTIONS === */
		$text['home']     = __('Home','cactusthemes'); // text for the 'Home' link
		$text['category'] = '%s'; // text for a category page
		$text['search']   = __('Search Results for','cactusthemes').' "%s"'; // text for a search results page
		$text['tag']      = __('Tag','cactusthemes').' "%s"'; // text for a tag page
		$text['author']   = __('Author','cactusthemes').' %s'; // text for an author page
		$text['404']      = __('404','cactusthemes'); // text for the 404 page

		$show_current   = 1; // 1 - show current post/page/category title in breadcrumbs, 0 - don't show
		$show_on_home   = 1; // 1 - show breadcrumbs on the homepage, 0 - don't show
		$show_home_link = 1; // 1 - show the 'Home' link, 0 - don't show
		$show_title     = 1; // 1 - show the title for the links, 0 - don't show
		$delimiter      = ' \\ '; // delimiter between crumbs
		$before         = '<span class="current">'; // tag before the current crumb
		$after          = '</span>'; // tag after the current crumb
		/* === END OF OPTIONS === */

		global $post;
		$home_link    = home_url('/');
		$link_before  = '<span typeof="v:Breadcrumb">';
		$link_after   = '</span>';
		$link_attr    = ' rel="v:url" property="v:title"';
		$link         = $link_before . '<a' . $link_attr . ' href="%1$s">%2$s</a>' . $link_after;
		$parent_id    = $parent_id_2 = ($post) ? $post->post_parent : 0;
		$frontpage_id = get_option('page_on_front');

		if (is_front_page()) {

			if ($show_on_home == 1) echo '<div class="breadcrumbs"><a href="' . $home_link . '">' . $text['home'] . '</a></div>';

		}elseif(is_home()){
			$title = get_option('page_for_posts')?get_the_title(get_option('page_for_posts')):__('Blog','cactusthemes');
			echo '<div class="breadcrumbs"><a href="' . $home_link . '">' . $text['home'] . '</a> \ '.$title.'</div>';
		} else {

			echo '<div class="breadcrumbs" xmlns:v="http://rdf.data-vocabulary.org/#">';
			if ($show_home_link == 1) {
				echo '<a href="' . $home_link . '" rel="v:url" property="v:title">' . $text['home'] . '</a>';
				if ($frontpage_id == 0 || $parent_id != $frontpage_id) echo $delimiter;
			}

			if ( is_category() ) {
				$this_cat = get_category(get_query_var('cat'), false);
				if ($this_cat->parent != 0) {
					$cats = get_category_parents($this_cat->parent, TRUE, $delimiter);
					if ($show_current == 0) $cats = preg_replace("#^(.+)$delimiter$#", "$1", $cats);
					$cats = str_replace('<a', $link_before . '<a' . $link_attr, $cats);
					$cats = str_replace('</a>', '</a>' . $link_after, $cats);
					if ($show_title == 0) $cats = preg_replace('/ title="(.*?)"/', '', $cats);
					echo $cats;
				}
				if ($show_current == 1) echo $before . sprintf($text['category'], single_cat_title('', false)) . $after;

			} elseif ( is_search() ) {
				echo $before . sprintf($text['search'], get_search_query()) . $after;

			} elseif ( is_day() ) {
				echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $delimiter;
				echo sprintf($link, get_month_link(get_the_time('Y'),get_the_time('m')), get_the_time('F')) . $delimiter;
				echo $before . get_the_time('d') . $after;

			} elseif ( is_month() ) {
				echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $delimiter;
				echo $before . get_the_time('F') . $after;

			} elseif ( is_year() ) {
				echo $before . get_the_time('Y') . $after;

			} elseif ( is_single() && !is_attachment() ) {
				if ( get_post_type() != 'post' ) {
					$post_type = get_post_type_object(get_post_type());
					$slug = $post_type->rewrite;
					printf($link, $home_link . '/' . $slug['slug'] . '/', $post_type->labels->singular_name);
					if ($show_current == 1) echo $delimiter . $before . get_the_title() . $after;
				} else {
					$cat = get_the_category(); $cat = $cat[0];
					$cats = get_category_parents($cat, TRUE, $delimiter);
					if ($show_current == 0) $cats = preg_replace("#^(.+)$delimiter$#", "$1", $cats);
					$cats = str_replace('<a', $link_before . '<a' . $link_attr, $cats);
					$cats = str_replace('</a>', '</a>' . $link_after, $cats);
					if ($show_title == 0) $cats = preg_replace('/ title="(.*?)"/', '', $cats);
					echo $cats;
					if ($show_current == 1) echo $before . get_the_title() . $after;
				}

			} elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
				$post_type = get_post_type_object(get_post_type());
				echo $before . $post_type->labels->singular_name . $after;

			} elseif ( is_attachment() ) {
				$parent = get_post($parent_id);
				printf($link, get_permalink($parent), $parent->post_title);
				if ($show_current == 1) echo $delimiter . $before . get_the_title() . $after;

			} elseif ( is_page() && !$parent_id ) {
				if ($show_current == 1) echo $before . get_the_title() . $after;

			} elseif ( is_page() && $parent_id ) {
				if ($parent_id != $frontpage_id) {
					$breadcrumbs = array();
					while ($parent_id) {
						$page = get_page($parent_id);
						if ($parent_id != $frontpage_id) {
							$breadcrumbs[] = sprintf($link, get_permalink($page->ID), get_the_title($page->ID));
						}
						$parent_id = $page->post_parent;
					}
					$breadcrumbs = array_reverse($breadcrumbs);
					for ($i = 0; $i < count($breadcrumbs); $i++) {
						echo $breadcrumbs[$i];
						if ($i != count($breadcrumbs)-1) echo $delimiter;
					}
				}
				if ($show_current == 1) {
					if ($show_home_link == 1 || ($parent_id_2 != 0 && $parent_id_2 != $frontpage_id)) echo $delimiter;
					echo $before . get_the_title() . $after;
				}

			} elseif ( is_tag() ) {
				echo $before . sprintf($text['tag'], single_tag_title('', false)) . $after;

			} elseif ( is_author() ) {
				global $author;
				$userdata = get_userdata($author);
				echo $before . sprintf($text['author'], $userdata->display_name) . $after;

			} elseif ( is_404() ) {
				echo $before . $text['404'] . $after;
			}

			if ( get_query_var('paged') ) {
				if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() || is_home() ) echo ' (';
				echo __('Page','cactusthemes') . ' ' . get_query_var('paged');
				if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() || is_home() ) echo ')';
			}

			echo '</div><!-- .breadcrumbs -->';

		}
	} // end tm_breadcrumbs()
}

//custom login fail
add_action( 'wp_login_failed', 'tm_login_fail' );  // hook failed login
function tm_login_fail( $username ) {
	if($login_page = ot_get_option('login_page',false)){
		$referrer = $_SERVER['HTTP_REFERER'];  // where did the post submission come from?
		// if there's a valid referrer, and it's not the default log-in screen
		if ( !empty($referrer) && !strstr($referrer,'wp-login') && !strstr($referrer,'wp-admin') ) {
			wp_redirect(get_permalink($login_page).'?login=failed');  // let's append some information (login=failed) to the URL for the theme to use
			exit;
		}
	}
}
//redirect default login
add_action('init','tm_login_redirect');
function tm_login_redirect(){
	if($login_page = ot_get_option('login_page',false)){
	 global $pagenow;
	  if( 'wp-login.php' == $pagenow ) {
		if ( (isset($_POST['wp-submit']) && $_POST['log']!='' && $_POST['pwd']!='') ||   // in case of LOGIN
		  ( isset($_GET['action']) && $_GET['action']=='logout') ||   // in case of LOGOUT
		  ( isset($_GET['checkemail']) && $_GET['checkemail']=='confirm') ||   // in case of LOST PASSWORD
		  ( isset($_GET['action']) && $_GET['action']=='lostpassword') ||
		  ( isset($_GET['checkemail']) && $_GET['checkemail']=='registered') || // in case of REGISTER
		  isset($_GET['loginFacebook']) || isset($_GET['imadmin'])) return true;
		elseif(isset($_POST['wp-submit'])&&($_POST['log']=='' || $_POST['pwd']=='')){ wp_redirect(get_permalink($login_page) . '?login=failed' ); }
		else wp_redirect( get_permalink($login_page) ); // or wp_redirect(home_url('/login'));
		exit();
	  }
	}
}
//replace login page template
add_filter( 'page_template', 'tm_login_page_template' );
function tm_login_page_template( $page_template )
{
	if($login_page = ot_get_option('login_page',false)){
		if ( is_page( $login_page ) ) {
			$page_template = dirname( __FILE__ ) . '/page-templates/tpl-login.php';
		}
	}
    return $page_template;
}
function tm_author_avatar($ID = false, $size = 60){
	$user_avatar = false;
	if($ID == false){
		global $post;
		$ID = get_the_author_meta('ID');
		$email = get_the_author_meta('email');
	}
	if($user_avatar==false){
		global $_is_retina_;
		if($_is_retina_ && $size>120){
			$user_avatar = get_avatar( $email, $size, get_template_directory_uri() . '/images/avatar-3x.png' );
		}elseif($_is_retina_ || $size>120){ 
			$user_avatar = get_avatar( $email, $size, get_template_directory_uri() . '/images/avatar-2x.png' );
		}else{
			$user_avatar = get_avatar( $email, $size, get_template_directory_uri() . '/images/avatar.png' );
		}
	}
	return $user_avatar;
}

//contact form 7 hook
function tm_contactform7_hook(&$WPCF7_ContactForm) {
	if(ot_get_option('user_submit',1)){
		if($video_url = $WPCF7_ContactForm->posted_data['video-url']){
			$video_title = $WPCF7_ContactForm->posted_data['video-title'];
			$video_description = $WPCF7_ContactForm->posted_data['video-desciption'];
			$video_excerpt = $WPCF7_ContactForm->posted_data['video-excerpt'];
			$video_user = $WPCF7_ContactForm->posted_data['your-email'];
			$video_cat = $WPCF7_ContactForm->posted_data['cat'];
			$video_tag = $WPCF7_ContactForm->posted_data['tag'];
			$video_status = ot_get_option('user_submit_status','pending');
			$video_format = ot_get_option('user_submit_format','video');
			$video_post = array(
			  'post_content'   => $video_description,
			  'post_excerpt'   => $video_excerpt,
			  'post_name' 	   => sanitize_title($video_title), //slug
			  'post_title'     => $video_title,
			  'post_status'    => $video_status,
			  'post_category'  => $video_cat,
			  'tags_input'	   => $video_tag,
			  'post_type'      => 'post'
			);
			if($new_ID = wp_insert_post( $video_post, $wp_error )){
				add_post_meta( $new_ID, 'tm_video_url', $video_url );
				add_post_meta( $new_ID, 'tm_user_submit', $video_user );
				if(!ot_get_option('user_submit_fetch',0)){
					add_post_meta( $new_ID, 'fetch_info', 1);
				}
				set_post_format( $new_ID, $video_format );
				$video_post['ID'] = $new_ID;
				wp_update_post( $video_post );
			}
		}
	}
}
add_action("wpcf7_before_send_mail", "tm_contactform7_hook");

function tm_wpcf7_add_shortcode(){
	if(function_exists('wpcf7_add_shortcode')){
		wpcf7_add_shortcode('category', 'tm_catdropdown', true);
	}
}
function tm_catdropdown(){
	$cargs = array(
		'hide_empty'    => false, 
		'exclude'       => explode(",",ot_get_option('user_submit_cat_exclude',''))
	); 
	$cats = get_terms( 'category', $cargs );
	if($cats){
		$output = '<div class="row">';
		foreach ($cats as $acat){
			$output .= '<label class="col-md-4"><input type="checkbox" name="cat[]" value="'.$acat->term_id.'" /> '.$acat->name.'</label>';
		}
		$output .= '</div>';
	}
	return $output;
}

add_filter( 'pre_get_posts' , 'search_exc_cats' );
function search_exc_cats( $query ) {

    if( $query->is_admin )
        return $query;

    if( $query->is_search ) {
        $query->set( 'category__not_in' , array( 1 ) ); // Cat ID
    }
    return $query;
}

add_action( 'init', 'tm_wpcf7_add_shortcode' );

add_theme_support( 'custom-header' );
add_theme_support( 'custom-background' );
/* Functions, Hooks, Filters and Registers in Admin */
require_once 'inc/functions-admin.php';