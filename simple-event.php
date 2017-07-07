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

/* Heavily modified from https://github.com/collizo4sky/WP_List_Table-Class-Plugin-Example/ */
if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

require_once plugin_dir_path( __FILE__ )  . '/includes/event-list.php';

class SE_Plugin {

	// class instance
	static $instance;

	// event WP_List_Table object
	public $events_obj;

	// class constructor
	public function __construct() {
		add_filter( 'set-screen-option', [ __CLASS__, 'set_screen' ], 10, 3 );
		add_action( 'admin_menu', [ $this, 'plugin_menu' ] );
	}


	public static function set_screen( $status, $option, $value ) {
		return $value;
	}

	public function plugin_menu() {

		$hook = add_menu_page(
			'Events',
			'Events',
			'manage_options',
			'event_list',
			[ $this, 'plugin_settings_page' ]
		);
		
		add_submenu_page(
		    'event_list',
			'Add Event',
			'Add Event',
			'manage_options',
			'event_create'
		);

		add_action( "load-$hook", [ $this, 'screen_option' ] );

	}


	/**
	 * Plugin settings page
	 */
	public function plugin_settings_page() {
		?>
		<div class="wrap">
			<h2>Event List</h2>

			<div id="poststuff">
				<div id="post-body" class="metabox-holder columns-2">
					<div id="post-body-content">
						<div class="meta-box-sortables ui-sortable">
							<form method="post">
								<?php
								$this->events_obj->prepare_items();
								$this->events_obj->display(); ?>
							</form>
						</div>
					</div>
				</div>
				<br class="clear">
			</div>
		</div>
	<?php
	}

	/**
	 * Screen options
	 */
	public function screen_option() {

		$option = 'per_page';
		$args   = [
			'label'   => 'Events',
			'default' => 5,
			'option'  => 'events_per_page'
		];

		add_screen_option( $option, $args );

		$this->events_obj = new Event_List();
	}


	/** Singleton instance */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

}


add_action( 'plugins_loaded', function () {
	SE_Plugin::get_instance();
} );