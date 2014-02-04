#!/usr/bin/php
<?php
// Everything is relative to the application root now.
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__));
}
chdir(ROOT_PATH);

/**
 * Console
 *
 * @package     base-app
 * @category    CLI
 * @version     2.0
 */
class Console extends \Phalcon\CLI\Console
{

    private $_di;
    private $_config;

    public function __construct(\Phalcon\DiInterface $di)
    {
        $this->_di = $di;

        $loaders = array('config', 'loader', 'db', 'router');

        // Register services
        foreach ($loaders as $service)
            $this->$service();

        // Register modules
        $this->registerModules(array(
            'cli' => array(
                'className' => 'Baseapp\Cli\Module',
                'path' => 'app/cli/Module.php'
            ),
        ));

        // Sets the parent Di
        parent::setDI($this->_di);
    }

    protected function loader()
    {
        // Register an autoloader
        $loader = new \Phalcon\Loader();
        $loader->registerNamespaces(array(
            'Baseapp\Models' => 'app/common/models/',
            'Baseapp\Library' => 'app/common/library/',
        ))->register();
    }

    protected function config()
    {
        // Create the new object
        $config = new \Phalcon\Config\Adapter\Ini('app/common/config/config.ini');

        // Store it in the Di container
        $this->_di->set('config', $config);
        $this->_config = $config;
    }

    protected function db()
    {
        $config = $this->_config;
        // Set the database service
        $this->_di->set('db', function() use ($config) {
            return new \Phalcon\Db\Adapter\Pdo\Mysql(array(
                "host" => $config->database->host,
                "username" => $config->database->username,
                "password" => $config->database->password,
                "dbname" => $config->database->dbname
            ));
        });
    }

    protected function router()
    {
        // Setting up the static router
        $this->_di->set('router', function() {
            $router = new \Phalcon\CLI\Router();
            return $router;
        });
    }

    public function handle($arguments = NULL)
    {
        $params = NULL;
        switch (count($arguments)) {
            case 1:
                $task = 'main';
                $action = 'main';
                break;
            case 2:
                $task = $arguments[1];
                $action = 'main';
                break;
            case 3:
                $task = $arguments[1];
                $action = $arguments[2];
                break;
            default:
                $task = $arguments[1];
                $action = $arguments[2];
                $params = array_slice($arguments, 3);
                break;
        }
        parent::handle(array('module' => 'cli', 'task' => $task, 'action' => $action, 'params' => $params));
    }

}

// Run console
$console = new Console(new \Phalcon\DI());
$console->handle($argv);
