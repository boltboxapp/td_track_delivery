<?php

/**
 * NewPostService
 */
class NewPostService implements IDeliveryService
{
    private $_language;
    private $_api_key;
    private $_track_code;

    // Request StateID
    const SID_NOT_FOUND       = 3,
          SID_PREPARING       = 4,
          SID_SENDING         = 5,
          SID_TARGET_DELIVERY = 6,
          SID_DELIVERED       = 7,
          SID_RECEIVED        = 10;

    public function setLanguage($lang)
    {
        switch ($lang){
            case 'uk':
                $this->_language = 'ua';
                break;
            case 'ru':
            case 'ru_RU':
                $this->_language = 'ru';
                break;
            default:
                $this->_language = 'ua'; 
        }
    }

    public function setApiKey($api_key)
    {
        $this->_api_key = $api_key;
    }

    public function setTrackCode($track_code)
    {
        $this->_track_code = $track_code;
    }

    public function isValidTrackCode()
    {
        return (bool)preg_match("/^\d{14}$/" , $this->_track_code);
    }

    /**
     * Request pattern
     * @return string xml
     */
    private function prepareRequest()
    {
        $data = [
            'apiKey' => $this->_api_key,
            'language' => $this->_language,
            'modelName' => 'InternetDocument',
            'calledMethod' => 'documentsTracking',
            'methodProperties' => [
                'Documents' => [
                    'item' => $this->_track_code,
                ],
            ],
        ];

        $xml = new SimpleXMLElement('<file/>');
        foreach ($data as $key => $value) {
            if ( is_array($value) ) {
                Helper::arrayToXML( $value, $xml->addChild($key) );
            } else {
                $xml->addChild($key, $value);
            }
        }
        return $xml->asXML();
    }

    /**
     * Create message from received data
     * @param array $response response data
     * @return string message
     */
    private function composeResponseMessage(array $response)
    {
        $city_receiver = $response['CityReceiver' . mb_strtoupper($this->_language)];
        $date_received = $response['DateReceived'];
        $state_name    = $response['StateName'];
        $address = $response['Address' . mb_strtoupper($this->_language)];
        $sum     = $response['Sum'];

        switch ( (int)$response['StateId'] ) {
            case self::SID_NOT_FOUND:
                $message = __('Sending in processing or wrong code', TD_LANG_DOMAIN);
                break;
            case self::SID_PREPARING:
                $message = __('Sending is preparing to send', TD_LANG_DOMAIN) . "\n" .
                           __('Delivery address', TD_LANG_DOMAIN) . $city_receiver . ' ' . $address . " \n" .
                           __('Cost of delivery',  TD_LANG_DOMAIN) . $sum . ' ' . __('grn', TD_LANG_DOMAIN);
                break;
            case self::SID_SENDING:
                $message = __('Sending is sent to the destination', TD_LANG_DOMAIN) . "\n" .
                           __('Delivery address', TD_LANG_DOMAIN) . $city_receiver . ' ' . $address . " \n" .
                           __('Cost of delivery', TD_LANG_DOMAIN) . $sum . ' ' . __('grn', TD_LANG_DOMAIN);
                break;
            case self::SID_TARGET_DELIVERY:
                $message = __('Waiting for targeted delivery', TD_LANG_DOMAIN) . "\n" .
                           __('Delivery address', TD_LANG_DOMAIN) . $city_receiver . ' ' . $address . "\n" .
                           __('Cost of delivery', TD_LANG_DOMAIN) . $sum . ' ' . __('grn', TD_LANG_DOMAIN);
                break;
            case self::SID_DELIVERED:
                $message = __('Sending arrived',  TD_LANG_DOMAIN) . "\n" .
                           __('Delivery address', TD_LANG_DOMAIN) . $city_receiver . ' ' . $address . "\n" .
                           __('Cost of delivery', TD_LANG_DOMAIN) . $sum . ' ' . __('grn', TD_LANG_DOMAIN);
                break;
            case self::SID_RECEIVED:
                $message = __('Sending received', TD_LANG_DOMAIN) . ' ' . $date_received;
                break;
            // Other variants
            default:
                $message = mb_strtolower($state_name);
        }
        
        return $message;
    }

    public function getDeliveryStatus()
    {
        if (!$this->_api_key || !$this->_language) return false;

        $request = $this->prepareRequest();

        // Disable warnings to output
        $error_reporting_level = error_reporting();
        error_reporting(E_ERROR);

        try {
            $response_object = new SimpleXMLElement( Helper::sendXMLRequest('https://api.novaposhta.ua/v2.0/xml/', $request) );
            // Bad Request
            if ($response_object->success == 'false') return false;

            $response = $this->composeResponseMessage( (array)$response_object->data->item );
        } catch (Exception $e) {
            return false;
        } finally {
            // Restore error reporting level
            error_reporting( $error_reporting_level );
        }
        
        return $response;
    }

}