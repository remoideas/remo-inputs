<?php  

/**
 * Function to Make UIX
 *
 * Toda las funciones para crear diferentes UIX dentro de WordPress
 */

/*
 * TABs Debug
 */
class add_tabs_page {

	
	
	/*
	 * Fired during plugins_loaded (very very early),
	 * so don't miss-use this, only actions and filters,
	 * current ones speak for themselves.
	 */
	function __construct( $page_title, $menu_title, $capability = 'manage_options', $menu_slug, $tabs, $icon_url='', $position, $parent_slug = NULL ) {

		add_action( 'admin_menu', array( &$this, 'add_admin_menus' ) );

		$this->page_title = $page_title;
		$this->menu_title = $menu_title;
		$this->capability = $capability;
		$this->menu_slug = $menu_slug;
		$this->icon_url = $icon_url;
		$this->position = $position;
		$this->parent_slug = $parent_slug;

		$this->tabs = $tabs;
	}
	
	
	/*
	 * Called during admin_menu, adds an options
	 * page under Settings called My Settings, rendered
	 * using the plugin_options_page method.
	 */
	function add_admin_menus() {

		if( empty($this->parent_slug) ){


			add_menu_page( $this->page_title, $this->menu_title, $this->capability, $this->menu_slug , array( &$this, 'callback_page' ), $this->icon_url , $this->position );

		} else {


			add_submenu_page( $this->parent_slug, $this->page_title, $this->menu_title, $this->capability, $this->menu_slug, array( &$this, 'callback_page' ) );

		}
		

	}
	
	/*
	 * Plugin Options page rendering goes here, checks
	 * for active tab and replaces key with the related
	 * settings key. Uses the plugin_options_tabs method
	 * to render the tabs.
	 */
	function callback_page() {

		if ( !current_user_can( 'manage_options' ) )  {
	        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	    }

	    echo '<div class="wrap">';


	    echo '<h2 class="nav-tab-wrapper">';


	    foreach ($this->tabs as $key => $value) {


	    			$key_tab = sanitize_key( $key );
	    			$active = ($_REQUEST['tab'] == $key_tab )?'nav-tab-active':'';


	    			echo '<a class="nav-tab ' . $active . '" href="'.add_query_arg( array('tab'=> $key_tab) ) . '">'.$key.'</a>';


	    		}		
		
		echo '</h2>';


		foreach ($this->tabs as $key => $value) {

					$key_tab = sanitize_key( $key );


	    			if ($_REQUEST['tab'] == $key_tab) {


	    				if(!empty($value))
	    					include $value;


	    			}


	    		}	


		echo '</div>';
	    
	    
	}

};

?>