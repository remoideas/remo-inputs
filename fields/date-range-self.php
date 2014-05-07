<?php  
/**
 * Text Field 
 */


class remo_date_range_safe extends remo_inputs_base
{

	function __construct()
	{
		// vars
		$this->name = 'date-range-self'; //Configurar en cada Input
		$this->label = __("Date Range Self",'acf');
		$this->defaults = array(
			'default_value'	=>	''
		);
		
		
		// do not delete!
    	parent::__construct();
	}


	/**
	 * Inserta Script y Styles necesarios 
	 * @return Any
	 */
	function scripts_field_custom(){
		wp_register_style( 'daterangepicker', REMO_INPUTS_CSS . '/daterangepicker.css', array('bootstrap-admin'), '1.0.0' );
		wp_enqueue_style( 'daterangepicker' );

		wp_register_script( 'daterangepicker', REMO_INPUTS_JS . '/daterangepicker.js', array('bootstrap-admin'), '1.0.0', true );
		wp_register_script( 'moment', REMO_INPUTS_JS . '/moment.min.js', array('bootstrap-admin'), '1.0.0', true );

		wp_enqueue_script( 'daterangepicker' );
		wp_enqueue_script( 'moment' );
	}

	/**
	 * set_value
	 * 
	 * Set value para este field.
	 */


	function set_value($fieldkey, $field){


		$campos = explode('/', $field['campo']);


		//Get fecha_inicio

		$field['campo'] = $campos[0];

		$fecha_inicio =  $this->set_value_custom_table($field, $value);


		//Get Fecha Final
		
		$field['campo'] = $campos[1];

		$fecha_fin =  $this->set_value_custom_table($field, $value);

		return $fecha_inicio . ' a ' . $fecha_fin;
	}


	/**
	 * create_field
	 *
	 * Función que crea el Input
	 */

	function create_field($fieldkey, $field, $value){

		$id = $this->sanitize($field['etiqueta']);
		$name = $this->name($fieldkey, $field);

		?>

	    <div class="form-group">
	     <label for="<?php echo $id ?>" ><?php echo $field['etiqueta'] ?></label>
	     <input type="text" class="<?php echo $field['etiqueta'] ?> datarangepicker" id="<?php echo $id ?>" name="<?php echo $name ?>" value="<?php echo $value ?>">
	    </div>

	    <?php

		}

}


new remo_date_range_safe();
