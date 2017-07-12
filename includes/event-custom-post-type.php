<?php

$event_cpt = new EventCustomPostType;
$event_cpt->init();

class EventCustomPostType {
    private static $instance = null;

	public function init() {
        add_action( 'init', array( self::getInstance(), 'create_event_cpt' ), 0);
		add_action( 'cmb2_admin_init', array( self::getInstance(), 'metabox' ));
		
		add_filter('post_type_link', array( self::getInstance(), 'event_links' ), 1, 3);
		
		add_filter( 'post_row_actions', array( self::getInstance(), 'event_remove_row_actions' ), 10, 2);
		
		add_filter( 'manage_simple_event_posts_columns', array( self::getInstance(), 'event_column_register' ) );
		add_action( 'manage_simple_event_posts_custom_column', array( self::getInstance(), 'title_column_display' ), 10, 2);
		add_action( 'manage_simple_event_posts_custom_column', array( self::getInstance(), 'display_column_display' ), 10, 2);
		add_action( 'manage_simple_event_posts_custom_column', array( self::getInstance(), 'display_until_column_display' ), 10, 2);
		add_action( 'manage_simple_event_posts_custom_column', array( self::getInstance(), 'event_date_time_column_display' ), 10, 2);
	}
	
	public static function getInstance() {
		if ( null === self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	
	// Register Custom Post Type Event
	// Post Type Key: event
	// Generated with https://www.wp-hasty.com/tools/wordpress-custom-post-type-generator/
	function create_event_cpt() {
		$labels = array(
			'name' => __( 'Events', 'Post Type General Name', 'simple-event' ),
			'singular_name' => __( 'Event', 'Post Type Singular Name', 'simple-event' ),
			'menu_name' => __( 'Events', 'simple-event' ),
			'name_admin_bar' => __( 'Event', 'simple-event' ),
			'archives' => __( 'Event Archives', 'simple-event' ),
			'attributes' => __( 'Event Attributes', 'simple-event' ),
			//'parent_item_colon' => __( 'Parent Event:', 'simple-event' ),
			'all_items' => __( 'All Events', 'simple-event' ),
			'add_new_item' => __( 'Add New Event', 'simple-event' ),
			'add_new' => __( 'Add New', 'simple-event' ),
			'new_item' => __( 'New Event', 'simple-event' ),
			'edit_item' => __( 'Edit Event', 'simple-event' ),
			'update_item' => __( 'Update Event', 'simple-event' ),
			//'view_item' => __( 'View Event', 'simple-event' ),
			//'view_items' => __( 'View Events', 'simple-event' ),
			'search_items' => __( 'Search Event', 'simple-event' ),
			'not_found' => __( 'Not found', 'simple-event' ),
			'not_found_in_trash' => __( 'Not found in Trash', 'simple-event' ),
			//'featured_image' => __( 'Featured Image', 'simple-event' ),
			//'set_featured_image' => __( 'Set featured image', 'simple-event' ),
			//'remove_featured_image' => __( 'Remove featured image', 'simple-event' ),
			//'use_featured_image' => __( 'Use as featured image', 'simple-event' ),
			//'insert_into_item' => __( 'Insert into Event', 'simple-event' ),
			//'uploaded_to_this_item' => __( 'Uploaded to this Event', 'simple-event' ),
			'items_list' => __( 'Events list', 'simple-event' ),
			'items_list_navigation' => __( 'Events list navigation', 'simple-event' ),
			'filter_items_list' => __( 'Filter Events list', 'simple-event' ),
		);
		$args = array(
			'label' => __( 'Event', 'simple-event' ),
			'description' => __( '', 'simple-event' ),
			'labels' => $labels,
			'menu_icon' => 'dashicons-admin-post',
			'supports' => array(''),
			'taxonomies' => array(),
			'public' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'menu_position' => 5,
			'show_in_admin_bar' => true,
			'show_in_nav_menus' => true,
			'can_export' => true,
			'has_archive' => true,
			'hierarchical' => false,
			'exclude_from_search' => false,
			'show_in_rest' => true,
			'publicly_queryable' => true,
			'capability_type' => 'post',
			'rewrite' => array(
	            'slug' => '/simple_event',
	            'feeds' => false
	        )
		);
		register_post_type( 'simple_event', $args );
	}
	
	
	
	// Custom custom post type url
	function event_links($post_link, $post = 0) {
	    if($post->post_type === 'simple_event') {
	        return home_url('simple_event/' . $post->ID . '/');
	    }
	    else{
	        return $post_link;
	    }
	}
	
	
	
	// Remove unwanted row actions
	public function event_remove_row_actions( $actions, $post )
	{
		if($post->post_type === 'simple_event') {
	        unset($actions['view']);
	    }
	    return $actions;
	}
	
	
	
	// Build form for custom post type with the CMB2 library
	function metabox() {
		$prefix = '_se_';
		
		$cmb = new_cmb2_box( array(
			'id'           => $prefix.'',
			'title'        => __( 'Event Details', 'simple-event' ),
			'object_types' => array( 'simple_event', ), // Post type
			'context'      => 'normal',
			'priority'     => 'high'
		) );
		$cmb->add_field( array(
			'name'    => 'Title',
			'desc'    => 'Must enter the title. <strong>This will be displayed everywhere!</strong>',
			'id'      => $prefix.'title',
			'type'    => 'wysiwyg',
			'options' => array(
				'textarea_rows' => 2,
				'media_buttons' => false,
			),
		) );
		$cmb->add_field( array(
			'name'        => __( 'Start Date', 'simple-event' ),
			'desc'        => __( 'Enter the date of the date. <strong>NOTE: Each event must have a start date!</strong>', 'simple-event' ) . '<br>(format: dd/mm/yy)',
			'id'          => $prefix.'start_date',
			'type'        => 'text_date_timestamp',
			'date_format' => 'd/m/Y',
		) );
		$cmb->add_field( array(
			'name'        => __( 'End Date', 'simple-event' ),
			'desc'        => __( 'Enter the date of the date. This is optional.', 'simple-event' ) .'<br>(format: dd/mm/yy)',
			'id'          => $prefix.'end_date',
			'type'        => 'text_date_timestamp',
			'date_format' => 'd/m/Y',
		) );
	}
	
	// Register the column
	function event_column_register( $columns ) {
		$columns['_se_title'] = 'Event Title';
		$columns['display'] = 'Display';
		$columns['display_until'] = 'Display Until';
	    $columns['date_time'] = 'Start - End Dates';
	    unset($columns['title']);
	    unset($columns['date']);
	
	    return $columns;
	}
	
	
	
	// Display the column content
	function title_column_display( $column_name, $post_id ) {
	    if ( '_se_title' != $column_name )
	        return;
		
		$post               = get_post( $post_id );
	    $title              = get_post_meta($post_id, '_se_title', true);
	    $post_type_object   = get_post_type_object( $post->post_type );
	    $can_edit_post      = current_user_can( 'edit_post', $post->ID );
		
		echo '<strong><a class="row-title" href="' . get_edit_post_link( $post->ID, true ) . '" title="' . esc_attr( __( 'Edit this item' ) ) . '">' . $title . '</a></strong>';
	}
	
	function display_column_display( $column_name, $post_id ) {
	    if ( 'display' != $column_name )
	        return;
	
	    //Date
		echo DisplayHelper::displayDate($post_id);
	    echo '<br />';
	    
	    //Title
	    echo DisplayHelper::displayTitle($post_id);
	    echo '<br />';
	}
	
	function display_until_column_display( $column_name, $post_id ) {
	    if ( 'display_until' != $column_name )
	        return;
	
	    $start_date = get_post_meta($post_id, '_se_start_date', true);
		$end_date = get_post_meta($post_id, '_se_end_date', true);
		
		$useThisDate = $start_date;
		
	    if(!empty($end_date)) {
	    	$useThisDate = $end_date;
	    }
	    
	    if($useThisDate>=time()-86400) {
	    	echo date('jS F H:i:s',$useThisDate+86400);
	    	echo '<br/>Now: '.date('d/m H:i:s',time()).' (GMT)';
	    }
	    else {
	    	echo 'SHOULD NOT BE DISPLAYED';
	    }
	}
	
	function event_date_time_column_display( $column_name, $post_id ) {
	    if ( 'date_time' != $column_name )
	        return;
	
	    $start_date = get_post_meta($post_id, '_se_start_date', true);
	    if ( !$start_date ) {
	    	echo '<strong style="color:red;">ERROR: Empty</strong>';
	    }
	    else {
	    	echo date('d/m/Y', $start_date);
	    }
	    
	    echo ' - ';
		
	    $end_date = get_post_meta($post_id, '_se_end_date', true);
	    if ( !$end_date || empty($end_date) ) {
	    	echo'<span style="color:#bbbbbb;">[Not Provided]</span>';
	    }
	    else {
	    	echo date('d/m/Y', $end_date);
	    }
	}
}

?>