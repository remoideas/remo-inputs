<?php  
/**
 * Text Field 
 */


class remo_ID extends remo_inputs_base
{

	function __construct()
	{
		// vars
		$this->name = 'ID'; //Configurar en cada Input
		$this->label = __("ID",'acf');
		$this->defaults = array(
			'default_value'	=>	''
		);
		
		
		// do not delete!
    	parent::__construct();
	}


	/**
	 * save_field
	 */

	function update_value($field, $value){


		exit;
	}


	/**
	 * Scripts
	 */
	function scripts_field_custom(){

		//exit;


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

		<input type="hidden" name="<?php echo $name ?>" value="<?php echo $value ?>">

		<?php
	}

	/**
	 * sql_type
	 *
	 * Conocer que tipo de valor es, por default será varchar. 
	 */

		function sql_type($campo){

		return $campo. ' INT NOT NULL';
	}


}


new remo_ID();
