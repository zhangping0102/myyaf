<?php

class HttpClient {

    private static $_instance;
    private $_header = array();

    public static function &getInstance() {
        if (!self::$_instance) {
            self::$_instance = new HttpClient();
        }
        return self::$_instance;
    }

    private function _set_header($header) {
        if (empty($header)) {
            return;
        }

        if (!empty($this->_header)) {
            unset($this->_header);
        }

        if (is_array($header)) {
            foreach ($header as $k => $v) {
                $this->_header[] = is_numeric($k) ? trim($v) : (trim($k) . ": " . trim($v));
            }
        } elseif (is_string($header)) {
            $this->_header[] = $header;
        }
    }

    private function _makeQueryArr2Str($array, $sep = '&') {
        $param = '';
        foreach ($array as $k => $v) {
            $param .= ($param ? $sep : "");
            $param.=($k . "=" . $v);
        }
        return $param;
    }

    public function request($url, $type = "GET", $params = array(), $timeout = 5, $header = array(), $cookie = '', $options = array()) {
        $curl = curl_init();

        if (!empty($params) && is_array($params)) {
            $params = $this->_makeQueryArr2Str($params);
        }

        if ($type === "GET" && !empty($params)) {
            $parse = parse_url($url);
            $sep = isset($parse['query']) ? '&' : '?';
            $url .= $sep . $params;
        }

        if ($type === "POST") {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
        }

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);

        if (empty($header)) {
            $this->_set_header(array(
                'User-Agent: Mozilla/5.0 (X11; U; Linux i686; zh-CN; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2',
            ));
        } else {
            $this->_set_header($header);
        }
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->_header);

        if (!empty($cookie)) {
            if (is_array($cookie)) {
                $cookie = $this->_makeQueryArr2Str($cookie, ';');
            }
            curl_setopt($curl, CURLOPT_COOKIE, $cookie);
        }

        if (is_array($options) && !empty($options)) {
            curl_setopt_array($curl, $options);
        }

        $result = curl_exec($curl);
        if (($err = curl_error($curl))) {
            throw new Exception('##' . $url . '## ' . $err);
        }
        curl_close($curl);
        return $result;
    }

/* }}} */
}
