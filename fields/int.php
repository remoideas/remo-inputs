<?php  
/**
 * Text Field 
 */


class remo_int extends remo_inputs_base
{

	function __construct()
	{
		// vars
		$this->name = 'int'; //Configurar en cada Input
		$this->label = __("Int",'acf');
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
		$readonly = ($field['readonly'] == true)?'readonly':'';

		if($field['etiqueta'] == false){

			if($field['hidden'] == true){
				echo '<input type="hidden" id="'.$id.'" name="'.$name.'" value="'.$value.'" '.$readonly.'>';
			}else{
				echo '<input type="text" class="'.$field['etiqueta'].'" id="'.$id.'" name="'.$name.'" value="'.$value.'" '.$readonly.'>';
			}
			

		}else {

			echo '<div class="form-group">
			    <label for="'.$id.'" >'.$field['etiqueta'].'</label>
			    <input type="text" class="'.$field['etiqueta'].'" id="'.$id.'" name="'.$name.'" value="'.$value.'" '.$readonly.'>
			</div>';

		}

	}

	/**
	 * sql_type
	 *
	 * Conocer que tipo de valor es, por default será varchar. 
	 */

	function sql_type($campo){

		return $campo. ' INT NULL';
	}


}


new remo_int();
