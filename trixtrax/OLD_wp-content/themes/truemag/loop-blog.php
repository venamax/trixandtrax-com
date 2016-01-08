<?php
global $wp_query;
global $listing_query;
if($listing_query){
	$wp_query = $listing_query;
}else if (!is_author()){
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $arg=array(
        'cat' => '1',
	    'post_type'=>'post',
		'posts_per_page'=>15,
        'paged' => $paged,
		'meta_key'=> 'event_begin',
		'orderby'=>'meta_value',
		'order'=>'ASC' 
        );
    // DATES
	$meta_query=array(
	    array(
		    'key' => 'event_end',
		    'value' => '',
		    'compare' => '!=' ),
		array(
		    'key' => 'event_begin',
		    'value' => '',
		    'compare' => '!=' )
	);
    $meta_query[]=array(
	    'key' => 'event_end',
		'value' => date('Y-m-d'),
		'compare' => '>=',
		//'type'=>'DATETIME' 
        );
    $arg['meta_query']=$meta_query;
    $wp_query = new WP_Query($arg);
}else{
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    
}
echo '<div class="post_ajax_tm" >';
global $welcu_url;
//die($wp_query->request);
while ($wp_query->have_posts()) :
    $wp_query->the_post(); 
    $welcu = get_post_custom_values('welcu_url');
    $time = get_post_meta($post->ID,'event_begin',true);
    $now = date('Y-m-d');
    
    if($time > $now)
        $welcu_url = $welcu[0];
?>
	<div id="post-<?php the_ID(); ?>" <?php post_class('blog-item video-item'.(has_post_thumbnail()?'':' no-thumbnail')) ?>>
      <div class="row">
        <div class="col-md-6 col-sm-6">
            <div class="item-thumbnail">
                <?php get_template_part('blog-thumbnail'); ?>
            </div>
            <div class="clearfix"></div>
        </div><!--/col6-->
        <div class="col-md-6 col-sm-6">
            <div class="item-head row">
                <div class="col-md-10 col-sm-10 col-xs-9">
                    <h3><a class="maincolor2hover" target="<?php if($welcu_url) echo '_blank'; else '_self'; ?>" href="<?php if($welcu_url) echo $welcu_url; else echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" rel="<?php the_ID(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
                    <div class="blog-meta">
                    	<?php if(ot_get_option('blog_show_meta_author',1)){ ?>
                        <span><?php the_author_posts_link(); ?></span>
                        <?php }?>
                        <?php if(ot_get_option('blog_show_meta_comment',1)){?>
                        <?php if(comments_open()){ ?> | 
                        <span><a href="<?php comments_link(); ?>"><?php comments_number(__('0 Comments','cactusthemes'),__('1 Comment','cactusthemes')); ?></a></span>
                        <?php } //check comment open 
						}?>
                    </div>
                </div>
                <div class="col-md-2 col-sm-2 col-xs-3">
                <?php if(ot_get_option('blog_show_meta_date',1)){ ?>
                    <div class="blog-date">
                        <?php 
                        $translate = 'es_ES'; ?>
                        <span><?php echo mysql2date('d', $time, $translate); ?></span>
                        <span><?php echo mysql2date('M', $time, $translate); ?></span>
                    </div>
                <?php } ?>
                </div>
            </div>
            <div class="blog-excerpt">
                <?php the_excerpt(); 
                      
                      if($welcu_url){
                ?>
                <a href="<?php echo $welcu_url; ?>" target="_blank" class="readmore maincolor2 bordercolor2 bgcolor2hover bordercolor2hover"><?php echo 'Inscribete'; ?> <i class="fa fa-angle-right"></i></a>
                <?php } ?>
            </div>
        </div><!--/col6-->
      </div><!--/row-->
      <div class="clearfix"></div>
    </div><!--/blog-item-->
<?php
endwhile;
tm_display_ads('ad_recurring');
echo '</div>';
?>