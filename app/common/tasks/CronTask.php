<?php

/**
 * Cron CLI Task
 * 
 * @package     Donook
 * @category    Task
 * @version     1.3
 */
class CronTask extends \Phalcon\CLI\Task
{

    /**
     * Initialize
     *
     * @package     base-app
     * @version     1.3
     */
    public function initialize()
    {
        
    }

    /**
     * Main Action 
     *
     * @package     base-app
     * @version     1.3
     */
    public function mainAction()
    {
        echo "main\n";
    }

    /**
     * Not found Action 
     *
     * @package     base-app
     * @version     1.3
     */
    public function notFoundAction()
    {
        echo "Task not found\n";
    }

}