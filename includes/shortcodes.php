<?php

// Requires font-awesome

$event_shortcodes = new Event_Shortcodes;
$event_shortcodes->init();

class Event_Shortcodes {
    private static $instance = null;

	public function init() {
        add_shortcode( 'simpleevent_list_all', array( self::getInstance(), 'event_list_display' ) );
	}
	
	public static function getInstance() {
		if ( null === self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}
    
    public function event_list_display() {
        $loop = QueryHelper::getAllCurrentEvents();
        
        echo '<ul class="fa-ul">'.PHP_EOL;
        while ( $loop->have_posts() ) {
            $loop->the_post();
            $post = get_post( $loop->ID );
            $post_id = $post->ID;
            
            echo '<li>';
            echo '<i class="fa-li fa fa-calendar" style="top: 0;"></i><strong>'.DisplayHelper::displayDate($post_id).'</strong> : '.DisplayHelper::displayTitle($post_id);
            echo '</li>'.PHP_EOL;
        }
        echo '</ul>'.PHP_EOL;
    }
}
?>