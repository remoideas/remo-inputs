<?php  

/**
 * Api para Remo Inputs
 *
 * En este API pondremos funciones generales que seguramente se utilizarán. 
 */


/**
 * Get info groupo
 * @param  [type] $group [description]
 * @return [type]        [description]
 */
function get_info_group($group){

	$get_fields = data_fields(); 

	foreach ($get_fields as $value) {

		if($value['name_group'] == $group){
			return $value;
		}
	}

}


/**
 * Borra los Row de los Múltiples. 
 * @param  [type] $array [description]
 * @return [type]        [description]
 */
function delete_row_multiple_group($array){
	global $wpdb;

	foreach($array as $key => $value) {
	    if($value['multiple']){

	    	/**
	    	 * borrar todo los row de los multiples.
	    	 */
	    	#echo "borrar todo en tabla " . $value['tabla'] .' con el where ' . $value['where_multiple'].  ' y el ID ' . $value['idwhere_multiple'];
	    	return $wpdb->delete( $value['tabla'], array( $value['where_multiple'] => $value['idwhere_multiple'] ), array( '%d' ) );
	    }
	}

}



?>