<?php  
/**
 * Text Field 
 */


class remo_textarea extends remo_inputs_base
{

	function __construct()
	{
		// vars
		$this->name = 'textarea'; //Configurar en cada Input
		$this->label = __("Textarea",'acf');
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

	}


	/**
	 * create_field
	 *
	 * Función que crea el Input
	 */
	function create_field($fieldkey, $field, $value){

		$id = $fieldkey;
		$name = $this->name($fieldkey, $field);

		echo '<div class="form-group">
			    <label for="'.$id.'" >'.$field['etiqueta'].'</label>
			    <textarea class="'.$field['etiqueta'].'" id="'.$id.'" name="'.$name.'">'.$value.'</textarea>
			</div>';
	}

	/**
	 * sql_type
	 *
	 * Conocer que tipo de valor es, por default será varchar. 
	 */

	function sql_type($campo){

		return $campo. ' LONGTEXT NULL';
	}


}


new remo_textarea();
