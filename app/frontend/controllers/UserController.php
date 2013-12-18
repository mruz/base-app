<?php

/**
 * Frontend User Controller
 * 
 * @package     base-app
 * @category    Controller
 * @version     1.3
 */

namespace Baseapp\Frontend\Controllers;

use \Baseapp\Library\Auth,
    \Baseapp\Models\Users,
    \Baseapp\Models\Roles,
    \Baseapp\Models\RolesUsers;

class UserController extends IndexController
{

    /**
     * Index Action
     *
     * @package     base-app
     * @version     1.3
     */
    public function indexAction()
    {
        //$this->view->setRenderLevel(\Phalcon\Mvc\View::LEVEL_NO_RENDER);
        if (Auth::instance()->logged_in()) {
            
        } else {
            $this->view->pick('msg');
            $this->tag->setTitle(__('No access'));
            $this->view->setVar('title', __('No access'));
            $this->view->setVar('redirect', 'user/signin');
            $this->flashSession->error(
                    $this->tag->linkTo(array('#', 'class' => 'close', 'title' => __("Close"), '×')) .
                    '<strong>' . __('Error') . '!</strong> ' .
                    __("Please log in to access."));
        }
    }

    /**
     * Sign in Action
     *
     * @package     base-app
     * @version     1.3
     */
    public function signinAction()
    {
        if ($this->request->hasPost('submit_signin') && $this->request->hasPost('username') && $this->request->hasPost('password')) {
            $login = Auth::instance()->login($this->request->getPost('username'), $this->request->getPost('password'), $this->request->getPost('remember') ? TRUE : FALSE);
            if (!$login) {
                $errors = new \Phalcon\Validation\Message\Group();
                if ($login === NULL)
                    $errors->appendMessage(new \Phalcon\Validation\Message(__('Incorrect username'), 'username', 'NotFound'));
                else
                    $errors->appendMessage(new \Phalcon\Validation\Message(__('Incorrect password'), 'password', 'Identical'));

                $this->view->setVar('errors', $errors);
                $this->flashSession->warning(
                        $this->tag->linkTo(array('#', 'class' => 'close', 'title' => __("Close"), '×')) .
                        '<strong>' . __('Warning') . '!</strong> ' .
                        __("Please correct the errors."));
            } else {
                $referer = $this->request->getHTTPReferer();
                if (strpos($referer, $this->request->getHttpHost() . "/") !== FALSE && $this->router->getControllerName() != 'user' && $this->router->getActionName() != 'signin') {
                    return $this->response->setHeader("Location", $referer);
                } else {
                    return $this->dispatcher->forward(array('controller' => 'index', 'action' => 'index'));
                }
            }
        }
    }

    /**
     * Sign up Action
     *
     * @package     base-app
     * @version     1.3
     */
    public function signupAction()
    {
        if ($this->request->isPost() == TRUE) {
            $signup = Users::signup();

            if ($signup === TRUE) {
                $this->flashSession->notice(
                        $this->tag->linkTo(array('#', 'class' => 'close', 'title' => __("Close"), '×')) .
                        '<strong>' . __('Success') . '!</strong> ' .
                        __("Check Email to activate your account."));
            } else {
                $this->view->setVar('errors', $signup);
                $this->flashSession->warning(
                        $this->tag->linkTo(array('#', 'class' => 'close', 'title' => __("Close"), '×')) .
                        '<strong>' . __('Warning') . '!</strong> ' .
                        __("Please correct the errors."));
            }
        }
    }

    /**
     * Log out Action 
     *
     * @package     base-app
     * @version     1.3
     */
    public function signoutAction()
    {
        Auth::instance()->logout();
        $this->response->redirect(NULL);
    }

    /**
     * Activation Action
     *
     * @package     base-app
     * @version     1.3
     */
    public function activationAction()
    {
        $this->view->pick('msg');
        $this->tag->setTitle(__('Activation'));
        $this->view->setVar('title', __('Activation'));

        $params = $this->router->getParams();
        $user = Users::findFirst(array('username=:user:', 'bind' => array('user' => $params[0])));

        if ($user && md5($user->id . $user->email . $user->password . $this->config->auth->hash_key) == $params[1]) {
            $roles = Auth::instance()->get_roles($user);

            if ($roles['login']) {
                $this->flashSession->notice(
                        $this->tag->linkTo(array('#', 'class' => 'close', 'title' => __("Close"), '×')) .
                        '<strong>' . __('Notice') . '!</strong> ' .
                        __("Activation has already been completed."));
            } else {
                $role = new RolesUsers();
                $role->user_id = $user->id;
                $role->role_id = Roles::findFirst(array('name="login"'))->id;
                $role->create();

                $this->flashSession->success(
                        $this->tag->linkTo(array('#', 'class' => 'close', 'title' => __("Close"), '×')) .
                        '<strong>' . __('Success') . '!</strong> ' .
                        __("Activation completed. Please log in."));

                $this->view->setVar('redirect', 'user/signin');
            }
        } else {
            $this->flashSession->error(
                    $this->tag->linkTo(array('#', 'class' => 'close', 'title' => __("Close"), '×')) .
                    '<strong>' . __('Error') . '!</strong> ' .
                    __("Activation cannot be completed. Invalid username or hash."));
        }
    }

}