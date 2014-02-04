<?php

/**
 * Backend Module
 *
 * @package     base-app
 * @category    Module
 * @version     2.0
 */

namespace Baseapp\Backend;

class Module implements \Phalcon\Mvc\ModuleDefinitionInterface
{

    public function registerAutoloaders($di = NULL)
    {
        $loader = new \Phalcon\Loader();

        $loader->registerNamespaces(array(
            'Baseapp\Backend\Controllers' => __DIR__ . '/controllers/',
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
                                'module' => 'backend',
                                'controller' => 'index',
                                'action' => 'notFound'
                            ));
                            return false;
                    }
                }
            });

            $dispatcher = new \Phalcon\Mvc\Dispatcher();
            //Set default namespace to backend module
            $dispatcher->setDefaultNamespace("Baseapp\Backend\Controllers");
            //Bind the EventsManager to the dispatcher
            $dispatcher->setEventsManager($eventsManager);

            return $dispatcher;
        });

        //Registering the view component
        $di->set('view', function() use($di) {
            $view = new \Phalcon\Mvc\View();
            $view->setViewsDir(__DIR__ . '/views/');
            $view->registerEngines(\Baseapp\Library\Tool::registerEngines($view, $di));
            return $view;
        });
    }

}
