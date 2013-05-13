<?php
error_reporting(E_ALL);
try {
    if ( ! defined('ROOT_PATH'))
    {
        define('ROOT_PATH', dirname(dirname(__FILE__)));
    }
    
    require_once ROOT_PATH . '/app/Bootstrap.php';

    $di  = new \Phalcon\DI\FactoryDefault();
    $app = new \Bootstrap($di);

    echo $app->run();
}
catch (\Exception $e){}
?>