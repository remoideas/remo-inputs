<?php 

/**
 * Class Input Field Base
 *
 * Para colocar todo lo que los inputs deben contener. 
 */

/**
* 
*/
class remo_inputs_base
{
	
	function __construct()
	{

		//Values
		$this->add_action('remo/update_value/type=' . $this->name, array($this, 'update_value'), 10, 2);


		//Fields
		$this->add_action('remo/create_field/type=' . $this->name, array($this, 'create_field'), 10, 3);
		$this->add_filter('remo/set_value/type=' . $this->name, array($this, 'set_value'), 10, 2);
		$this->add_filter('remo/sql_type/type=' . $this->name, array($this, 'sql_type'), 10, 1);


		//Scripts
		$this->add_action( 'admin_enqueue_scripts', array($this, 'scripts_field_custom') );
	}


	/*
	*  add_filter
	*
	*  @description: checks if the function is_callable before adding the filter
	*  @since: 3.6
	*  @created: 30/01/13
	*/
	
	function add_filter($tag, $function_to_add, $priority = 10, $accepted_args = 1)
	{
		if( is_callable($function_to_add) )
		{
			add_filter($tag, $function_to_add, $priority, $accepted_args);
		}
	}
	
	
	/*
	*  add_action
	*
	*  @description: checks if the function is_callable before adding the action
	*  @since: 3.6
	*  @created: 30/01/13
	*/
	
	function add_action($tag, $function_to_add, $priority = 10, $accepted_args = 1)
	{
		if( is_callable($function_to_add) )
		{
			add_action($tag, $function_to_add, $priority, $accepted_args);
		}
	}

	/**
	 * name
	 *
	 * Para colocar correctamente el name del input dependiendo si es único o múltiple. 
	 */
	function name($fieldkey, $field){

		if($field['multiple'] == true){

			//return 'datos_remo['.$fieldkey.'][]['.$field['idwhere'].']';
			return 'datos_remo['.$fieldkey.'][]';

		} else {

			return 'datos_remo['.$fieldkey.']';

		}

	}

	/**
	 * sql_type
	 *
	 * Conocer que tipo de valor es, por default será varchar. 
	 */

	function sql_type($campo){

		return $campo. ' VARCHAR(100) NULL';
	}

	/**
	 * set_value
	 *
	 * Sirve para desplegar la información del input. 
	 */
	function set_value($fieldkey, $field){

		//var_dump($field['idwhere']);

		if($field['serialize'] == false){

			//Si en el registro de field no existe el key value
			if(!array_key_exists('value', $field)){ 

				switch ($field['tabla']) {
				    case 'users': 
				    	
				    	return $this->set_value_users($field, $value);

				        break;


				    case 'posts': 

				    	return $this->set_value_posts($field, $value);

				        break;

				    case 'usermeta': 
				    	
				    	return $this->set_value_usermeta($field, $value);

				        break;


				    case 'postmeta': 

				    	return $this->set_value_postmeta($field, $value);

				        break;


				    default: 
				    	
				    	return $this->set_value_custom_table($field, $value);

				        break;
				}

			} else {

				return $field['value'];

			}

		} else {


			switch ($field['tabla']) {

			    case 'postmeta': 

			    	return $this->set_value_postmeta_serialize($field, $value);

			        break;


			    default: 
			    	
			    	return $this->set_value_custom_table_serialize($field, $value);

			        break;
			}

		}

	}


	/**
	 * set_value_users
	 *
	 * Conseguir el valor desde el API de usuarios 
	 */

	function set_value_users($field, $value){
		
		$user_info = get_userdata( $field['idwhere'] );

		$GLOBALS['fields_info'][$field['name_field']]['value'] = $user_info;

		return $user_info->$field['campo'];
	}


	/**
	 * set_value_usermeta
	 *
	 * Conseguir el valor desde el API del Meta User
	 */

	function set_value_usermeta($field, $value){
		
		$usermeta_info = get_user_meta( $field['idwhere'], $field['campo'], true );

		$GLOBALS['fields_info'][$field['name_field']]['value'] = $usermeta_info;

		return $usermeta_info;
	}


	/**
	 * set_value_posts
	 *
	 * Conseguir el valor desde el API de Posts
	 */

	function set_value_posts($field, $value){

		$post_info = get_post( $field['idwhere'] );

		$GLOBALS['fields_info'][$field['name_field']]['value'] = $post_info;

		return $post_info->$field['campo'];

	}


	/**
	 * set_value_postmeta
	 *
	 * Conseguir el valor desde el API del Meta Posts
	 */

	function set_value_postmeta($field, $value){

		$postmeta_info = get_post_meta( $field['idwhere'], $field['campo'], true );

		$GLOBALS['fields_info'][$field['name_field']]['value'] = $postmeta_info;

		return $postmeta_info;

	}


	/**
	 * set_value_postmeta_serialize
	 *
	 * Conseguir el valor de una cadena serializada. 
	 */

	function set_value_postmeta_serialize($field, $value){
		global $wpdb;


		$value_serialize = get_post_meta( $field['idwhere'], $field['serialize_campo'], true );

		$value_sql_unserialize = unserialize($value_serialize);


		return $value_sql_unserialize[$field['campo']];


	}


	/**
	 * set_value_custom_table
	 *
	 * Conseguir el valor desde la tabla propia
	 */

	function set_value_custom_table($field, $value){
		global $wpdb;

		//Esto ponerlo dentro de una función que carge el valor. 
		$value_new = $wpdb->get_var( "SELECT {$field['campo']} FROM {$field['tabla']} WHERE {$field['where']} = '{$field['idwhere']}'" );

		$value_new = $this->set_value_filter($field, $value_new );

		$GLOBALS['fields_info'][$field['name_field']]['value'] = $value_new;

		return $value_new;

	}


	/**
	 * set_value_custom_table_serialize
	 *
	 * Conseguir el valor de una cadena serializada. 
	 */

	function set_value_custom_table_serialize($field, $value){
		global $wpdb;


			//Esto ponerlo dentro de una función que carge el valor. 
			$result_sql_serialize = $wpdb->get_var( "SELECT {$field['serialize_campo']} FROM {$field['tabla']} WHERE {$field['where']} = '{$field['idwhere']}'" );

			$result_sql_unserialize = unserialize($result_sql_serialize);



		return $result_sql_unserialize[$field['campo']];
	}


	function set_value_filter($field, $value){

		return $value;

	}

	/**
	 * sanitize 
	 *
	 * Para que espacios se conviertan en guiones. 
	 */

	function sanitize($etiqueta)
	{
		return sanitize_title($etiqueta);
	}


	/**
	 * save_field
	 */

	function update_value($field, $value){

		//var_dump($field);
		

		//Filtro del Valor
		$value = $this->update_value_filter($field, $value);

		if($field['serialize'] == false || $field['serialize'] == NULL){

			switch ($field['tabla']) {
			    case 'users': 
			    	
			    	$this->update_users($field, $value);

			        break;


			    case 'posts': 

			    	$this->update_posts($field, $value);

			        break;


			    case 'usermeta': 
			    	
			    	$this->update_usermeta($field, $value);

			        break;


			    case 'postmeta': 

			    	$this->update_postmeta($field, $value);

			        break;


			    default: 
			    	
			    	$this->update_custom_table($field, $value);

			        break;
			}

		} else {


			switch ($field['tabla']) {

			    case 'postmeta': 

			    	$this->update_postmeta_serialize($field, $value);

			        break;


			    default: 
			    	
			    	$this->update_custom_table_serialize($field, $value);

			        break;

			}

		}

		
	}


	/**
	 * update_custom_table
	 *
	 * Default actualizar tabla propia. 
	 */

	function update_custom_table($field, $value){

		$this->wpdb_update($field, $value);

	}

	/**
	 * update_custom_table_serialize
	 *
	 * Default actualizar tabla propia con serislize
	 */

	function update_custom_table_serialize($field, $value){

		do_action( 'remo/datos_serialize', $field, $value );

	}


	/**
	 * update_users
	 *
	 * Actualizar Tabla Usuarios
	 */

	function update_users($field, $value){

		if($value != '')
		wp_update_user( array( $field['where'] => $field['idwhere'], $field['campo'] => $value ) );

	}


	/**
	 * update_usermeta
	 *
	 * Actualizar Tabla Usuarios meta
	 */

	function update_usermeta($field, $value){

		if($value != '')
		update_user_meta( $field['idwhere'], $field['campo'], $value );

	}


	/**
	 * update_posts
	 *
	 * Actualizar Tabla Posts
	 */

	function update_posts($field, $value){

		if ( ! wp_is_post_revision( $field['idwhere'] ) ){

			global $remo_inputs;
	
			// unhook this function so it doesn't loop infinitely
			remove_action('save_post', array( $remo_inputs, 'save_post' ));
		
			// update the post, which calls save_post again
			wp_update_post( array( $field['where'] => $field['idwhere'], $field['campo'] => $value ) );

			// re-hook this function
			add_action('save_post', array( $remo_inputs, 'save_post' ));
		}

		//wp_update_post( array( $field['where'] => $field['idwhere'], $field['campo'] => $value ) );

	}


	/**
	 * update_postmeta
	 *
	 * Actualizar Tabla Usuarios meta
	 */

	function update_postmeta($field, $value){

		if($value != '')
		update_post_meta( $field['idwhere'], $field['campo'], $value );

	}


	/**
	 * update_postmeta_serialize
	 *
	 * Default actualizar tabla propia con serislize
	 */

	function update_postmeta_serialize($field, $value){

		do_action( 'remo/datos_serialize', $field, $value );

	}



	/**
	 * wpdb_update
	 *
	 * Update Function to use quick. 
	 */

	function wpdb_update($field, $value){

		apply_filters( 'remo/update_value' ,  $field, $value);

		global $wpdb;

	}



	function update_value_filter($field, $value){

		return $value;

	}


	/**
	 * jquery_action no se usa. 
	 */
	function jquery_action($fieldkey, $field){

		if(array_key_exists('jquery', $field)){

			foreach ($field['jquery'] as $action => $function) {

				$id = $this->sanitize($field['etiqueta']);

				if($action == 'init'){

					?>

					<script>
						jQuery(document).ready(function($) {
							<?php echo $function ?>();
						});
					</script>

					<?php

				}else{

					?>

					<script>
						jQuery(document).ready(function($) {
							$(document.body).on('click', ".btn-group", hola);
						});
					</script>
						
					<?php

				}

			}

		}

	}


}

?>