<?php
require('vendor/autoload.php');
require "app/Queue.php";

$url = "amqp://xitcrhfn:xGA2xHF8jbYiDdyzKHNocxKyh4GgXT5a@baboon.rmq.cloudamqp.com/xitcrhfn";
$channel = "development";


$_POST = array_merge($_POST, (array) json_decode(file_get_contents('php://input')));

if(!empty($_POST)){
    if(isset($_POST['message']) && !empty($_POST['message'])){
        $Queue = new Queue($url, $channel);
        $Queue->putMessage($_POST['message']);
        echo json_encode(array("response" => "ok"));
        exit;
    }
}
?>