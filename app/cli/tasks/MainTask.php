<?php

/**
 * Main CLI Task
 *
 * @package     Donook
 * @category    Task
 * @version     2.0
 */

namespace Baseapp\Cli\Tasks;

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
