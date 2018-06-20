<?php
define('AMQP_DEBUG', false);
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class Queue{
    private $url;
    private $channel;
    private $channelConnection;
    private $exchange = 'amq.fanout';
    private $vhost;
    private $conn;
    private $debug_mode;

    public function __construct($url, $channel, $debug_mode = false){
        $this->url = parse_url($url);
        $this->channel = $channel;
        $this->vhost = substr($this->url['path'], 1);
        $this->debug_mode = $debug_mode;
    }

    private function testUrl(){
        if(isset($this->url['host']) && isset($this->url['user']) && isset($this->url['pass'])){
            return true;
        }else{
            return false;
        }
    }

    private function openConnection(){
        $this->conn = new AMQPStreamConnection($this->url['host'], 5672, $this->url['user'], $this->url['pass'], $this->vhost);
        $this->channelConnection = $this->conn->channel();
        $this->channelConnection->queue_declare($this->channel, false, true, false, false);
        $this->channelConnection->exchange_declare($this->exchange, 'fanout', true, true, false);
        $this->channelConnection->queue_bind($this->channel, $this->exchange);
    }

    private function closeConnection(){
        $this->channelConnection->close();
        $this->conn->close();
    }

    public function putMessage($message){
        if(self::testUrl()){
            try{
                self::openConnection();
                $msg = new AMQPMessage($message, array('content_type' => 'text/plain', 'delivery_mode' => 2));
                $this->channelConnection->basic_publish($msg, $this->exchange);
                self::closeConnection();
                if($this->debug_mode){
                    return array("response" => true);
                }else{
                    return true;
                }
            }catch(Exception $e) {
                if($this->debug_mode){
                    return array("response" => false, "error" => $e);
                }else{
                    return false;
                }
            }
        }else{
            if($this->debug_mode){
                return array("response" => false, "error" => "Invalid URL");
            }else{
                return false;
            }
        }
    }

    public function getMessage(){
        if(self::testUrl()){
            try{
                self::openConnection();
                $retrived_msg = $this->channelConnection->basic_get($this->channel);
                $message_type = gettype($retrived_msg);
                if($message_type == "object"){
                    $this->channelConnection->basic_ack($retrived_msg->delivery_info['delivery_tag']);
                    $return = $retrived_msg->body;
                }else{
                    $return = null;
                }
                self::closeConnection();
                if($this->debug_mode){
                    return array("response" => true, "message" => $return);
                }else{
                    return $return;
                }
            }catch(Exception $e) {
                if($this->debug_mode){
                    return array("response" => false, "error" => $e);
                }else{
                    return false;
                }
            }
        }else{
            if($this->debug_mode){
                return array("response" => false, "error" => "Invalid URL");
            }else{
                return false;
            }
        }
    }
}