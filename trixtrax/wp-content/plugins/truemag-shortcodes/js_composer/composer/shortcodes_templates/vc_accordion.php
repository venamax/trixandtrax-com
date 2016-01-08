<?php
wp_enqueue_script('jquery-ui-accordion');
$output = $title = $expand_one = $interval = $el_class =  $collapsible = $active_tab = '';
//
extract(shortcode_atts(array(
    'title' => '',
	'expand_one' => '',
	'type' => '',
    'interval' => 0,
    'el_class' => '',
    'collapsible' => 'no',
    'active_tab' => '1'
), $atts));

$el_class = $this->getExtraClass($el_class);
$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_accordion wpb_content_element '.$el_class.' not-column-inherit', $this->settings['base']);
// Begin Edit By cactusthemes
global $toogle;
$toogle = $atts['type'];
$id = rand();
$id_accordion = '';
$id_toggle = '';
$id_accordion = 'accordion'.$id;
//if($atts['type'] == 'Accordion'){
//	$id_accordion = 'accordion'.$id;
if($expand_one=='yes'){
	$output .= '<script type="text/javascript">
				jQuery(document).ready(function(e) {
					  jQuery(".'.$id_accordion.' .panel-collapse ").first().addClass("in");
					  jQuery(".'.$id_accordion.' .panel").first().removeClass("collapsed");
				});
	
	</script>';
}
//}
$class_tog='';

//$output .= '<div class="panel-group" id="accordion"> ';
if($toogle=='Accordion'){
$output .= '<div class="panel-group '.$id_accordion.'" id="accordion">';
}else
{
$output .= '<div class="panel-group '.$id_accordion.'" id="accordion">';	
}
$output .= "\n\t\t\t".wpb_js_remove_wpautop($content);
$output .= '</div>';
// End edit

/*$output .= "\n\t".'<div class="'.$css_class.'" data-collapsible='.$collapsible.' data-active-tab="'.$active_tab.'">'; //data-interval="'.$interval.'"
$output .= "\n\t\t".'<div class="wpb_wrapper wpb_accordion_wrapper ui-accordion">';
$output .= wpb_widget_title(array('title' => $title, 'extraclass' => 'wpb_accordion_heading'));

$output .= "\n\t\t\t".wpb_js_remove_wpautop($content);
$output .= "\n\t\t".'</div> '.$this->endBlockComment('.wpb_wrapper');
$output .= "\n\t".'</div> '.$this->endBlockComment('.wpb_accordion');*/

echo $output;