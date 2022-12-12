<?php

namespace Vantevo;

class Client
{
    const DEFAULT_BASE_URL = "https://api.vantevo.io";
    const SEND_EVENT_API = "https://api.vantevo.io/v1/event";
    const SEND_EVENT_ECOMMERCE_API = "https://api.vantevo.io/v1/event-ecommerce";
    const STATS_API = "https://api.vantevo.io/v1/";


    const REFERRER_PARAMS = array(
        'ref',
        'referer',
        'referrer',
        'source',
        'utm_source'
    );

    private $accessToken;
    private $domain;
    private $timeout = 30;
    private $dev = false;


    function __construct($accessToken, $domain, $timeout, $dev = false)
    {
        if ($dev) {
            $this->dev = $dev;
        }
        if ($timeout) {
            $this->timeout = $timeout;
        }
        $this->domain = $domain;
        $this->accessToken = $accessToken;
    }

    function event($event, $retry = true)
    {

        $default_hit = array(
            "title" => null,
            "url" => $this->getRequestURL(),
            "referrer" => $this->getReferrer(),
            "width" => 0,
            "height" => 0,
            "meta" => json_encode([])
        );

        $hit = array_merge($default_hit, $event);

        if ($this->dev) {
            return print_r($hit);
        }

        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => array(
                    'Content-Type: application/json',
                    'User-Agent: ' . $this->getHeader("HTTP_USER_AGENT")
                ),
                'content' => json_encode($hit),
                'timeout' => $this->timeout
            )
        );

        $context = stream_context_create($options);
        $result = @file_get_contents(self::SEND_EVENT_API, false, $context);


        if ($result === FALSE) {
            if ($retry) {
                return  $this->event($event, false);
            } else {
                $responseHeader = $http_response_header[0];
                throw new \Exception('Error request: ' . $responseHeader);
            }
        }

        return $result;
    }

    function trackEcommerce($event, $retry = true)
    {

        $default_hit = array(
            "title" => null,
            "url" => $this->getRequestURL(),
            "referrer" => $this->getReferrer(),
            "width" => 0,
            "height" => 0
        );

        $hit = array_merge($default_hit, $event);

        if ($this->dev) {
            return print_r($hit);
        }

        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => array(
                    'Content-Type: application/json',
                    'User-Agent: ' . $this->getHeader("HTTP_USER_AGENT")
                ),
                'content' => json_encode($hit),
                'timeout' => $this->timeout
            )
        );

        $context = stream_context_create($options);
        $result = @file_get_contents(self::SEND_EVENT_ECOMMERCE_API, false, $context);


        if ($result === FALSE) {
            if ($retry) {
                return  $this->trackEcommerce($event, false);
            } else {
                $responseHeader = $http_response_header[0];
                throw new \Exception('Error request: ' . $responseHeader);
            }
        }

        return $result;
    }

    function stats($filters)
    {
        return $this->sendRequest("stats", $filters, true);
    }

    function events($filters)
    {
        return $this->sendRequest("events", $filters, true);
    }

    private function sendRequest($type, $filters, $retry = true)
    {

        $options = array(
            'http' => array(
                'method' => 'GET',
                'header' => 'Authorization: Bearer ' . $this->accessToken,
                'timeout' => $this->timeout
            )
        );
        $context = stream_context_create($options);
        $filters["domain"] = $this->domain;
        $result = @file_get_contents(self::STATS_API . $type . "?" . http_build_query($filters), false, $context);
        if ($result === FALSE) {
            if ($retry) {
                return  $this->sendRequest($type, $filters, false);
            } else {
                $responseHeader = $http_response_header[0];
                //$error = error_get_last();
                //echo "HTTP request failed. Error was: " . $error['message'];
                throw new \Exception('Error request: ' . $responseHeader);
            }
        }

        if (is_object(json_decode($result))) {
            return json_decode($result);
        }

        if ($retry) {
            return $this->sendRequest($type, $filters, false);
        } else {
            throw new \Exception('Error request: ' . $result);
        }
    }

    private function getRequestURL()
    {
        return 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }

    private function getReferrer()
    {
        $referrer = $this->getInfoHeader('HTTP_REFERER');
        if (empty($referrer)) {
            foreach (self::REFERRER_PARAMS as $key) {
                $referrer = $this->getQueryParams($key);

                if ($referrer != '') {
                    return $referrer;
                }
            }
        }

        return $referrer;
    }

    private function getHeader($name)
    {
        if (isset($_SERVER[$name])) {
            return $_SERVER[$name];
        }

        return '';
    }

    private function getQueryParams($name)
    {
        if (isset($_GET[$name])) {
            return $_GET[$name];
        }

        return '';
    }

    private function getInfoHeader($name)
    {
        if (isset($_SERVER[$name])) {
            return $_SERVER[$name];
        }

        return '';
    }
}
