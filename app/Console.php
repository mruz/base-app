#!/usr/bin/php
<?php
/**
 * Console
 * 
 * @package     base-app
 * @category    CLI
 * @version     1.3
 */
// Everything is relative to the application root now.
chdir(dirname(__DIR__));

// Init loader
$loader = new \Phalcon\Loader();
$loader->registerDirs(array(
    'app/common/tasks/',
    'app/common/library/',
    'app/common/models/',
))->register();


// Setup dependency injection
$di = new Phalcon\DI();

// Router
$di->setShared('router', function() {
            return new Phalcon\CLI\Router();
        });

// View component
$di->set('view', function() {
            $view = new \Phalcon\Mvc\View();
            $view->setViewsDir('app/views/');
            return $view;
        });

// Dispatcher
$di->setShared('dispatcher', function() {
            return new Phalcon\CLI\Dispatcher();
        });

// Load config file
$config = new \Phalcon\Config\Adapter\Ini('app/config/config.ini');
$di->set('config', $config);

// Set DB connectionS
$di->set('db', function() use ($config) {
            return new \Phalcon\Db\Adapter\Pdo\Mysql(array(
                "host" => $config->database->host,
                "username" => $config->database->username,
                "password" => $config->database->password,
                "dbname" => $config->database->dbname
            ));
        });

// Run console application
$console = new \Phalcon\CLI\Console();
$console->setDI($di);
$console->handle(array('task' => isset($argv[1]) ? $argv[1] : 'main', 'action' => isset($argv[2]) ? $argv[2] : 'main', 'params' => $argv));
?>