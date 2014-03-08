<?php

namespace Baseapp\Cli\Tasks;

use Baseapp\Library\I18n;
use Baseapp\Library\Auth;

/**
 * Prepare CLI Task
 *
 * @package     base-app
 * @category    Task
 * @version     2.0
 */
class PrepareTask extends MainTask
{

    /**
     * Minify css and js collection
     *
     * @package     base-app
     * @version     2.0
     */
    public function assetAction()
    {
        
    }

    /**
     * Chmod for folders
     *
     * @package     base-app
     * @version     2.0
     */
    public function chmodAction()
    {
        $dirs = array(
            '/app/common/cache',
            '/app/common/logs',
            '/public/min',
        );

        foreach ($dirs as $dir) {
            chmod(ROOT_PATH . $dir, 0777);
        }
    }

    /**
     * Render views from volt files
     *
     * @package     base-app
     * @version     2.0
     */
    public function voltAction()
    {
        $this->view->setVars(array(
            'i18n' => I18n::instance(),
            'auth' => Auth::instance(),
        ));
        ob_start();
        $e = '';
        foreach (array('frontend', 'backend') as $module) {
            foreach ($iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(ROOT_PATH . '/app/' . $module . '/views/', \RecursiveDirectoryIterator::SKIP_DOTS), \RecursiveIteratorIterator::SELF_FIRST) as $item) {
                if (!$item->isDir() && $item->getExtension() == 'volt') {
                    $this->view->setViewsDir(ROOT_PATH . '/app/' . $module . '/views/');

                    $subPath = $iterator->getSubPathName();
                    $file = strstr($item->getFilename(), '.volt', true);
                    $dir = strstr($subPath, $item->getFilename(), true);

                    $e .= $this->view->partial($dir . $file);
                }
            }
        }
        ob_get_clean();
        //\Baseapp\Console::log($e);
    }

}
