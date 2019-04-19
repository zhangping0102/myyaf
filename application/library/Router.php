<?php

/**
 * yaf 框架路由类
 * 功能：路由设计的目的仅作为api使用，严格要求参数格式
 */
class Router {

    public static $_route = array(
        'module' => 'Index',
        'controller' => 'Index',
        'action' => 'index',
        'params' => array()
    );

    public static function getRoute() {
        $data = file_get_contents("php://input");
        $c = isset($_POST['c']) ? $_POST['c'] : NULL;
        $m = isset($_POST['m']) ? $_POST['m'] : NULL;

        if ($c && $m) {
            self::$_route['controller'] = $c;
            self::$_route['action'] = $m;
            return self::$_route;
        }

        $data = json_decode($data, true);

        if (isset($data['c']) && !empty($data['c'])) {
            self::$_route['controller'] = $data['c'];
        }

        if (isset($data['m']) && !empty($data['m'])) {
            self::$_route['action'] = $data['m'];
        }

        if (!empty($data['params'])) {
            self::$_route['params'] = $data['params'];
            foreach (self::$_route['params'] as $name => $value) {
                $_POST[$name] = $value;
            }
        }
        return self::$_route;
    }

}
