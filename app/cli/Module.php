<?php

/**
 * Cli Module
 *
 * @package     base-app
 * @category    Module
 * @version     1.3
 */

namespace Baseapp\Cli;

class Module implements \Phalcon\Mvc\ModuleDefinitionInterface
{

    public function registerAutoloaders($dependencyInjector = NULL)
    {
        $loader = new \Phalcon\Loader();

        $loader->registerNamespaces(array(
            'Baseapp\Cli\Tasks' => __DIR__ . '/tasks/',
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
                        case \Phalcon\Cli\Dispatcher::EXCEPTION_HANDLER_NOT_FOUND:
                        case \Phalcon\Dispatcher::EXCEPTION_ACTION_NOT_FOUND:
                            $dispatcher->forward(array(
                                'task' => 'main',
                                'action' => 'notFound'
                            ));
                            return false;
                    }
                }
            });

            //$dispatcher = new \Phalcon\Mvc\Dispatcher();
            $dispatcher = new \Phalcon\Cli\Dispatcher();
            //Set default namespace to frontend module
            $dispatcher->setDefaultNamespace("Baseapp\Cli\Tasks");
            //Bind the EventsManager to the dispatcher
            $dispatcher->setEventsManager($eventsManager);

            return $dispatcher;
        });
    }

}
