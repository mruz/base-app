<?php

namespace Baseapp\Frontend\Controllers;

use Baseapp\Library\Auth;
use Baseapp\Models\Users;

/**
 * Frontend User Controller
 *
 * @package     base-app
 * @category    Controller
 * @version     2.0
 */
class UserController extends IndexController
{

    /**
     * Index Action
     *
     * @package     base-app
     * @version     2.0
     */
    public function indexAction()
    {
        //$this->view->setRenderLevel(\Phalcon\Mvc\View::LEVEL_NO_RENDER);
        if ($this->auth->logged_in()) {

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
     * @version     2.0
     */
    public function signinAction()
    {
        if ($this->request->hasPost('submit_signin') && $this->request->hasPost('username') && $this->request->hasPost('password')) {
            $login = $this->auth->login($this->request->getPost('username'), $this->request->getPost('password'), $this->request->getPost('rememberMe') ? TRUE : FALSE);
            if (!$login) {
                $errors = new \Phalcon\Validation\Message\Group();
                if ($login === NULL) {
                    $errors->appendMessage(new \Phalcon\Validation\Message(__('Field :field is incorrect', array(':field' => __('Username'))), 'username', 'Incorrect'));
                } else {
                    $errors->appendMessage(new \Phalcon\Validation\Message(__('Field :field is incorrect', array(':field' => __('Password'))), 'password', 'Incorrect'));
                }

                $this->view->setVar('errors', $errors);
                $this->flashSession->warning(
                        $this->tag->linkTo(array('#', 'class' => 'close', 'title' => __("Close"), '×')) .
                        '<strong>' . __('Warning') . '!</strong> ' .
                        __("Please correct the errors."));
            } else {
                $referer = $this->request->getHTTPReferer();
                $needBackRedirect = !empty($referer) && strpos(parse_url($referer, PHP_URL_PATH), '/user/signin') !== 0 && parse_url($referer, PHP_URL_HOST) == $this->request->getHttpHost();

                if ($needBackRedirect) {
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
     * @version     2.0
     */
    public function signupAction()
    {
        if ($this->request->isPost() == TRUE) {
            $user = new Users();
            $signup = $user->signup();

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
     * @version     2.0
     */
    public function signoutAction()
    {
        $this->auth->logout();
        $this->response->redirect(NULL);
    }

    /**
     * Activation Action
     *
     * @package     base-app
     * @version     2.0
     */
    public function activationAction()
    {
        $this->view->pick('msg');
        $this->tag->setTitle(__('Activation'));
        $this->view->setVar('title', __('Activation'));

        $params = $this->router->getParams();
        $user = Users::findFirst(array('username=:user:', 'bind' => array('user' => $params[0])));

        if ($user && md5($user->id . $user->email . $user->password . $this->config->auth->hash_key) == $params[1]) {
            $activation = $user->activation();

            if ($activation === NULL) {
                $this->flashSession->notice(
                        $this->tag->linkTo(array('#', 'class' => 'close', 'title' => __("Close"), '×')) .
                        '<strong>' . __('Notice') . '!</strong> ' .
                        __("Activation has already been completed."));
            } elseif ($activation === TRUE) {
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
