<?php

class Pizarra_Widget extends WP_Widget {	

	function __construct() {
    	$widget_ops = array(
			'classname'   => 'pizarra_widget', 
			'description' => __('Mostrar la pizarra ')
		);
    	parent::__construct('pizarra_posiciones', __('Pizarra Posiciones'), $widget_ops);
        add_action( 'wp_enqueue_scripts', array( &$this, 'register_styles' ) );
	}
    
    function register_styles() {
		wp_register_style( 'pizarra_style', plugins_url( 'truemag-movie/css/pizarra.css' ) );
		wp_enqueue_style( 'pizarra_style' );
		
	}
    
    function form( $instance ) {
        if( $instance) {
            $image_url = esc_attr($instance['image_url']);
        } else {
            $image_url = '';
        }
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('image_url'); ?>"><?php _e('Image Url', 'pizarra_widget'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('image_url'); ?>" name="<?php echo $this->get_field_name('image_url'); ?>" type="text" value="<?php echo $image_url; ?>" />
        </p>
        <?php
	}
    
    function update($new_instance, $old_instance) 
    {
      $instance = $old_instance;
      $instance['image_url'] = strip_tags($new_instance['image_url']);
      return $instance;
    }

    
	function widget($args, $instance) {
        global $wpdb;
		extract( $args );
        $image_url = apply_filters('widget_image_url', $instance['image_url']);
        $rows = $wpdb->get_results("select DISTINCT t.name, t.slug, SUM(l.value) as likes from wp_posts p inner join
        wp_term_relationships tr ON p.ID=tr.object_id inner join
        wp_term_taxonomy tt ON tr.term_taxonomy_id=tt.term_taxonomy_id AND taxonomy='post_tag' inner join
        wp_terms t ON tt.term_id=t.term_id AND t.name like '@%' inner join
        wp_wti_like_post l on p.ID=l.post_id GROUP BY t.name ORDER BY likes DESC");
        $index = 1;
        ?>
<div id="pizarra">
    	<div class="fondo-pizarra">
        	<div class="titulo-pizarra" style="text-align:center;">
                <img src="<?php echo $image_url; ?>"></div>
            <div id="scrollbar1">
           			<div class="scrollbar"><div class="track"><div class="thumb"><div class="end"></div></div></div></div>
					<div class="viewport">
			 		<div class="overview">
                    <div class="lista-colegios">
                        <?php
                            $pos = 0;
                            $last = ''; 
                            foreach($rows as $row){
                                $imagen = $wpdb->get_row("select guid from wp_posts WHERE post_title='$row->name' AND post_type='attachment'");
                                $class = 'odd';
                                if($index % 2 == 0)
                                    $class = 'even';
                                if (strcmp($last, $row->likes) == 0){
                                    $pos = $pos;
                                }else {
                                    $pos=$pos+1;
                                }
                                $last = $row->likes;
                        ?>

                                <a href="/tag/<?php echo $row->slug; ?>/">
                                    <div class="item">
                                        <div style="width:20%" class="posi-bg"><div class="puntos"></div><div style="color:#FFFFFF;" class="puntos-cantidad"><?php echo $pos.'.-'; ?></div></div>
                                        <div style="width:20%" class="colegio"><img style="border-radius:50%; width:56px" src="<?php echo $imagen->guid; ?>"></div>
                                        <div style="width:40%" class="info"><div class="nombre-colegio"><?php echo substr(str_replace('-', ' ', str_replace('@', '', $row->name)),0,10); ?></div></div>
                                        <div style="width:20%" class="puntos-bg"><div class="puntos">LIKES</div><div class="puntos-cantidad"><?php echo $row->likes; ?></div></div>
                                    </div>
                                </a>
                        <?php
                                $index++;
                            }
                        
                        ?>
                       
                    </div> <!-- fin lista-colegios -->
        		
                </div></div><!-- fin viewport/overview -->
                
                </div><!-- fin scrollbar1 -->
        </div><!-- fin fondo-pizarra -->
        
         <div class="inf-pizarra">
      	  <div class="ico-potazo"></div>
          <div class="info"><div class="texto-verde">AÑO ESCOLAR 2014-2015:</div> Dale like a los videos favoritos.</div>
        </div>

    
    </div>
        <?php
        
	}
	
	function flush_widget_cache() {
		wp_cache_delete('widget_custom_type_posts', 'widget');
	}
}

// register Pizarra widget
add_action( 'widgets_init', create_function( '', 'return register_widget("Pizarra_Widget");' ) );
?>