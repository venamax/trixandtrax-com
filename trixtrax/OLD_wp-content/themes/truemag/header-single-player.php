<?php while (have_posts()) : the_post(); global $post;?>
<?php
if( !isset($content_width) ){ $content_width = 900; }
$file = get_post_meta($post->ID, 'tm_video_file', true);
$url = trim(get_post_meta($post->ID, 'tm_video_url', true));
$code = trim(get_post_meta($post->ID, 'tm_video_code', true));
$auto_load= ot_get_option('auto_load_next_video');
$auto_play= ot_get_option('auto_play_video');
$delay_video= ot_get_option('delay_video');
$delay_video=$delay_video*1000;
if($delay_video==''){$delay_video=1000;}
$detect = new Mobile_Detect;
global $_device_, $_device_name_, $_is_retina_;
$_device_ = $detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'mobile') : 'pc';
if($detect->isMobile() || $detect->isTablet()){
	$auto_play=0;
}
$onoff_related_yt= ot_get_option('onoff_related_yt');
$onoff_html5_yt= ot_get_option('onoff_html5_yt');
$using_yt_param = ot_get_option('using_yout_param');
$onoff_info_yt = ot_get_option('onoff_info_yt');
$allow_full_screen = ot_get_option('allow_full_screen');
$allow_networking = ot_get_option('allow_networking');
$remove_annotations = ot_get_option('remove_annotations');
$user_turnoff = ot_get_option('user_turnoff_load_next');
$interactive_videos = ot_get_option('interactive_videos');
//auto-load
if($file ==''&& $url =='' && $code ==''){
echo '<style type="text/css">
		#player{ display: none}
	 </style>';	
}
if((strpos($file, 'youtube.com') !== false)&&($using_yt_param !=1)||(strpos($url, 'youtube.com') !== false )&&($using_yt_param !=1)){
	?>
	<script src="http://www.youtube.com/player_api"></script> 
	<script>
        // create youtube player
        var player;
        function onYouTubePlayerAPIReady() {
            player = new YT.Player('player-embed', {
              height: '506',
              width: '900',
              videoId: '<?php echo Video_Fetcher::extractIDFromURL($url); ?>',
			  <?php if($onoff_related_yt!= '0' || $onoff_html5_yt== '1' || $remove_annotations!= '1' || $onoff_info_yt=='1'){ ?>
			  playerVars : {
				 <?php if($remove_annotations!= '1'){?>
				  iv_load_policy : 3,
				  <?php } 
				  if($onoff_related_yt== '1'){?>
				  rel : 0,
				  <?php } 
				  if($onoff_html5_yt== '1'){
				  ?>
				  html5 : 1,
				  <?php }
				  if($onoff_info_yt=='1'){
				  ?>
				  showinfo:0,
				  <?php }?>
			  },
			  <?php }?>
              events: {
				<?php if($auto_play=='1'){?>  
                'onReady': onPlayerReady,
				<?php } if($auto_load=='1' || $user_turnoff==1){?>
                'onStateChange': onPlayerStateChange
				<?php } ?>
              }
            });
        }
        // autoplay video
		<?php
		if($auto_play=='1'){?> 
        function onPlayerReady(event) {
            event.target.playVideo();
        }
		<?php }?>
        // when video ends
		function onPlayerStateChange(event) {
			if(event.data === 0) { 
				setTimeout(function(){
				var link = jQuery('.prev-post a').attr('href');
				var className = jQuery('#tm-autonext span#autonext').attr('class');
				//alert(className);
				if(className!=''){
				  if(link !=undefined){
					  window.location.href= link;
				  }
				}
				},<?php echo $delay_video ?>);
			}
		}

    </script>
<?php }

else if( $auto_load=='1' && (strpos($file, 'vimeo.com') !== false )|| (strpos($url, 'vimeo.com') !== false )|| (strpos($code, 'vimeo.com') !== false ) ){ ?>
	<script src="http://a.vimeocdn.com/js/froogaloop2.min.js"></script> 
	<script>
		jQuery(document).ready(function() {	
			jQuery('iframe').attr('id', 'player_1');

			var iframe = jQuery('#player_1')[0],
				player = $f(iframe),
				status = jQuery('.status_videos');
			
			// When the player is ready, add listeners for pause, finish, and playProgress
			player.addEvent('ready', function() {
				status.text('ready');
				
				player.addEvent('pause', onPause);
				<?php if ($auto_load=='1' || $user_turnoff==1){?>
				player.addEvent('finish', onFinish);
				<?php }?>
				//player.addEvent('playProgress', onPlayProgress);
			});
			
			// Call the API when a button is pressed
			jQuery(window).load(function() {
				player.api(jQuery(this).text().toLowerCase());
			});
			
			function onPause(id) {
			}
			
			function onFinish(id) {
				setTimeout(function(){
					var link = jQuery('.prev-post a').attr('href');
					var className = jQuery('#tm-autonext span#autonext').attr('class');
					//alert(className);
					if(className!=''){
						if(link !=undefined){
							window.location.href= link;
						}
					}
				},<?php echo $delay_video ?>);
			}
		});	
	</script>
<?php }else{?>
<?php }
wp_reset_postdata();
endwhile; 
$background_image_post = get_post_meta($post->ID, 'ct_bg_image', true);
if($background_image_post){
	$ct_bg_attachment = get_post_meta($post->ID, 'ct_bg_attachment', true);
	$ct_bg_repeat = get_post_meta($post->ID, 'ct_bg_repeat', true);
	$ct_bg_position = get_post_meta($post->ID, 'ct_bg_position', true);
	$background_image_post = wp_get_attachment_image_src( $background_image_post, 'full', $icon );
	if($ct_bg_attachment=='fixed'){ ?>
    <style scoped="scoped">
		@media(min-width:768px){
			#body-wrap{
				position:fixed;
				top:0;
				bottom:0;
				left:0;
				right:0;
			}
			.admin-bar #body-wrap{
				top:32px;
			}
		}
		@media(min-width:768px) and (max-width:782px){
			.admin-bar #body-wrap{
				top:46px;
			}
			.admin-bar #off-canvas{
				top:46px;
			}
		}
		.bg-ad {
			right: 14px;
		}
		<?php if(ot_get_option('theme_layout')!=1){ ?>
			#body-wrap{
				position:fixed;
				top:0;
				bottom:0;
				left:0;
				right:0;
			}
			.admin-bar #body-wrap{
				top:32px;
			}
			@media(max-width:782px){
				.admin-bar #body-wrap{
					top:46px;
				}
				.admin-bar #off-canvas{
					top:46px;
				}
			}
		<?php } ?>
        </style>
	<?php
	}
}
?>  
		<div id="player" <?php if($background_image_post){echo 'style="background-image:url('.$background_image_post[0].');  background-attachment:'.$ct_bg_attachment.';background-position:'.$ct_bg_position.'; background-repeat:'.$ct_bg_repeat.'"'; }?>>
        	<div class="container">
            	<div class="video-player">
                	<div class="player-content" style="background-image:url(images/avatar.png)">
                    	<div id="player-embed">
							<?php
							if((strpos($url, 'wistia.com') !== false )|| (strpos($code, 'wistia.com') !== false ) ){
								$id = substr($url, strrpos($url,'medias/')+7);
								?>
                                <div id="wistia_<?php echo $id ?>" class="wistia_embed" style="width:900px;height:506px;" data-video-width="900" data-video-height="506">&nbsp;</div>
                                <script charset="ISO-8859-1" src="//fast.wistia.com/assets/external/E-v1.js"></script>
                                <script>
                                wistiaEmbed = Wistia.embed("<?php echo $id ?>", {
                                  version: "v1",
                                  videoWidth: 900,
                                  videoHeight: 506,
                                  volumeControl: true,
                                  controlsVisibleOnLoad: true,
                                  playerColor: "688AAD",
                                  volume: 0
                                });
                                </script>
                                <?php 
							}else {	
								 if((strpos($file, 'youtube.com') !== false)&&($using_yt_param ==1)||(strpos($url, 'youtube.com') !== false )&&($using_yt_param ==1)){?>
                                 <div class="obj-youtube">
                                    <object width="900" height="506">
                                    <param name="movie" value="http://www.youtube.com/v/<?php echo Video_Fetcher::extractIDFromURL($url); ?><?php if($onoff_related_yt!= '0'){?>&rel=0<?php }if($auto_play=='1'){?>&autoplay=1<?php }if($onoff_info_yt=='1'){?>&showinfo=0<?php }if($remove_annotations!= '1'){?>&iv_load_policy=3<?php }if($onoff_html5_yt== '1'){?>&html5=1<?php }?>" ></param>
                                    <param name="allowFullScreen" value="<?php if($allow_full_screen!='0'){?>true<?php }else {?>false<?php }?>"></param>
                                    <?php if($interactive_videos==0){?>
                                    <param name="allowScriptAccess" value="samedomain"></param>
                                    <?php } else {?>
                                    <param name="allowScriptAccess" value="always"></param>
                                    <?php }?>
                                    <embed src="http://www.youtube.com/v/<?php echo Video_Fetcher::extractIDFromURL($url);if($onoff_related_yt!= '0'){?>&rel=0<?php }if($auto_play=='1'){?>&autoplay=1<?php }if($onoff_info_yt=='1'){?>&showinfo=0<?php }if($remove_annotations!= '1'){?>&iv_load_policy=3<?php }if($onoff_html5_yt== '1'){?>&html5=1<?php }?>"
                                      type="application/x-shockwave-flash"
                                      allowfullscreen="<?php if($allow_full_screen!='0'){?>true<?php }else {?>false<?php }?>"
                                      <?php if($allow_networking=='0'){ ?>
                                      allowNetworking="internal"
                                      <?php }?>
                                      <?php if($interactive_videos==0){?>
                                      allowscriptaccess="samedomain"
                                      <?php } else {?>
                                      allowscriptaccess="always"
                                      <?php }?>
                                      width="100%" height="100%">
                                    </embed>
                                    </object>
                                    </div>
                                 <?php	 
								 }else {
									 if($auto_play=='1'){ $url = (tm_video($post->ID, true));
								 	 }else { $url = (tm_video($post->ID, false));  }
								 }
							 }
							?>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <!--<div class="player-button">
                       <a href="#" class="prev maincolor1hover bordercolor1hover"><i class="fa fa-chevron-left"></i></a>
                       <a href="#" class="next maincolor1hover bordercolor1hover"><i class="fa fa-chevron-right"></i></a>
                    </div>--><!--/player-button-->
                <?php $auto_load_same_cat= ot_get_option('auto_load_same_cat');
				
				if($auto_load_same_cat=='1'){?>
                    <div class="player-button">
                        <div class="prev-post same-cat"><?php next_post_link('%link','<i class="fa fa-chevron-left"></i>',TRUE,'') ; ?></div>
                        <div class="next-post same-cat"><?php previous_post_link('%link ','<i class="fa fa-chevron-right"></i>',TRUE,''); ?></div>
                   </div>
                    <?php 
				}else
				 if($auto_load_same_cat=='0' || $auto_load_same_cat=='' ){?>
                     <div class="player-button">
                     <?php 
                        $test = "";
                        $posttags = get_the_tags();
                        if ($posttags) {
                        foreach($posttags as $tag) {
                            $test .= ',' . $tag->slug; 
                        }
                        }
                        $test = substr($test, 1); 
                     $args = array(
                        'post_type' => 'post',
                        'post_status' => 'publish',
                        'tag' => $test,
                     );
                     $current_key = $next = $previous= '';
                     $tm_query = get_posts($args);
                     foreach ( $tm_query as $key => $post ) : setup_postdata( $post );
                        if($post->ID == get_the_ID()){$current_key = $key;}
                     endforeach;
					 $id_pre = ($tm_query[$current_key+1]->ID);
					 $id_nex = ($tm_query[$current_key-1]->ID);
                     if($id_pre!= ''){$next = get_permalink($tm_query[$current_key+1]->ID); }
                     if($id_nex!= ''){$previous = get_permalink($tm_query[$current_key-1]->ID);}
                     ?>
                    <div class="post-nav">
                    	<?php if($previous!=''){?><div class="next-post"><a href="<?php echo $previous;?>" class="next maincolor1hover bordercolor1hover bgcolor-hover" ><i class="fa fa-chevron-right"></i></a></div><?php }?>
                        <?php if($next!=''){?><div class="prev-post"><a href="<?php echo $next;?>" class="prev maincolor1hover bordercolor1hover bgcolor-hover" ><i class="fa fa-chevron-left"></i></a></div><?php }?>
                    </div>
				 
                </div>
                <?php  }?>
                </div>   
             <?php 
			 $onoff_more_video = ot_get_option('onoff_more_video');
			 if($onoff_more_video !='0'){ ?>               
             <div class="box-m">
                <span class="box-more  single-full-pl" id="click-more" ><?php echo __('More','cactusthemes'); ?> &nbsp;<i class="fa fa-angle-down"></i></span>
            </div>
			<?php }?>
            </div><!--/container-->
        </div><!--/player-->
		<?php //related carousel
		if($onoff_more_video !='0'){ 
		wp_reset_postdata();
      	global $post;
		$id_curr = $post->ID;
		if(function_exists('ot_get_option')){$number_of_more = ot_get_option('number_of_more');}
		if($number_of_more=='' || !$number_of_more){$number_of_more=11;}
		global $wp_query;
			 $args = array(
				'posts_per_page' => $number_of_more,
				'post_type' => 'post',
				'post_status' => 'publish',
				'tax_query' => array(
				array(
					'taxonomy' => 'post_format',
					'field' => 'slug',
					'terms' => 'post-format-video',
				))
			 );
			 if(function_exists('ot_get_option')){$sort_of_more = ot_get_option('sort_of_more');}
			 if($sort_of_more=='1'){
				 $categories = get_the_category();
				 $category_id = $categories[0]->cat_ID;
				 if(isset($category_id)){
					$cats = explode(",",$category_id);
					if(is_numeric($cats[0])){
						//$args += array('category__in' => $cats);
						$args['category__in'] = $cats;
					}
				}
			 }
			 $current_key_more = '';
			 $tm_query_more = get_posts($args);
			 //print_r($tm_query);
			 foreach ( $tm_query_more as $key_more => $post ) : setup_postdata( $post );
				if($post->ID == $id_curr){$current_key_more = $key_more;}
			 endforeach;
			 
			 $e_in = $number_of_more/2;
			 if($number_of_more%2!=0){
				$e_in=explode(".",$e_in);
				$e_in = $e_in[1];
			 }
			 $n= $e_in;
		echo  '
			<div id="top-carousel" class="full-more more-hide">
				<div class="container">
					<div class="is-carousel" id="top2" data-notauto=1>
						<div class="carousel-content">';
								?>
                                    <div class="video-item marking_vd">
                                        <div class="item-thumbnail">
                                            <a href="<?php echo get_permalink($id_curr) ?>" title="<?php echo get_the_title($id_curr)?>">
                                            <?php
                                            if(has_post_thumbnail($id_curr)){
                                                $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($id_curr),'thumb_196x126', true);
                                            }else{
                                                $thumbnail[0]=function_exists('tm_get_default_image')?tm_get_default_image():'';
                                            }
                                            ?>
                                            <img src="<?php echo $thumbnail[0] ?>" alt="<?php the_title_attribute($id_curr); ?>" title="<?php the_title_attribute($id_curr); ?>">
                                                <div class="link-overlay fa fa-play"></div>
                                            </a>
                                            <?php echo tm_post_rating($id_curr) ?>
                                            <div class="item-head">
                                                <h3><a href="<?php echo get_permalink($id_curr) ?>" title="<?php echo get_the_title($id_curr)?>"><?php echo get_the_title($id_curr)?></a></h3>
                                            </div>
                                       		 <div class="mark_bg"><?php  echo __('NOW PLAYING','cactusthemes');?></div>
                                    
                                        </div>
                                    </div><!--/video-item-->
               				<?php

								$add_cl='';
								$tm_query_more[$current_key_more]->ID;
								for($i=1;$i<=$n;$i++){
								$id_pre_m = ($tm_query_more[$current_key_more+$i]->ID);
								 //if($i==0){$add_cl='marking_vd';}
								if($id_pre_m){
								?>
                                    <div class="video-item <?php //echo $add_cl;?>">
                                        <div class="item-thumbnail">
                                            <a href="<?php echo get_permalink($id_pre_m) ?>" title="<?php echo get_the_title($id_pre_m)?>">
                                            <?php
                                            if(has_post_thumbnail($id_pre_m)){
                                                $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($id_pre_m),'thumb_196x126', true);
                                            }else{
                                                $thumbnail[0]=function_exists('tm_get_default_image')?tm_get_default_image():'';
                                            }
                                            ?>
                                            <img src="<?php echo $thumbnail[0] ?>" alt="<?php the_title_attribute($id_pre_m); ?>" title="<?php the_title_attribute($id_pre_m); ?>">
                                                <div class="link-overlay fa fa-play"></div>
                                            </a>
                                            <?php echo tm_post_rating($id_pre_m) ?>
                                            <div class="item-head">
                                                <h3><a href="<?php echo get_permalink($id_pre_m) ?>" title="<?php echo get_the_title($id_pre_m)?>"><?php echo get_the_title($id_pre_m)?></a></h3>
                                            </div>
                                            <?php if($i==909){?>
                                       		 <div class="mark_bg"><?php  echo __('NOW PLAYING','cactusthemes');?></div>
                                    
                                        	<?php }?>
                                        </div>
                                    </div><!--/video-item-->
               				<?php
								}
								$add_cl='';
								}
						for($j=$n;$j>0;$j--){
						$id_nex_m = ($tm_query_more[$current_key_more-$j]->ID);
						if($id_nex_m!=''){
						?>
							<div class="video-item">
								<div class="item-thumbnail">
									<a href="<?php echo get_permalink($id_nex_m) ?>" title="<?php echo get_the_title($id_nex_m)?>">
									<?php
									if(has_post_thumbnail($id_nex_m)){
										$thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($id_nex_m),'thumb_196x126', true);
									}else{
										$thumbnail[0]=function_exists('tm_get_default_image')?tm_get_default_image():'';
									}
							?>
									<img src="<?php echo $thumbnail[0] ?>" alt="<?php the_title_attribute($id_nex_m); ?>" title="<?php the_title_attribute($id_nex_m); ?>">
										<div class="link-overlay fa fa-play"></div>
									</a>
									<?php echo tm_post_rating($id_nex_m) ?>
									<div class="item-head">
										<h3><a href="<?php echo get_permalink($id_nex_m) ?>" title="<?php echo get_the_title($id_nex_m)?>"><?php echo get_the_title($id_nex_m)?></a></h3>
									</div>
								</div>
							</div><!--/video-item-->
					<?php
						}
						}
					wp_reset_postdata();
					echo '
						</div><!--/carousel-content-->
						<div class="carousel-button more-videos">
							<a href="#" class="prev maincolor1 bordercolor1 bgcolor1hover"><i class="fa fa-chevron-left"></i></a>
							<a href="#" class="next maincolor1 bordercolor1 bgcolor1hover"><i class="fa fa-chevron-right"></i></a>
						</div><!--/carousel-button-->
					</div><!--/is-carousel-->
				</div><!--/container-->
			</div>';
		}
		?>
        <?php ob_start(); //get toolbar html?>
        <div id="video-toolbar" class="light-div">              
        	<div class="container">            
                <div class="video-toolbar-inner">
                	<?php if(ot_get_option('single_show_meta_view',1)){ 
					if(is_plugin_active('baw-post-views-count/bawpv.php')){
					?>
                    <div class="video-toolbar-item">
                        <div class="wrap-toolbar-item">
                            <div class="maincolor2 toolbar-views-number">
							<?php echo  tm_short_number(get_post_meta(get_the_ID(),'_count-views_all',true)) ?>
                            </div>
                            <div class="maincolor2hover toolbar-views-label"><?php echo __('Views','cactusthemes'); ?> 
                            <i class="fa fa-eye"></i></div>
                        </div>
                        <span class="middlefix"></span>
                    </div>
                    <?php }}
					if(ot_get_option('single_show_meta_comment',1)){ ?>
                    <div class="video-toolbar-item count-cm">
                        <span class="maincolor2hover"><a href="#comments" class="maincolor2hover"><i class="fa fa-comment"></i>  <?php echo  get_comments_number() ?></a></span>
                    </div>
                    <?php }?>
                    
                    <?php if (function_exists('wpfp_link')) { ?>
                    <div class="video-toolbar-item tm-favories">
                    	<?php wpfp_link(); ?>
                    </div>
                    <?php }?>
                    <?php $show_hide_sharethis = ot_get_option('show_hide_sharethis');
					if(ot_get_option('share_facebook')||ot_get_option('share_twitter')||ot_get_option('share_linkedin')||ot_get_option('share_tumblr')||ot_get_option('share_google-plus')||ot_get_option('share_pinterest')||ot_get_option('share_email')||$show_hide_sharethis){
					?>
                    <div class="video-toolbar-item <?php echo $show_hide_sharethis?'':'tm-' ?>share-this">
                        <span class="maincolor2hover">
                        <?php if($show_hide_sharethis == 1){
						$sharethis_key = ot_get_option('sharethis_key');	
						?>
                        <span class='st_sharethis_large' displayText='ShareThis'></span>
                        <script type="text/javascript">var switchTo5x=false;</script>
                        <script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
                        <script type="text/javascript">stLight.options({publisher: "<?php echo $sharethis_key ?>", doNotHash: false, doNotCopy: false, hashAddressBar: false});</script>
                        <?php }else{ ?>
                        <i class="ficon-share" data-toggle="collapse" data-target="#tm-share"></i>
                        <?php }?>
                        </span>
                    </div>
                    <?php
					}
                    gp_social_share(get_the_ID());
					?>
                    
                    <div class="clearfix"></div>
                    <?php if(!$show_hide_sharethis){?>
                    <div id="tm-share" class="">
                    	<div class="tm-share-inner social-links">
						<?php
						_e('Share this with your friends via: ','cactusthemes');
						tm_social_share();
						?>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div><!--/container-->
        </div><!--/video-toolbar-->
        <?php
		global $video_toolbar_html;
		$video_toolbar_html = ob_get_clean();
		if(ot_get_option('video_toolbar_position','top')=='top'){
			echo $video_toolbar_html;
		}
        wp_reset_postdata(); ?>

