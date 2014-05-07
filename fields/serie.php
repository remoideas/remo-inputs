<?php  
/**
 * Text Field 
 */


class remo_serie extends remo_inputs_base
{

	function __construct()
	{
		// vars
		$this->name = 'serie'; //Configurar en cada Input
		$this->label = __("Serie",'acf');
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

	
	/**
	 * create_field
	 *
	 * Función que crea el Input
	 */
	function create_field($fieldkey, $field, $value){

		$id = $fieldkey;
		$name = $this->name($fieldkey, $field);
		$disabled = (!empty($value))?'disabled':'';

		?>
	
		<div class="form-group">
		   <label for="<?php echo $id ?>" ><?php echo $field['etiqueta'] ?></label>
		   <select class="selectpicker show-tick" data-container="body" id="<?php echo $id ?>" name="<?php echo $name ?>" <?php echo $disabled ?>>      
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


new remo_serie();
