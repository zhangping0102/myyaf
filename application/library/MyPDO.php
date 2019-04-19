<?php

/**
 * PDO对象封装，此类改造以前当当底层pdo类
 */
class MyPDO extends PDO {

    private $dbInfo         = array();
    private $enableError        = true; 
    public  $result;

    public function __construct($host, $dbname, $user, $pass, $dbType='mysql') 
    {/*{{{*/
        try {
            $charset = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8';");
            if ($dbType != 'mysql') 
                $charset = array();
            if ($dbType == 'oci') {
                putenv("ORACLE_HOME=/usr/lib/oracle/11.1/client64/lib");
                parent::__construct("$dbType:dbname=//$host/$dbname;charset=ZHS16GBK", $user, $pass);
                $this->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
            } else {
                parent::__construct("$dbType:host=$host;dbname=$dbname", $user, $pass, $charset);
                $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
        } catch (PDOException $e) {                                     
            $message = sprintf("class:%s function:%s | error:%s",__CLASS__,__FUNCTION__, $e->getMessage());
            throw new Exception($message);
            die();
        }
    }/*}}}*/

    private function bindParams($sth,$bindArrParams) 
    {/*{{{*/
        if (count($bindArrParams) > 0) {
            $key = array_keys($bindArrParams);
            if (!is_numeric($key[0]) && (substr($key[0], 0, 1) == ':')) {
                foreach ($bindArrParams as $keyParams => $valueParams) {
                    $sth->bindValue($keyParams, $valueParams);
                }
                $this->result = $sth->execute();
            } else {
                $this->result = $sth->execute($bindArrParams);
            }
        } else {
            $this->result = $sth->execute();
        }

        return $sth;
    }/*}}}*/

    public function update($query,$bindArrParams) 
    {/*{{{*/
        try {
            $sth = parent::prepare($query);
            $sth = $this->bindParams($sth,$bindArrParams);
            $rowCount = $sth->rowCount();
            $sth->closeCursor();
            return $rowCount;

        } catch (PDOException $e) {
            $message = sprintf("class:%s function:%s | error:%s",__CLASS__,__FUNCTION__, $e->getMessage());
            throw new Exception($message);
            die();
        }
    }/*}}}*/

    public function insert($query,$bindArrParams) 
    {/*{{{*/
        try {
            $sth = parent::prepare($query);
            $sth = $this->bindParams($sth,$bindArrParams);
            $sth->closeCursor();
            return $this->lastInsertId();

        } catch (PDOException $e) {
            $message = sprintf("class:%s function:%s | error:%s",__CLASS__,__FUNCTION__, $e->getMessage());
            throw new Exception($message);
            die();
        }
    }/*}}}*/

    public function getAll($query,$bindArrParams)
    {/*{{{*/
        try {
            $sth = parent::prepare($query);
            $sth = $this->bindParams($sth,$bindArrParams);
            $this->result = $sth->fetchAll(PDO::FETCH_ASSOC);
            $sth->closeCursor();
            return $this->result;
        } catch (PDOException $e) {
            $message = sprintf("class:%s function:%s | error:%s",__CLASS__,__FUNCTION__, $e->getMessage());
            throw new Exception($message);
            die();
        }
    }/*}}}*/

    public function getColumn($query,$bindArrParams,$column)
    {/*{{{*/
        try {
            $sth = parent::prepare($query);
            $sth = $this->bindParams($sth,$bindArrParams);
            $this->result = $sth->fetchAll(PDO::FETCH_COLUMN, $column);
            $sth->closeCursor();
            return $this->result;
        } catch (PDOException $e) {
            $message = sprintf("class:%s function:%s | error:%s",__CLASS__,__FUNCTION__, $e->getMessage());
            throw new Exception($message);
            die();
        }
    }/*}}}*/

    //不要在函数形参内对参数进行初始化
    //public function get($query,$bindArrParams=array()) 
    public function get($query,$bindArrParams) 
    {/*{{{*/
        try {
            $sth = parent::prepare($query);
            $sth = $this->bindParams($sth,$bindArrParams);
            $this->result = $sth->fetch(PDO::FETCH_ASSOC);
            $sth->closeCursor();
            return $this->result;
        } catch (PDOException $e) {
            $message = sprintf("class:%s function:%s | error:%s",__CLASS__,__FUNCTION__, $e->getMessage());
            throw new Exception($message);
            die();
        }
    }/*}}}*/

    public function closeConnection() {
        try {               /*{{{*/
            $this->dbh = null;
        } catch (PDOException $e) {
            $message = sprintf("class:%s function:%s | error:%s",__CLASS__,__FUNCTION__, $e->getMessage());
            throw new Exception($message);
            die();
        }
    }/*}}}*/

    public function displayError() {
        try {/*{{{*/
            parent::setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            $message = sprintf("class:%s function:%s | error:%s",__CLASS__,__FUNCTION__, $e->getMessage());
            throw new Exception($message);
            die();
        }
    }/*}}}*/

    public function getErrorCode() {
        return parent::errorCode();
    }

    public function getErrorInfo() {
        return parent::errorInfo();
    }
}
