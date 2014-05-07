<?php  
/**
 * Text Field 
 */


class remo_money extends remo_inputs_base
{

	function __construct()
	{
		// vars
		$this->name = 'money'; //Configurar en cada Input
		$this->label = __("money",'acf');
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

		if($field['etiqueta'] == false){

			echo '<input type="text" class="'.$field['etiqueta'].'" id="'.$id.'" name="'.$name.'" value="'.$value.'">';

		}else {

			echo '<div class="form-group">
			    <label for="'.$id.'" >'.$field['etiqueta'].'</label>
			    <input type="text" class="'.$field['etiqueta'].'" id="'.$id.'" name="'.$name.'" value="'.$value.'">
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


new remo_money();
