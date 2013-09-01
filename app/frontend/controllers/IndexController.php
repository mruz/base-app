<?php

/**
 * Frontend Index Controller
 * 
 * @package     base-app
 * @category    Controller
 * @version     1.2
 */

namespace Baseapp\Frontend\Controllers;

use \Baseapp\Library\I18n,
    \Baseapp\Library\Auth;

class IndexController extends \Phalcon\Mvc\Controller
{

    public $site_desc;

    /**
     * Initialize
     *
     * @package     base-app
     * @version     1.2
     */
    public function initialize()
    {
        // Check the session lifetime
        if ($this->session->has('last_active') && time() - $this->session->get('last_active') > $this->config->session->lifetime)
            $this->session->destroy();

        $this->session->set('last_active', time());

        // Set the language from session
        if ($this->session->has('lang'))
            I18n::instance()->lang($this->session->get('lang'));
        // Set the language from cookie
        elseif ($this->cookies->has('lang'))
            I18n::instance()->lang($this->cookies->get('lang')->getValue());

        $this->view->setVar('i18n', I18n::instance());
        $this->view->setVar('auth', Auth::instance());
    }

    /**
     * Before Action
     *
     * @package     base-app
     * @version     1.2
     */
    public function beforeExecuteRoute($dispatcher)
    {
        // Set default title and description
        $this->tag->setTitle('Default');
        $this->site_desc = 'Default';
        
        // Add css and js to assets collection
        $this->assets->addCss('css/app.css');
        $this->assets->addJs('js/plugins/flashclose.js');
    }

    /**
     * Index Action 
     *
     * @package     base-app
     * @version     1.2
     */
    public function indexAction()
    {
        $this->tag->setTitle(__('Home'));
        $this->site_desc = __('Home');
    }

    /**
     * After Action
     *
     * @package     base-app
     * @version     1.2
     */
    public function afterExecuteRoute($dispatcher)
    {
        // Set final title and description
        $this->tag->appendTitle(' | base-app');
        $this->view->setVar('site_desc', mb_substr($this->filter->sanitize($this->site_desc, 'string'), 0, 200, 'utf-8'));
        
        // Minify css and js collection
        foreach ($this->assets->getCss() as $resource){
            $min = new \Phalcon\Assets\Filters\Cssmin();
            $resource->setTargetUri('min/' . $resource->getPath());
            if (md5($min->filter($resource->getContent())) != md5_file(ROOT_PATH . '/public/min/' . $resource->getPath()))
                file_put_contents(ROOT_PATH . '/public/min/' . $resource->getPath(), $min->filter($resource->getContent()));
        }
        foreach ($this->assets->getJs() as $resource){
            $min = new \Phalcon\Assets\Filters\Jsmin();
            $resource->setTargetUri('min/' . $resource->getPath());
            if (md5($min->filter($resource->getContent())) != md5_file(ROOT_PATH . '/public/min/' . $resource->getPath()))
                file_put_contents(ROOT_PATH . '/public/min/' . $resource->getPath(), $min->filter($resource->getContent()));
        }
    }
    
    /**
     * Not found Action 
     *
     * @package     base-app
     * @version     1.2
     */
    public function notFoundAction()
    {
        // Send a HTTP 404 response header
        $this->response->setStatusCode(404, "Not Found");
        $this->view->setMainView('404');
    }

}