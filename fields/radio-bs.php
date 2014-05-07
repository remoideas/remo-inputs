<?php  
/**
 * Text Field 
 */


class remo_radio_bs extends remo_inputs_base
{

	function __construct()
	{
		// vars
		$this->name = 'radio-bs'; //Configurar en cada Input
		$this->label = __("Radio-bs",'acf');
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

			<label ><?php echo $field['etiqueta'] ?></label>

			<div class="btn-group" data-toggle="buttons">
				
				<?php 	
					foreach($field['options'] as $key => $option):

					$checked = ($key == $value)?'checked':'';

					$active = ($key == $value)?'active':'';
				?>

				<label class="btn btn-remo btn-sm <?php echo $active ?>">

					<input type="radio" name="<?php echo $name ?>" id="<?php echo $id ?>" value="<?php echo $key ?>" class="<?php echo $id ?>" <?php echo $checked ?>> <?php echo $option ?>

				</label>

				<?php endforeach; ?>

			</div>

		</div>

		<?php
	}

}


new remo_radio_bs();
