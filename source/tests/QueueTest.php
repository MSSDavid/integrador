<?php
require 'vendor/autoload.php';

use PHPUnit\Framework\TestCase;

final class QueueTest extends TestCase{

    private $channel = "test";
    private $url = "amqp://xitcrhfn:xGA2xHF8jbYiDdyzKHNocxKyh4GgXT5a@baboon.rmq.cloudamqp.com/xitcrhfn";
    private $fakeUrl = "amqp://fake5421:xGA2xHF8jbYiDdyzKHNocxKyh4GgXT5a@baboon.rmq.cloudamqp.com/fake123";
    private $invalidUrl = "invalidUrl";

    public function testPutAndGetMessage(){
        $q = new Queue($this->url, $this->channel);
        $message = array("a" => 1, "b" => 10, "c" => "teste");
        $message = json_encode($message);
        $put_response =  $q->putMessage($message);
        $returnMessage = $q->getMessage();

        $this->assertEquals(true, $put_response);
        $this->assertEquals($message, $returnMessage);
    }

    public function testPutAndGetMessageDebugMode(){
        $q = new Queue($this->url, $this->channel, true);
        $message = array("a" => 1, "b" => 10, "c" => "teste");
        $message = json_encode($message);
        $put_response =  $q->putMessage($message);
        $get_response = $q->getMessage();

        $this->assertEquals(true, $put_response['response']);
        $this->assertEquals(true, $get_response['response']);
        $this->assertEquals($message, $get_response['message']);
    }

    public function getEmptyQueue(){
        $q = new Queue($this->url, $this->channel);
        $get_response = $q->getMessage();

        $this->assertEquals(null, $get_response);
    }

    public function getEmptyQueueDebugMode(){
        $q = new Queue($this->url, $this->channel, true);
        $get_response = $q->getMessage();

        $this->assertEquals(true, $get_response['response']);
        $this->assertEquals(null, $get_response['message']);
    }

    public function testPutMessageFakeUrl(){
        $q = new Queue($this->fakeUrl, $this->channel);
        $message = array("a" => 1, "b" => 10, "c" => "teste");
        $message = json_encode($message);
        $put_response = $q->putMessage($message);

        $this->assertEquals(false, $put_response);
    }

    public function testGetMessageFakeUrl(){
        $q = new Queue($this->fakeUrl, $this->channel);
        $get_response = $q->getMessage();

        $this->assertEquals(false, $get_response);
    }

    public function testPutMessageInvalidUrl(){
        $q = new Queue($this->invalidUrl, $this->channel);
        $message = array("a" => 1, "b" => 10, "c" => "teste");
        $message = json_encode($message);
        $put_response = $q->putMessage($message);

        $this->assertEquals(false, $put_response);
    }

    public function testGetMessageInvalidUrl(){
        $q = new Queue($this->invalidUrl, $this->channel);
        $get_response = $q->getMessage();

        $this->assertEquals(false, $get_response);
    }

    public function testPutMessageFakeUrlDebugMode(){
        $q = new Queue($this->fakeUrl, $this->channel, true);
        $message = array("a" => 1, "b" => 10, "c" => "teste");
        $message = json_encode($message);
        $put_response = $q->putMessage($message);

        $this->assertEquals(false, $put_response["response"]);
        $this->assertArrayHasKey('error', $put_response);
    }

    public function testGetMessageFakeUrlDebugMode(){
        $q = new Queue($this->fakeUrl, $this->channel, true);
        $get_response = $q->getMessage();

        $this->assertEquals(false, $get_response["response"]);
        $this->assertArrayHasKey('error', $get_response);
    }

    public function testPutMessageInvalidUrlDebugMode(){
        $q = new Queue($this->invalidUrl, $this->channel, true);
        $message = array("a" => 1, "b" => 10, "c" => "teste");
        $message = json_encode($message);
        $put_response = $q->putMessage($message);

        $this->assertEquals(false, $put_response["response"]);
        $this->assertArrayHasKey('error', $put_response);
        $this->assertEquals("Invalid URL", $put_response["error"]);
    }

    public function testGetMessageInvalidUrlDebugMode(){
        $q = new Queue($this->fakeUrl, $this->channel, true);
        $get_response = $q->getMessage();

        $this->assertEquals(false, $get_response["response"]);
        $this->assertArrayHasKey('error', $get_response);
    }
}
?>
