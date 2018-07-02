<?php
/**
 * Class Queue | app/Queue.php
 *
 * @package     github.com/MSSDavid/integrador
 * @subpackage  Queue
 * @author      Samuel Costa <samu.rcosta@gmail.com>
 * @version     v.1.0.1 (20/06/2018)
 * @since       v.1.0.0 (19/06/2018)
 * @copyright   Copyright (c) 2018, Samuel Costa
 */
define('AMQP_DEBUG', false);
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
/**
 * Esta classe implementa troca de mensagens em um mecanismo de fila. Utiliza a biblioteca phpamqp-lib do Cloudamqp para utilizar o serviço de troca de mensagens. Nesta classe é possível inserir ou retirar uma mensagem de um canal de um servidor cloudamqp (recebidos no construtor da classe).
 *
 * Esta classe possui um modo de debug (por padrão fica desativado), que ao invés de retornar true ou false se o método funcionar ou não, irá retornar um array com a resposta da interação e com os detalhes do erro (caso exista).
 *
 */
class Queue{
    /** @var string Url da instância do CloudAMAQP. */
    private $url;
    /** @var string Nome do canal a ser trabalhado. */
    private $channel;
    /** @var object Armazena a conexão com o canal. */
    private $channelConnection;
    /** @var string Tipo de interação com o canal. */
    private $exchange;
    /** @var string Vhost extraído da url. */
    private $vhost;
    /** @var object Conexão com o CloudAMQP. */
    private $conn;
    /** @var boolean Controla a opção de auto conexão (Abre e fecha conexão automaticamente a cada operação no canal). */
    private $auto_connection;
    /** @var boolean Controla a opção debug. */
    private $debug_mode;

    /**
     * Construtor da classe, recebe os dados essenciais para realizar uma conexão com o CloudAMQP
     *
     * @param   $url                 string para a url AMQP.
     * @param   $channel             string para o nome do canal.
     * @param   $auto_connection     boolean por padrão true do modo de auto conexão.
     * @param   $debug_mode          boolean por padrão false do modo de debug.
     */
    public function __construct($url, $channel,$auto_connection = true , $debug_mode = false){
        $this->url = parse_url($url);
        $this->channel = $channel;
        $this->exchange = $channel;
        $this->vhost = substr($this->url['path'], 1);
        $this->debug_mode = $debug_mode;
        $this->auto_connection = $auto_connection;
        $this->channelConnection = null;
        $this->conn = null;
    }

    /**
     * Esta função testa se a url recebida no construtor é um AMQP URL válida
     *
     * @return boolean true caso a URL esteja válida e falso caso não esteja
     */
    private function testUrl(){
        return isset($this->url['host']) && isset($this->url['user']) && isset($this->url['pass']);
    }

    /**
     * Esta função é a chamada pública para a abertura da conexão com o canal de mensagens, caso a opção de auto conexão esteja desabilitada e não exista uma conexão já em aberto. Esta função deve ser usada cuidadosamente para que a a conexão não fique aberta por tempo inderteminado no canal, ocupando o limite de conexões simultâneas.
     */
    public function openConnection(){
        if(!$this->auto_connection && !$this->channelConnection && !$this->conn){
            self::openConn();
        }
    }

    /**
     * Esta função abre a conexão com o canal de mensagens
     */
    private function openConn(){
        $this->conn = new AMQPStreamConnection($this->url['host'], 5672, $this->url['user'], $this->url['pass'], $this->vhost);
        $this->channelConnection = $this->conn->channel();
        $this->channelConnection->queue_declare($this->channel, false, true, false, false);
        $this->channelConnection->exchange_declare($this->exchange, 'fanout', false, true, false);
        $this->channelConnection->queue_bind($this->channel, $this->exchange);
    }

    /**
     * Esta função é a chamada pública para o fechamento da conexão com o canal de mensagens, caso a opção de auto conexão esteja desabilitada e exista uma conexão já em aberto. Sempre que a função openConnection for usada, após o uso do canal, esta função deve ser chamada para fechar a conexão com o servidor.
     */
    public function closeConnection(){
        if(!$this->auto_connection && $this->channelConnection && $this->conn){
            self::closeConn();
        }
    }

    /**
     * Esta função fecha a conexão com o canal de mensagens
     */
    private function closeConn(){
        $this->channelConnection->close();
        $this->conn->close();

        // Muda para null as variáveis
        $this->channelConnection = null;
        $this->conn = null;
    }

    /**
     * Esta função insere uma mensagem no canal
     *
     * @param   $message    string da mensagem a ser inserida no canal recebido no construtor
     *
     * @return mixed irá retornar true caso o processo funcione ou false se a operação não seja executada da forma esperada, caso o debug mode esteja ativado (true) irá retornar um array com a resposta e os erros (caso existam)
     */
    public function putMessage($message){
        if(self::testUrl()){
            try{
                if($this->auto_connection) {
                    self::openConn();
                }

                $msg = new AMQPMessage($message, array('content_type' => 'text/plain', 'delivery_mode' => 2));
                $this->channelConnection->basic_publish($msg, $this->exchange);

                if($this->auto_connection) {
                    self::closeConn();
                }

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

    /**
     * Esta função retira uma mensagem do canal
     *
     * @return mixed irá retornar true caso o processo funcione ou false se a operação não seja executada da forma esperada, caso o debug mode esteja ativado (true) irá retornar um array com a resposta e os erros (caso existam)
     */
    public function getMessage(){
        if(self::testUrl()){
            try{
                if($this->auto_connection){
                    self::openConn();
                }

                $retrived_msg = $this->channelConnection->basic_get($this->channel);
                $message_type = gettype($retrived_msg);
                if($message_type == "object"){
                    $this->channelConnection->basic_ack($retrived_msg->delivery_info['delivery_tag']);
                    $return = $retrived_msg->body;
                }else{
                    $return = null;
                }

                if($this->auto_connection) {
                    self::closeConn();
                }

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