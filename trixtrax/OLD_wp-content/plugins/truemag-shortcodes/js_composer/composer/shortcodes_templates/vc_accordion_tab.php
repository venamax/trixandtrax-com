<?php
$output = $title = '';

extract(shortcode_atts(array(
	'title' => __("Section", "js_composer"),
), $atts));

$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_accordion_section group', $this->settings['base']);
// Begin Edit By cactusthemes
global $id_too,$id_head;
global $toogle;
$id = rand();
$id_too = 'toog'.$id;
$id_head = 'head'.$id;
$output .= '<div class="panel panel-default collapsed"  data-toggle="collapse" data-parent="#accordion" href="#collapse'.$id.'">';
$output .= '<div class="panel-heading" >';
$output .= '<h4 class="panel-title"><a>';
$output .= $title;
$output .= '</a></h4>';
$output .= '</div>';
//$output .= '<div id="collapse'.$id.'" class="accordion-body collapse">';
$output .= '<div id="collapse'.$id.'" class="panel-collapse collapse">';
$output .= '<div class="panel-body">';
$output .= ($content=='' || $content==' ') ? __("Empty section. Edit page to add content here.", "js_composer") : "\n\t\t\t\t" . wpb_js_remove_wpautop($content);
$output .= '</div>';
$output .= '</div>';
$output .= '</div>';



// End edit


/*$output .= "\n\t\t\t" . '<div class="'.$css_class.'">';
    $output .= "\n\t\t\t\t" . '<h3 class="wpb_accordion_header ui-accordion-header"><a href="#'.sanitize_title($title).'">'.$title.'</a></h3>';
    $output .= "\n\t\t\t\t" . '<div class="wpb_accordion_content ui-accordion-content clearfix">';
        $output .= ($content=='' || $content==' ') ? __("Empty section. Edit page to add content here.", "js_composer") : "\n\t\t\t\t" . wpb_js_remove_wpautop($content);
        $output .= "\n\t\t\t\t" . '</div>';
    $output .= "\n\t\t\t" . '</div> ' . $this->endBlockComment('.wpb_accordion_section') . "\n";*/

echo $output;