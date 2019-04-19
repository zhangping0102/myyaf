<?php

/**
 * @name Bootstrap
 * @desc 所有在Bootstrap类中, 以_init开头的方法, 都会被Yaf调用,
 * @see http://www.php.net/manual/en/class.yaf-bootstrap-abstract.php
 * 这些方法, 都接受一个参数:Yaf_Dispatcher $dispatcher
 * 调用的次序, 和申明的次序相同
 */

/**
 * 实例化成功之后，所有在Bootstrap中定义的，以_init开头的方法，都会依次被调用，而这些方法都可以接受一个Yaf_Dispatcher实例作为参数
 */
class Bootstrap extends Yaf_Bootstrap_Abstract {

    public function _initConfig() {
        //把配置保存起来
        $arrConfig = Yaf_Application::app()->getConfig();
        //print_r($arrConfig);exit;
        Yaf_Registry::set('config', $arrConfig);
    }

    public function _initPlugin(Yaf_Dispatcher $dispatcher) {
        //注册一个插件
        $objSamplePlugin = new SamplePlugin();
        $dispatcher->registerPlugin($objSamplePlugin);
    }

    public function _initRoute(Yaf_Dispatcher $dispatcher) {
        //在这里注册自己的路由协议,默认使用简单路由
        $router = Router::getRoute();
        $method = $dispatcher->getRequest()->getMethod();
        if ($router['module'] === 'Index' && $method === 'POST') {
            $dispatcher->disableView();
            $request = new Yaf_Request_Simple($method, 'Index', $router['controller'], $router['action'], $router['params']);
            $dispatcher->setRequest($request);
        } else if ($method === 'GET') {
            $info = array(
                'status' => 1,
                'message' => 'Comment api framework no support GET request!',
                'data' => array()
            );
            echo json_encode($info);
            exit;
        }
    }

    public function _initView(Yaf_Dispatcher $dispatcher) {
        //在这里注册自己的view控制器，例如smarty,firekylin
    }

}
