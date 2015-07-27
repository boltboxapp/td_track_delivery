<?php

/**
 * TrackDeliveryWidget
 * 
 * Main widget's class
 * Inheritance of the standard WordPress widgets class
 */
class TrackDeliveryWidget extends WP_Widget
{
    const BASE_ID = 'tm_td';

    public function __construct()
    {
        parent::__construct(self::BASE_ID, 'Отследить отправление', [
            'name'        => 'Track Delivery',
            'description' => 'Виджет отслеживания доставки по трек-коду',
            'classname'   => 'td-delivery'
        ]);
    }

    /**
     * Frontend widget view
     * @param  array $args
     * @param  array $instance
     */
    public function widget($args, $instance)
    {
        // Include style & javascript
        wp_enqueue_style( 'td-style' );
        // Use embed JQuery
        wp_enqueue_script('jquery');
        wp_enqueue_script('td-tracker-client-script');
        wp_enqueue_script('td-main-script');

        // Add data to JavaScript
        wp_localize_script('td-main-script', 'TDObject', [
            'ajax_url' => admin_url( 'admin-ajax.php' ),
        ]);
        
        echo (new View())->make('main', $args + [
            'title' => apply_filters( 'widget_title', $instance['title'] ),
            'text'  => apply_filters( 'widget_text', $instance['text'] ),
        ]);
    }

    /**
     * Backend widget view
     * @param  array $instance
     * @return string
     */
    public function form($instance)
    {
        $backend_view = new View();
        $backend_view->setWidgetInstance($this);
        echo $backend_view->make('backend', $instance);
    }

    /**
     * Event before save settings
     * @param array $new_instance
     * @param array $old_instance
     * @return array
     */
    public function update($new_instance, $old_instance)
    {
        $new_instance['title'] = !empty($new_instance['title']) ? strip_tags($new_instance['title']) : '';
        $new_instance['newpost_apikey'] = isset($new_instance['newpost_apikey']) ? strip_tags($new_instance['newpost_apikey']) : '';
        $new_instance['ukrpost_guid']   = isset($new_instance['ukrpost_guid'])   ? strip_tags($new_instance['ukrpost_guid'])   : '';

        return $new_instance;
    }

    /**
     * Custom getting widget option
     * @param  string $option_name
     * @return mixed
     */
    public static function get_option($option_name)
    {
        $widget_options = get_option('widget_' . self::BASE_ID);
        foreach($widget_options as $option) {
            if ( is_array($option) && array_key_exists($option_name, $option) )
                return $option[$option_name];
        }
        return false;
    }
}