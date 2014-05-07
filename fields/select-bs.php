<?php  
/**
 * Text Field 
 */


class remo_select_bs extends remo_inputs_base
{

	function __construct()
	{
		// vars
		$this->name = 'select-bs'; //Configurar en cada Input
		$this->label = __("Select_bs",'acf');
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
		//wp_register_style( 'select-bs', REMO_INPUTS_CSS . '/bootstrap-select.min.css', array('bootstrap-admin'), '1.0.0' );
		//wp_enqueue_style( 'select-bs' );

		//wp_register_script( 'select-bs', REMO_INPUTS_JS . '/bootstrap-select.min.js', array('bootstrap-admin'), '1.0.0', true );
		//wp_enqueue_script( 'select-bs' );
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
	
		<div class="form-group">
		   <label for="<?php echo $id ?>" ><?php echo $field['etiqueta'] ?></label>
		   <select class="selectpicker show-tick" data-container="body" id="<?php echo $id ?>" name="<?php echo $name ?>">      
		     <?php foreach( $field['options'] as $key => $text): 
		     $selected = ($key == $value)?'selected':'';
		     ?>
		     <option value="<?php echo $key ?>" <?php echo $selected ?>><?php echo $text ?></option>
		     <?php endforeach; ?>
		    </select>
		</div>

		<?php
	}

}


new remo_select_bs();
