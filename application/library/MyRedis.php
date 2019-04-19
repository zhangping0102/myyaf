<?php

class MyRedis {

    public $_client = NULL;
    public $_conredis = FALSE;
    private static $_instance = NULL;

    public function __construct($hostname, $port, $longConnect) {
        $redisKey = 'redis_' . $hostname . '_' . $port . '_' . $longConnect;
        if ( !isset(self::$_instance[$redisKey]) || !is_object(self::$_instance[$redisKey])) {
            $this->_client = new Redis();
            if ($longConnect === FALSE) {
                $con = $this->_client->connect($hostname, $port);
            } else {   
                ini_set('default_socket_timeout', -1);
                $con = $this->_client->pconnect($hostname, $port);
            }   
            if ($con === TRUE) {
                $this->_conredis = TRUE;
                self::$_instance[$redisKey] = $this->_client;
            } else {
                $message = sprintf("class:%s function:%s | error:%s",__CLASS__,__FUNCTION__, 
                    'redis server: ' . $hostname . ' port:' . $port . ' connect failed!');
                throw new Exception($message);
                die();
            }
        } else {
            $this->_client = self::$_instance[$redisKey];
        }
    }
}

?>
