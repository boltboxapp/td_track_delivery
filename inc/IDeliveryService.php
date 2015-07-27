<?php

/**
 * IDeliveryService
 * 
 * Interface for delivery services
 */
interface IDeliveryService
{
    /**
     * Language of request
     * @param string $lang
     * @return void
     */
    public function setLanguage($lang);

    /**
     * Api key for authorized access
     * @param $api_key
     * @return void
     */
    public function setApiKey($api_key);

    /**
     * Tracking code of delivery
     * @param string $trak_code
     * @return void
     */
    public function setTrackCode($trak_code);

    /**
     * Check tracking code format for standards
     * @return bool validation passed/fails
     */
    public function isValidTrackCode();

    /**
     * Getting a status of delivery
     * @return mixed response of false(if fail)
     */
    public function getDeliveryStatus();
}