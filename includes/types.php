<?php
add_action( 'init', 'create_event_cpt' ,0);
add_action( 'cmb2_admin_init', 'metabox' );

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
		'parent_item_colon' => __( 'Parent Event:', 'simple-event' ),
		'all_items' => __( 'All Events', 'simple-event' ),
		'add_new_item' => __( 'Add New Event', 'simple-event' ),
		'add_new' => __( 'Add New', 'simple-event' ),
		'new_item' => __( 'New Event', 'simple-event' ),
		'edit_item' => __( 'Edit Event', 'simple-event' ),
		'update_item' => __( 'Update Event', 'simple-event' ),
		'view_item' => __( 'View Event', 'simple-event' ),
		'view_items' => __( 'View Events', 'simple-event' ),
		'search_items' => __( 'Search Event', 'simple-event' ),
		'not_found' => __( 'Not found', 'simple-event' ),
		'not_found_in_trash' => __( 'Not found in Trash', 'simple-event' ),
		'featured_image' => __( 'Featured Image', 'simple-event' ),
		'set_featured_image' => __( 'Set featured image', 'simple-event' ),
		'remove_featured_image' => __( 'Remove featured image', 'simple-event' ),
		'use_featured_image' => __( 'Use as featured image', 'simple-event' ),
		'insert_into_item' => __( 'Insert into Event', 'simple-event' ),
		'uploaded_to_this_item' => __( 'Uploaded to this Event', 'simple-event' ),
		'items_list' => __( 'Events list', 'simple-event' ),
		'items_list_navigation' => __( 'Events list navigation', 'simple-event' ),
		'filter_items_list' => __( 'Filter Events list', 'simple-event' ),
	);
	$args = array(
		'label' => __( 'Event', 'simple-event' ),
		'description' => __( '', 'simple-event' ),
		'labels' => $labels,
		'menu_icon' => 'dashicons-admin-post',
		'supports' => array('title'),
		'taxonomies' => array(),
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'menu_position' => 5,
		'show_in_admin_bar' => false,
		'show_in_nav_menus' => true,
		'can_export' => true,
		'has_archive' => true,
		'hierarchical' => false,
		'exclude_from_search' => false,
		'show_in_rest' => true,
		'publicly_queryable' => true,
		'capability_type' => 'post',
	);
	register_post_type( 'simple_event', $args );
}


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
		'name'        => __( 'Start Date', 'simple-event' ),
		'desc'        => __( 'Enter the date of the date. <strong>NOTE: Each event must have a start date!</strong>', 'simple-event' ) . '<br>(format: dd/mm/yy)',
		'id'          => $prefix.'start_date',
		'type'        => 'text_date_timestamp',
		'date_format' => 'd/m/Y',
	) );
	$cmb->add_field( array(
		'name'        => __( 'End Date', 'simple-event' ),
		'desc'        => __( 'Enter the date of the date.', 'simple-event' ) .'<br>(format: dd/mm/yy)',
		'id'          => $prefix.'end_date',
		'type'        => 'text_date_timestamp',
		'date_format' => 'd/m/Y',
	) );
	$cmb->add_field( array(
		'name'        => __( 'Time', 'simple-event' ),
		'desc'        => __( 'Enter time for display'),
		'id'          => $prefix.'time',
		'type'        => 'text_time'
	) );
}


add_filter( 'manage_simple_event_posts_columns', 'event_column_register' );
add_action( 'manage_simple_event_posts_custom_column', 'event_start_date_column_display', 10, 2);
add_action( 'manage_simple_event_posts_custom_column', 'event_end_date_column_display', 10, 2);
add_action( 'manage_simple_event_posts_custom_column', 'event_time_column_display', 10, 2);
// Register the column
function event_column_register( $columns ) {
    $columns['_se_start_date'] = 'Start Date';
    $columns['_se_end_date'] = 'End Date';
    $columns['_se_time'] = 'Time';
    unset($columns['date']);

    return $columns;
}
// Display the column content
function event_start_date_column_display( $column_name, $post_id ) {
    if ( '_se_start_date' != $column_name )
        return;

    $start_date = get_post_meta($post_id, '_se_start_date', true);
    if ( !$start_date )
        $start_date = '<em>Empty</em>';

    echo date('d/m/Y', $start_date);
}
function event_end_date_column_display( $column_name, $post_id ) {
    if ( '_se_end_date' != $column_name )
        return;

    $end_date = get_post_meta($post_id, '_se_end_date', true);
    if ( !$end_date )
        $end_date = '<em>Not Provided</em>';

    echo date('d/m/Y', $end_date);;
}
function event_time_column_display( $column_name, $post_id ) {
    if ( '_se_time' != $column_name )
        return;

    $time = get_post_meta($post_id, '_se_time', true);
    if ( !$time )
        $time = '<em>Not Provided</em>';

    echo $time;
}

?>