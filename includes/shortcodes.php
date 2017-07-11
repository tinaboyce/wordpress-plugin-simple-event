<?php
class Event_Shortcodes {
    private static $instance = null;

	public function init() {
        add_shortcode( 'se_front_page', array( self::getInstance(), 'front_page_display' ) );
	}
	
	public static function getInstance() {
		if ( null === self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}
    
    public function front_page_display() {
        $args = array(
        	'post_type'  => 'simple_event',
        	'posts_per_page' => -1,
        	'orderby' => '_se_start_date',
    	    'order'   => 'ASC',
        	'meta_query' => array(
        	    'relation' => 'OR',
        		array(
        			'key'     => '_se_start_date',
        			'value'   => time()-86400,
        			'compare' => '>=',
        		),
        		array(
        			'key'     => '_se_end_date',
        			'value'   => time()-86400,
        			'compare' => '>=',
        		),
        	),
        );
        $loop = new WP_Query( $args );
        while ( $loop->have_posts() ) : $loop->the_post();
            $post               = get_post( $loop->ID );
            $post_id = $post->ID;
            $title = get_post_meta($post_id, '_se_title', true);
            $start_date = get_post_meta($post_id, '_se_start_date', true);
            $end_date = get_post_meta($post_id, '_se_end_date', true);
            $time = get_post_meta($post_id, '_se_time', true);
        
            echo '<h2>';
                // End date exists and on different day
                if(!empty($end_date) && $start_date!=$end_date) {
                    //Same month
                    if(date('F',$start_date) == date('F',$end_date)) {
                        echo date('jS',$start_date);
                        echo ' - ';
                	    echo date('jS F',$end_date);
                    }
                    else {  // Different month
                        echo date('jS F',$start_date);
                        echo ' - ';
                	    echo date('jS F',$end_date);
                    }
                }
                else {  // Only start date exist
                    echo date('jS F',$start_date);
                }
            echo '</h2>';
            echo '<p>';
                echo $title;
                if(!empty($time)) {
                    echo ' @ ';
                	echo $time;
                }
            echo '</p>';
        endwhile;
    }
}

$event_shortcodes = new Event_Shortcodes;
$event_shortcodes->init();
?>