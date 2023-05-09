<?php

/*
 * phunTill you have to do it yourself
 *
 */

/*
   DOC: https://testapi.untill.com/shield/api/
 */

namespace HexMakina\phunTill;

class POSAPI
{
    public const AUTH_FORMAT = '%s:%s';
    public const SUCCESS_CODES = [200, 201];

    private $curl_handle;

    private $baseUrl;
    private $database;
    private $appToken;
    private $appName;

    private $username;
    private $password;

    private $version;


    private $headers;
    private $onlineTableName = null;
    private $paymentMethods = null;


    /**
     * Header examples
     *  Only application token presents - AppToken: myAppToken
     *  Application token and application name presents - AppToken: myAppToken:myAppName
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
            'Content-Type: application/json'
        ];
    }

    public function setCredentials($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    public function database(): string
    {
        return $this->database;
    }

    public function version(): string
    {
        return $this->version;
    }

    public function baseUrl(): string
    {
        return $this->baseUrl;
    }

    public function onlineTableName($name = null)
    {
        if (!is_null($name))
            $this->onlineTableName = $name;

        return $this->onlineTableName;
    }

    public function headers(): array
    {
        return $this->headers;
    }

    public function authString(): string
    {
        return sprintf(self::AUTH_FORMAT, $this->username, $this->password);
    }

    // throws phunTillException depending of success
    public function get(string $endpoint, $params = [], $version = null): Response
    {
        $request = new Request($this, $endpoint, 'GET', $version);
        $request->withParameters($params);

        return $this->execute($request);
    }

    // throws phunTillException depending of success
    public function post(string $endpoint, string $json_content, $version = null): Response
    {
        $post_options = [
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $json_content
        ];

        $request = new Request($this, $endpoint, 'POST', $version);
        $request->withOptions($post_options);

        return $this->execute($request);
    }

    private function execute(Request $request): Response
    {
        $this->curl_handle = curl_init();

        $request->withOption(CURLOPT_URL, $request->URL());

        foreach ($request->options() as $const => $value)
            curl_setopt($this->curl_handle, $const, $value);


        $body = curl_exec($this->curl_handle);
        if ($body === false) {
            $body = json_encode(curl_getinfo($this->curl_handle)); // error context
        }

        $status = curl_getinfo($this->curl_handle, CURLINFO_HTTP_CODE);
        if (!in_array($status, self::SUCCESS_CODES))
            throw new phunTillException('API_' . $request->method() . '_FAILURE-#' . $status);

        return new Response($body, $status);
    }


    public function paymentMethods(): PaymentMethods
    {
        if (is_null($this->paymentMethods))
            $this->paymentMethods = new PaymentMethods($this);

        return $this->paymentMethods;
    }
    
    // public function createOrder(Order $order): int
    // {
    //     $response = $this->post('order',$order->json(), 'v2');
    //     $response = $response->array();
    //     return (int)$response['transactionId'];
    // }

    // public function pay(Order $order, int $paymentId): Response
    // {
    //     $payment = [
    //         'tableNumber' => $order->tableNumber(),
    //         'tablePart' => $order->tablePart(),
    //         'paymentId' => $paymentId,
    //         // 'returnTicket' => true,
    //         'amount' => 0.0
    //     ];
    //     $payment = json_encode($payment);

    //     $response = $this->post('pay-order', $payment, 'v1');


    //     return $response;
    // }


    // // Find active table by parameters. Required user permission: Ordering
    // public function onlineTable($number = 1, $part = 'a'): array
    // {
    //     dd($this->get('table', ['tableNumber' => $number, 'tablePart' => $part])->array());
    //     return [];
    //     // $endpoint = sprintf("/table?tableNumber=%d&tablePart=%s", $number, urlencode($part));
    // }
}
