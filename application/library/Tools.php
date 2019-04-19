<?php


class Tools {
    /**
     * 将字符串或者数组由gbk转化为utf8
     */
    public static function gbk2u8($array) {
        if (!is_array($array)) {
            return mb_convert_encoding($array, "utf-8", "gbk");
        }
        foreach ($array as &$value) {
            $value = self::gbk2u8($value);
        }
        return $array;
    }

    public static function getJavaTimestamp() {
        list($usec, $sec) = explode(" ", microtime());
        $msec = round($usec*1000);
        return date('YmdHis',$sec) . $msec;
    }

    /**
     * 校验数字签名
     * @param $params
     * @return bool
     */
    public static function checkSignature($params) {

        $result = false;
        if(!empty($params['signature']) && !empty($params['timestamp']) && !empty($params['nonce']) && !empty($params['token'])) {
            $tmpStr = array($params['token'], $params['timestamp'], $params['nonce']);
            sort($tmpStr, SORT_STRING);
            $tmpStr = implode($tmpStr);
            $tmpStr = sha1($tmpStr);

            if($tmpStr == $params['signature']) {
                $result = true;
            }
        }

        return $result;
    }

    public static function get_client_ip() {
        $user_IP = (isset($_SERVER["HTTP_VIA"])) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : $_SERVER["REMOTE_ADDR"];
        return $user_IP;
    }

    /* 正则表达式 */
    public static $preg = array(
                    "mobilePreg"      => "/^1[3,4,5,7,8][0-9]{9}$/", //手机号正则
                    "passwordPreg"    => "/^\S{6,20}$/", //密码正则
                    "ipPreg"          => "/^((?:(?:25[0-5]|2[0-4]\d|((1\d{2})|([1-9]?\d)))\.){3}(?:25[0-5]|2[0-4]\d|((1\d{2})|([1-9]?\d))))$/", //ip正则
                    "pureDigitalPreg" => "/^\d+$/", //纯数字正则
                    "passwordPreg"    => "/^\S{6,20}$/",
                    "emailRegPreg"    => "/^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(\.([a-zA-Z0-9_-])+)+$/", //注册时邮箱正则
                    "vcodePreg"       => "/^[a-zA-Z0-9]{6}$/", //当当的邮箱、手机验证码正则
                    "usernameReg"     => "/^[a-z0-9]{4,20}$/", //登录用户名正则
    );
    
}
