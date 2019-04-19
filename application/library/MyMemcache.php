<?php

class MyMemcache {

    public static $_memcached;

    public function __construct() {
        if (!is_object(self::$_memcached)) {
            self::$_memcached = new Memcached();
        }
    }

    public function addServer($host, $port, $weight = 0) {
        $result = self::$_memcached->addServer($host, $port, $weight = 0);
        if (!$result) {
            $message = sprintf("class:%s function:%s | error:%s", __CLASS__, __FUNCTION__, "addServer $host, $port failure!");
            throw new Exception($message);
        }
    }

    /**
     * 将value存储在一个memcached服务上的key下
     * @key string
     * @value mixed
     */
    public function set($key, $value, $expiration = 0) {
        return self::$_memcached->set($key, $value, $expiration);
    }

    /**
     * 设置多个值
     * @items array
     *
     */
    public function setMulti($items, $expiration = 0) {
        return self::$_memcached->setMulti($items, $expiration);
    }

    /**
     * 替换已存在的key，如果key不存在，则操作失败
     * @key string
     * @value mixed
     */
    public function replace($key, $value, $expiration = 0) {
        return self::$_memcached->replace($key, $value, $expiration);
    }

    /**
     * 检索keys数组指定的多个key对应的元素
     * @key array
     */
    public function getMulti($keys) {
        return self::$_memcached->getMulti($keys);
    }

    public function get($key) {
        return self::$_memcached->get($key);
    }

    public function delete($key) {
        return self::$_memcached->delete($key);
    }

?>
