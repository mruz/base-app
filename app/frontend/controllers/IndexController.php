<?php

/**
 * Frontend Index Controller
 * 
 * @package     base-app
 * @category    Controller
 * @version     1.1
 */

namespace Baseapp\Frontend\Controllers;

use \Phalcon\Tag,
    \Baseapp\Library\I18n;

class IndexController extends \Phalcon\Mvc\Controller
{

    public $site_desc;

    /**
     * Initialize
     *
     * @package     base-app
     * @version     1.1
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
    }

    /**
     * Before Action
     *
     * @package     base-app
     * @version     1.1
     */
    public function beforeExecuteRoute($dispatcher)
    {
        // Set default title and description
        Tag::setTitle('Index');
        $this->site_desc = 'Index';
    }

    /**
     * Index Action 
     *
     * @package     base-app
     * @version     1.1
     */
    public function indexAction()
    {
        Tag::setTitle(__('Home'));
        $this->site_desc = __('Home');
    }

    /**
     * After Action
     *
     * @package     base-app
     * @version     1.1
     */
    public function afterExecuteRoute($dispatcher)
    {
        Tag::appendTitle(' | base-app');
        $this->view->setVar('site_desc', mb_substr($this->filter->sanitize($this->site_desc, 'string'), 0, 200, 'utf-8'));
    }

    /**
     * Not found Action 
     *
     * @package     base-app
     * @version     1.1
     */
    public function notFoundAction()
    {
        // Send a HTTP 404 response header
        $this->response->setStatusCode(404, "Not Found");
        $this->view->setMainView('404');
    }

}