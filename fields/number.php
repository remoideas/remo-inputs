<?php  
/**
 * Text Field 
 */


class remo_number extends remo_inputs_base
{

	function __construct()
	{
		// vars
		$this->name = 'number'; //Configurar en cada Input
		$this->label = __("Number",'acf');
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

	
	function create_field($fieldkey, $field, $value){

		$id = $fieldkey;
		$name = $this->name($fieldkey, $field);
		$disable = ($field['disabled'] == true)? 'disabled':'';

		if($field['etiqueta'] == false){

			echo '<input type="text" class="'.$field['etiqueta'].'" id="'.$id.'" name="'.$name.'" value="'.$value.'" '.$disable.' >';

		}else {

			echo '<div class="form-group">
			    <label for="'.$id.'" >'.$field['etiqueta'].'</label>
			    <input type="text" class="'.$field['etiqueta'].'" id="'.$id.'" name="'.$name.'" value="'.$value.'" '.$disable.' >
			</div>';

		}

	}

	/**
	 * sql_type
	 *
	 * Conocer que tipo de valor es, por default será varchar. 
	 */

	function sql_type($campo){

		return $campo. ' DECIMAL(10,2) NULL';
	}


}


new remo_number();
