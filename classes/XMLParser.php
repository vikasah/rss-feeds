<?php

/**
 * This class parses the url for xml content and returns an array with the values,
 * if url does not contain any xml content, then returns false
 * 
 * @author  Sukh
 */
class XMLParser {

    /**
     * $xml
     * 
     * URL for xml content
     *
     * @var string
     */
    private $xml;

    /**
     * Class constructor.
     *
     */
    public function __construct() {}

    /**
     * getParsedContent
     * 
     * This adds the url and parses it, if parsing passes then it will format an array to return the results otherwise returns a false.
     * 
     * @param string $url Pass the url that will be used to return the correct response
     * 
     * @return $arr returns an array with xml parsed data
     * @return false returns false if value not parsed or if xml does not exist
     */
    public function getParsedContent($url) {
        $arr = array();
        if($this->parse($url)) {
            if($this->xml->item) {
                for($i=0; $i < $this->xml->item->count(); $i++) {
                    array_push($arr, array(
                        'title' => $this->xml->item[$i]->title[0] ? $this->xml->item[$i]->title[0] : '',
                        'description' => $this->xml->item[$i]->description[0] ? $this->xml->item[$i]->description[0] : '',
                        'link' => $this->xml->item[$i]->link[0] ? $this->xml->item[$i]->link[0] : '',
                    ));
                }
                return $arr;
            } elseif($this->xml->channel->item) {
                for($i=0; $i < $this->xml->channel->item->count(); $i++) {
                    array_push($arr, array(
                        'title' => $this->xml->channel->item[$i]->title[0] ? $this->xml->channel->item[$i]->title[0] : '',
                        'description' => $this->xml->channel->item[$i]->description[0] ? $this->xml->channel->item[$i]->description[0] : '',
                        'link' => $this->xml->channel->item[$i]->link[0] ? $this->xml->channel->item[$i]->link[0] : '',
                    ));
                }
                return $arr;
            } else {
                return false;
            }
        }else {
            return false;
        }
    }

    /**
     * parse
     * 
     * This checks the url and tries to see what content the url shows, if it returns xml then it will return true otherwise false.
     * 
     * @param string $url Pass the url that will be used to return the correct response
     * 
     * @return true if value parsed
     * @return false if value not parsed or value not sanitised
     */
    private function parse($url) {
        $clean_value = $this->validateSanitiseURL($url);
        if($clean_value) {
            $context  = stream_context_create(array('http' => array('header' => 'Accept: application/xml')));
            if(@file_get_contents($clean_value, false, $context)) {
                $this->xml = file_get_contents($clean_value, false, $context);
                if(@simplexml_load_string($this->xml)) {
                    $this->xml = simplexml_load_string($this->xml);
                    return true;
                }else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * validateSanitiseURL
     * 
     * This validates then sanitises the value passed for security reasons.
     * 
     * @param string $url Pass the url for validation
     * 
     * @return $sanitised_url if value validated
     * @return false if value not validated
     */
    private function validateSanitiseURL($url) {
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            $validated_url = filter_var($url, FILTER_VALIDATE_URL);
            if(filter_var($validated_url, FILTER_SANITIZE_URL)) {
                $sanitised_url = filter_var($validated_url, FILTER_SANITIZE_URL);
                return $sanitised_url;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

}