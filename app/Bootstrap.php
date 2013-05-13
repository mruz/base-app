<?php
/**
 * Bootstrap
 * 
 * @package     base-app
 * @category    Bootstrap
 * @version     1.0
 */
class Bootstrap
{
    private $_di;
    private $_config;

    /**
     * Constructor
     * 
     * @param $di
     */
    public function __construct($di)
    {
        $this->_di = $di;
    }

    /**
     * Runs the application performing all initializations
     */
    public function run()
    {
        $loaders = array(
            'config',
            'loader',
            'timezone',
            'lang',
            'db',
            'flash',
            'crypt',
            'session',
            'cookie',
            //'cache',
            'url',
            'router',
        );
        
        try {
            foreach ($loaders as $service)
            {
                $this->$service();
            }

            $application = new \Phalcon\Mvc\Application();
            $application->setDI($this->_di);
            
            $application->registerModules(array(
                'frontend' => array(
                    'className' => 'Modules\Frontend\Module',
                    'path' => ROOT_PATH . '/app/frontend/Module.php'
                ),
                'backend' => array(
                    'className' => 'Modules\Backend\Module',
                    'path' => ROOT_PATH . '/app/backend/Module.php'
                )
            ));

            return $application->handle()->getContent();

        }
        catch (\Phalcon\Exception $e)
        {
            $this->log($e);
        }
        catch (\PDOException $e)
        {
            $this->log($e);
        }
        catch (\Exception $e)
        {
            $this->log($e);
        }
    }
    
    protected function loader()
    {
        //Register an autoloader
        $loader = new \Phalcon\Loader();
        $loader->registerDirs(array(
            ROOT_PATH . '/app/common/models/',
            ROOT_PATH . '/app/common/library/'
        ))->register();
    }
    
    protected function config()
    {
        // Create the new object
        $config = new \Phalcon\Config\Adapter\Ini(ROOT_PATH . '/app/common/config/config.ini');

        // Store it in the Di container
        $this->_di->set('config', $config);
        $this->_config = $config;
    }
    
    protected function timezone()
    {
        date_default_timezone_set($this->_config->app->timezone);
    }
    
    protected function lang()
    {
        I18n::instance();
    }
    
    protected function crypt()
    {
        $config = $this->_config;
        
        $this->_di->set('crypt', function() use ($config) {
            $crypt = new \Phalcon\Crypt();
            $crypt->setKey($config->crypt->key);
            return $crypt;
        });
    }
    
    
    protected function cookie()
    {
        $this->_di->set('cookies', function() {
            $cookies = new \Phalcon\Http\Response\Cookies();
            return $cookies;
        });
    }
    
    protected function db()
    {
        $config = $this->_config;
        //Set the database service
        $this->_di->set('db', function() use ($config){
            return new \Phalcon\Db\Adapter\Pdo\Mysql(array(
                "host"     => $config->database->host,
                "username" => $config->database->username,
                "password" => $config->database->password,
                "dbname"   => $config->database->dbname
            ));
        });
    }
    
    protected function flash()
    {
        $this->_di->set('flashSession', function(){
            $flash = new \Phalcon\Flash\Session(array(
                'warning' => 'alert',
                'notice' => 'alert alert-info',
                'success' => 'alert alert-success',
                'error' => 'alert alert-error',
                'block' => 'alert alert-block',
            ));
            return $flash;
        });
    }
    
    protected function session()
    {
        //Start the session the first time some component request the session service
        $this->_di->set('session', function(){
            $session = new \Phalcon\Session\Adapter\Files();
            $session->start();
            return $session;
        });
    }
    
    protected function cache()
    {
        $config = $this->_config;
        $this->_di->set('cache', function() use ($config){
            // Get the parameters
            $frontCache = new \Phalcon\Cache\Frontend\Data(array('lifetime' => $config->cache->lifetime));
            $cache      = new \Phalcon\Cache\Backend\File($frontCache, array('cacheDir' => ROOT_PATH . '/app/common/cache/'));

            return $cache;
        });
    }
    
    protected function url()
    {
        $config = $this->_config;
        $this->_di->set('url', function() use ($config){
            $url = new \Phalcon\Mvc\Url();
            $url->setBaseUri($config->app->base_uri);
            return $url;
        });
    }
    
    protected function router()
    {
        //Setting up the static router
        $this->_di->set('router', function(){
            $router = new \Phalcon\Mvc\Router(FALSE);
            
            $router->setDefaultModule('frontend');
            $router->setDefaultController('index');
            $router->setDefaultAction('index');
            
            
            $router->add('/:controller/:action/:params', array(
                'module'     => 'frontend',
                'controller' => 1,
                'action'     => 2,
                'params'     => 3,
            ));
            
            $router->add('/:controller[/]?', array(
                'module'     => 'frontend',
                'controller' => 1,
                'action'     => 'index'
            ));
            
            $router->add('/', array(
                'module'     => 'frontend',
                'controller' => 'index',
                'action'     => 'index'
            ));
            
            $router->add('/admin/:controller/:action/:params', array(
                'module'     => 'backend',
                'controller' => 1,
                'action'     => 2,
                'params'     => 3,
            ));
            
            $router->add('/admin/:controller[/]?', array(
                'module'     => 'backend',
                'controller' => 1,
                'action'     => 'index',
            ));
            
            $router->add('/admin[/]?', array(
                'module'     => 'backend',
                'controller' => 'index',
                'action'     => 'index',
            ));
            
            $router->notFound(array(
                'controller' => 'index',
                'action'     => 'notFound'
            ));
            
            return $router;
        });
    }

    protected function log(Exception $e)
    {
        if ($this->_config->log->file)
        {
            $logger = new \Phalcon\Logger\Adapter\File(ROOT_PATH . '/app/common/logs/'.date('Ymd').'.log', array('mode' => 'a+'));
            $logger->error(get_class($e). '['.$e->getCode().']: '. $e->getMessage());
            $logger->info($e->getFile().'['.$e->getLine().']');
            $logger->debug("Trace: \n".$e->getTraceAsString()."\n");
            $logger->close();
        }

        if ($this->_config->log->debug)
        {
            echo Debug::dump(get_class($e). '['.$e->getCode().']: '. $e->getMessage(), 'Message');
            echo Debug::dump($e->getFile().'['.$e->getLine().']', 'File');
            echo Debug::dump($e->getTrace(), 'Trace');
        }
        else
        {
            $view = new \Phalcon\Mvc\View();
            $view->setViewsDir(ROOT_PATH . '/app/frontend/views/');
            $view->setMainView('error');
        }
    }
}