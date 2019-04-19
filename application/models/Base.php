<?php


/**
 * 如果数据来自于底层表，请model类继承该BaseModel
 */
class BaseModel {

    protected static $_pdo;
    protected static $_dbName;

    public function __construct($dbName) {
        self::$_dbName = $dbName;
        if (!isset(self::$_pdo[self::$_dbName])) {
            $config = Yaf_Application::app()->getConfig();
            $hostname = $config->db->get(self::$_dbName)->hostname;
            $database = $config->db->get(self::$_dbName)->database;
            $username = $config->db->get(self::$_dbName)->username;
            $password = $config->db->get(self::$_dbName)->password;
            $dbdriver = $config->db->get(self::$_dbName)->dbdriver;
            self::$_pdo[self::$_dbName] = new MyPDO($hostname,$database,$username,$password,$dbdriver);
        } 
    }

    //不要在函数形参内对参数进行初始化
    public function getAll($query,$bindArrParams) {
        return self::$_pdo[self::$_dbName]->getAll($query,$bindArrParams);
    }

    public function getColumn($query,$bindArrParams,$column) {
        return self::$_pdo[self::$_dbName]->getColumn($query,$bindArrParams,$column);
    }

    //不要在函数形参内对参数进行初始化
    public function get($query,$bindArrParams) {
        return self::$_pdo[self::$_dbName]->get($query,$bindArrParams);
    }

    public function insert($query,$bindArrParams)  {
        return self::$_pdo[self::$_dbName]->insert($query,$bindArrParams);
    }

    public function update($query,$bindArrParams) {
        return self::$_pdo[self::$_dbName]->update($query,$bindArrParams);
    }
}
