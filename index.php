<?php

define('APPLICATION_PATH', dirname(__FILE__));

$application = new Yaf_Application(APPLICATION_PATH . "/conf/application.ini");
//在一个Yaf_Application被实例化后，运行Yaf_Application::run之前，可选的可以运行Yaf_Application::bootstrap
//bootstrap被调用的时候，Yaf_Application就会在APPLICATION_PATH
//$application->run();
$application->bootstrap()->run();
?>
