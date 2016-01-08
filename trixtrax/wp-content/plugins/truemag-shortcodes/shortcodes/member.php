<?php
/* Register shortcode with Visual Composer */
wpb_map( array(
   "name" => __("Member"),
   "base" => "member",
   "class" => "",
   "controls" => "full",
   "category" => __('Content'),
   "params" => array(
	  array(
         "type" => "textfield",
         "holder" => "div",
         "class" => "",
         "heading" => __("ID Member"),
         "param_name" => "id",
         "value" => '',
         "description" => '',
      ),
   )
));