<?php  
/**
 * Text Field 
 */


class remo_serial extends remo_inputs_base
{

	function __construct()
	{
		// vars
		$this->name = 'serial'; //Configurar en cada Input
		$this->label = __("Serial",'acf');
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

		if(empty($field['serie'])){
			$value_completo = $value;
		} else {
			$value_completo = $GLOBALS['fields_info'][$field['serie']]['value'] . "-".$value;
		}

		if($field['etiqueta'] == false){

			echo '<input type="hidden" class="'.$field['etiqueta'].'" id="'.$id.'" name="'.$name.'" value="01'.$value.'" >';

		}else {

			echo '<div class="form-group">
			    <label for="'.$id.'" >'.$field['etiqueta'].'</label>
			    ';

			    echo (empty($value))?'Sin Asignar':$value_completo;

			echo'
			    <input type="hidden" class="'.$field['etiqueta'].'" id="'.$id.'" name="'.$name.'" value="'.$value.'" >
			</div>';

		}

	}

	/**
	 * sql_type
	 *
	 * Conocer que tipo de valor es, por default será varchar. 
	 */

	function sql_type($campo){

		return $campo. ' INT NOT NULL, ' . $campo . '_full VARCHAR(100) NOT NULL';
	}


	function update_value($field, $value){
		global $wpdb;

		$serie = $GLOBALS['crm_values'][$field['serie']];

		if($value == false || $value == 0){
			
			$max_value = $wpdb->get_var( "SELECT max(".$field['campo'].") FROM ".$field['tabla']." WHERE ".$field['serie']." = '".$serie."'" );

			$new_number = $max_value + 1;

			apply_filters('remo/update_value',  $field, $new_number);

		}

	}


}


new remo_serial();
