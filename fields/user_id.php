<?php  
/**
 * Text Field 
 */


class remo_user_id extends remo_inputs_base
{

	function __construct()
	{
		// vars
		$this->name = 'user_id'; //Configurar en cada Input
		$this->label = __("User Id",'acf');
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


		echo '<input type="hidden" id="'.$id.'" name="'.$name.'" value="'.$value.'">';


	}

	/**
	 * Update and create user.
	 */

	function update_value($field, $value){

		$user_email = $GLOBALS['crm_values'][$field['email_field']];

		if(!empty($user_email )){
			$user_id = email_exists($user_email);


			if ( !$user_id ) {
				$random_password = wp_generate_password( $length = 8, $include_standard_special_chars = false );
				$user_id = wp_create_user( $user_email, $random_password, $user_email );

				//Para envir notificación de nuevo usuario. 
				if($field['notificar'] == true){
					wp_new_user_notification( $user_id, $random_password );
				}
				
			}

			$this->update_custom_table($field, $user_id);
		}

		//exit;

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


new remo_user_id();
