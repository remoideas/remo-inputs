<?php  
/**
 * Text Field 
 */


class remo_date_range extends remo_inputs_base
{

	function __construct()
	{
		// vars
		$this->name = 'date-range'; //Configurar en cada Input
		$this->label = __("Date Range",'acf');
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
		wp_register_style( 'jquery-ui-datepicker', REMO_INPUTS_CSS . '/jquery-ui-1.10.3.custom.css', array('bootstrap-admin'), '1.0.0' );
		wp_enqueue_style( 'jquery-ui-datepicker' );
		wp_enqueue_script( 'jquery-ui-datepicker' );
	}


	/**	function sql_type($campo){

		return $campo. ' VARCHAR(100) NOT NULL';
	}
	 * create_field
	 *
	 * Función que crea el Input
	 */

	function create_field($fieldkey, $field, $value){

		$id = $fieldkey;
		$name = $this->name($fieldkey, $field);

		$value_alt_array = explode('-', $value ); 
		$value_alt = $value_alt_array[2] . '-' . $value_alt_array[1] . '-' . $value_alt_array[0];

		?>

	    <div class="form-group">
	     <label for="<?php echo $id ?>" ><?php echo $field['etiqueta'] ?></label>
	     <input type="hidden" class="datapickeralt-<?php echo $field['destination'] ?>" id="<?php echo $id ?>" name="<?php echo $name ?>" value="<?php echo $value ?>">
	     <input type="text" class="<?php echo $field['etiqueta'] ?> datapicker-<?php echo $field['destination'] ?>" value="<?php echo $value_alt ?>">
	    </div>

	    <?php

		}



	/**
	 * sql_type
	 *
	 * Conocer que tipo de valor es, por default será varchar. 
	 */

	function sql_type($campo){

		return $campo. ' DATE NULL';
	}

}


new remo_date_range();
