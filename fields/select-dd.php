<?php  
/**
 * Text Field 
 */


class remo_select_dd extends remo_inputs_base
{

	function __construct()
	{
		// vars
		$this->name = 'select-dd'; //Configurar en cada Input
		$this->label = __("Select_dd",'acf');
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

		wp_register_script( 'select-dd', REMO_INPUTS_JS . '/jquery.ddslick.min.js', array('jquery'), '2.0.0', true );
		wp_enqueue_script( 'select-dd' );
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
	
		<select class="ddSlick">
        <option value="0" data-imagesrc="http://dl.dropbox.com/u/40036711/Images/facebook-icon-32.png"
            data-description="Description with Facebook">Facebook</option>
        <option value="1" data-imagesrc="http://dl.dropbox.com/u/40036711/Images/twitter-icon-32.png"
            data-description="Description with Twitter">Twitter</option>
        <option value="2" selected="selected" data-imagesrc="http://dl.dropbox.com/u/40036711/Images/linkedin-icon-32.png"
            data-description="Description with LinkedIn">LinkedIn</option>
        <option value="3" data-imagesrc="http://dl.dropbox.com/u/40036711/Images/foursquare-icon-32.png"
            data-description="Description with Foursquare">Foursquare</option>
    </select>

		<?php
	}

}


new remo_select_dd();
