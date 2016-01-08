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
if($file ==''&& $url =='' && $code ==''){
echo '<style type="text/css">
		#player{ display: none}
	 </style>';	
}
if($delay_video==''){$delay_video=1000;}
//auto-load
//if($auto_load=='1'){
if((strpos($file, 'youtube.com') !== false)&&($using_yt_param !=1)||(strpos($url, 'youtube.com') !== false )&&($using_yt_param !=1)){
	?>
	<script src="http://www.youtube.com/player_api"></script> 
	<script>
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
					  html5 : 1
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
<?php }elseif((strpos($file, 'vimeo.com') !== false )|| (strpos($url, 'vimeo.com') !== false )|| (strpos($code, 'vimeo.com') !== false ) ){ ?>
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
					var link = jQuery('.next-post a').attr('href');
					var className = jQuery('#tm-autonext span#autonext').attr('class');
					if(className!=''){
						if(link !=undefined){
							window.location.href= link;
						}
					}
				},<?php echo $delay_video ?>);
			}
		});	
	</script>
<?php }?>
<?php //}
wp_reset_postdata();
endwhile; ?>  
<div class="single-inbox">
		<div id="player" >
            	<div class="video-player">
                	<div class="player-content">
                    	<div id="player-embed">
							<?php
							if((strpos($url, 'wistia.com') !== false )|| (strpos($code, 'wistia.com') !== false ) ){
								$id = substr($url, strrpos($url,'medias/')+7);
								?>
                                <div id="wistia_<?php echo $id ?>" class="wistia_embed" style="width:750px;height:506px;" data-video-width="750" data-video-height="506">&nbsp;</div>
                                <script charset="ISO-8859-1" src="//fast.wistia.com/assets/external/E-v1.js"></script>
                                <script>
                                wistiaEmbed = Wistia.embed("<?php echo $id ?>", {
                                  version: "v1",
                                  videoWidth: 750,
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
                                 <div class="obj-yt-inbox">
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
                                      <?php if($interactive_videos==0){?>
                                      allowscriptaccess="samedomain"
                                      <?php } else {?>
                                      allowscriptaccess="always"
                                      <?php }?>
                                      <?php if($allow_networking=='0'){ ?>
                                      allowNetworking="internal"
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
                        <div class="prev-post"><?php previous_post_link('%link','<i class="fa fa-chevron-left"></i>',TRUE,'') ; ?></div>
                        <div class="next-post"><?php next_post_link('%link ','<i class="fa fa-chevron-right"></i>',TRUE,''); ?></div>
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
                     $current_key = '';
                     $tm_query = get_posts($args);
                     //print_r($tm_query);
                     foreach ( $tm_query as $key => $post ) : setup_postdata( $post );
                        if($post->ID == get_the_ID()){$current_key = $key;}
                     endforeach;
                     $next = get_permalink($tm_query[$current_key+1]->ID); 
                     $previous = get_permalink($tm_query[$current_key-1]->ID);
                     ?>
                    <div class="post-nav">
                        <?php if($next!=''){?><div class="next-post"><a href="<?php echo $next;?>" class="next maincolor1hover bordercolor1hover" ><i class="fa fa-chevron-right"></i></a></div><?php }?>
                        <?php if($previous!=''){?><div class="prev-post"><a href="<?php echo $previous;?>" class="prev maincolor1hover bordercolor1hover" ><i class="fa fa-chevron-left"></i></a></div><?php }?>
                    </div>
				 
                </div>
                <?php wp_reset_postdata(); }?>
                </div>
        </div><!--/player-->
 			<?php
			$onoff_more_video = ot_get_option('onoff_more_video');
			if($onoff_more_video !='0'){ 
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
					 $a = get_the_ID();
                     foreach ( $tm_query_more as $key => $post ) : setup_postdata( $post );
                        if($post->ID == $a){$current_key_more = $key;}
                     endforeach;
					 $e_in = $number_of_more/2;
					 if($number_of_more%2!=0){
					 	$e_in=explode(".",$e_in);
						$e_in = $e_in[1];
					 }
					 $n= $e_in;

                echo  '
                    <div id="top-carousel" class="inbox-more more-hide">
                        <div class="container">
                            <div class="is-carousel" id="top2" data-notauto=1>
                                <div class="carousel-content">';
								$add_cl='';
								for($i=0;$i<=$n;$i++){
								$id_pre_m = ($tm_query_more[$current_key_more+$i]->ID);
								 if($i==0){$add_cl='marking_vd';}
								if($id_pre_m){
								?>
                                    <div class="video-item <?php echo $add_cl;?>">
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
                                            <?php if($i==0){?>
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
								if($id_nex_m){
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
        <?php 
        if (have_posts()) :
            while (have_posts()) : the_post();?>
            <div class="box-title">
            	<div class="title-info">
                    <h1 class="light-title"><?php the_title(); ?></h1>
                    <?php if(is_single()){ ?>
                    <div class="item-info">
						<?php if(ot_get_option('single_show_meta_author',1)){the_author_posts_link();} ?>
                        <?php if(ot_get_option('single_show_meta_date',1)){ ?>
                        <span class="item-date"><?php the_time(get_option('date_format')); ?> <?php the_time(get_option('time_format')); ?></span>
                        <?php }?>
                    </div>
                </div>
                <?php 
				 
				 if($onoff_more_video !='0'){ ?> 
                <div class="box-m">
                	<span class="box-more" id="click-more" ><?php echo __('More videos','cactusthemes'); ?> <i class="fa fa-angle-down"></i></span>
                </div>
                <?php }?>
            </div>
        <?php ob_start(); //get toolbar html?>
        <div id="video-toolbar">
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
                            <div class="maincolor2hover toolbar-views-label"><?php echo __('Views  ','cactusthemes'); ?><i class="fa fa-eye"></i></div>
                        </div>
                        <span class="middlefix"></span>
                    </div>
                    <?php }}
					if(ot_get_option('single_show_meta_comment',1)){ ?>
                    <div class="video-toolbar-item count-cm">
                        <span class="maincolor2hover"><a href="#comments" class="maincolor2hover"><i class="fa fa-comment"></i>  <?php echo  get_comments_number() ?></a></span>
                    </div>
                    <?php }?>
                    <?php if(function_exists('GetWtiLikePost')){ ?>
                    <div class="video-toolbar-item like-dislike">
                    	<?php if(function_exists('GetWtiLikePost')){ GetWtiLikePost();}?>
                        <!--<span class="maincolor2hover like"><i class="fa fa-thumbs-o-up"></i></span>
                        <span class="maincolor2hover dislike"><i class="fa fa-thumbs-o-down"></i></span>-->
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
                        <?php if($show_hide_sharethis==1){
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
				   if(ot_get_option('single_show_meta_like',1)){ 
					if(function_exists('GetWtiLikePost')){
					?>
                    <div class="video-toolbar-item pull-right col-md-3 video-toolbar-item-like-bar">
                        <div class="wrap-toolbar-item">
                        <?php 
						  $main_color_2 = ot_get_option('main_color_2')?ot_get_option('main_color_2'):'#4141a0';
                          $mes= '<style type="text/css">.action-like a:after{ color:'.$main_color_2.' !important}</style>';
                          $mes_un= '<style type="text/css">.action-unlike a:after{ color:'.$main_color_2.' !important}</style>';
                          if(function_exists('GetWtiVotedMessage')){$msg = GetWtiVotedMessage(get_the_ID());}
                          if(!$msg){
                             echo '<style type="text/css">
                              .video-toolbar-item.like-dislike .status{display:none !important;}
							  .video-toolbar-item.like-dislike:hover .status{display:none !important;}</style>';
                          }
						  $ip='';
						  if(function_exists('WtiGetRealIpAddress')){$ip = WtiGetRealIpAddress();}
                          $tm_vote = TmAlreadyVoted(get_the_ID(), $ip);
                          
                              // get setting data
                              $is_logged_in = is_user_logged_in();
                              $login_required = get_option('wti_like_post_login_required');
                              if ($login_required && !$is_logged_in) {
                                      echo $mes;
                                      echo $mes_un;
                              } else {
                                  if(function_exists('HasWtiAlreadyVoted')){$has_already_voted = HasWtiAlreadyVoted(get_the_ID(), $ip);}
                                  $voting_period = get_option('wti_like_post_voting_period');
                                  $datetime_now = date('Y-m-d H:i:s');
                                  if ("once" == $voting_period && $has_already_voted) {
                                      // user can vote only once and has already voted.
                                      if($tm_vote>0){echo $mes;}
                                      else if ($tm_vote<0){echo $mes_un;}
                                  } elseif (0 == $voting_period) {
									  if($tm_vote>0){echo $mes;}
                                      else if ($tm_vote<0){echo $mes_un;}
                                  } else {
                                      if (!$has_already_voted) {
                                          // never voted befor so can vote
                                      } else {
                                          // get the last date when the user had voted
                                          if(function_exists('GetWtiLastVotedDate')){$last_voted_date = GetWtiLastVotedDate(get_the_ID(), $ip);}
                                          // get the bext voted date when user can vote
                                          if(function_exists('GetWtiLastVotedDate')){$next_vote_date = GetWtiNextVoteDate($last_voted_date, $voting_period);}
                                          if ($next_vote_date > $datetime_now) {
                                              $revote_duration = (strtotime($next_vote_date) - strtotime($datetime_now)) / (3600 * 24);
                                              
                                              if($tm_vote>0){echo $mes;}
                                              else if ($tm_vote<0){echo $mes_un;}
                                          }
                                      }
                                  }
                              }

							$like = $unlike = $fill_cl = $sum = '';
                            if(function_exists('GetWtiLikeCount')){$like = GetWtiLikeCount(get_the_ID());}
                            if(function_exists('GetWtiUnlikeCount')){$unlike = GetWtiUnlikeCount(get_the_ID());}
							$re_like = str_replace('+','',$like);
							$re_unlike = str_replace('-','',$unlike);
							$sum = $re_like + $re_unlike;
							if($sum!=0 && $sum!=''){
								$fill_cl = (($re_like/$sum)*100);
							} else 
							if($sum==0){
								$fill_cl = 50;
							}
                            ?>
                            <div class="like-bar"><span style="width:<?php echo $fill_cl ?>%"><!----></span></div>
                            <div class="like-dislike pull-right">
                            	<span class="like"><i class="fa fa-thumbs-o-up"></i>  <?php echo $like ?></span>
                            	<span class="dislike"><i class="fa fa-thumbs-o-down"></i>  <?php echo $unlike ?></span>
                            </div>
                        </div>
                    </div>
                    <?php } }?>
                    <div class="clearfix"></div>
                    <?php if(!$show_hide_sharethis){?>
                    <div id="tm-share" class="collapse">
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
		$video_toolbar_html = ob_get_clean();
		if(ot_get_option('video_toolbar_position','top')=='top'){
			echo $video_toolbar_html;
		}
       // $social_post= get_post_meta($post->ID,'show_hide_social',true);
       /// if($social_post=='show'){ //check if show social share
            gp_social_share(get_the_ID());
       //}
//        if($social_post=='def'){
//            if( ot_get_option( 'blog_show_socialsharing', 1)){ //check if show social share
//                gp_social_share(get_the_ID());
//            }
//        }
        ?>
        <?php tm_display_ads('ad_single_content');?>        
        <div class="<?php echo is_single()?'item-content':'content-single'; ?>">
			<?php the_content(); ?>
            <div class="clearfix"></div>
            <?php if(is_single()){ ?>
            <div class="item-tax-list">
            	<?php 
				$onoff_tag = ot_get_option('onoff_tag');
				$onoff_cat = ot_get_option('onoff_cat');
				if($onoff_cat !='0'){
				 ?>
                <div><strong><?php _e('Category:', 'cactusthemes'); ?> </strong><?php the_category(', '); ?></div>
                <?php }
				if($onoff_tag !='0'){
				?>
                <div><?php the_tags('<strong>'.__('Tags:', 'cactusthemes').' </strong>', ', ', ''); ?></div>
                <?php }?>
            </div>
            <?php 
				if(ot_get_option('video_toolbar_position','top')=='bottom'){
					echo '<br>'.$video_toolbar_html;
				}
			} ?>
    	</div><!--/item-content-->
        <?php }endwhile;
        endif;
		?>
</div>
