<?php
require('../vendor/autoload.php');
require "app/Queue.php";

$url = "amqp://xitcrhfn:xGA2xHF8jbYiDdyzKHNocxKyh4GgXT5a@baboon.rmq.cloudamqp.com/xitcrhfn";
$channel = "development";


$Queue = new Queue($url, $channel);
echo json_encode(array("response" => "ok", "message" => $Queue->getMessage()));
exit;