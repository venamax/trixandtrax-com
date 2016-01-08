<?php
global $header_query;
$header_height = ot_get_option('header_home_height');
?>
<div id="head-carousel">
    <?php /*?><div class="container"><?php */?>
    <div class="is-carousel" id="big-carousel" <?php echo !ot_get_option('header_home_auto')?'data-notauto=1':'' ?>>
        <div class="carousel-content">
            <?php if($header_query->have_posts()){
                      while($header_query->have_posts()):
                          $header_query->the_post();
                          $pId = get_the_ID();
                          $format = get_post_format($pId);
            ?>
            <div class="video-item">
                <?php 
                          $url = get_permalink();
                          $target = "_self";
                          $external_url = get_post_custom_values('external_url');
                          if(count($external_url) > 0){
                              $url = $external_url[0];
                              $target="_self";
                          }
                          
                          $quick_if = ot_get_option('quick_view_for_slider');
                          if($quick_if=='1'){
                              echo '
                        <div class="qv_tooltip"  title="
                            <h4 class=\'gv-title\'>'.esc_attr(get_the_title()).'</h4>
                            <div class=\'gv-ex\' >'.esc_attr(get_the_excerpt()).'</div>
                            <div class= \'gv-button\'>
                                <div class=\'quick-view\'><a target="'.$target.'" href='.$url.' title=\''.get_the_title().'\'>'.__('Watch Now','cactusthemes').'</a></div>
                                <div class= \'gv-link\'>'.quick_view_tm().'</div>
                            </div>
                            </div>
                        ">';
                          }
                ?>
                <div class="item-thumbnail">
                    <?php
                          $url = get_permalink();
                          $external_url = get_post_custom_values('external_url');
                          $target = "_self";
                          if(count($external_url) > 0){
                              $url = $external_url[0];
                              $target = "_self";
                          }
                          $time = get_post_meta($pId,'event_begin',true);
                          if($time !== "" && $time !== " 0:0:00" && count($external_url) == 0){
                              $event_time = strtotime($time);
                              if($event_time > time())
                                  $url = '/proximos-eventos#post-'.$pId;
                              else
                                  $url = get_author_posts_url( get_the_author_meta( 'ID' ));
                          }
                          
                    ?>
                    <a href="<?php echo $url; ?>" title="<?php the_title_attribute(); ?>" target="<?php echo $target; ?>" >
                        <?php
                          if(has_post_thumbnail()){
                              global $_device_;
                              global $_is_retina_;
                              if($_device_== 'mobile' && !$_is_retina_){
                                  $thumb='thumb_260x146';
                              }else{
                                  if($header_height>350){
                                      $thumb='thumb_748x421';
                                  }else{
                                      $thumb='thumb_520x293';
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
                        <?php if(ot_get_option('show_hide_title') == 1) { ?>
                        <h3><a href="<?php echo $url; ?>" title="<?php the_title_attribute(); ?>" target="<?php echo $target; ?>"><?php the_title(); ?></a></h3>
                        <?php } ?>
                        <?php if(!ot_get_option('header_home_hidecat')){ ?>
                        <span><?php the_category(', '); ?></span>
                        <?php }?>
                    </div>
                </div>
                <?php if($quick_if=='1'){
                          echo '</div>';
                      }?>
            </div>
            <!--/video-item-->
            <?php
                      endwhile;
                      wp_reset_postdata();
                  }?>
        </div>
        <!--/carousel-content-->
        <div class="carousel-button">
            <a href="#" class="prev maincolor1 bordercolor1 bgcolor1hover"><i class="fa fa-chevron-left"></i></a>
            <a href="#" class="next maincolor1 bordercolor1 bgcolor1hover"><i class="fa fa-chevron-right"></i></a>
        </div>
        <!--/carousel-button-->
    </div>
    <!--/is-carousel-->
    <?php /*?></div><!--/container--><?php */?>
</div>
<!--head-carousel-->
