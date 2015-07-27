<?php

/**
 * Tracker
 *
 * Delivery Services controller
 */
class Tracker
{
    // Response status codes
    const ST_ERROR   = 0;
    const ST_SUCCESS = 1;

    /**
     * Main form ajax request listener
     * @return void
     */
    public function mainFormHandler()
    {
        $delivery_service = $_POST['td_delivery_service'];
        $track_code = isset($_POST['td_track_code']) ? str_replace(' ', '', $_POST['td_track_code']) : '';

        if ( ($track_code != '') && $delivery_service ) {
            $response = $this->checkDeliveryStatus($delivery_service, $track_code);
        } else {
            if ( $track_code == '' ) $message = __('Tracking code is missed', TD_LANG_DOMAIN);
            else $message = __('Delivery service do not selected', TD_LANG_DOMAIN);
            $response = [
                'status'  => self::ST_ERROR,
                'message' => $message,
                ];
        }
        
        wp_send_json($response);
    }

    /**
     * Check delivery status
     * @param  string $delivery_name delivery service slug
     * @param  int $track_code
     * @return array answer
     */
    public function checkDeliveryStatus($delivery_name, $track_code)
    {
        switch($delivery_name) {
            case 'ukr_post':
                $delivery_service = new UkrPostService();
                $delivery_service->setApiKey( TrackDeliveryWidget::get_option('ukrpost_guid') );
                break;
            case 'new_post':
                $delivery_service = new NewPostService();
                $delivery_service->setApiKey( TrackDeliveryWidget::get_option('newpost_apikey') );
                break;
            default:
                return [
                    'status'  => self::ST_ERROR,
                    'message' => __('Wrong delivery service', TD_LANG_DOMAIN),
                ];
        }

        $delivery_service->setTrackCode($track_code);
        if ( !$delivery_service->isValidTrackCode() )
            return [
                'status'  => self::ST_ERROR,
                'message' => __('Wrong track code format', TD_LANG_DOMAIN),
            ];

        $delivery_service->setLanguage( get_locale() );

        $response = $delivery_service->getDeliveryStatus();
        $status  = ($response !== false) ? self::ST_SUCCESS : self::ST_ERROR;
        $message = ($response !== false) ? $response : __('Response error', TD_LANG_DOMAIN);

        return [
            'status'  => $status,
            'message' => nl2br($message, false),
        ];
    }
}