<?php

/**
 * Helper
 * 
 * Contains some helper methods 
 */
class Helper
{
    /**
     * Converts array to xml
     * @param  array   $data
     * @param  boolean $xml
     * @return string xml
     */
    public static function arrayToXML(array $data, $xml = false)
    {
        if ($xml === false) $xml = new \SimpleXMLElement('<file/>');
        foreach($data as $key => $value){
            if (is_array($value)){
                self::arrayToXML($value, $xml->addChild($key));
            } else {
                $xml->addChild($key, $value);
            }
        }
        return $xml->asXML();
    }

    /**
     * Sending XML request and get response
     * @param  [type] $url request url
     * @param  [type] $xml request xml
     * @return [type] response (xml)
     */
    public static function sendXMLRequest($url, $xml)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: text/xml"]);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }
}