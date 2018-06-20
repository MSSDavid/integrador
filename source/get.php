<?php
require('../vendor/autoload.php');
require "app/Queue.php";

$url = "amqp://xitcrhfn:xGA2xHF8jbYiDdyzKHNocxKyh4GgXT5a@baboon.rmq.cloudamqp.com/xitcrhfn";
$channel = "development";
$fake ="TESTE";
$fake2 = "amqp://teste456:xGA2xHF8jbYiDdyzKHNocxKyh4GgXT5a@baboon.rmq.cloudamqp.com/teste123";
$fake3 = "qempq://teste456:xGA2xHF8jbYiDdyzKHNocxKyh4GgXT5a@asassa.asfaf.fasfasfa.asfa/teste123";


$Queue = new Queue($url, $channel, true);
echo json_encode(array("response" => "ok", "message" => $Queue->getMessage()));
exit;