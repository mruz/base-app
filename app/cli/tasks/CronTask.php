<?php

/**
 * Cron CLI Task
 *
 * @package     Donook
 * @category    Task
 * @version     2.0
 */

namespace Baseapp\Cli\Tasks;

class CronTask extends MainTask
{

    /**
     * Main Action
     *
     * @package     base-app
     * @version     2.0
     */
    public function mainAction()
    {
        echo "cronTask/mainAction\n";
    }
    
    public function testAction()
    {
        echo "cronTask/testAction\n";
        print_r($this->router->getParams());
    }

}
