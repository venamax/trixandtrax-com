<?php
global $wp_query;
global $listing_query;
if($listing_query){
	$wp_query = $listing_query;
}
if( (isset($_GET['orderby']) && $orderby=$_GET['orderby']) || ($orderby = ot_get_option('default_blog_order')) ){ //process custom order by
    if($orderby=='like')
    {
        $atts=array();
        global $wpdb;	
        $show_count = 1 ;
        $time_range = 'all';
        if($orderby == 'like')
			$order_by = 'ORDER BY like_count DESC, post_title';
        
        global $paged, $myOffset;
        if (empty($paged)) {
            $paged = 1;
        }
        $postperpage = intval(get_option('posts_per_page'));
        $pgstrt = ((intval($paged) -1) * $postperpage) + $myOffset . ', ';
        $limit = 'LIMIT '.$pgstrt.$postperpage;
        //$ppp = get_option('posts_per_page');
        //$limit = "LIMIT " .$ppp ;
        $show_excluded_posts = get_option('wti_like_post_show_on_widget');
        $excluded_post_ids = explode(',', get_option('wti_like_post_excluded_posts'));
        
        if(!$show_excluded_posts && count($excluded_post_ids) > 0) {
            $where = "AND post_id NOT IN (" . get_option('wti_like_post_excluded_posts') . ")";
        }
        //getting the most liked posts
        //$query = "SELECT post_id, SUM(value) AS like_count, post_title FROM `{$wpdb->prefix}wti_like_post` L, {$wpdb->prefix}posts P, {$wpdb->prefix}term_relationships r ";
        //$query .= "WHERE L.post_id = P.ID AND r.object_id=P.ID AND r.term_taxonomy_id NOT IN (1) AND post_status = 'publish' AND value >= 0 AND post_type = 'post' $where GROUP BY post_id $order_by $limit ";
        if(is_author()){
            $query = "SELECT l.post_id, SUM(value) AS like_count, p.post_title FROM ";
            $query .= "{$wpdb->prefix}wti_like_post l INNER JOIN ";
            $query .= "{$wpdb->prefix}posts p ON l.post_id=p.ID AND p.post_status = 'publish' AND value >= 0 AND p.post_type = 'post' AND p.post_author=$author ";
            $query .= "AND l.post_id NOT IN (SELECT p1.id FROM {$wpdb->prefix}posts p1 INNER JOIN ";
            $query .= "{$wpdb->prefix}term_relationships r ON p1.id=r.object_id AND r.term_taxonomy_id = 1 AND p1.post_status = 'publish' AND p1.post_type = 'post') $where";
            $query .= "GROUP BY l.post_id $order_by $limit";
        }else if(is_category()){
            $cat_id = get_query_var('cat');
            $query = "SELECT l.post_id, SUM(value) AS like_count, p.post_title FROM ";
            $query .= "{$wpdb->prefix}wti_like_post l INNER JOIN ";
            $query .= "wp_term_relationships tr ON l.post_id=tr.object_id ";
            $query .= "INNER JOIN wp_term_taxonomy tt ON tr.term_taxonomy_id=tt.term_taxonomy_id AND tt.term_id=$cat_id INNER JOIN ";
            $query .= "{$wpdb->prefix}posts p ON l.post_id=p.ID AND p.post_status = 'publish' AND value >= 0 AND p.post_type = 'post' ";
            $query .= "AND l.post_id NOT IN (SELECT p1.id FROM {$wpdb->prefix}posts p1 INNER JOIN ";
            $query .= "{$wpdb->prefix}term_relationships r ON p1.id=r.object_id AND r.term_taxonomy_id = 1 AND p1.post_status = 'publish' AND p1.post_type = 'post') $where";
            $query .= "GROUP BY l.post_id $order_by $limit";
        }else if(is_tag()){
            $query = "SELECT l.post_id, SUM(value) AS like_count, p.post_title FROM ";
            $query .= "{$wpdb->prefix}wti_like_post l INNER JOIN ";
            $query .= "wp_term_relationships tr ON l.post_id=tr.object_id ";
            $query .= "INNER JOIN wp_term_taxonomy tt ON tr.term_taxonomy_id=tt.term_taxonomy_id AND tt.term_id=$tag_id INNER JOIN ";
            $query .= "{$wpdb->prefix}posts p ON l.post_id=p.ID AND p.post_status = 'publish' AND value >= 0 AND p.post_type = 'post' ";
            $query .= "AND l.post_id NOT IN (SELECT p1.id FROM {$wpdb->prefix}posts p1 INNER JOIN ";
            $query .= "{$wpdb->prefix}term_relationships r ON p1.id=r.object_id AND r.term_taxonomy_id = 1 AND p1.post_status = 'publish' AND p1.post_type = 'post') $where";
            $query .= "GROUP BY l.post_id $order_by $limit";
        }
        else{
            $query = "SELECT l.post_id, SUM(value) AS like_count, p.post_title FROM ";
            $query .= "{$wpdb->prefix}wti_like_post l INNER JOIN ";
            $query .= "{$wpdb->prefix}posts p ON l.post_id=p.ID AND p.post_status = 'publish' AND value >= 0 AND p.post_type = 'post' ";
            $query .= "AND l.post_id NOT IN (SELECT p1.id FROM {$wpdb->prefix}posts p1 INNER JOIN ";
            $query .= "{$wpdb->prefix}term_relationships r ON p1.id=r.object_id AND r.term_taxonomy_id = 1 AND p1.post_status = 'publish' AND p1.post_type = 'post') $where";
            $query .= "GROUP BY l.post_id $order_by $limit";
        }
        
        if($query)
            $posts = $wpdb->get_results($query);
        else
            $posts = 
        $item_loop_video = new CT_ContentHtml;
        $loop_count=0;
        echo '
				<div class="post_ajax_tm" >
					<div class="row">';
        
        if(count($posts) > 0) {
            foreach ($posts as $post) {
                $loop_count++;
                $post_title = stripslashes($post->post_title);
                $permalink = get_permalink($post->post_id);
                $like_count = $post->like_count;
                $class ='';
                $format = get_post_format($post->post_id);
                if($format=='' || $format =='standard'){$class ='news';}
?>
<div class="col-md-3 col-sm-6 col-xs-6 <?php echo $class; ?> ">
    <div id="post-<?php $post->post_id; ?>" <?php post_class('video-item') ?>>
        <?php 
                $quick_if = ot_get_option('quick_view_info');
                if($quick_if=='1'){
                    echo '
									<div class="qv_tooltip"  title="
										<h4 class=\'gv-title\'>'.esc_attr(get_the_title()).'</h4>
										<div class=\'gv-ex\' >'.esc_attr(get_the_excerpt()).'</div>
										<div class= \'gv-button\'>';
                    if($format=='video'){
                        echo  '<div class=\'quick-view\'><a href='.get_permalink().' title=\''.esc_attr(get_the_title()).'\'>'.__('Watch Now','cactusthemes').'</a></div>';
                    }else{
                        echo  '<div class=\'quick-view\'><a href='.get_permalink().' title=\''.esc_attr(get_the_title()).'\'>'.__('Read more','cactusthemes').'</a></div>';
                    }
                    echo '
											<div class= \'gv-link\'>'.quick_view_tm().'</div>
										</div>
										</div>
									">';
                }
        ?>
        <div class="item-thumbnail">
            <a href="<?php  echo $permalink ?>">
                <?php
                if(has_post_thumbnail($post->post_id)){
                    $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($post->post_id),'thumb_520x293', true);
                }else{
                    $thumbnail[0]=function_exists('tm_get_default_image')?tm_get_default_image():'';
                }
                ?>
                <img src="<?php echo $thumbnail[0] ?>" alt="<?php the_title_attribute(); ?>" title="<?php the_title_attribute(); ?>">
                <?php if($format=='' || $format =='standard'  || $format =='gallery'){ ?>
                <div class="link-overlay fa fa-search"></div>
                <?php }else {?>
                <div class="link-overlay fa fa-play"></div>
                <?php }  ?>
            </a>
            <?php echo tm_post_rating($post->post_id); ?>
        </div>
        <?php if($quick_if=='1'){
                  echo '</div>';
              }?>
        <div class="item-head">
            <h3>
                <a href="<?php echo $permalink ?>" rel="<?php $post->post_id; ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
            </h3>
            <div class="item-info hidden">
                <?php if(ot_get_option('blog_show_meta_author',1)){ ?>
                <span class="item-author"><?php the_author_posts_link(); ?></span>
                <?php }
                      if(ot_get_option('blog_show_meta_date',1)){ ?>
                <span class="item-date hidden"><?php echo date_i18n(get_option('date_format') ,strtotime(get_the_date())); ?></span>
                <?php }?>
                <div class="item-meta">
                    <?php echo tm_html_video_meta(false,false,false,true,$post->post_id) ?>
                </div>
            </div>
        </div>
        <div class="item-content hidden"><?php the_excerpt(); ?></div>
        <div class="clearfix"></div>
    </div>
</div>
<!--/col3-->


<?php 
                if($loop_count%4==0){ echo  '</div><div class="row">';}
            }
        }
        echo '
						</div>
			</div>';
        
    }else{
        global $wp_query;
        $atts=array();
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        $atts=array(
        'post_type'=>'post',
        'posts_per_page'=>8,
        'paged' => $paged
        );
        if(is_author())
            $atts['author'] = $author;
        if(is_category()){
            $cat_id = get_query_var('cat');
            $atts['cat'] = "$cat_id";
        }else if(is_tag())
            $atts['tag'] = $tag;
        else{
            $atts['cat'] = '-1';
        }
        if($orderby=='view'){
            $atts['orderby']='meta_value_num';
            $atts['meta_key']='_count-views_all'; //view metadata
        }elseif($orderby=='comment'){
            if(is_plugin_active('facebook/facebook.php')&&get_option('facebook_comments_enabled')||is_plugin_active('disqus-comment-system/disqus.php')){
                $atts['orderby']='meta_value_num';
                $atts['meta_key']='custom_comment_count';
            }else{
                $atts['orderby']='comment_count';
            }
        }elseif($orderby=='title'){
            $atts['orderby']=$orderby;
            $atts['order']='ASC';
        }elseif($orderby == 'resumen'){
            $atts['cat'] = 177;
        }
        else{
            $atts['orderby']='date';
            $atts['order']='DESC';
        }
        $atts = array_merge( $wp_query->query_vars, $atts );
        $wp_query = new WP_Query($atts);
        
        $loop_count=0;
        echo '
			<div class="post_ajax_tm" >
			<div class="row">';
        while ($wp_query->have_posts()) :
            $wp_query->the_post();
			$loop_count++;
			$class ='';
			$format = get_post_format(get_the_ID());
			if($format!='' || $format =='standard'){$class ='news';}
?>
<div class="col-md-3 col-sm-6 col-xs-6  <?php echo $class; ?>">
    <div id="post-<?php the_ID(); ?>" <?php post_class('video-item') ?>>
        <?php 
            $quick_if = ot_get_option('quick_view_info');
            if($quick_if=='1'){
                echo '
								<div class="qv_tooltip"  title="
									<h4 class=\'gv-title\'>'.esc_attr(get_the_title()).'</h4>
									<div class=\'gv-ex\' >'.esc_attr(get_the_excerpt()).'</div>
									<div class= \'gv-button\'>';
                if($format=='video'){
                    echo  '<div class=\'quick-view\'><a href='.get_permalink().' title=\''.esc_attr(get_the_title()).'\'>'.__('Watch Now','cactusthemes').'</a></div>';
                }else{
                    echo  '<div class=\'quick-view\'><a href='.get_permalink().' title=\''.esc_attr(get_the_title()).'\'>'.__('Read more','cactusthemes').'</a></div>';
                }
                echo '
										<div class= \'gv-link\'>'.quick_view_tm().'</div>
									</div>
									</div>
								">';
            }
        ?>
        <div class="item-thumbnail">
            <a href="<?php the_permalink() ?>">
                <?php
            if(has_post_thumbnail()){
                $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id(),'thumb_520x293', true);
            }else{
                $thumbnail[0]=function_exists('tm_get_default_image')?tm_get_default_image():'';
            }
                ?>
                <img src="<?php echo $thumbnail[0] ?>" alt="<?php the_title_attribute(); ?>" title="<?php the_title_attribute(); ?>">
                <?php if($format=='' || $format =='standard'){ ?>
                <div class="link-overlay fa fa-search"></div>
                <?php }else {?>
                <div class="link-overlay fa fa-play"></div>
                <?php }  ?>
            </a>
            <?php echo tm_post_rating(get_the_ID()); ?>
        </div>
        <?php if($quick_if=='1'){
                  echo '</div>';
              }?>
        <div class="item-head">
            <h3><a href="<?php the_permalink() ?>" rel="<?php the_ID(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
            </h3>
            <div class="item-info hidden">
                <?php if(ot_get_option('blog_show_meta_author',1)){ ?>
                <span class="item-author"><?php the_author_posts_link(); ?></span>
                <?php }
                      if(ot_get_option('blog_show_meta_date',1)){ ?>
                <span class="item-date"><?php the_time(get_option('date_format')); ?></span>
                <?php }?>
                <div class="item-meta">
                    <?php echo tm_html_video_meta(false,false,false,true) ?>
                </div>
            </div>
        </div>
        <div class="item-content hidden"><?php the_excerpt(); ?></div>
        <div class="clearfix"></div>
    </div>
</div>
<!--/col3-->
<?php
			if($loop_count%4==0){ echo '</div><div class="row">';}
        endwhile;
        echo '</div>
			</div>';
        
        
        
    }
}else
{
    $loop_count=0;
    echo '
		<div class="post_ajax_tm" >
		<div class="row">';
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $arg=array(
    'post_type'=>'post',
    'posts_per_page'=>8,
    'paged' => $paged
    );
    if(is_author())
        $arg['author'] = $author;
    else if(is_category()){
        $cat_id = get_query_var('cat');
        $arg['cat'] = "$cat_id";
    }else if(is_tag())
        $arg['tag'] = $tag;
    else{
        $arg['cat'] = '-1';
    }
    $wp_query = new WP_Query($arg);
    while ($wp_query->have_posts()) :
        $wp_query->the_post();
		$loop_count++;
		$class ='';
		$format = get_post_format(get_the_ID());
		
?>
<div class="col-md-3 col-sm-6 col-xs-6 <?php echo $class; ?>">
    <div id="post-<?php the_ID(); ?>" <?php post_class('video-item') ?>>
        <?php 
        $quick_if = ot_get_option('quick_view_info');
        if($quick_if=='1'){
            echo '
						<div class="qv_tooltip"  title="
							<h4 class=\'gv-title\'>'.esc_attr(get_the_title()).'</h4>
							<div class=\'gv-ex\' >'.esc_attr(get_the_excerpt()).'</div>
							<div class= \'gv-button\'>';
            if($format=='video'){
                echo  '<div class=\'quick-view\'><a href='.get_permalink().' title=\''.esc_attr(get_the_title()).'\'>'.__('Watch Now','cactusthemes').'</a></div>';
            }else{
                echo  '<div class=\'quick-view\'><a href='.get_permalink().' title=\''.esc_attr(get_the_title()).'\'>'.__('Read more','cactusthemes').'</a></div>';
            }
            echo '
								<div class= \'gv-link\'>'.quick_view_tm().'</div>
							</div>
							</div>
						">';
        }
        ?>
        <div class="item-thumbnail">
            <a href="<?php the_permalink() ?>">
                <?php
        if(has_post_thumbnail()){
            $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id(),'thumb_520x293', true);
        }else{
            $thumbnail[0]=function_exists('tm_get_default_image')?tm_get_default_image():'';
        }
                ?>
                <img src="<?php echo $thumbnail[0] ?>" alt="<?php the_title_attribute(); ?>" title="<?php the_title_attribute(); ?>">
                <?php if($format=='' || $format =='standard'){ ?>
                <div class="link-overlay fa fa-search"></div>
                <?php }else {?>
                <div class="link-overlay fa fa-play"></div>
                <?php }  ?>
            </a>
            <?php echo tm_post_rating(get_the_ID()); ?>
        </div>
        <?php if($quick_if=='1'){
                  echo '</div>';
              }?>
        <div class="item-head">
            <h3><a href="<?php the_permalink() ?>" rel="<?php the_ID(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
            </h3>
            <div class="item-info hidden">
                <?php if(ot_get_option('blog_show_meta_author',1)){ ?>
                <span class="item-author"><?php the_author_posts_link(); ?></span>
                <?php }
                      if(ot_get_option('blog_show_meta_date',1)){ ?>
                <span class="item-date"><?php the_time(get_option('date_format')); ?></span>
                <?php }?>
                <div class="item-meta">
                    <?php echo tm_html_video_meta(false,false,false,true) ?>
                </div>
            </div>
        </div>
        <div class="item-content hidden"><?php the_excerpt(); ?></div>
        <div class="clearfix"></div>
    </div>
</div>
<!--/col3-->
<?php
		if($loop_count%4==0){ echo '</div><div class="row">';}
    endwhile;
    echo '</div>';
    tm_display_ads('ad_recurring');
    echo '</div>';
    
}
?>