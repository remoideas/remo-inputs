<?php  
/**
 * Text Field 
 */


class remo_text extends remo_inputs_base
{

	function __construct()
	{
		// vars
		$this->name = 'text'; //Configurar en cada Input
		$this->label = __("Text",'acf');
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

}


new remo_text();
