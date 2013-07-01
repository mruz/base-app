<?php

/**
 * Frontend User Controller
 * 
 * @package     base-app
 * @category    Controller
 * @version     1.2
 */

namespace Baseapp\Frontend\Controllers;

use Baseapp\Library\Auth;

class UserController extends IndexController
{

    /**
     * Index Action
     *
     * @package     base-app
     * @version     1.2
     */
    public function indexAction()
    {
        //$this->view->setRenderLevel(\Phalcon\Mvc\View::LEVEL_NO_RENDER);
        echo \Baseapp\Library\Debug::vars(\Baseapp\Library\I18n::instance()->getCache());
    }

    /**
     * Sign in Action
     *
     * @package     base-app
     * @version     1.2
     */
    public function signinAction()
    {
        if ($this->request->hasPost('submit_signin') && $this->request->hasPost('username') && $this->request->hasPost('password')) {
            if ($this->request->getPost('username') && $this->request->getPost('password')) {
                $login = Auth::instance()->login($this->request->getPost('username'), $this->request->getPost('password'), $this->request->getPost('remember') ? TRUE : FALSE);
                if (!$login) {
                    $this->view->setVar('error_signin', TRUE);
                    $this->flashSession->warning(
                        $this->tag->linkTo(array('#', 'class' => 'close', 'title' => __("Close"), '×' )).
                        '<strong>'.__('Warning').'!</strong> '.
                        __("Please correct the errors."));
                } else {
                    $referer = $this->request->getHTTPReferer();
                    if (strpos($referer, $this->request->getHttpHost() . "/") !== false) {
                        return $this->response->setHeader("Location", $referer);
                    } else {
                        return $this->dispatcher->forward(array('controller' => 'index', 'action' => 'index'));
                    }
                }
            } else {
                $this->view->setVar('error_signin', TRUE);
                $this->flashSession->warning(
                        $this->tag->linkTo(array('#', 'class' => 'close', 'title' => __("Close"), '×' )).
                        '<strong>'.__('Warning').'!</strong> '.
                        __("Please correct the errors."));
            }
        }
    }
    
    /**
     * Sign up Action
     *
     * @package     base-app
     * @version     1.2
     */
    public function signupAction()
    {
        if ($this->request->isPost() == TRUE){
            $signup = \Baseapp\Models\Users::signup();

            if ($signup === TRUE){
                $this->flashSession->notice(
                        $this->tag->linkTo(array('#', 'class' => 'close', 'title' => __("Close"), '×' )).
                        '<strong>'.__('Success').'!</strong> '.
                        __("Check E-mail to activate your account."));
            }else{
                $this->view->setVar('errors', $signup);
                $this->flashSession->warning(
                        $this->tag->linkTo(array('#', 'class' => 'close', 'title' => __("Close"), '×' )).
                        '<strong>'.__('Warning').'!</strong> '.
                        __("Please correct the errors."));
            }
        }
    }

    /**
     * Log out Action 
     *
     * @package     base-app
     * @version     1.2
     */
    public function signoutAction()
    {
        Auth::instance()->logout();
        $this->response->redirect(NULL);
    }

}