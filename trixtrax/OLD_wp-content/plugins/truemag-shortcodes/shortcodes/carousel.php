<?php
/**
 * WPBakery Visual Composer shortcodes
 *
 * @package WPBakeryVisualComposer
 *
 */

class WPBakeryShortCode_Carousel_item extends WPBakeryShortCode_VC_Tab {
    protected  $predefined_atts = array(
        //'el_class' => '',
        //'width' => '',
        //'title' => '',
		
    );
    public function content( $atts, $content = null ) {
		$img = wpb_getImageBySize(array( 'attach_id' => preg_replace('/[^\d]/', '', $atts['avatar']), 'thumb_size' => array(60,60) ));
		//print_r ($img);
		$bg = ($img['thumbnail']);
		wp_enqueue_script( 'jquery-isotope');
		//$el_class = $this->getExtraClass($el_class);
		$output = '';
		$output .= '<div class="item-testi">';
		$output .= '<div class="car-content">';
		$output .=  $content ;
		$output .= '<div class="tt-tooltip"><!----></div>';
		$output .= '</div>';
		$output .= '</div>';
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
    "name"		=> __("Carousel item", "cactusthemes"),
    "base"		=> "Carousel_item",
    "class"		=> "wpb_vc_accordion_tab",
    "icon"      => "",
    "wrapper_class" => "",
    "controls"	=> "full",
    "content_element" => false,
    "params"	=> array(
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

class WPBakeryShortCode_Carousel extends WPBakeryShortCode {

    public function __construct($settings) {
        parent::__construct($settings);
        // WPBakeryVisualComposer::getInstance()->addShortCode( array( 'base' => 'vc_accordion_tab' ) );
    }

    protected function content( $atts, $content = null ) {
        wp_enqueue_style( 'ui-custom-theme' );
        wp_enqueue_script('jquery-ui-accordion');
        $title = $interval = $width = $el_position = $el_class = $animation_class = '';
        //
        extract(shortcode_atts(array(
            'title' => '',
            'interval' => 0,
            'width' => '1/1',
            'el_position' => '',
            'el_class' => '',
			'animation' => '',
        ), $atts));
		$id = rand();
        $output = '';
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
        str_replace("[Carousel_item","",$content,$i);
		$output .= "\n\t".'<div class="is-carousel simple-carousel testimonial car-style" id="post-gallery'.$id.'">';
		$output .= "\n\t\t".'<div class="simple-carousel-content carousel-content">';
		$output .= "\n\t\t\t".wpb_js_remove_wpautop($content);
		$output .= "\n\t\t".'</div>';
		$output .= "\n\t\t".'<div class="carousel-pagination"></div>';
		$output .= "\n\t".'</div>';
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
       // $template = '<div class="wpb_template">'.do_shortcode('[Carousel_item name="New Section"][/Carousel_item]').'</div>';

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
    "name"		=> __("Carousel", "cactusthemes"),
    "base"		=> "Carousel",
    "controls"	=> "full",
    "show_settings_on_create" => false,
	"is_container" => true,
    "class"		=> "wpb_vc_accordion vc_not_inner_content wpb_container_block",
	//"icon"		=> "icon-wpb-ui-Carousel",
	"category"  => __('Content', "cactusthemes"),
//	"wrapper_class" => "clearfix",
    "params"	=> array(
        array(
            "type" => "textfield",
            "heading" => __("Name", "cactusthemes"),
            "param_name" => "name",
            "value" => "",
            "description" => '',
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
		<button class="add_tab" title="'.__("Add Carousel section", "cactusthemes").'">'.__("Add Carousel section", "cactusthemes").'</button>
  </div>',
    'default_content' => '
     [Carousel_item title="Carousel title 1"][/Carousel_item]
    ',
	'js_view' => 'VcCarouselView'
) );

