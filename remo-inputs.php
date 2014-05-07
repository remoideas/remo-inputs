<?php
/*
Plugin Name: Remo - Inputs
Description: Inputs para crear fomularios personalizados en Sitios. 
Plugin URI: http://www.remoideas.com
Author: Antonio Reyes
Author URI: http://www.remoideas.com
Version: 1.0
License: Reservados
Text Domain: RemoIdeas.com
Domain Path: Domain Path
*/


include_once('updater.php');


if (is_admin()) { // note the use of is_admin() to double check that this is happening in the admin
    $config = array(
        'slug' => plugin_basename(__FILE__), // this is the slug of your plugin
        'proper_folder_name' => 'remo-inputs', // this is the name of the folder your plugin lives in
        'api_url' => 'https://api.github.com/repos/remoideas/remo-inputs', // the github API url of your github repo
        'raw_url' => 'https://raw.github.com/remoideas/remo-inputs/master', // the github raw url of your github repo
        'github_url' => 'https://github.com/remoideas/remo-inputs', // the github url of your github repo
        'zip_url' => 'https://github.com/remoideas/remo-inputs/zipball/master', // the zip url of the github repo
        'sslverify' => true, // wether WP should check the validity of the SSL cert when getting an update, see https://github.com/jkudish/WordPress-GitHub-Plugin-Updater/issues/2 and https://github.com/jkudish/WordPress-GitHub-Plugin-Updater/issues/4 for details
        'requires' => '3.0', // which version of WordPress does your plugin require?
        'tested' => '3.3', // which version of WordPress is your plugin tested up to?
        'readme' => 'README.md', // which file to use as the readme for the version number
        'access_token' => '', // Access private repositories by authorizing under Appearance > Github Updates when this example plugin is installed
    );
    new WP_GitHub_Updater($config);
}


//Defino la ruta del plugin
define('REMO_INPUTS', plugins_url( '' , __FILE__ ));
define('REMO_INPUTS_JS', REMO_INPUTS . '/assets/js');
define('REMO_INPUTS_CSS', REMO_INPUTS . '/assets/css');

class remo_inputs
{
	public function __construct() {
	    add_action('plugins_loaded', array($this, 'pluginInit_remo_inputs') );
	 }
	
	/**
	 * __consturct
	 */
	public function pluginInit_remo_inputs()
	{	

		//Scripts
		add_action( 'admin_enqueue_scripts', array($this, 'scripts_field') );

		//Save Post
		add_action('save_post', array($this, 'save_post'));

		//Delete Post Meta
		if ( current_user_can( 'delete_posts' ) )
        add_action( 'delete_post', array($this, 'delete_post'), 10, 1 );

		//Include Fields
		$this->include_fields();


		//Guardo toda la información de los fields. 
		//$GLOBALS['fields_info'] = apply_filters('remo/get_info_fields',  data_fields());

	}





	/**
	 * scripts_field
	 *
	 * Todos los Scripts del Head
	 */
	function scripts_field()
	{

		/**
		 * Styles 
		 */
		wp_enqueue_style( 'bootstrap-admin', plugins_url( 'assets/css/bootstrap.css' , __FILE__ ), array(), '3' );
		wp_enqueue_style( 'main-field', plugins_url( 'assets/css/main-field.css' , __FILE__ ), array(), '1' );

		/**
		 * Scripts 
		 */
		wp_enqueue_script( 'bootstrap-admin', plugins_url( 'assets/js/bootstrap.js' , __FILE__ ), array(), '3', true );
		wp_enqueue_script( 'repeatable', plugins_url( 'assets/js/jquery.repeatable.js' , __FILE__ ), array('jquery'), '3', true );
		wp_enqueue_script( 'main-field', plugins_url( 'assets/js/main-field.js' , __FILE__ ), array(), '1', true );


		/**
		 * Localize Scripts
		 */
		wp_localize_script( 'jquery', 'remo_inputs', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	}


	function include_fields(){

		//Includes
		include_once('core/api.php');
		include_once('core/uix.php');


		//Fields
		include_once 'fields/_functions.php';	//*
		include_once 'fields/_base.php';		//*
		include_once 'fields/ID.php';			//*
		include_once 'fields/user_id.php'; 		//*
		include_once 'fields/serial.php';
		include_once 'fields/serie.php';
		include_once 'fields/text.php';			//*
		include_once 'fields/number.php';		//*
		include_once 'fields/money.php';		//*
		include_once 'fields/int.php';			//*
		include_once 'fields/mask.php';			//*
		include_once 'fields/select-bs.php';	//*
		include_once 'fields/select-dd.php';	//*
		//include_once 'fields/select-id.php';	//*
		include_once 'fields/radio-bs.php';		//*
		include_once 'fields/textarea-wp.php';	//*
		include_once 'fields/textarea.php';		//*
		include_once 'fields/date-range.php';	//*
		include_once 'fields/date-range-self.php';	//*
		include_once 'fields/date.php';			//*
		include_once 'fields/gallery.php';			//*

		do_action( 'remo/includes_fields' );

	}



	/**
	 * save_post 
	 *
	 * Todo lo que se necesita para guardar los Inputs. 
	 */
	function save_post($post_id)
	{
		global $wpdb;

		/**
		 * Consigo los datos de todos los inputs del formulario
		 * @var array
		 */
		
		$get_fields = data_fields(); 


		/**
		 * Mezco y consigo todas las variables necesarias de cada field. 
		 * @var array
		 */
		
		$get_data_fields = apply_filters( 'remo/get_info_fields', $get_fields );

		
		/**
		 * Si se envío algo relacionado a lo de Remo-Input. 
		 */
		
		if($_POST['datos_remo']):


		/**
		 * Array con todos los argumentos del POST
		 * @var array
		 */
		$datos_remo = $_POST['datos_remo'];

		#var_dump($datos_remo);
		#exit;


		/**
		 * To save all the values in order. 
		 * @var array
		 */
		
 		$GLOBALS['crm_values'] = $datos_remo;


		/**
		 * To save serialize
		 * @var array
		 */
		
 		$GLOBALS['datos_serialize'] = array();


 		/**
		 * To save row created
		 */
		
 		$GLOBALS['row_created'] = array();


 		/**
 		 * Guardo el array de cada id de los fields multiples. 
 		 * @var array
 		 */
 		
		$current_multiple = array();


		/**
		 * Borra los Row de los Múltiples. 
		 * @param  [type] $array [description]
		 * @return [type]        [description]
		 */
		delete_row_multiple_group($get_fields);
		#exit;


		/**
		 * Separo los datos POST
		 */
		foreach ($datos_remo as $key => $value) {
			

			// Se consiguen los datos del fields

			$get_data_field = $get_data_fields[$key];


			//Comprobar si son Multiple

			if($get_data_field['multiple']){

				// 1. Al inicio borro todos los row de los multiples. 

				/** 
				 * Recorreo cada valor del field multiple
				 */

				foreach ($value as $multiples_order => $multiples_value) {


					/**
					 * Creo el Nuevo Row si todavia no existe. 
					 */
					
					if( empty( $current_multiple['idwhere'][$get_data_field['name_group']][$multiples_order] ) ){

						// Creo los datos donde se incertará cada row de cada repetible por primera ves. 
						
						$new_insert['campo'] = $get_data_field['where_multiple'];
						$new_insert['tabla'] = $get_data_field['tabla'];
						$new_insert['value'] = $get_data_field['idwhere_multiple'];


						//Creo de nuevo el Row para este repetrible				
						
						$current_multiple['idwhere'][$get_data_field['name_group']][$multiples_order] = apply_filters('remo/insert_value', $new_insert, $new_insert['value']  );

						if(!empty($get_data_field['where_order_by_multiple'])){

							$new_order['tabla'] = $get_data_field['tabla'];
							$new_order['campo'] = $get_data_field['where_order_by_multiple'];
							$new_order['where'] = $get_data_field['where'];
							$new_order['idwhere'] = $current_multiple['idwhere'][$get_data_field['name_group']][$multiples_order];

							var_dump($new_order);
							
							apply_filters('remo/update_value', $new_order,  $multiples_order);
						}
					}



					//Set the idwher with the key that its on [] on input. 

					$get_data_field['idwhere'] = $current_multiple['idwhere'][$get_data_field['name_group']][$multiples_order];


					//Guardo los datos con Fields Multiples

					do_action( 'remo/update_value/type=' . $get_data_field['type'], $get_data_field, $multiples_value );

			
				}


			} else {


				//Guardo los datos con fields unicos 

				do_action( 'remo/update_value/type=' . $get_data_field['type'], $get_data_field, $value );

			}


			
		}

		#exit;


		//Acción para guardar los datos serialize. 
		do_action( 'remo/update_value_serialize');

		endif;

	}


	/**
	 * delete_post 
	 *
	 * Delete all the data when post its delete. 
	 */

	function delete_post($post_id){
		global $wpdb;



		//if ( current_user_can( 'delete_posts' ) )

		//Consigo los datos de todos los inputs del formulario
		$get_fields = data_fields(); 

		$get_data_groups = apply_filters( 'remo/get_info_groups', $get_fields );


		foreach ($get_data_groups as $get_data_group) {

			if( ($get_data_group['tabla'] != 'postmeta') ){
			
				//var_dump($get_data_group);
				$wpdb->show_errors();

				$exist_value = apply_filters( 'remo/exist_value' ,  $get_data_group);

				if( $exist_value == true ){

					do_action( 'remo/delete_before',  $post_id);

					if( !$wpdb->delete( $get_data_group['tabla'], array( $get_data_group['where'] => $post_id ) ) ){

						do_action( 'remo/delete_afeter', $post_id);

						//Debug
						/*
						echo "Hubo un problema, por favor contacta al Administrador del CRM y haz una captura de la pantalla. ";
						var_dump($wpdb->print_error());
						var_dump($get_data_group);
						exit();
						*/
					}

				}

			}

		}



	}



}


/*
*  remo_inputs_init
*
*  The main function responsible for returning the one true acf Instance to functions everywhere.
*  Use this function like you would a global variable, except without needing to declare the global.
*
*  Example: <?php $acf = acf(); ?>
*
*  @type	function
*  @date	4/09/13
*  @since	4.3.0
*
*  @param	N/A
*  @return	(object)
*/

function remo_inputs()
{
	global $remo_inputs;
	
	if( !isset($remo_inputs) )
	{
		$remo_inputs = new remo_inputs();
	}
	
	return $remo_inputs;
}


// initialize remo-inputs
remo_inputs();



?>