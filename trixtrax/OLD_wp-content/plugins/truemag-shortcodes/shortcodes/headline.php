<?php
function headline_func($atts, $content){
	wp_enqueue_script( 'js-scrollbox');
	$title = isset($atts['title']) ? $atts['title'] : '';
	$number = isset($atts['number']) ? $atts['number'] : -1;
	$cat = isset($atts['cat']) ? $atts['cat'] : '';
	$sort_by = isset($atts['sortby']) ? $atts['sortby'] : '';
	$icon = isset($atts['icon']) ? $atts['icon'] : '';
	$link = isset($atts['link']) ? $atts['link'] : '';
//	$color = isset($atts['color']) ? $atts['color'] : '';
//	$coloricon = isset($atts['coloricon']) ? $atts['coloricon'] : '';	
//	$bg_color = isset($atts['background']) ? $atts['background'] : '';
	$posttypes = isset($atts['posttypes']) ? $atts['posttypes'] : '';
//	if(class_exists('Mobile_Detect')){
//		$detect = new Mobile_Detect;
//		$_device_ = $detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'mobile') : 'pc';
//		$animation_class = ($atts['animation']&&$_device_=='pc')?'wpb_'.$atts['animation'].' wpb_animate_when_almost_visible':'';
//	}else{
//		$animation_class = $atts['animation']?'wpb_'.$atts['animation'].' wpb_animate_when_almost_visible':'';
//	}
	$args = array(
		'post_type' => 'post',
		'posts_per_page' => $number,
		'orderby' => $sort_by,
		'post_status' => 'publish',
		
	);
	$id = rand();
		if(isset($cat)){
			$cats = explode(",",$cat);
			if(is_numeric($cats[0])){
				$args['cat'] = $cat;
			}else{			 
				$args['category_name'] = $cat;
				//$args['showposts'] = $number;
			}
		}
	$html ='';
	if($icon==''){$html.='<style type="text/css" scoped="scoped">#head'.$id.' .scroll-text ul{ padding-left:0}</style>';}
	$the_query = new WP_Query( $args );
	$html .= '
				<div class="headline"  id="head'.$id.'" >
				<div class="row-fluid">
					<div class="htitle">
					<span class="hicon"><i class="fa '.$icon.'"></i>';
					if($title!=''){
						$html .= '<span class="first-tex">'.$title.'</span>';
					}
					$html .= '</span>
					<div id="tm-'.$id.'"  class="scroll-text" >
						<ul>
						
	';
	
				if($the_query->have_posts()){
					while($the_query->have_posts()){ $the_query->the_post();
					if($link!="no"){
					$html .= '
									<li><a href='.get_permalink().'>'.get_the_title().'</a></li>
					';
					}else
					{
						$html .= '
						<li><p>'.get_the_title().'</p></li>
						';
					}
					}
				}
				wp_reset_query();
				$html .= '
						
						</ul>
						</div>
					</div>
				</div>
			</div>
	';
	global $headline_js;
	$headline_js[]='
		<script>
			jQuery(document).ready(function(e) {
				jQuery(function () {
				  jQuery("#tm-'.$id.'").scrollbox({
					speed: 50
				  });
				});
			});
		</script>
	';
	return $html;

}
add_shortcode( 'headline', 'headline_func' );

function headline_js(){
	global $headline_js;
	if($headline_js) foreach($headline_js as $aheadline_js){echo $aheadline_js;}
}
add_action('wp_footer', 'headline_js', 100);

/* Register shortcode with Visual Composer */
wpb_map( array(
   "name" => __("Headline", 'castusthemes'),
   "base" => "headline",
   "class" => "",
   "controls" => "full",
   "category" => __('Content'),
   "params" => array(
   	  array(
         "type" => "textfield",
         "holder" => "div",
         "class" => "",
         "heading" => __("Title", 'castusthemes'),
         "param_name" => "title",
         "value" => '',
         "description" => ''
      ),	
	  array(
         "type" => "textfield",
         "holder" => "div",
         "class" => "",
         "heading" => __("Number of post to show", 'castusthemes'),
         "param_name" => "number",
         "value" => '',
         "description" => ''
      ),

      array(
         "type" => "textfield",
         "holder" => "div",
         "class" => "",
         "heading" => __("Categories ID/ Slug", 'castusthemes'),
         "param_name" => "cat",
         "value" => '',
         "description" => '',
      ),
      array(
         "type" => "dropdown",
         "holder" => "div",
         "class" => "",
         "heading" => __("Link", 'castusthemes'),
         "param_name" => "link",
		 "value" => array(__('Yes') => 'yes', __('No') => 'no'),
         "description" => __("Link for title", 'castusthemes'),
      ),

	  array(
         "type" => "dropdown",
         "holder" => "div",
         "class" => "",
         "heading" => __("Sort by", 'castusthemes'),
         "param_name" => "sortby",
         "value" => array(__('Random') => 'rand', __('Title') => 'title', __('Date') => 'date'),
         "description" => ''
      ),
	  array(
         "type" => "textfield",
         "holder" => "div",
         "class" => "",
         "heading" => __("Icon", 'castusthemes'),
         "param_name" => "icon",
         "value" => '',
         "description" => __("Name Font-Awesome. Ex: fa-bullhorn", 'castusthemes'),
      ),
//	  array(		
//		 "type" => "dropdown",
//		 "holder" => "div",
//		 "class" => "",
//		 "heading" => __("CSS Animation", 'cactusthemes'),
//		 "param_name" => "animation",
//		 "value" => array(
//			__("No", 'cactusthemes') => '',
//			__("Top to bottom", 'cactusthemes') => 'top-to-bottom',
//			__("Bottom to top", 'cactusthemes') => 'bottom-to-top',
//			__("Left to right", 'cactusthemes') => 'left-to-right',
//			__("Right to left", 'cactusthemes') => 'right-to-left',
//			__("Appear from center", 'cactusthemes') => 'appear',
//		 ),
//		 "description" => ''
//	  ),

   )
) );