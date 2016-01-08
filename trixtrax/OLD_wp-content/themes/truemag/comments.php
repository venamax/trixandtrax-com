<?php
/**
 * The template for displaying Comments.
 *
 * The area of the page that contains both current comments
 * and the comment form. The actual display of comments is
 * handled by a callback to twentytwelve_comment() which is
 * located in the functions.php file.
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() )
	return;
if (!comments_open())
	return;
?>

<div id="comments" class="comments-area">
    <h4 class="count-title"><?php echo comments_number('');?></h4>
	<div class="comment-form-tm">
	<?php $ycom= 'Your comment ...'; ?>
	<?php comment_form_tm(array('logged_in_as'=>'','comment_notes_before'=>'','comment_field'=>'
	
	<p class="comment-form-comment"><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true" onblur="if(this.value == \'\') this.value = \''.__('Your comment ...','cactusthemes').'\';" onfocus="if(this.value == \''.__('Your comment ...','cactusthemes').'\') this.value = \'\'; jQuery(\'.comment-form-tm .collapse\').addClass(\'in\');">'.__('Your comment ...','cactusthemes').'</textarea></p>','title_reply'=>'','id_submit'=>'comment-submit')); ?>
    <script type="text/javascript">
		jQuery(document).ready(function(e) {
			jQuery( "#comment-submit" ).click(function() {
				var $a = jQuery("#comment").val();
				var $b = "<?php echo $ycom ?>";
				if ( $a == $b){
					jQuery("#comment").val('');
				}
			  //alert( $a );
			});
		});	
	</script>
    </div>
	<?php if ( have_comments() ) : ?>
		<ul class="commentlist">
			<?php wp_list_comments( array( 'callback' => 'cactusthemes_comment', 'style' => 'ul' ) ); ?>
		</ul><!-- .commentlist -->

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav id="comment-nav-below" class="navigation" role="navigation">
			<h1 class="assistive-text section-heading"><?php _e( 'Comment navigation', 'cactusthemes' ); ?></h1>
			<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'cactusthemes' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'cactusthemes' ) ); ?></div>
		</nav>
		<?php endif; // check for comment navigation ?>
	<?php endif; // have_comments() ?>
	<div style="display:none !important"><?php comment_form();?></div>

</div><!-- #comments .comments-area -->