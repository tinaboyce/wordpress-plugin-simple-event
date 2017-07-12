<?php
class DisplayHelper
{
    private function __construct() {}
    private static $initialized = false;

    private static function initialize()
    {
        if (self::$initialized)
            return;
        
        self::$initialized = true;
    }
    
    public static function displayTitle($post_id) {
        self::initialize();
        $title = get_post_meta($post_id, '_se_title', true);
        
        return $title;
    }
    
    public static function displayDate($post_id) {
        self::initialize();
        
        $start_date = get_post_meta($post_id, '_se_start_date', true);
        $end_date = get_post_meta($post_id, '_se_end_date', true);
        
        // End date exists and on different day
        if(!empty($end_date) && $start_date!=$end_date) {
            //Same month
            if(date('F',$start_date) == date('F',$end_date)) {
                return date('jS',$start_date).' - '.date('jS F',$end_date);
            }
            else {  // Different month
                return date('jS F',$start_date).' - '.date('jS F',$end_date);
            }
        }
        else {  // Only start date exist
            return date('jS F',$start_date);
        }
    }
}
?>