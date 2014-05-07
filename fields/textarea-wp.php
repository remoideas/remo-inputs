<?php  
/**
 * Text Field 
 */


class remo_textarea_wp extends remo_inputs_base
{

	function __construct()
	{
		// vars
		$this->name = 'textarea-wp'; //Configurar en cada Input
		$this->label = __("Textarea-wp",'acf');
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

		$content = $value;
		$editor_id = $fieldkey;

		wp_editor( $content, $editor_id, array('textarea_name' => $name ) ); //Id can`t be some[somemore]
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


new remo_textarea_wp();
