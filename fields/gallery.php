<?php  
/**
 * gallery Field 
 */


class remo_gallery extends remo_inputs_base
{

	function __construct()
	{
		// vars
		$this->name = 'gallery'; //Configurar en cada Input
		$this->label = __("gallery",'acf');
		$this->defaults = array(
			'default_value'	=>	'',
			'formatting' 	=>	'html',
			'maxlength'		=>	'',
			'placeholder'	=>	'',
			'prepend'		=>	'',
			'append'		=>	''
		);
		
		
		// do not delete!
    	parent::__construct();
	}


	function scripts_field_custom()
	{
		// register acf scripts
		wp_register_script( 'acf-input-gallery', REMO_INPUTS_JS . '/gallery/input.js', array(), 1 );
		wp_register_style( 'acf-input-gallery', REMO_INPUTS_CSS . '/gallery/input.css', array(), 1 ); 
		
		
		// scripts
		wp_enqueue_script(array(
			'acf-input-gallery',	
		));

		// styles
		wp_enqueue_style(array(
			'acf-input-gallery',	
		));
		
	}


	function format_value( $value )
	{
		$new_value = array();
		
		
		// empty?
		if( empty($value) || !is_array($value) )
		{
			return $value;
		}
		
		
		// find attachments (DISTINCT POSTS)
		$attachments = get_posts(array(
			'post_type' => 'attachment',
			'numberposts' => -1,
			'post_status' => null,
			'post__in' => $value,
		));
		
		$ordered_attachments = array();
		foreach( $attachments as $attachment)
		{
			// create array to hold value data
			$ordered_attachments[ $attachment->ID ] = array(
				'id'			=>	$attachment->ID,
				'alt'			=>	get_post_meta($attachment->ID, '_wp_attachment_image_alt', true),
				'title'			=>	$attachment->post_title,
				'caption'		=>	$attachment->post_excerpt,
				'description'	=>	$attachment->post_content,
				'mime_type'		=>	$attachment->post_mime_type,
			);
			
		}
		
		
		// override value array with attachments
		foreach( $value as $v)
		{
			if( isset($ordered_attachments[ $v ]) )
			{
				$new_value[] = $ordered_attachments[ $v ];
			}
		}
		
		
		// return value
		return $new_value;	
	}



	function format_value_for_api( $value )
	{
		$value = $this->format_value( $value );
		
		// find all image sizes
		$image_sizes = get_intermediate_image_sizes();


		if( $value )
		{
			foreach( $value as $k => $v )
			{
				if( strpos($v['mime_type'], 'image') !== false )
				{
					// is image
					$src = wp_get_attachment_image_src( $v['id'], 'full' );
					
					$value[ $k ]['url'] = $src[0];
					$value[ $k ]['width'] = $src[1];
					$value[ $k ]['height'] = $src[2];
					
					
					// sizes
					if( $image_sizes )
					{
						$value[$k]['sizes'] = array();
						
						foreach( $image_sizes as $image_size )
						{
							// find src
							$src = wp_get_attachment_image_src( $v['id'], $image_size );
							
							// add src
							$value[ $k ]['sizes'][ $image_size ] = $src[0];
							$value[ $k ]['sizes'][ $image_size . '-width' ] = $src[1];
							$value[ $k ]['sizes'][ $image_size . '-height' ] = $src[2];
						}
						// foreach( $image_sizes as $image_size )
					}
					// if( $image_sizes )
				}
				else
				{
					// is file
					$src = wp_get_attachment_url( $v['id'] );
					
					$value[ $k ]['url'] = $src;
				}	
			}
			// foreach( $value as $k => $v )
		}
		// if( $value )
		
		
		// return value
		return $value;
	}


	function set_value_filter($field, $value){

		$value =  $this->format_value_for_api( unserialize($value) );

		return $value;

	}

	
	function create_field($fieldkey, $field, $value){

		$id = $fieldkey;
		$name = $this->name($fieldkey, $field);

		//var_dump($value);

		?>
		<div class="acf-gallery" data-preview_size="thumbnail" data-library="uploadedTo">
			
			<input type="hidden" name="<?php echo $name; ?>" value="" />
			
			<div class="thumbnails">
				<div class="inner clearfix">
				<?php if( $value ): foreach( $value as $attachment ): 
					
					$src = '';
					
					if( strpos($attachment['mime_type'], 'image') !== false )
					{
						$src = wp_get_attachment_image_src( $attachment['id'], 'thumbnail' );
						$src = $src[0];
					}
					else
					{
						$src = wp_mime_type_icon( $attachment['id'] );
					}

					?>
					<div class="thumbnail" data-id="<?php echo $attachment['id']; ?>">
						<input class="acf-image-value" type="hidden" name="<?php echo $name; ?>[]" value="<?php echo $attachment['id']; ?>" />
						<div class="inner clearfix">
							<img src="<?php echo $src; ?>" alt="" />
							<div class="list-data">
								<table>
									<tbody>
									<tr>
										<th><label><?php _e("Title",'acf'); ?>:</label></th>
										<td class="td-title"><?php echo $attachment['title']; ?></td>
									</tr>
									<tr>
										<th><label><?php _e("Alternate Text",'acf'); ?>:</label></th>
										<td class="td-alt"><?php echo $attachment['alt']; ?></td>
									</tr>
									<tr>
										<th><label><?php _e("Caption",'acf'); ?>:</label></th>
										<td class="td-caption"><?php echo $attachment['caption']; ?></td>
									</tr>
									<tr>
										<th><label><?php _e("Description",'acf'); ?>:</label></th>
										<td class="td-description"><?php echo $attachment['description']; ?></td>
									</tr>
									</tbody>
								</table>
							</div>
						</div>
						<div class="hover">
							<ul class="bl">
								<li><a href="#" class="acf-button-delete ir"><?php _e("Remove",'acf'); ?></a></li>
								<li><a href="#" class="acf-button-edit ir"><?php _e("Edit",'acf'); ?></a></li>
							</ul>
						</div>
						
					</div>
				<?php endforeach; endif; ?>
				</div>
			</div>

			<div class="toolbar">
				<ul class="hl clearfix">
					<li class="add-image-li"><a class="acf-button add-image" href="#"><?php _e("Add Image",'acf'); ?></a></li>
					<li class="gallery-li view-grid-li active"><div class="divider divider-left"></div><a class="ir view-grid" href="#"><?php _e("Grid",'acf'); ?></a><div class="divider"></div></li>
					<li class="gallery-li view-list-li"><a class="ir view-list" href="#"><?php _e("List",'acf'); ?></a><div class="divider"></div></li>
					<li class="gallery-li count-li right">
						<span class="count"></span>
					</li>
				</ul>
			</div>
			
			<script type="text/html" class="tmpl-thumbnail">
			<div class="thumbnail" data-id="{id}">
				<input type="hidden" class="acf-image-value" name="<?php echo $name; ?>[]" value="{id}" />
				<div class="inner clearfix">
					<img src="{url}" alt="{alt}" />
					<div class="list-data">
						<table>
						<tbody>
							<tr>
								<th><label><?php _e("Title",'acf'); ?>:</label></th>
								<td class="td-title">{title}</td>
							</tr>
							<tr>
								<th><label><?php _e("Alternate Text",'acf'); ?>:</label></th>
								<td class="td-alt">{alt}</td>
							</tr>
							<tr>
								<th><label><?php _e("Caption",'acf'); ?>:</label></th>
								<td class="td-caption">{caption}</td>
							</tr>
							<tr>
								<th><label><?php _e("Description",'acf'); ?>:</label></th>
								<td class="td-description">{description}</td>
							</tr>
						</tbody>
					</table>
					</div>
				</div>
				<div class="hover">
					<ul class="bl">
						<li><a href="#" class="acf-button-delete ir"><?php _e("Remove",'acf'); ?></a></li>
						<li><a href="#" class="acf-button-edit ir"><?php _e("Edit",'acf'); ?></a></li>
					</ul>
				</div>
				
			</div>
			</script>
			
		</div>
		<?php

		/*
		if($field['etiqueta'] == false){

			echo '<input type="gallery" class="'.$field['etiqueta'].'" id="'.$id.'" name="'.$name.'" value="'.$value.'">';

		}else {

			echo '<div class="form-group">
			    <label for="'.$id.'" >'.$field['etiqueta'].'</label>
			    <input type="gallery" class="'.$field['etiqueta'].'" id="'.$id.'" name="'.$name.'" value="'.$value.'">
			</div>';

		}
		*/

	}


	function update_value_filter($field, $value){


		return serialize($value);


	}




}


new remo_gallery();
