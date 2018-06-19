<?php
require '../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;

final class QueueTest extends TestCase{

    private $channel = "test";
    private $url = "amqp://xitcrhfn:xGA2xHF8jbYiDdyzKHNocxKyh4GgXT5a@baboon.rmq.cloudamqp.com/xitcrhfn";
    private $fakeUrl = "amqp://fake5421:xGA2xHF8jbYiDdyzKHNocxKyh4GgXT5a@baboon.rmq.cloudamqp.com/fake123";

    public function testPutAndGetMessage(){
        $q = new Queue($this->url, $this->channel);
        $message = array("a" => 1, "b" => 10, "c" => "teste");
        $message = json_encode($message);
        $q->putMessage($message);
        $returnMessage = $q->getMessage();

        $this->assertEquals($message, $returnMessage);
    }
}
?>
