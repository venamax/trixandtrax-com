<?php
/**
 * WPBakery Visual Composer shortcodes
 *
 * @package WPBakeryVisualComposer
 *
 */

class WPBakeryShortCode_Boxed_item extends WPBakeryShortCode_VC_Tab {
    protected  $predefined_atts = array(
        //'el_class' => '',
        //'width' => '',
        'heading' => '',
		'icon' => '',
    );
/*fff*/
    public function content( $atts, $content = null ) {
        $heading = '';
		$icon = '';
		$id = rand();
		$style = '';
		$border = '';
		$output = '';
		global $style,$border;
        extract(shortcode_atts($this->predefined_atts, $atts));
		global $number_item;
		$number_item=$number_item+1;
		//$output_css = '';
		$res_css = '';
		if($border=='0'){
		$res_css ='notop-bt';
		}
        $output .= "\n\t\t\t" . '<div class="boxed-item" id="boxed-'.$id.'"><div class="re_box '.$res_css.'">';
		if($style=='style-1'){
			$output .= "\n\t\t\t\t" . '<h2 class="heading"><i class="fa '.$icon.' icon_ct" ></i>'.$heading.'</h2>';
		}else
		{
			$output .= "\n\t\t\t\t" . '<div class="ic_st2"><span class="icon_ct"><i class="fa '.$icon.'" ></i></span></div><h2 class="heading">'.$heading.'</h2>';
		}
		$output .= "\n\t\t\t\t" . '<div class="contain"><div class="boxed-item-bg"></div>';
		
		$output .= "\n\t\t\t\t" . '<div class="contain-content">'.$content.'</div>';

		$output .= "\n\t\t\t\t" . '</div>';
		$output .= "\n\t\t\t" . '</div></div>';
        return $output;
    }
    public function contentAdmin($atts, $content = null) {
        $width = $el_class = $title = '';
        extract(shortcode_atts($this->predefined_atts, $atts));
        $output = '';

        $column_controls = $this->getColumnControls($this->settings('controls'));
        $column_controls_bottom =  $this->getColumnControls('add', 'bottom-controls');

        if ( $width == 'column_14' || $width == '1/4' ) {
            $width = array('span3');
        }
        else if ( $width == 'column_14-14-14-14' ) {
            $width = array('span3', 'span3', 'span3', 'span3');
        }

        else if ( $width == 'column_13' || $width == '1/3' ) {
            $width = array('span4');
        }
        else if ( $width == 'column_13-23' ) {
            $width = array('span4', 'span8');
        }
        else if ( $width == 'column_13-13-13' ) {
            $width = array('span4', 'span4', 'span4');
        }

        else if ( $width == 'column_12' || $width == '1/2' ) {
            $width = array('span6');
        }
        else if ( $width == 'column_12-12' ) {
            $width = array('span6', 'span6');
        }

        else if ( $width == 'column_23' || $width == '2/3' ) {
            $width = array('span8');
        }
        else if ( $width == 'column_34' || $width == '3/4' ) {
            $width = array('span9');
        }
        else if ( $width == 'column_16' || $width == '1/6' ) {
            $width = array('span2');
        }
        else {
            $width = array('');
        }


        for ( $i=0; $i < count($width); $i++ ) {
		$output .= '<div class="group wpb_sortable">';
			$output .= '<h3><span class="tab-label"><%= params.title %></span></h3>';
			$output .= '<div '.$this->mainHtmlBlockParams($width, 0).'>';
				$output .= str_replace("%column_size%", wpb_translateColumnWidthToFractional($width[0]), $column_controls);
				$output .= '<div class="wpb_element_wrapper">';
					$output .= '<div '.$this->containerHtmlBlockParams($width, 0).'>';
						$output .= '<%= params.text %>';
						$output .= do_shortcode( shortcode_unautop($content) );
						$output .= WPBakeryVisualComposer::getInstance()->getLayout()->getContainerHelper();
					$output .= '</div>';
					
					if ( isset($this->settings['params']) ) {
						$inner = '';
						foreach ($this->settings['params'] as $param) {
							$param_value = isset($$param['param_name']) ? $$param['param_name'] : '';
							if ( is_array($param_value)) {
								// Get first element from the array
								reset($param_value);
								$first_key = key($param_value);
								$param_value = $param_value[$first_key];
							}
							$inner .= $this->singleParamHtmlHolder($param, $param_value);
						}
						$output .= $inner;
					}
				$output .= '</div>';
				$output .= str_replace("%column_size%", wpb_translateColumnWidthToFractional($width[$i]), $column_controls_bottom);
			$output .= '</div>';
		$output .= '</div>';
		}	
        return $output;

    }

	public function mainHtmlBlockParams($width, $i) {
        return 'data-element_type="'.$this->settings["base"].'" class="' . $this->settings['class'] . ' wpb_'.$this->settings['base'].'"'.$this->customAdminBlockParams();
    }
    public function containerHtmlBlockParams($width, $i) {
        return 'class="wpb_column_container"';
    }
	
	public function contentAdmin_old($atts, $content = null) {
        $width = $el_class = $title = '';
        extract(shortcode_atts($this->predefined_atts, $atts));
        $output = '';
        $column_controls = $this->getColumnControls($this->settings('controls'));
        for ( $i=0; $i < count($width); $i++ ) {
            $output .= '<div class="group wpb_sortable">';
            $output .= '<div class="wpb_element_wrapper">';
            $output .= '<div class="vc_row-fluid wpb_row_container">';
            $output .= '<h3><a href="#">'.$title.'</a></h3>';
            $output .= '<div '.$this->customAdminBockParams().' data-element_type="'.$this->settings["base"].'" class=" wpb_'.$this->settings['base'].' wpb_sortable">';
            $output .= '<div class="wpb_element_wrapper">';
            $output .= '<div class="vc_row-fluid wpb_row_container">';
            $output .= do_shortcode( shortcode_unautop($content) );
            $output .= '</div>';
            if ( isset($this->settings['params']) ) {
                $inner = '';
                foreach ($this->settings['params'] as $param) {
                    $param_value = isset($$param['param_name']) ? $$param['param_name'] : '';
                    if ( is_array($param_value)) {
                        // Get first element from the array
                        reset($param_value);
                        $first_key = key($param_value);
                        $param_value = $param_value[$first_key];
                    }
                    $inner .= $this->singleParamHtmlHolder($param, $param_value);
                }
                $output .= $inner;
            }
            $output .= '</div>';
            $output .= '</div>';
            $output .= '</div>';
            $output .= '</div>';
            $output .= '</div>';
        }

        return $output;
    }

    protected function outputTitle($title) {
        return  '';
    }

    public function customAdminBlockParams() {
        return '';
    }

}
wpb_map( array(
    "name"		=> __("Boxed item", "cactusthemes"),
    "base"		=> "boxed_item",
    "class"		=> "wpb_vc_accordion_tab",
    "icon"      => "",
    "wrapper_class" => "",
    "controls"	=> "full",
    "content_element" => false,
    "params"	=> array(
        array(
            "type" => "textfield",
            "heading" => __("Heading", "cactusthemes"),
            "param_name" => "heading",
            "value" => "",
            "description" => '',
        ),
        array(
            "type" => "textfield",
            "heading" => __("Icon", "cactusthemes"),
            "param_name" => "icon",
            "value" => "",
            "description" => __("Name Font-Awesome . Ex:fa-random ")
        ),
        array(
            "type" => "textarea_html",
            "holder" => "div",
            "class" => "",
            "heading" => __("Content", "cactusthemes"),
            "param_name" => "content",
            "value" => '',
            "description" => '',
        ),

    ),
	'js_view'=>'VcCarouselItemView'
) );

class WPBakeryShortCode_boxed extends WPBakeryShortCode {

    public function __construct($settings) {
        parent::__construct($settings);
        // WPBakeryVisualComposer::getInstance()->addShortCode( array( 'base' => 'vc_accordion_tab' ) );
    }

    protected function content( $atts, $content = null ) {
        wp_enqueue_style( 'ui-custom-theme' );
        wp_enqueue_script('jquery-ui-accordion');
        $heading = $style = $border = $interval = $width = $el_position = $animation_class = $el_class = '';
        //
		global $number_item,$sp,$style,$border;
        extract(shortcode_atts(array(
            'heading' => '',
			'style' => '',
			'border' => '',
            'interval' => 0,
            'width' => '1/1',
            'el_position' => '',
            'el_class' => '',
			'animation' => '',
			
        ), $atts));
        $output = '';
		$title = '';
		$id = 'box-'.rand();
		if(class_exists('Mobile_Detect')){
		$detect = new Mobile_Detect;
			$_device_ = $detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'mobile') : 'pc';
			if(isset($atts['animation'])){
			$animation_class = ($atts['animation']&&$_device_=='pc')?'wpb_'.$atts['animation'].' wpb_animate_when_almost_visible':'';
			}
		}else{
			if(isset($atts['animation'])){
			$animation_class = $atts['animation']?'wpb_'.$atts['animation'].' wpb_animate_when_almost_visible':'';
			}
		}
		
        $el_class = $this->getExtraClass($el_class);
		wpb_js_remove_wpautop($content);
		if($number_item==1){$w_col='item_1';}
		else if($number_item==2){$w_col='item_2';}
		else if($number_item==3){$w_col='item_3';}
		else if($number_item==4){$w_col='item_4';}
		else if($number_item==5){$w_col='item_5';}
		else if($number_item==6){$w_col='item_6';}

		$number_item=0;
        $width = '';//wpb_translateColumnWidthToSpan($width);
        $output .= "\n\t\t".'<div class="boxedicon">';
		if($border=='0'){
		$output_css ='#'.$id.'{ border:0; padding-bottom:0} ';
		$output .= "\n\t\t" .'<style type="text/css" scoped="scoped"> '.$output_css.' </style>';
		}

		$output .= "\n\t\t" . '<div id="'.$id.'" class="boxed-icon '.$style.' '.$w_col.' '.$animation_class.' ">';
        //$output .= ($title != '' ) ? "\n\t\t\t".'<h2 class="wpb_heading wpb_accordion_heading">'.$title.'</h2>' : '';
        $output .= wpb_widget_title(array('title' => $title, 'extraclass' => 'wpb_accordion_heading'));

        $output .= "\n\t\t\t".wpb_js_remove_wpautop($content);
		//echo $number_item;
		//$w_item=(100/$number_item);
		$output .= "\n\t\t".'</div> ';
		
        $output .= "\n\t\t".'</div><div class="clear"><!-- --></div>';
		$number_item=0;
        $output = $this->startRow($el_position) . $output . $this->endRow($el_position);
        return $output;

    }

    public function contentAdmin( $atts, $content ) {
        $width = $custom_markup = '';
        $shortcode_attributes = array('width' => '1/1');
        foreach ( $this->settings['params'] as $param ) {
            if ( $param['param_name'] != 'content' ) {
                if ( is_string($param['value']) ) {
                    $shortcode_attributes[$param['param_name']] = __($param['value'], "js_composer");
                } else {
                    $shortcode_attributes[$param['param_name']] = $param['value'];
                }
            } else if ( $param['param_name'] == 'content' && $content == NULL ) {
                $content = __($param['value'], "js_composer");
            }
        }
        extract(shortcode_atts(
            $shortcode_attributes
            , $atts));

        $output = '';

        $elem = $this->getElementHolder($width);

        $iner = '';
        foreach ($this->settings['params'] as $param) {
            $param_value = '';
            $param_value = $$param['param_name'];
            if ( is_array($param_value)) {
                // Get first element from the array
                reset($param_value);
                $first_key = key($param_value);
                $param_value = $param_value[$first_key];
            }
            $iner .= $this->singleParamHtmlHolder($param, $param_value);
        }
        //$elem = str_ireplace('%wpb_element_content%', $iner, $elem);
        $tmp = '';
        //$template = '<div class="wpb_template">'.do_shortcode('[boxed_item title="New boxed item"][/boxed_item]').'</div>';

        if ( isset($this->settings["custom_markup"]) && $this->settings["custom_markup"] != '' ) {
			
            if ( $content != '' ) {
                $custom_markup = str_ireplace("%content%", $tmp.$content, $this->settings["custom_markup"]);
            } else if ( $content == '' && isset($this->settings["default_content_in_template"]) && $this->settings["default_content_in_template"] != '' ) {
                $custom_markup = str_ireplace("%content%", $this->settings["default_content_in_template"], $this->settings["custom_markup"]);
            } else {
                $custom_markup =  str_ireplace("%content%", '', $this->settings["custom_markup"]);
            }
            //$output .= do_shortcode($this->settings["custom_markup"]);
            $inner .= do_shortcode($custom_markup);
        }
        $elem = str_ireplace('%wpb_element_content%', $inner, $elem);
        $output = $elem;

        return $output;
    }
}
wpb_map( array(
    "name"		=> __("Icon Box", "cactusthemes"),
    "base"		=> "boxed",
    "controls"	=> "full",
    "show_settings_on_create" => false,
 	"is_container" => true,
    "class"		=> "wpb_vc_accordion vc_not_inner_content wpb_container_block",
	"category"  => __('Content', "cactusthemes"),
//	"wrapper_class" => "clearfix",
    "params"	=> array(
        array(
            "type" => "dropdown",
            "heading" => __("Style", "cactusthemes"),
            "param_name" => "style",
            "value" => array(
			__("Icon in Heading", 'castusthemes') =>"style-1",
			__("Icon Centered", 'castusthemes') =>"style-2"),
            "description" => __("", "cactusthemes")
        ),
        array(
            "type" => "dropdown",
            "heading" => __("Border", "cactusthemes"),
            "param_name" => "border",
            "value" => array(
			__("Yes", 'castusthemes') =>"1",
			__("No", 'castusthemes') =>"0"),
            "description" => __("", "cactusthemes")
        ),

		array(		
			"type" => "dropdown",
			"holder" => "div",
			"class" => "",
			"heading" => __("CSS Animation", 'cactusthemes'),
			"param_name" => "animation",
			"value" => array(
				__("No", 'cactusthemes') => '',
				__("Top to bottom", 'cactusthemes') => 'top-to-bottom',
				__("Bottom to top", 'cactusthemes') => 'bottom-to-top',
				__("Left to right", 'cactusthemes') => 'left-to-right',
				__("Right to left", 'cactusthemes') => 'right-to-left',
				__("Appear from center", 'cactusthemes') => 'appear',
			),
			"description" => ''
		),

    ),
    "custom_markup" => '

	<div class="wpb_accordion_holder wpb_holder clearfix vc_container_for_children">
		%content%
	</div>
    <div class="tab_controls">
		<button class="add_tab" title="'.__("Add boxed section", "cactusthemes").'">'.__("Add boxed section", "cactusthemes").'</button>
  </div>',
    'default_content' => '
     [boxed_item title="Boxed Item 1"][/boxed_item]
    ',
	'js_view' => 'VcBoxedView'
) );



