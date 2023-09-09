<?php

/**
 * it's phunTill you have to do it yourself
 * phunTillException is thrown on failure.
 *
 * API Documentation: https://testapi.untill.com/shield/api/
 */

namespace HexMakina\phunTill;

class POSAPI
{
    public const AUTH_FORMAT = '%s:%s';

    private $curl_handle;
    private $baseUrl;
    private $database;
    private $appToken;
    private $appName;
    private $username;
    private $password;
    private $version;
    private $headers;

    /**
     * Header examples:
     * - Only application token present: AppToken: myAppToken
     * - Application token and application name present: AppToken: myAppToken:myAppName
     *
     * @param string $baseUrl
     * @param string $database
     * @param string $appToken
     * @param string $appName
     * @param string $version
     */
    public function __construct($baseUrl, $database, $appToken, $appName, $version)
    {
        $this->baseUrl = $baseUrl;
        $this->database = $database;
        $this->appToken = $appToken;
        $this->appName = $appName;
        $this->version = $version;

        $this->headers = [
            'AppToken: ' . sprintf(self::AUTH_FORMAT, $this->appToken, $this->appName),
            'Content-Type: application/json',
        ];
    }

    /**
     * Set API credentials.
     *
     * @param string $username
     * @param string $password
     */
    public function setCredentials($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Get the database name.
     *
     * @return string
     */
    public function database(): string
    {
        return $this->database;
    }

    /**
     * Get the API version.
     *
     * @return string
     */
    public function version(): string
    {
        return $this->version;
    }

    /**
     * Get the base URL.
     *
     * @return string
     */
    public function baseUrl(): string
    {
        return $this->baseUrl;
    }

    /**
     * Get the headers array.
     *
     * @return array
     */
    public function headers(): array
    {
        return $this->headers;
    }

    /**
     * Get the authentication string.
     *
     * @return string
     */
    public function authString(): string
    {
        return sprintf(self::AUTH_FORMAT, $this->username, $this->password);
    }

    /**
     * Send a GET request.
     *
     * @param string $endpoint
     * @param array $params
     * @param string|null $version
     * @return Response
     * @throws phunTillException
     */
    public function get(string $endpoint, $params = [], $version = null): Response
    {
        $request = new Request($this, $endpoint, 'GET', $version);
        $request->withParameters($params);
        return $this->execute($request);
    }

    /**
     * Send a POST request.
     *
     * @param string $endpoint
     * @param string $json_content
     * @param string|null $version
     * @return Response
     * @throws phunTillException
     */
    public function post(string $endpoint, string $json_content, $version = null): Response
    {
        $post_options = [
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $json_content,
        ];

        $request = new Request($this, $endpoint, 'POST', $version);
        $request->withOptions($post_options);

        return $this->execute($request);
    }

    /**
     * Execute the request and handle exceptions.
     *
     * @param Request $request
     * @return Response
     * @throws phunTillException
     */
    private function execute(Request $request): Response
    {
        $this->curl_handle = curl_init();
        $request->withOption(CURLOPT_URL, $request->URL());

        foreach ($request->options() as $const => $value) {
            curl_setopt($this->curl_handle, $const, $value);
        }

        $body = curl_exec($this->curl_handle); // string application/json

        if ($body === false) {
            throw new phunTillException(curl_error($this->curl_handle), curl_errno($this->curl_handle));
        }

        $status = curl_getinfo($this->curl_handle, CURLINFO_HTTP_CODE);

        return new Response($body, $status);
    }
}
