<?php

/**
 * UkrPostService
 */
class UkrPostService implements IDeliveryService
{
    private $_language;
    private $_api_key;
    private $_track_code;

    public function setLanguage($lang)
    {
        switch ($lang) {
            case 'uk':
                $this->_language = 'uk';
                break;
            case 'en':
            case 'en_US':
                $this->_language = 'en';
                break;
            default:
                $this->_language = 'uk';
        }
    }

    public function setApiKey($api_key)
    {
        $this->_api_key = $api_key;
    }

    public function setTrackCode($trak_code)
    {
        $this->_track_code = $trak_code;
    }

    public function isValidTrackCode()
    {
        return (bool)preg_match("/^\d{13}$/" , $this->_track_code);
    }

    public function getDeliveryStatus()
    {
        if (!$this->_api_key || !$this->_language) return false;

        $client = new SoapClient("http://services.ukrposhta.com/barcodestatistic/barcodestatistic.asmx?WSDL");

        // Getting delivery status
        try{
            $result = $client->GetBarcodeInfo([
                'guid'    => $this->_api_key,
                'barcode' => $this->_track_code,
                'culture' => $this->_language,
            ]);
            
            return $result->GetBarcodeInfoResult->eventdescription;
        }
        catch(SoapFault $sf){
            // Request error
            return false;
        }
        catch(Exeption $ex){
            // Time out
            return false;
        }
        
        return false;
    }
    
}