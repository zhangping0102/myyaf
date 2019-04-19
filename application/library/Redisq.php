<?php

/**
 * Redisq 封装
 * 请不要更改，系统核心代码
 */
class Redisq {

    private static $_client = NULL;
    private $_queue = NULL;
    private $_callback = NULL;

    private function lpush($queue, $data) {
        $qlen = self::$_client->lPush($queue, $data);
        if (!$qlen) {
            $error = $queue . ' queue, [ ' . $data . ' ] ' . ' push failure.';
            $message = sprintf("<c:%s><a:%s><line:%s><error:%s>", __CLASS__, __FUNCTION__, __LINE__, $error);
            throw new Exception($message);
            die();
        }
        return $qlen;
    }

    private function brpop($queue, $time) {
        return self::$_client->brpop($queue, $time);
    }

    public function type($queue) {
        return self::$_client->type($queue);
    }

    public function getLSize($queue) {
        return self::$_client->llen($queue);
    }

    public function addServer($redis) {
        self::$_client = $redis;
    }

    /**
     * Redisq Client 数据入队
     * $queue 队列名
     * $jsonData 入队数据
     */
    public function doBackground($queue, $jsonData) {
        return $this->lpush($queue, $jsonData);
    }

    /**
     * Redisq Work
     * 指定队列中的数据用某个可调用的方法进行处理
     * @queue 队列名
     * @method 可调用的方法
     */
    public function addFunction($queue, $method) {
        if (!is_callable($method)) {/* {{{ */
            $error = 'Queue Name [ ' . $queue . ' ] 没有可调用的work来处理该队列中的数据';
            $message = sprintf("<c:%s><a:%s><line:%s><error:%s>", __CLASS__, __FUNCTION__, __LINE__, $error);
            throw new Exception($message);
            die();
        }
        $this->_callback = $method;
        $this->_queue = $queue; /* }}} */
    }

    /**
     * Redisq Work
     * 队列的消费方法
     */
    public function work() {
        try {/* {{{ */
            $data = $this->brpop($this->_queue, 0);
            if (is_array($data) && !empty($data)) {
                call_user_func($this->_callback, $data);
            } else {
                sleep(5);
            }
        } catch (Exception $e) {
            $error = $this->_queue . ' queue 该队列中的数据消费失败. ' . $e->getMessage();
            $message = sprintf("<c:%s><a:%s><line:%s><error:%s>", __CLASS__, __FUNCTION__, __LINE__, $error);
            throw new Exception($message);
            die();
        }
    }

/* }}} */
}
