<?php
/**
 * Class QueueTest | tests/QueueTest.php
 *
 * @package     github.com/MSSDavid/integrador
 * @subpackage  Queue
 * @author      Samuel Costa <samu.rcosta@gmail.com>
 * @version     v.1.0.0 (20/06/2018)
 * @since       v.1.0.0 (20/06/2018)
 * @copyright   Copyright (c) 2018, Samuel Costa
 */
require 'vendor/autoload.php';
use PHPUnit\Framework\TestCase;

/**
 * Esta classe implementa os testes da classe Queue em apps/Queue.php
 */
final class QueueTest extends TestCase{
    /** @var string Canal para testes. */
    private $channel = "test";
    /** @var string Url válida da instância do CloudAMAQP. */
    private $url = "amqp://xitcrhfn:xGA2xHF8jbYiDdyzKHNocxKyh4GgXT5a@baboon.rmq.cloudamqp.com/xitcrhfn";
    /** @var string Url falsa, com o formato AMPQ para testes. */
    private $fakeUrl = "amqp://fake5421:xGA2xHF8jbYiDdyzKHNocxKyh4GgXT5a@baboon.rmq.cloudamqp.com/fake123";
    /** @var string Url inválida. */
    private $invalidUrl = "invalidUrl";

    /**
     * Esta função testa as operações simples de colocar e retirar uma mensagem, usa o putMessage para colocar uma mensagem no canal e a compara com a mensagem recebida utilizando o getMessage
     */
    public function testPutAndGetMessage(){
        $q = new Queue($this->url, $this->channel);
        $message = array("a" => 1, "b" => 10, "c" => "teste");
        $message = json_encode($message);
        $put_response =  $q->putMessage($message);
        $returnMessage = $q->getMessage();

        $this->assertEquals(true, $put_response);
        $this->assertEquals($message, $returnMessage);
    }

    /**
     * Esta função testa as operações simples de colocar e retirar uma mensagem, porém com o debug ativado
     */
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

    /**
     * Esta função testa o resultado da função quando a fila de mensagens está vazia
     */
    public function testGetEmptyQueue(){
        $q = new Queue($this->url, $this->channel);
        $get_response = $q->getMessage();

        $this->assertEquals(null, $get_response);
    }

    /**
     * Esta função testa o resultado da função quando a fila de mensagens está vazia, entretanto com o debug ativado
     */
    public function testGetEmptyQueueDebugMode(){
        $q = new Queue($this->url, $this->channel, true);
        $get_response = $q->getMessage();

        $this->assertEquals(true, $get_response['response']);
        $this->assertEquals(null, $get_response['message']);
    }

    /**
     * Esta função testa o resultado de colocar uma mensagem no canal utilizando uma Url falsa
     */
    public function testPutMessageFakeUrl(){
        $q = new Queue($this->fakeUrl, $this->channel);
        $message = array("a" => 1, "b" => 10, "c" => "teste");
        $message = json_encode($message);
        $put_response = $q->putMessage($message);

        $this->assertEquals(false, $put_response);
    }

    /**
     * Esta função testa o resultado de retirar uma mensagem do canal utilizando uma Url falsa
     */
    public function testGetMessageFakeUrl(){
        $q = new Queue($this->fakeUrl, $this->channel);
        $get_response = $q->getMessage();

        $this->assertEquals(false, $get_response);
    }

    /**
     * Esta função testa o resultado de colocar uma mensagem no canal utilizando uma Url inválida
     */
    public function testPutMessageInvalidUrl(){
        $q = new Queue($this->invalidUrl, $this->channel);
        $message = array("a" => 1, "b" => 10, "c" => "teste");
        $message = json_encode($message);
        $put_response = $q->putMessage($message);

        $this->assertEquals(false, $put_response);
    }

    /**
     * Esta função testa o resultado de retirar uma mensagem do canal utilizando uma Url inválida
     */
    public function testGetMessageInvalidUrl(){
        $q = new Queue($this->invalidUrl, $this->channel);
        $get_response = $q->getMessage();

        $this->assertEquals(false, $get_response);
    }

    /**
     * Esta função testa o resultado de colocar uma mensagem no canal utilizando uma Url falsa, porém com o modo debug ativado
     */
    public function testPutMessageFakeUrlDebugMode(){
        $q = new Queue($this->fakeUrl, $this->channel, true);
        $message = array("a" => 1, "b" => 10, "c" => "teste");
        $message = json_encode($message);
        $put_response = $q->putMessage($message);

        $this->assertEquals(false, $put_response["response"]);
        $this->assertArrayHasKey('error', $put_response);
    }

    /**
     * Esta função testa o resultado de retirar uma mensagem do canal utilizando uma Url falsa, porém com o modo debug ativado
     */
    public function testGetMessageFakeUrlDebugMode(){
        $q = new Queue($this->fakeUrl, $this->channel, true);
        $get_response = $q->getMessage();

        $this->assertEquals(false, $get_response["response"]);
        $this->assertArrayHasKey('error', $get_response);
    }

    /**
     * Esta função testa o resultado de colocar uma mensagem no canal utilizando uma Url inválida, porém com o modo debug ativado
     */
    public function testPutMessageInvalidUrlDebugMode(){
        $q = new Queue($this->invalidUrl, $this->channel, true);
        $message = array("a" => 1, "b" => 10, "c" => "teste");
        $message = json_encode($message);
        $put_response = $q->putMessage($message);

        $this->assertEquals(false, $put_response["response"]);
        $this->assertArrayHasKey('error', $put_response);
        $this->assertEquals("Invalid URL", $put_response["error"]);
    }

    /**
     * Esta função testa o resultado de retirar uma mensagem do canal utilizando uma Url inválida, porém com o modo debug ativado
     */
    public function testGetMessageInvalidUrlDebugMode(){
        $q = new Queue($this->invalidUrl, $this->channel, true);
        $get_response = $q->getMessage();

        $this->assertEquals(false, $get_response["response"]);
        $this->assertArrayHasKey('error', $get_response);
        $this->assertEquals("Invalid URL", $get_response["error"]);
    }
}
?>
