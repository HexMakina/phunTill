<?php

/*
 * phunTill you have to do it yourself
 *
 */

namespace HexMakina\phunTill;

class Request
{
    private $curl_handle;
    private $body;

    private POSAPI $api;
    private $version;
    private $endPoint;


    public function __construct(POSAPI $api, $endPoint, $version)
    {
        $this->api = $api;
        $this->version = $version;
        $this->endPoint = $endPoint;

        $this->curl_handle = curl_init();
        vd($this->url());
        curl_setopt($this->curl_handle, CURLOPT_URL, $this->url());
        curl_setopt($this->curl_handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl_handle, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($this->curl_handle, CURLOPT_USERPWD, $api->authString());
        curl_setopt($this->curl_handle, CURLOPT_HTTPHEADER, $api->headers());
    }

    public function get() : Response
    {
      return $this->execute();
    }

    public function post(string $json_content) : Response
    {
      $this->body = $json_content;
      curl_setopt($this->curl_handle, CURLOPT_POST, 1);
      curl_setopt($this->curl_handle, CURLOPT_POSTFIELDS, $this->body);

      return $this->execute();
    }

    private function execute() : Response
    {
      $response = curl_exec($this->curl_handle);
      $response_code = curl_getinfo($this->curl_handle, CURLINFO_HTTP_CODE);

      return new Response($response, $response_code);
    }

    private function url()
    {
        return $this->api->baseUrl() . '/api/' . $this->api->getVersion() .'/' . $this->api->getDatabase() . $this->endPoint;
    }
}
