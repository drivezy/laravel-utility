<?php

namespace Drivezy\LaravelUtility\Library;

use Drivezy\LaravelUtility\LaravelUtility;
use GuzzleHttp\Client;

class RemoteRequest {
    /**
     * @param $url
     * @param array $headers
     * @param null $key
     * @return string
     */
    public static function getRequest ($url, $headers = [], $key = null) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, '300');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_USERPWD, $key);
        $o = trim(curl_exec($ch));

        curl_close($ch);

        return $o;
    }

    /**
     * @param $url
     * @param $params
     * @param null $key
     * @param array $header
     * @return array|string
     */
    public static function postRequest ($url, $params, $key = null, $header = []) {
        $ch = curl_init();
        curl_setopt_array($ch, array(
                CURLOPT_URL            => $url,
                CURLOPT_POST           => true,
                CURLOPT_POSTFIELDS     => http_build_query($params),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_USERAGENT      => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/36.0.1985.125 Safari/537.36',
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_USERPWD        => $key,
                CURLOPT_HTTPHEADER     => $header)
        );

        $o = trim(curl_exec($ch));
        if ( curl_errno($ch) ) {
            $c_error = curl_error($ch);
            if ( empty($c_error) )
                $c_error = 'Server Error';

            return array('curl_status' => 0, 'error' => $c_error);
        }
        curl_close($ch);

        return $o;
    }

    /**
     * @param $url
     * @param $content
     * @param $path
     * @param array $headers
     * @return array|\Psr\Http\Message\StreamInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function multipartPostRequest ($url, $content, $path, $headers = []) {
        $client = new Client();
        $data = [];

        if ( $content )
            array_push($data, [
                'name'     => 'request',
                'contents' => $content,
            ]);

        if ( $path )
            array_push($data, [
                'name'     => 'file',
                'contents' => fopen($path, 'r'),
            ]);

        $response = $client->request('POST', $url, [
            'multipart' => $data,
            'headers'   => $headers,
        ]);

        $data = $response->getBody();

        return $data;
    }

    /**
     * @param $url
     * @return mixed
     */
    public static function getShortUrl ($url) {
        $apiKey = LaravelUtility::getProperty('google.api.key');

        $postData = array('longUrl' => $url);
        $jsonData = json_encode($postData);

        $curlObj = curl_init();

        curl_setopt($curlObj, CURLOPT_URL, 'https://www.googleapis.com/urlshortener/v1/url?key=' . $apiKey);
        curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curlObj, CURLOPT_HEADER, 0);
        curl_setopt($curlObj, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/36.0.1985.125 Safari/537.36');
        curl_setopt($curlObj, CURLOPT_HTTPHEADER, array('Content-type:application/json'));
        curl_setopt($curlObj, CURLOPT_POST, 1);
        curl_setopt($curlObj, CURLOPT_POSTFIELDS, $jsonData);

        $response = curl_exec($curlObj);
        $json = json_decode($response);

        curl_close($curlObj);

        return $json;
    }


    /**
     * This will push a JSON Request of user defined method.
     *
     * @param $url
     * @param $params
     * @param string $method
     * @param null $header
     * @return \Psr\Http\Message\StreamInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function pushJsonRequest ($url, $params, $method = 'POST', $header = null) {
        $client = new Client();
        $headers = ['Content-Type' => 'application/json',];

        if ( $header )
            foreach ( $header as $key => $value ) {
                $headers[ $key ] = $value;
            }

        $response = $client->request($method, $url, [
            'headers' => $headers,
            'json'    => $params,
        ]);

        return $response->getBody();
    }


    /**
     * Hits an API and returns JSON response
     *
     * @param $url
     * @param $headers
     * @param $query
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function getJson ($url, $headers, $query) {
        $client = new Client();
        $response = $client->request('GET', $url, [
            'headers' => $headers,
            'query'   => $query,
        ]);

        return json_decode($response->getBody());
    }
}
