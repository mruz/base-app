<?php

/**
 * Frontend Module
 * 
 * @package     base-app
 * @category    Module
 * @version     1.2
 */

namespace Baseapp\Frontend;

class Module implements \Phalcon\Mvc\ModuleDefinitionInterface
{

    public function registerAutoloaders()
    {
        $loader = new \Phalcon\Loader();

        $loader->registerNamespaces(array(
            'Baseapp\Frontend\Controllers' => __DIR__ . '/controllers/',
        ));

        $loader->register();
    }

    public function registerServices($di)
    {
        //Registering a dispatcher
        $di->set('dispatcher', function() {
                    //Create/Get an EventManager
                    $eventsManager = new \Phalcon\Events\Manager();
                    //Attach a listener
                    $eventsManager->attach("dispatch", function($event, $dispatcher, $exception) {
                                //controller or action doesn't exist
                                if ($event->getType() == 'beforeException') {
                                    switch ($exception->getCode()) {
                                        case \Phalcon\Dispatcher::EXCEPTION_HANDLER_NOT_FOUND:
                                        case \Phalcon\Dispatcher::EXCEPTION_ACTION_NOT_FOUND:
                                            $dispatcher->forward(array(
                                                'controller' => 'index',
                                                'action' => 'notFound'
                                            ));
                                            return false;
                                    }
                                }
                            });

                    $dispatcher = new \Phalcon\Mvc\Dispatcher();
                    //Set default namespace to frontend module
                    $dispatcher->setDefaultNamespace("Baseapp\Frontend\Controllers");
                    //Bind the EventsManager to the dispatcher
                    $dispatcher->setEventsManager($eventsManager);

                    return $dispatcher;
                });

        //Registering the view component
        $di->set('view', function() {
                    $view = new \Phalcon\Mvc\View();
                    $view->setViewsDir(__DIR__ . '/views/');
                    $view->registerEngines(array(
                        ".phtml" => '\Phalcon\Mvc\View\Engine\Php',
                        ".volt" => function($view, $di) {
                            $volt = new \Phalcon\Mvc\View\Engine\Volt($view, $di);

                            $volt->setOptions(array(
                                'compiledPath' => function($templatePath) {
                                    $templatePath = strstr($templatePath, '/app');
                                    $dirName = dirname($templatePath);

                                    if (!is_dir(ROOT_PATH . '/app/common/cache/volt' . $dirName)) {
                                        mkdir(ROOT_PATH . '/app/common/cache/volt' . $dirName, 0777, TRUE);
                                    }
                                    return ROOT_PATH . '/app/common/cache/volt' . $dirName . '/' . basename($templatePath, '.volt') . '.php';
                                },
                                'compileAlways' => TRUE
                            ));

                            $compiler = $volt->getCompiler();

                            $compiler->addExtension(new \Baseapp\Extension\VoltPHPFunctions());

                            $compiler->addFunction('debug', function($resolvedArgs) {
                                        return '\Baseapp\Library\Debug::vars(' . $resolvedArgs . ')';
                                    });

                            $compiler->addFilter('isset', function($resolvedArgs) {
                                        return '(isset(' . $resolvedArgs . ') ? ' . $resolvedArgs . ' : NULL)';
                                    });

                            $compiler->addFilter('label', function($resolvedArgs) {
                                        return '\Baseapp\Library\Tool::label(' . $resolvedArgs . ')';
                                    });

                            return $volt;
                        }
                    ));
                    return $view;
                });
    }

}