<?php

/**
 * @name ErrorController
 * @desc 错误控制器, 在发生未捕获的异常时刻被调用
 * @see http://www.php.net/manual/en/yaf-dispatcher.catchexception.php
 */
class ErrorController extends Yaf_Controller_Abstract {

    //从2.1开始, errorAction支持直接通过参数获取异常
    public function errorAction($exception) {
        $message = '| error code:' . $exception->getCode() . ' | line:' . $exception->getLine() . ' | ' . $exception->getMessage();
        Log::writeLog('error', $message);
    }

}
