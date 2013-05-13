<?php
/**
 * Frontend Index Controller
 * 
 * @package     base-app
 * @category    Controller
 * @version     1.0
 */
namespace Modules\Frontend\Controllers;

class IndexController extends \Phalcon\Mvc\Controller
{
    public $site_desc;
    
    /**
     * Initialize
     *
     * @package     base-app
     * @version     1.0
     */
    public function initialize()
    {
        if($this->session->has('lang'))
        {
            // set the language from session
            \I18n::instance()->lang($this->session->get('lang'));
        }
        elseif($this->cookies->has('lang'))
        {
            // set the language from cookie
            \I18n::instance()->lang($this->cookies->get('lang')->getValue());
        }
    }
    
    /**
     * Before Action
     *
     * @package     base-app
     * @version     1.0
     */
    public function beforeExecuteRoute($dispatcher)
    {
        // Set default title and description
        \Phalcon\Tag::setTitle('Index');
        $this->site_desc = 'Index';
    }
    
    /**
     * Index Action 
     *
     * @package     base-app
     * @version     1.0
     */
    public function indexAction()
    {
        \Phalcon\Tag::setTitle(__('Home'));
        $this->site_desc = __('Home');
    }
    
    /**
     * After Action
     *
     * @package     base-app
     * @version     1.0
     */
    public function afterExecuteRoute($dispatcher)
    {
        \Phalcon\Tag::appendTitle(' | base-app');
        $this->view->setVar('site_desc', mb_substr($this->filter->sanitize($this->site_desc, 'string'), 0, 200, 'utf-8'));
    }
    
    /**
     * Not found Action 
     *
     * @package     base-app
     * @version     1.0
     */
    public function notFoundAction()
    {
        // Send a HTTP 404 response header
        $this->response->setStatusCode(404, "Not Found");
        $this->view->setMainView('404');
    }
}