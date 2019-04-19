<?php

/**
 * BaseController yaf框架Controller Base类
 */
abstract class BaseController extends Yaf_Controller_Abstract {

    protected $_r = array(
        'status' => 0, //返回的状态码 0:正常
        'message' => '', //返回的状态信息, 如果正常，则信息为ok；如果其他，则显示具体信息
        'data' => array()       //返回的数据信息，如果正常，则返回正常的数据；如果错误，则返回空数组
    );

}
