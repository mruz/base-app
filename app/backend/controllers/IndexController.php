<?php
/**
 * Backend Index Controller
 * 
 * @package     base-app
 * @category    Controller
 * @version     1.0
 */
namespace Modules\Backend\Controllers;

class IndexController extends \Phalcon\Mvc\Controller
{
    /**
     * Initialize
     *
     * @package     base-app
     * @version     1.0
     */
    public function initialize()
    {
        // Redirect to home page if user is not admin
        if ( ! \Auth::instance()->logged_in('admin'))
            $this->response->redirect('');
        
        // Check the session lifetime
        if ($this->session->has('last_active') && time() - $this->session->get('last_active') > $this->config->session->lifetime)
        {
            $this->session->destroy();
        }
        $this->session->set('last_active', time());
        
        if($this->session->has('lang'))
        {
            // Set the language from session
            \I18n::instance()->lang($this->session->get('lang'));
        }
        elseif($this->cookies->has('lang'))
        {
            // Set the language from cookie
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
        
    }
    
    /**
     * Index Action 
     *
     * @package     base-app
     * @version     1.0
     */
    public function indexAction()
    {
        echo __('Admin panel');
    }
    
    /**
     * After Action
     *
     * @package     base-app
     * @version     1.0
     */
    public function afterExecuteRoute($dispatcher)
    {
        
    }
    
    /**
     * Not found Action 
     *
     * @package     base-app
     * @version     1.0
     */
    public function notfoundAction()
    {
        // Send a HTTP 404 response header
        $this->response->setStatusCode(404, "Not Found");
        $this->view->setMainView('404');
    }
}