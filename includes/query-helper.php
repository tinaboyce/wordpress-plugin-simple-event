<?php
class QueryHelper
{
    private function __construct() {}
    private static $initialized = false;

    private static function initialize()
    {
        if (self::$initialized)
            return;
        
        self::$initialized = true;
    }
    
    public static function getAllCurrentEvents() {
        self::initialize();
        
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
        return new WP_Query( $args );
    }
}
?>