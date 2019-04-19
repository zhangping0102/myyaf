<?php

/**
 * 时间有限，Log类暂时以此简陋的，性能低下的类代替，将来可以引入第三方Log类
 */
class Log {

    public static function writeLog($logfileName, $message) {/* {{{ */
        $config = Yaf_Application::app()->getConfig();
        $logPath = $config->application->logs;
        if (!is_dir($logPath)) {
            if (!mkdir($logPath, 0755, true)) {
                $message = sprintf("class:%s function:%s error:%s", __CLASS__, __FUNCTION__, 'create directory failed');
                throw new Exception($message);
            }
        }
        $log = date('Y-m-d H:i:s') . ' ' . $message . "\n";
        echo $log;
        $logPath = substr($logPath, -1, 1) === '/' ? $logPath : $logPath . '/';
        $file = $logPath . $logfileName . '_' . date('Y-m-d') . '.log';
        $fp = fopen($file, 'a');
        if (flock($fp, LOCK_EX)) {
            fwrite($fp, $log);
            flock($fp, LOCK_UN);
        }
        fclose($fp);
    }

/* }}} */
}
