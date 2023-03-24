<?php
$api = new POSAPI("https://your-api-url.com", "your-access-token");

$table = $api->getTable('anticasicilia.be');

$order = new Order($table->getNumber());

$order->addItem("3", "1");
$order->addItem("1", "2");
$order->addItem("2", "2");

$response = $api->createOrder($order);

if ($response->success) {
    echo "Order created successfully with order ID: " . $response->orderId;
} else {
    echo "Error: " . $response->error;
}
