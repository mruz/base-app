<?php

/**
 * index.php
 * 
 * @package     base-app
 * @category    Running
 * @version     1.1
 */
error_reporting(E_ALL);

try {
    if (!defined('ROOT_PATH')) {
        define('ROOT_PATH', dirname(dirname(__FILE__)));
    }

    require_once ROOT_PATH . '/app/Bootstrap.php';

    $app = new \Bootstrap(new \Phalcon\DI\FactoryDefault());

    echo $app->handle()->getContent();
} catch (\Phalcon\Exception $e) {
    Bootstrap::log($e);
} catch (\PDOException $e) {
    Bootstrap::log($e);
} catch (\Exception $e) {
    Bootstrap::log($e);
}
?>