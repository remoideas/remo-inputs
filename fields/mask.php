<?php  
/**
 * Text Field 
 */


class remo_mask extends remo_inputs_base
{

	function __construct()
	{
		// vars
		$this->name = 'mask'; //Configurar en cada Input
		$this->label = __("Mask",'acf');
		$this->defaults = array(
			'default_value'	=>	''
		);
		
		
		// do not delete!
    	parent::__construct();
	}


	/**
	 * Scripts
	 */
	function scripts_field_custom(){
		wp_register_script( 'mask', REMO_INPUTS_JS . '/jquery.maskedinput.min.js', array('jquery'), '1.0.0', true );
		wp_enqueue_script( 'mask' );
	}


	/**
	 * create_field
	 *
	 * Función que crea el Input
	 */
	function create_field($fieldkey, $field, $value){

		$id = $fieldkey;
		$name = $this->name($fieldkey, $field);

		?>
	
		<script type="text/javascript">jQuery(document).ready(function($){$("#<?php echo $id ?>").mask("<?php echo $field['mask'] ?>");});</script>

		

		<div class="form-group">
		    <label for="<?php echo $id ?>" ><?php echo $field['etiqueta'] ?></label>
		    <input type="text" class="<?php echo $field['etiqueta'] ?>" id="<?php echo $id ?>" name="<?php echo $name ?>" value="<?php echo $value ?>">
		</div>

		<?php
	}

}


new remo_mask();
