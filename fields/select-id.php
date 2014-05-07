<?php  
/**
 * Select-id Field 
 */


class remo_select_id extends remo_inputs_base
{

	function __construct()
	{
		// vars
		$this->name = 'select-id'; //Configurar en cada Input
		$this->label = __("Select_id",'acf');
		$this->defaults = array(
			'default_value'	=>	''
		);

		add_action( 'wp_ajax_select_id', array($this,'selec_id_ajax_callback') );
		
		
		// do not delete!
    	parent::__construct();
	}


	/**
	 * Scripts
	 */

	function scripts_field_custom(){
		wp_register_style( 'select2', REMO_INPUTS_CSS . '/select2/select2.css', array('bootstrap-admin'), '1.0.0' );
		wp_enqueue_style( 'select2' );

		wp_register_script( 'select2', REMO_INPUTS_JS . '/select2.min.js', array('bootstrap-admin'), '1.0.0', true );
		wp_enqueue_script( 'select2' );
	}


	/**
	 * selec_id_ajax_callback
	 *
	 * Seleccionar por ajax el id de cualquier custom post type. 
	 */

	function selec_id_ajax_callback() {
		global $wpdb; // this is how you get access to the database

		if($_REQUEST['get'] == true){


		$fivesdrafts = $wpdb->get_results( 
			"
Select
  wp_posts.ID As id,
  wp_posts.post_title As text
From
  wp_posts 
Where
  wp_posts.ID = '".$_REQUEST['q']."'",
			"ARRAY_A"
		);


		} else {


			$fivesdrafts = $wpdb->get_results( 
			"
Select
  wp_posts.ID As id,
  wp_posts.post_title As text
From
  wp_posts Inner Join
  wp_crm_clientes_datos_agencia On wp_crm_clientes_datos_agencia.post_id =
    wp_posts.ID
Where
  (wp_posts.ID Like '%".$_REQUEST['q']."%' And
  wp_posts.post_type = 'clientes' And
  wp_posts.post_status = 'publish') Or
  (wp_posts.post_title Like '%".$_REQUEST['q']."%') Or
  (wp_crm_clientes_datos_agencia.cliente_telefono_principal Like '%".$_REQUEST['q']."%') Or
  (wp_crm_clientes_datos_agencia.cliente_email_principal Like '%".$_REQUEST['q']."%') Or
  (wp_crm_clientes_datos_agencia.cliente_propietario Like '%".$_REQUEST['q']."%')
			",
			"ARRAY_A"
		);


		}
		

		$data = array();

		$data['id'] = 9;
		$data['text'] = 'Antonio';

		echo apply_filters('remo/set_jsonp' , $fivesdrafts );

		echo $wpdb->show_errors();

		die(); // this is required to return a proper result
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
	
		<div class="form-group">

		   <label for="<?php echo $id ?>" ><?php echo $field['etiqueta'] ?></label>

		    <input type="hidden" id="<?php echo $id ?>" name="<?php echo $name ?>"  id="e6" style="width:100%;" tabindex="-1" value="<?php echo $value ?>">

		</div>

		<script>

		function formatValues(data) {
		    return data.firstName + ' ' + data.lastName;
		}

		jQuery(document).ready(function($) {

			$("#<?php echo $id ?>").select2({
			    placeholder: "",
			    minimumInputLength: 1,
			    ajax: { // instead of writing the function to execute the request we use Select2's convenient helper
			        url: remo_inputs.ajaxurl,
			        dataType: 'jsonp',
			        data: function (term, page) {
			            return {
			                q: term, // search term
			                action: "select_id", // please do not use so this example keeps working
			                custom_post_type: '<?php echo $field['custom_post_type'] ?>' //Dependiendo el que exista
			            };
			        },
			        results: function (data) { // parse the results into the format expected by Select2.
			            // since we are using custom formatting functions we do not need to alter remote JSON data
			            //console.log(data.data.cliente);
			            return {results: data};
			        }

			    },
			    initSelection: function(element, callback) {
				        // the input tag has a value attribute preloaded that points to a preselected movie's id
				        // this function resolves that id attribute to an object that select2 can render
				        // using its formatResult renderer - that way the movie name is shown preselected
				        var id=$(element).val();
				        if (id!=="") {
				            $.ajax(remo_inputs.ajaxurl, {
				                data: {
				                    action: "select_id",
				                    q: id,
				                    custom_post_type: '<?php echo $field['custom_post_type'] ?>', //Dependiendo el que exista
				                    get: true
				                },
				                dataType: "jsonp"
				            }).done(function(data) { callback(data[0]); });
				        }
				    }

			});
			
		});

		</script>

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


new remo_select_id();
