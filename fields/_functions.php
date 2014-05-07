<?php 

/** 
 * _Functions 
 * 
 * Funciones especificas para ejecutar los inputs del formulario
 */

 class remo_inputs_functions
 {
 	
 	function __construct()
 	{	

 		//Serialize Function
 		add_action('remo/datos_serialize', array($this, 'datos_serialize'), 5, 2);
 		add_action('remo/update_value_serialize', array($this, 'update_value_serialize'), 5, 0);

 		//SQL
		add_filter('remo/update_value', array($this, 'wpdb_update'), 10, 2);
		add_filter('remo/insert_value', array($this, 'wpdb_insert'), 10, 2);
		add_filter('remo/exist_value', array($this, 'exist_value'), 10, 1);

		//SQL maker
		add_action('remo/create_sql', array($this, 'create_sql'), 10, 1);
		add_filter('remo/add_first_custom_meta_data', array($this, 'add_first_custom_meta_data'), 5, 1);

 		//Create Fields
 		add_action('remo/display_field', array($this, 'display_field'), 5, 2);
		add_filter('remo/get_info_fields', array($this, 'get_info_fields'), 5, 1);
		add_filter('remo/get_info_groups', array($this, 'get_info_groups'), 5, 1);

		//Create MetaBox
		add_action('remo/make_form', array($this, 'make_form'), 5, 2);

		//Fields Multiple
		add_filter('remo/get_multiples', array($this, 'get_multiples'), 5, 2);

		//Ajax
		add_filter('remo/set_jsonp', array($this, 'set_jsonp'), 5, 1);

 	}

 	/*
	*  display_field
	*
	*  Consigue toda la información para Imprimir el Field en el formulario. 
	*/

 	function display_field( $fieldkey, $fidwhere = false )
 	{

 		//Consigo toda la información de este field
 		
 		$get_fields = data_fields(); 

 		$field_info = $this->get_info_fields($get_fields);

 		if($fidwhere != false){
 			$field_info[$fieldkey]['idwhere'] = $fidwhere;
 		}

 		// create field specific html

 		$value = apply_filters('remo/set_value/type=' . $field_info[$fieldkey]['type'], $fieldkey, $field_info[$fieldkey]);

		do_action('remo/create_field/type=' . $field_info[$fieldkey]['type'], $fieldkey, $field_info[$fieldkey], $value);

 	}

 	function get_multiples($group, $post_id){

 		global $wpdb;

 		/**
 		 * Consgio toda la información de los groups
 		 * @var [type]
 		 */
 		
 		$get_group = get_info_group($group);

		return $wpdb->get_results( 
			"
			Select
			  ".$get_group['where']."
			From
			  ".$get_group['tabla']."
			Where
			  ".$get_group['where_multiple']." = ".$post_id."
			ORDER BY 
			  ".$get_group['where_order_by_multiple']." ".$get_group['order_by_multiple']."
			"
		);

 	}



	/*
	*  get_info_fields
	*
	*  Consigue toda la información de cada uno de los campos que se ocuparan
	*/

	function get_info_fields($fields){


		//Get the all the Form Groups.
		foreach($fields as $key_groups => $value_groups) {

			//Guardo en un array el nombre del grupo 
			$this->formgroups[] = $value_groups['name_group'];


			foreach ($value_groups['fields'] as $name_field => $value_field) {
				
				//Recolecto todos los Fields de la sección a editar. 
				$fields_info[ $name_field ] = $value_field;

				//Ingreso la tabla, where y idwhere and groupname
				$fields_info[ $name_field ]['tabla'] = $value_groups['tabla'];
				$fields_info[ $name_field ]['where'] = $value_groups['where'];
				$fields_info[ $name_field ]['serialize'] = $value_groups['serialize'];
				$fields_info[ $name_field ]['serialize_campo'] = $value_groups['serialize_campo'];
				$fields_info[ $name_field ]['multiple'] = $value_groups['multiple'];
				$fields_info[ $name_field ]['where_multiple'] = $value_groups['where_multiple'];
				$fields_info[ $name_field ]['idwhere_multiple'] = $value_groups['idwhere_multiple'];
				$fields_info[ $name_field ]['where_order_by_multiple'] = $value_groups['where_order_by_multiple'];
				$fields_info[ $name_field ]['order_by_multiple'] = $value_groups['order_by_multiple'];
				$fields_info[ $name_field ]['idwhere'] = $value_groups['idwhere'];
				$fields_info[ $name_field ]['name_group'] = $value_groups['name_group'];
				$fields_info[ $name_field ]['name_field'] = $name_field;
				
			}
		}


		return $fields_info;
		

	}


	/*
	*  get_info_groups
	*
	*  Consigue toda la información de cada uno de los grupos
	*/

	function get_info_groups($fields){



		//Get the all the Form Groups.
		foreach($fields as $key_groups => $value_groups) {

			//Guardo en un array el nombre del grupo 
			$groups_info[$key_groups]['name_group'] = $value_groups['name_group'];
			$groups_info[$key_groups]['tabla'] = $value_groups['tabla'];
			$groups_info[$key_groups]['where'] = $value_groups['where'];
			$groups_info[$key_groups]['serialize'] = $value_groups['serialize'];
			$groups_info[$key_groups]['serialize_campo'] = $value_groups['serialize_campo'];
			$groups_info[$key_groups]['multiple'] = $value_groups['multiple'];
			$groups_info[$key_groups]['idwhere'] = $value_groups['idwhere'];

		}

		return $groups_info;
		

	}

	/**
	 * Crear formulario con los row-fluid y col. 
	 *
	 * Solo funciona con Bootstra 3
	 * 
	 * @param  array $key_fields     [description]
	 * @param  number $number_columns [description]
	 * @return null                 [description]
	 */
	function make_form($inputs_elements, $number_columns = 2, $title = NULL, $divide = NULL){


		if (!empty($title)) {


			$html_title = '<div class="col-md-12"><h4>'.$title.'</h4></div>';

		}


		foreach ($inputs_elements as $elements) {

			if(  $elements['type'] == 'title' ){


				$title = '<div class="row-fluid"><div class="col-md-12" ><h4>'. $elements['title'] .'</h4></div></div>';

				echo $title;


			}



			if( $elements['type'] == 'fields' ){

				//Cuantos elementos son:
				$count_elements = count( $elements['items'] );
				$runs_elements = 0;


				foreach ($elements['items'] as $key_field) {

					$each_row_fluid = $runs_elements%$number_columns;

					if($each_row_fluid == 0){


						echo '<div class="row-fluid">';

					}
					

					echo '<div class="col-md-6 form-block" >';

						do_action('remo/display_field', $key_field);

					echo '</div>';


					if($each_row_fluid == 1){


						echo '</div><div class="clearfix"></div>';

					}


					//Cuento el número de veces que se ha hecho el loop
					$runs_elements++;
					

				}


			}

		}


	}


	/**
	 * create_sql
	 * 
	 * Crear SQL
	 */

	function create_sql($field_group){


		//Consigo toda la información de este field
 		
 		$get_fields = data_fields(); 

 		//Variables
 		$sql_create_field = array();
 		$sql_create_field_index = array();

 		//Get the all the Form Groups.
		foreach($get_fields as $key_groups => $value_groups) {


			//Guardo en un array el nombre del grupo 
			 $group = $value_groups['name_group'];


			//Variables
		 	$sql_create_field[$group] = array();
		 	$sql_create_field_index[$group] = array();


			foreach ($value_groups['fields'] as $name_field => $value_field) {


				//Consigo el tipo de campo que será.

				$sql_type = apply_filters('remo/sql_type/type=' . $value_field['type'], $value_field['campo']);


				//Genero cada campo

				$sql_create_field[$group][] = $sql_type;



				//Genero los indices si hubiera. 

				if($value_field['index'] == true){
					
					$sql_create_field_index[$group][] = 'INDEX ('.$value_field['campo'].')';

				}

				if($value_field['fulltext'] == true){

					$sql_create_field_index[$group][] = 'FULLTEXT ('.$value_field['campo'].')';
					
				}

				//$sql_create_juntos[] = array_merge($sql_create_field, $sql_create_field_index);

				//var_dump($sql_create_juntos);

				
			}

			echo "CREATE TABLE IF NOT EXISTS " . $value_groups['tabla'] . " (<br>";

				//var_dump($sql_create_field[$group]);

				//var_dump($sql_create_field_index[$group]);
	

				$sql_create_juntos[$group] = array_merge($sql_create_field[$group], $sql_create_field_index[$group]);

				echo "id_" . $value_groups['name_group'] . " mediumint(9) NOT NULL AUTO_INCREMENT PRIMARY KEY, <br>";

				echo implode(', <br>', $sql_create_juntos[$group]);


			echo "<br>)<br><br>";
		}

 		

	}


	/**
	 * get_datos_serialize
	 * 
	 * Almacena los serialize que se necesitan para guardarse ya formateados. 
	 */

	function datos_serialize($field, $value){

		//var_dump($field);

		$GLOBALS['datos_serialize'][$field['name_group']]['tabla'] = $field['tabla'];
		$GLOBALS['datos_serialize'][$field['name_group']]['where'] = $field['where'];
		$GLOBALS['datos_serialize'][$field['name_group']]['idwhere'] = $field['idwhere'];
		$GLOBALS['datos_serialize'][$field['name_group']]['campo'] = $field['serialize_campo'];
		$GLOBALS['datos_serialize'][$field['name_group']]['values'][$field['campo']] = $value;

	}


	/**
	 * wpdb_insert
	 *
	 * Insert Function to use quick. 
	 */

	function wpdb_insert($field, $value){
		global $wpdb;

		//Insert
		$wpdb->insert( 
			$field['tabla'], 
			array( 
				$field['campo'] => $value
			), 
			array( 
				'%s'
			) 
		);

		return $wpdb->insert_id;

	}

	
	/**
	 * wpdb_update
	 *
	 * Update Function to use quick. 
	 */

	function wpdb_update($field, $value){
		global $wpdb;


		//Checo si ya existe, si no lo vuelvo a crear. 

		if($GLOBALS['row_created'][$field['name_group']] != true)
		$this->add_first_custom_meta_data($field, $value);


		//Reviso en donde guardo los valores 
		if($field['tabla'] == 'postmeta'){

			//Update para post types.
			update_post_meta( $field['idwhere'], $field['campo'], $value );			

		} else {

			//Update
			$wpdb->update( 
				$field['tabla'], 
				array( 
					$field['campo'] => $value //value1
				), 
				array( $field['where'] => $field['idwhere'] ), 
				array( 
					'%s',	// value1
				), 
				array( '%s' ) 
			);


		}

	}


	/**
	 * update_value_serialize
	 */

	function update_value_serialize(){
		//var_dump($GLOBALS['datos_serialize']);

		foreach ($GLOBALS['datos_serialize'] as $key => $field) {

			$value = maybe_serialize($field['values']);

			$this->wpdb_update( $field, $value );

		}
	}

	function exist_value($field){
		global $wpdb;

		$existe = $wpdb->get_var( "SELECT COUNT(*) FROM ".$field['tabla']." WHERE ". $field['where'] . " = '" . $field['idwhere'] . "'" );

		return $existe;
	}


	/**
	 * add_custom_meta_data
	 */
	function add_first_custom_meta_data($field){
		global $wpdb;

		$field['campo'] = $field['where'];

		if($this->exist_value($field) == 0)
		$this->wpdb_insert($field, $field['idwhere']);
			
		return $GLOBALS['row_created'][$field['name_group']] = true;

	

	}


	/**
	 * set_jsonp
	 *
	 * To send json data with ajax. 
	 */

	function set_jsonp($data){

		header('Access-Control-Allow-Origin: *');

		$callback = isset($_GET['callback']) ? preg_replace('/[^a-z0-9$_]/si', '', $_GET['callback']) : false;
		header('Content-Type: ' . ($callback ? 'application/javascript' : 'application/json') . ';charset=UTF-8');		

		return ($callback ? $callback . '(' : '') . json_encode($data) . ($callback ? ')' : '');
	}


 }

 new remo_inputs_functions();

?>