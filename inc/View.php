<?php

/**
 * View
 * 
 * Simple templater
 */
class View
{
    // Reference to an instance of WP_Widget
    private $_widget_instance;
    
    public function __construct()
    {
        $this->_widget_instance = TrackDeliveryWidget::getInstance();
    }
    
    /**
     * Get rendered template file
     * @param  string $tpl_name template name
     * @param  array  $vars variables used in template
     * @return string rendered file
     */
    public function make($tpl_name, array $vars)
    {
        $tpl_file = TD_VIEWS_DIR . '/' . $tpl_name . '.tpl.php';
        
        if ( file_exists($tpl_file) ) {
            $__widget = $this->_widget_instance;

            ob_start();
            if ( !empty($vars) ) extract($vars);
            require($tpl_file);
                
            return ob_get_clean();
        }
        
        throw new TrackDeliveryException('Template file not found');
    }
}