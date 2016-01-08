<?php
global $header_query;
$header_height = ot_get_option('header_home_height');
?>
<div id="head-carousel">
    <div class="is-carousel" id="metro-carousel" <?php echo !ot_get_option('header_home_auto')?'data-notauto=1':'' ?>>
        <div class="carousel-content">
        <?php if($header_query->have_posts()){
			$item_count=0;
			$open_double = 0;
			while($header_query->have_posts()): $header_query->the_post();
			$item_count++;
			if($item_count%7==2){ echo '<div class="video-item">'; $open_double = 1;}
			if($item_count%7==4||$item_count%7==6){ echo '</div><!--/two-video-item--><div class="video-item">'; $open_double = 1;}
			if($item_count%7==1&&$item_count>7){ echo '</div><!--/two-video-item-->'; $open_double = 0;}
			$format = get_post_format(get_the_ID());
		?>
            <div class="video-item">
            	<?php 
                  $quick_if = ot_get_option('quick_view_for_slider');
                  if($quick_if=='1'){
                  echo '
                        <div class="qv_tooltip"  title="
                            <h4 class=\'gv-title\'>'.esc_attr(get_the_title()).'</h4>
                            <div class=\'gv-ex\' >'.esc_attr(get_the_excerpt()).'</div>
                            <div class= \'gv-button\'>
                                <div class=\'quick-view\'><a href='.get_permalink().' title=\''.get_the_title().'\'>'.__('Watch Now','cactusthemes').'</a></div>
                                <div class= \'gv-link\'>'.quick_view_tm().'</div>
                            </div>
                            </div>
                        ">';}
                  ?>
                <div class="item-thumbnail">
                    <a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>" >
                    <?php
						if(has_post_thumbnail()){
							global $_device_;
							global $_is_retina_;
							if($_device_== 'mobile' && !$_is_retina_){
								$thumb=$open_double?'thumb_130x73':'thumb_520x293';
							}else{
								if($header_height>350){
									$thumb=$open_double?'thumb_356x200':'thumb_748x421';
								}else{
									$thumb=$open_double?'thumb_260x146':'thumb_520x293';
								}
							}
							$thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id(),$thumb, true);
						}else{
							$thumbnail[0]=function_exists('tm_get_default_image')?tm_get_default_image():'';
							$thumbnail[1]=520;
							$thumbnail[2]=293;
						}
						?>
						<img src="<?php echo $thumbnail[0] ?>" width="<?php echo $thumbnail[1] ?>" height="<?php echo $thumbnail[2] ?>" alt="<?php the_title_attribute(); ?>" title="<?php the_title_attribute(); ?>">
						  <?php if($format=='' || $format =='standard'  || $format =='gallery'){ ?>
                          <div class="link-overlay fa fa-search"></div>
                          <?php }else {?>
                          <div class="link-overlay fa fa-play"></div>
                          <?php }  ?>

                    </a>
                    <div class="item-head">
                        <h3><a href="<?php the_permalink() ?>"  title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
                        <?php if(!$open_double && !ot_get_option('header_home_hidecat')){?>
                        <span><?php the_category(', '); ?></span>
                        <?php }?>
                    </div>
                </div>
                <?php if($quick_if=='1'){
						echo '</div>';
				}?>
            </div><!--/video-item-->
        <?php
			if($item_count==$header_query->post_count && $open_double){ echo '</div><!--/two-video-item-last-->';}
			endwhile;
			wp_reset_postdata();
		}?>
        </div><!--/carousel-content-->
        <div class="carousel-button">
            <a href="#" class="prev maincolor1 bordercolor1 bgcolor1hover"><i class="fa fa-chevron-left"></i></a>
            <a href="#" class="next maincolor1 bordercolor1 bgcolor1hover"><i class="fa fa-chevron-right"></i></a>
        </div><!--/carousel-button-->
    </div><!--/is-carousel-->
</div><!--head-carousel-->