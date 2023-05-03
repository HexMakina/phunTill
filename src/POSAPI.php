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

    public function __construct($baseUrl, $database, $appToken, $appName, $version = 'v1')
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

    public function getDatabase(): string
    {
        return $this->database;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function baseUrl()
    {
        return $this->baseUrl;
    }

    public function onlineTableName($name = null)
    {
        if (!is_null($name))
            $this->onlineTableName = $name;

        return $this->onlineTableName;
    }

    public function headers()
    {
        return $this->headers;
    }

    public function authString()
    {
        return sprintf(self::AUTH_FORMAT, $this->username, $this->password);
    }


    public function request($endpoint, $version = 'v1'): Request
    {
        return new Request($this, $endpoint, $version);
    }

    public function categories(bool $only_active = true): array
    {
        $endpoint = '/category';
        $res = $this->request($endpoint)->get();
        return $res->array();
    }

    public function courses(bool $only_active = true): array
    {
        $endpoint = '/course';
        if ($only_active === true)
            $endpoint .= '?active=true';
        $res = $this->request($endpoint)->get();
        return $res->array();
    }

    public function articles(bool $only_active = true): array
    {
        $endpoint = '/article';
        if ($only_active === true)
            $endpoint .= '?active=true';

        $res = $this->request($endpoint)->get();
        return $res->array();
    }

    public function paymentMethods(): PaymentMethods
    {
        if (is_null($this->paymentMethods))
            $this->paymentMethods = new PaymentMethods($this);

        return $this->paymentMethods;
    }

    public function onlineTable($number = 1, $part = 'a'): array
    {
        $endpoint = sprintf("/table?tableNumber=%d&tablePart=%s", $number, urlencode($part));
        $res = $this->request($endpoint)->get();

        return $res->array();
    }

    public function createOrder(Order $order): int
    {
        $response = $this->request('order', 'v2')->post($order->json());

        if (!$response->success())
            throw new phunTillException('Order creation failed');

        $res = $response->array();

        return (int)$res['transactionId'];
    }

    public function pay(Order $order, int $paymentId): Response
    {
        $payment = [
            'tableNumber' => $order->tableNumber(),
            'tablePart' => $order->tablePart(),
            'paymentId' => $paymentId,
            // 'returnTicket' => true,
            'amount' => 0.0
        ];
        $payment = json_encode($payment);

        $request = $this->request('pay-order', 'v1');
        $response = $request->post($payment);
        if (!$response->success()) {
            throw new phunTillException($response->content(), $response->status());
        }

        return $response;
    }

    // public function getTables() : array
    // {
    //   $endpoint = 'tables';
    //   $response = $this->read($endpoint);
    //   $response = json_decode($response);
    //
    //   $ret =[];
    //   foreach($response as $table){
    //       $ret []= Table::fromJSON($response);
    //   }
    //
    //   return $ret;
    // }
    //
    // public static function getItems()
    // {
    //   $endpoint = 'tables';
    //   $response = $this->read($endpoint);
    //   $items = json_decode($response);
    //   $items_arr = array();
    //   foreach ($items as $item) {
    //       $items_arr[] = new Item($item->id, $item->name, $item->price, $item->description);
    //   }
    //   return $items_arr;
    // }


}
