<?php

namespace Baseapp;

use Baseapp\Library\I18n;
use Baseapp\Library\Email;
use Phalcon\Debug\Dump;

/**
 * Bootstrap
 *
 * @package     base-app
 * @category    Application
 * @version     2.0
 */
class Bootstrap extends \Phalcon\Mvc\Application
{

    private $_di;
    private $_config;

    /**
     * Bootstrap constructor - set the dependency Injector
     *
     * @package     base-app
     * @version     2.0
     *
     * @param \Phalcon\DiInterface $di
     */
    public function __construct(\Phalcon\DiInterface $di)
    {
        $this->_di = $di;

        $loaders = array('config', 'loader', 'timezone', 'lang', 'db', 'filter', 'flash', 'crypt', 'session', 'cookie', 'cache', 'url', 'router');

        // Register services
        foreach ($loaders as $service) {
            $this->$service();
        }

        // Register modules
        $this->registerModules(array(
            'frontend' => array(
                'className' => 'Baseapp\Frontend\Module',
                'path' => ROOT_PATH . '/app/frontend/Module.php'
            ),
            'backend' => array(
                'className' => 'Baseapp\Backend\Module',
                'path' => ROOT_PATH . '/app/backend/Module.php'
            )
        ));

        // Register the app itself as a service
        $this->_di->set('app', $this);

        // Set the dependency Injector
        parent::__construct($this->_di);
    }

    /**
     * Register an autoloader
     *
     * @package     base-app
     * @version     2.0
     *
     * @return void
     */
    protected function loader()
    {
        $loader = new \Phalcon\Loader();
        $loader->registerNamespaces(array(
            'Baseapp\Models' => ROOT_PATH . '/app/common/models/',
            'Baseapp\Library' => ROOT_PATH . '/app/common/library/',
            'Baseapp\Extension' => ROOT_PATH . '/app/common/extension/'
        ))->register();
    }

    /**
     * Set the config service
     *
     * @package     base-app
     * @version     2.0
     *
     * @return void
     */
    protected function config()
    {
        $config = new \Phalcon\Config\Adapter\Ini(ROOT_PATH . '/app/common/config/config.ini');
        $this->_di->set('config', $config);
        $this->_config = $config;
    }

    /**
     * Set the time zone
     *
     * @package     base-app
     * @version     2.0
     *
     * @return void
     */
    protected function timezone()
    {
        date_default_timezone_set($this->_config->app->timezone);
    }

    /**
     * Set the language
     *
     * @package     base-app
     * @version     2.0
     *
     * @return void
     */
    protected function lang()
    {
        I18n::instance()->lang();
    }

    /**
     * Set the security service
     *
     * @package     base-app
     * @version     2.0
     *
     * @return void
     */
    protected function security()
    {
        $config = $this->_config;
        $this->_di->set('security', function() use ($config) {
            $security = new \Phalcon\Security();
            $security->setDefaultHash($config->security->key);
            return $security;
        });
    }

    /**
     * Set the crypt service
     *
     * @package     base-app
     * @version     2.0
     *
     * @return void
     */
    protected function crypt()
    {
        $config = $this->_config;
        $this->_di->set('crypt', function() use ($config) {
            $crypt = new \Phalcon\Crypt();
            $crypt->setKey($config->crypt->key);
            return $crypt;
        });
    }

    /**
     * Set the filter service
     *
     * @package     base-app
     * @version     2.0
     *
     * @return void
     */
    protected function filter()
    {
        $this->_di->set('filter', function() {
            $filter = new \Phalcon\Filter();
            $filter->add('repeat', new Extension\Repeat());
            return $filter;
        });
    }

    /**
     * Set the cookie service
     *
     * @package     base-app
     * @version     2.0
     *
     * @return void
     */
    protected function cookie()
    {
        $this->_di->set('cookies', function() {
            $cookies = new \Phalcon\Http\Response\Cookies();
            return $cookies;
        });
    }

    /**
     * Set the database service
     *
     * @package     base-app
     * @version     2.0
     *
     * @return void
     */
    protected function db()
    {
        $config = $this->_config;
        $this->_di->set('db', function() use ($config) {
            return new \Phalcon\Db\Adapter\Pdo\Mysql(array(
                "host" => $config->database->host,
                "username" => $config->database->username,
                "password" => $config->database->password,
                "dbname" => $config->database->dbname
            ));
        });
    }

    /**
     * Set the flash service
     *
     * @package     base-app
     * @version     2.0
     *
     * @return void
     */
    protected function flash()
    {
        $this->_di->set('flashSession', function() {
            $flash = new \Phalcon\Flash\Session(array(
                'warning' => 'alert alert-warning',
                'notice' => 'alert alert-info',
                'success' => 'alert alert-success',
                'error' => 'alert alert-danger',
                'dismissable' => 'alert alert-dismissable',
            ));
            return $flash;
        });
    }

    /**
     * Set the session service
     *
     * @package     base-app
     * @version     2.0
     *
     * @return void
     */
    protected function session()
    {
        $this->_di->set('session', function() {
            $session = new \Phalcon\Session\Adapter\Files();
            $session->start();
            return $session;
        });
    }

    /**
     * Set the cache service
     *
     * @package     base-app
     * @version     2.0
     *
     * @return void
     */
    protected function cache()
    {
        $config = $this->_config;
        foreach ($config->cache->services as $service => $section) {
            $this->_di->set($service, function() use ($config, $section) {
                // Load settings for some section
                $frontend = $config->$section;
                $backend = $config->{$frontend->backend};

                // Set adapters
                $adapterFrontend = "\Phalcon\Cache\Frontend\\" . $frontend->adapter;
                $adapterBackend = "\Phalcon\Cache\Backend\\" . $backend->adapter;

                // Set cache
                $frontCache = new $adapterFrontend($frontend->options->toArray());
                $cache = new $adapterBackend($frontCache, $backend->options->toArray());
                return $cache;
            });
        }
    }

    /**
     * Set the url service
     *
     * @package     base-app
     * @version     2.0
     *
     * @return void
     */
    protected function url()
    {
        $config = $this->_config;
        $this->_di->set('url', function() use ($config) {
            $url = new \Phalcon\Mvc\Url();
            $url->setBaseUri($config->app->base_uri);
            $url->setStaticBaseUri($config->app->static_uri);
            return $url;
        });
    }

    /**
     * Set the static router service
     *
     * @package     base-app
     * @version     2.0
     *
     * @return void
     */
    protected function router()
    {
        $this->_di->set('router', function() {
            $router = new \Phalcon\Mvc\Router(FALSE);

            $router->setDefaultModule('frontend');
            $router->setDefaultController('index');
            $router->setDefaultAction('index');

            $router->add('/:controller/:action/:params', array(
                'module' => 'frontend',
                'controller' => 1,
                'action' => 2,
                'params' => 3,
            ));

            $router->add('/:controller/:int', array(
                'module' => 'frontend',
                'controller' => 1,
                'action' => 'index',
                'id' => 2,
            ));

            $router->add('/:controller[/]?', array(
                'module' => 'frontend',
                'controller' => 1,
                'action' => 'index'
            ));

            $router->add('/{action:(buy|contact)}[/]?', array(
                'module' => 'frontend',
                'controller' => 'static',
                'action' => 'action'
            ));

            $router->add('/', array(
                'module' => 'frontend',
                'controller' => 'index',
                'action' => 'index'
            ));

            $router->add('/admin/:controller/:action/:params', array(
                'module' => 'backend',
                'controller' => 1,
                'action' => 2,
                'params' => 3,
            ));

            $router->add('/admin/:controller[/]?', array(
                'module' => 'backend',
                'controller' => 1,
                'action' => 'index',
            ));

            $router->add('/admin[/]?', array(
                'module' => 'backend',
                'controller' => 'index',
                'action' => 'index',
            ));

            $router->notFound(array(
                'controller' => 'index',
                'action' => 'notFound'
            ));

            return $router;
        });
    }

    /**
     * HMVC request in the application
     *
     * @package     base-app
     * @version     2.0
     *
     * @param array $location location to run the request
     *
     * @return mixed response
     */
    public function request($location)
    {
        $dispatcher = clone $this->getDI()->get('dispatcher');

        if (isset($location['controller'])) {
            $dispatcher->setControllerName($location['controller']);
        } else {
            $dispatcher->setControllerName('index');
        }

        if (isset($location['action'])) {
            $dispatcher->setActionName($location['action']);
        } else {
            $dispatcher->setActionName('index');
        }

        if (isset($location['params'])) {
            if (is_array($location['params'])) {
                $dispatcher->setParams($location['params']);
            } else {
                $dispatcher->setParams((array) $location['params']);
            }
        } else {
            $dispatcher->setParams(array());
        }

        $dispatcher->dispatch();

        $response = $dispatcher->getReturnedValue();
        if ($response instanceof \Phalcon\Http\ResponseInterface) {
            return $response->getContent();
        }

        return $response;
    }

    /**
     * Log message into file, notify the admin on stagging/production
     *
     * @package     base-app
     * @version     2.0
     *
     * @param mixed $messages messages to log
     */
    public static function log($messages)
    {
        $config = \Phalcon\DI::getDefault()->getShared('config');
        $dump = new Dump();

        if ($config->app->env == "development") {
            foreach ($messages as $key => $message) {
                echo $dump->dump($message, $key);
            }
            exit();
        } else {
            $logger = new \Phalcon\Logger\Adapter\File(ROOT_PATH . '/app/common/logs/' . date('Ymd') . '.log', array('mode' => 'a+'));
            $log = '';

            foreach ($messages as $key => $message) {
                if (in_array($key, array('alert', 'debug', 'error', 'info', 'notice', 'warning'))) {
                    $logger->$key($message);
                } else {
                    $logger->log($message);
                }
                $log .= $dump->dump($message, $key);
            }
            $logger->close();

            if ($config->app->env != "testing") {
                $email = new Email();
                $email->prepare(__('Something is wrong!'), $config->app->admin, 'error', array('log' => $log));
                $email->Send();
            }
        }
    }

    /**
     * Catch the exception and log it, display pretty view
     *
     * @package     base-app
     * @version     2.0
     *
     * @param \Exception $e
     */
    public static function exception(\Exception $e)
    {
        $config = \Phalcon\DI::getDefault()->getShared('config');
        $errors = array(
            'error' => get_class($e) . '[' . $e->getCode() . ']: ' . $e->getMessage(),
            'info' => $e->getFile() . '[' . $e->getLine() . ']',
            'debug' => "Trace: \n" . $e->getTraceAsString() . "\n",
        );

        if ($config->app->env == "development") {
            // Display debug output
            $dump = new Dump();
            echo $dump->vars($errors);
        } else {
            // Display pretty view of the error
            $di = new \Phalcon\DI\FactoryDefault();
            $view = new \Phalcon\Mvc\View\Simple();
            $view->setDI($di);
            $view->setViewsDir(ROOT_PATH . '/app/frontend/views/');
            $view->registerEngines(\Baseapp\Library\Tool::registerEngines($view, $di));
            echo $view->render('error', array('i18n' => I18n::instance(), 'config' => $config));

            // Log errors to file and send email with errors to admin
            \Baseapp\Bootstrap::log($errors);
        }
    }

}
