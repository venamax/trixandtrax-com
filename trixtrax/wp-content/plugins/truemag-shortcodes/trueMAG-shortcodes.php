<?php
   /*
   Plugin Name: TrueMAG - Shortcodes
   Plugin URI: http://www.cactusthemes.com
   Description: TrueMAG - Shortcodes
   Version: 2.8.2
   Author: Cactusthemes
   Author URI: http://www.cactusthemes.com
   License: GPL2
   */
if ( ! defined( 'TM_SHORTCODE_BASE_FILE' ) )
    define( 'TM_SHORTCODE_BASE_FILE', __FILE__ );
if ( ! defined( 'TM_SHORTCODE_BASE_DIR' ) )
    define( 'TM_SHORTCODE_BASE_DIR', dirname( TM_SHORTCODE_BASE_FILE ) );
if ( ! defined( 'TM_SHORTCODE_PLUGIN_URL' ) )
    define( 'TM_SHORTCODE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
	
/**
 * Integrate Visual Composer
 */
 $dir = dirname(__FILE__) . '';

 
$composer_settings = Array(
    'APP_ROOT'      => $dir . '/js_composer',
    'WP_ROOT'       => dirname( dirname( dirname( dirname($dir ) ) ) ). '/',
    'APP_DIR'       => basename( $dir ) . '/',
    'CONFIG'        => $dir . '/js_composer/config/',
    'ASSETS_DIR'    => 'js_composer/assets/',
    'COMPOSER'      => $dir . '/js_composer/composer/',
    'COMPOSER_LIB'  => $dir . '/js_composer/composer/lib/',
    'SHORTCODES_LIB'  => $dir . '/js_composer/composer/lib/shortcodes/',
 
    /* for which content types Visual Composer should be enabled by default */
    'default_post_types' => Array('page','post','movie')
);

require_once('js_composer/js_composer.php');
 
$wpVC_setup->init($composer_settings);

/* ================================================================
 *
 * 
 * Class to register shortcode with TinyMCE editor
 *
 * Add to button to tinyMCE editor
 *
 */
class CactusThemeShortcodes{
	
	function __construct()
	{
		add_action('init',array(&$this, 'init'));
	}
	
	function init(){		
		if(is_admin()){
			// CSS for button styling
			wp_enqueue_style("ct_shortcode_admin_style", TM_SHORTCODE_PLUGIN_URL . '/shortcodes/shortcodes.css');
		}

		if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') ) {
	    	return;
		}
	 
		if ( get_user_option('rich_editing') == 'true' ) {
			add_filter( 'mce_external_plugins', array(&$this, 'regplugins'));
			add_filter( 'mce_buttons_3', array(&$this, 'regbtns') );
			
			// remove a button. Used to remove a button created by another plugin
			remove_filter('mce_buttons_3', array(&$this, 'remobtns'));
		}
	}
	
	function remobtns($buttons){
		// add a button to remove
		// array_push($buttons, 'ct_shortcode_collapse');
		return $buttons;	
	}
	
	function regbtns($buttons)
	{
		array_push($buttons, 'ct_alert');
		array_push($buttons, 'ct_checklist');
		array_push($buttons, 'shortcode_testimonial');
		array_push($buttons, 'ct_dropcap');
		array_push($buttons, 'shortcode_button');
		array_push($buttons, 'shortcode_tooltip');
		array_push($buttons, 'shortcode_carousel');
		array_push($buttons, 'shortcode_headline');	
		array_push($buttons, 'shortcode_heading');		
		array_push($buttons, 'shortcode_padding');
		array_push($buttons, 'shortcode_smartcontent');	
		array_push($buttons, 'shortcode_featuredcontent');	
		array_push($buttons, 'shortcode_member');	
		array_push($buttons, 'ct_shortcode_compare_table');
		array_push($buttons, 'shortcode_videoscarousel');		
		return $buttons;
	}
	
	function regplugins($plgs)
	{
		$plgs['ct_alert'] = TM_SHORTCODE_PLUGIN_URL . 'shortcodes/js/alert.js';
		$plgs['ct_checklist'] = TM_SHORTCODE_PLUGIN_URL . 'shortcodes/js/checklist.js';
		$plgs['shortcode_testimonial'] = TM_SHORTCODE_PLUGIN_URL . 'shortcodes/js/testimonial.js';
		$plgs['ct_dropcap'] = TM_SHORTCODE_PLUGIN_URL . 'shortcodes/js/dropcap.js';
		$plgs['shortcode_button'] = TM_SHORTCODE_PLUGIN_URL . 'shortcodes/js/button.js';
		$plgs['shortcode_tooltip'] = TM_SHORTCODE_PLUGIN_URL . 'shortcodes/js/tooltip.js';		
		$plgs['shortcode_carousel'] = TM_SHORTCODE_PLUGIN_URL . 'shortcodes/js/carousel.js';
		$plgs['shortcode_headline'] = TM_SHORTCODE_PLUGIN_URL . 'shortcodes/js/headline.js';
		$plgs['shortcode_heading'] = TM_SHORTCODE_PLUGIN_URL . 'shortcodes/js/heading.js';
		$plgs['shortcode_padding'] = TM_SHORTCODE_PLUGIN_URL . 'shortcodes/js/padding.js';
		$plgs['shortcode_smartcontent'] = TM_SHORTCODE_PLUGIN_URL . 'shortcodes/js/smart-content.js';
		$plgs['shortcode_featuredcontent'] = TM_SHORTCODE_PLUGIN_URL . 'shortcodes/js/featured-content.js';
		$plgs['shortcode_member'] = TM_SHORTCODE_PLUGIN_URL . 'shortcodes/js/member.js';
		$plgs['ct_shortcode_compare_table'] = TM_SHORTCODE_PLUGIN_URL . 'shortcodes/js/pricingtables.js';
		$plgs['shortcode_videoscarousel'] = TM_SHORTCODE_PLUGIN_URL . 'shortcodes/js/video-carousel.js';
		return $plgs;
	}
}

$ctshortcode = new CactusThemeShortcodes();
include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); //for check plugin status
// Register element with visual composer and do shortcode

include('shortcodes/alert.php');
include('shortcodes/checklist.php');
include('shortcodes/member.php');
include('shortcodes/boxedicon.php');
include('shortcodes/tooltip.php');
include('shortcodes/headline.php');
include('shortcodes/button.php');
include('shortcodes/carousel.php');
include('shortcodes/dropcap.php');
include('shortcodes/padding.php');
include('shortcodes/heading.php');
include('shortcodes/smart-content.php');
include('shortcodes/featured-content.php');
include('shortcodes/testimonial.php');
include('shortcodes/pricingtables.php');
include('shortcodes/amazing.php');
include('shortcodes/video-slider.php');
//include('shortcodes/related-format.php');

//function
if(!function_exists('hex2rgb')){
	function hex2rgb($hex) {
	   $hex = str_replace("#", "", $hex);
	
	   if(strlen($hex) == 3) {
		  $r = hexdec(substr($hex,0,1).substr($hex,0,1));
		  $g = hexdec(substr($hex,1,1).substr($hex,1,1));
		  $b = hexdec(substr($hex,2,1).substr($hex,2,1));
	   } else {
		  $r = hexdec(substr($hex,0,2));
		  $g = hexdec(substr($hex,2,2));
		  $b = hexdec(substr($hex,4,2));
	   }
	   $rgb = array($r, $g, $b);
	   //return implode(",", $rgb); // returns the rgb values separated by commas
	   return $rgb; // returns an array with the rgb values
	}
}