<?php
/**
 * @package Simple-Event
 */
/*
Plugin Name: Simple Event
Description: Simple Event plugin to display event and remove the need to manually delete past event.
Version: 0.1
Author: Tina Boyce
Author URI: https://github.com/tinaboyce
*/


class SimpleEvent {

	private static $instance = null;

	public function __construct() {
		$this->includes();
	}

    private function includes() {
    	/**
    	 * Files to include on frontend and backend
    	 */
    	$includes = array(
    		'/includes/event-custom-post-type.php',
    		'/includes/shortcodes.php',
    		'/includes/display-helper.php',
    		'/includes/query-helper.php'
    	);
    	
    	$admin_includes = array();
    	
    	if ( file_exists( dirname( __FILE__ ) . '/includes/cmb2/init.php' ) ) {
        	require_once dirname( __FILE__ ) . '/includes/cmb2/init.php';
        } elseif ( file_exists( dirname( __FILE__ ) . '/includes/CMB2/init.php' ) ) {
        	require_once dirname( __FILE__ ) . '/includes/CMB2/init.php';
        }
    
    	// Load files
    	foreach ( $includes as $file ) {
    		if ( file_exists( dirname( __FILE__ ) . $file ) ) {
    			require_once dirname( __FILE__ ) . $file;
    		}
    	}
    
    	// Load admin files
    	if ( is_admin() ) {
    		foreach ( $admin_includes as $file ) {
    			if ( file_exists( dirname( __FILE__ ) . $file ) ) {
    				require_once dirname( __FILE__ ) . $file;
    			}
    		}
    	}
    }
    
    public static function get_instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}
}


add_action( 'plugins_loaded', array( 'SimpleEvent', 'get_instance' ), 9 );