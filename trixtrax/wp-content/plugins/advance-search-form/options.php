<?php 
function asf_settings_page(){
?>
	<div class="asf-form wrap">
	<h2><?php echo __('Advance Search Form','asf');?></h2>
	<form method="post" action="options.php"> 
	<?php 
	settings_fields( 'asf-settings-group' );
	do_settings_fields( 'asf-settings-group','asf-settings-group');
?>
<table cellspacing="0" cellpadding="0">
	<tr>
		<td><label for="post-types"><?php echo __('Post Types to search:','asf');?></label></td>
		<td>
		<?php			
			$selected_post_types = get_option('asf-post-types',array()); // get current values
			
			$post_types = get_post_types(array('_builtin'=>false),'objects');
			$obj = (object)(array("query_var"=>"post","name"=>"post","label"=>"Post"));		
			array_unshift($post_types,$obj); // insert $obj at first index
		
			foreach ($post_types as $post_type ) {
				
				$selected = '';
				$query_var = (empty($post_type->query_var) ? $post_type->name:$post_type->query_var);
				if (is_array($selected_post_types)) {
					foreach ($selected_post_types as $type) {							
						if((is_array($type) && in_array($query_var,$type)) || $type==$query_var) {
							$selected = 'checked="checked"';
							break;
						}
					}
				}
			?>							
			<input type="checkbox" name="asf-post-types[]" value="<?php echo $query_var;?>" <?php echo $selected;?> /> <?php echo $post_type->label;?>
			<br/>
				<?php					  
			}
		?>
		</td>
	</tr>
	<tr>
		<td><label for="post-types"><?php echo __('Search in categories: <br/><i>If leave empty, all categories will be searched</i><br/>'); ?></label></td>	
		<td>
			<div style="height:120px;overflow-y:scroll">
<?php
	$categories = get_terms(array('category'));// get all categories
	$select_categories = get_option('asf-categories',array()); // current selected categories

	foreach ($categories as $cat ) {
		
		$selected = '';
		if (is_array($select_categories)) {
		
			foreach ($select_categories as $select) {
				if((is_array($select) && in_array($cat->term_id,$select)) || $select==$cat->term_id){
					$selected = 'checked="checked"';
					break;
				}
			}
		}
	?>
	<input type="checkbox" name="asf-categories[]" value="<?php echo $cat->term_id;?>" <?php echo $selected;?> /> <?php echo $cat->name;?>
	<br/>
	<?php					  
	}
?>
			</div>
		</td>
	</tr>
	<tr>
		<td><label><?php echo __('Label Text');?></td>
		<td><input type="textbox" name="asf-label" value="<?php echo get_option('asf-label');?>"/></td>
	</tr>
	<tr>
		<td><label><?php echo __('Button Text');?></td>
		<td><input type="textbox" name="asf-button-text" value="<?php echo get_option('asf-button-text');?>"/></td>
	</tr>
	<tr>
		<td><label><?php echo __('Placeholder Text In Textbox');?></td>
		<td><input type="textbox" name="asf-placeholder-text" value="<?php echo get_option('asf-placeholder-text');?>"/></td>
	</tr>
	<tr>
		<td><label><?php echo __('Show Category List');?></td>
		<td><input type="checkbox" name="asf-show-categories" <?php echo get_option('asf-show-categories') ? 'checked="checked"':'';?>/></td>
	</tr>
	<tr>
		<td><label><?php echo __('Show Tag Filter');?></td>
		<td><input type="checkbox" name="asf-show-tag-filter" <?php echo get_option('asf-show-tag-filter') ? 'checked="checked"':'';?>/></td>
	</tr>
	<tr>
		<td><label><?php echo __('Highlight Results');?></td>
		<td><input type="checkbox" name="asf-highlight-results" <?php echo get_option('asf-highlight-results') ? 'checked="checked"':'';?>/></td>
	</tr>
	<tr>
		<td><label><?php echo __('Ajax Search Suggestion');?></td>
		<td><input type="checkbox" name="asf-ajax-suggestion" <?php echo get_option('asf-ajax-suggestion') ? 'checked="checked"':'';?>/></td>
	</tr>
	<tr>
		<td><label><?php echo __('Maximum Ajax Results');?></td>
		<td><input type="text" name="asf-ajax-count" value="<?php echo get_option('asf-ajax-count');?>"/></td>
	</tr>
	<tr>
		<td><label><?php echo __('Load Default CSS');?></td>
		<td><input type="checkbox" name="asf-load-css" <?php echo get_option('asf-load-css') ? 'checked="checked"':'';?>/></td>
	</tr>
	<tr>
		<td colspan="2">
<p><?php echo __('Advance search form will auto include these following strings into suggestion list:','asf');?></p>
<ul style="list-style: initial;margin-left: 40px;">
	<li>Post title</li>
	<li>Taxonomy title (page, category, tag)</li>
	<li>Custom Post title</li>
	<li>Custom taxonomy title</li>
</ul>
<p>If you want to add addition list of words, please enter in textarea below. One word a line</p>
		</td>
	</tr>
	<tr>
		<td><label for="asf-custom-words"><?php echo __('Custom Words','asf');?></label></td>
		<td><textarea name="asf-custom-words" cols="50" rows="15"><?php echo get_option('asf-custom-words'); ?></textarea>
		</td>
	</tr>
	<tr>
		<td><label><?php echo __('Custom CSS','asf');?></td>
		<td><textarea name="asf-custom-css" cols="50" rows="10"><?php echo get_option('asf-custom-css'); ?></textarea>
</table>

<?php
	submit_button(); 
	?>
	</form>
	</div>
<?php }?>