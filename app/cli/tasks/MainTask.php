<?php

namespace Baseapp\Cli\Tasks;

/**
 * Main CLI Task
 *
 * @package     base-app
 * @category    Task
 * @version     2.0
 */
class MainTask extends \Phalcon\CLI\Task
{

    /**
     * Initialize
     *
     * @package     base-app
     * @version     2.0
     */
    public function initialize()
    {

    }

    /**
     * Main Action
     *
     * @package     base-app
     * @version     2.0
     */
    public function mainAction()
    {
        echo "mainTask/mainAction\n";
    }

    /**
     * Not found Action
     *
     * @package     base-app
     * @version     2.0
     */
    public function notFoundAction()
    {
        echo "Task not found\n";
    }

}
