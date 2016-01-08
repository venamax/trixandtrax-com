// JavaScript Document
jQuery(document).ready(function(e) {
	jQuery(document).on('click','[id^=newcolorpicker]',function(){
		parent_ID = jQuery(this).parent().attr('id');
		if(!jQuery('.'+parent_ID+'-picker').length){
			jQuery(this).parent().append('<div class="'+parent_ID+'-picker new-color-picker"></div>');
		}
		jQuery(document).farbtastic('.new-color-picker').hide();
		jQuery('.'+parent_ID+'-picker').farbtastic(this).show();
	});
	jQuery(document).on('blur','[id^=newcolorpicker]',function(){
		parent_ID = jQuery(this).parent().attr('id');
		jQuery('.'+parent_ID+'-picker').farbtastic(this).hide();
	});
});