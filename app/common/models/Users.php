<?php

/**
 * User Model
 * 
 * @package     base-app
 * @category    Model
 * @version     1.2
 */

namespace Baseapp\Models;

use \Baseapp\Library\Auth,
    \Baseapp\Library\Email;

class Users extends \Phalcon\Mvc\Model
{

    public function validation()
    {
        $this->validate(new \Phalcon\Mvc\Model\Validator\Uniqueness(array(
            'field' => 'username'
        )));
        if ($this->validationHasFailed() == true) {
            return false;
        }
    }

    /**
     * User initialize
     *
     * @version     1.2
     */
    public function initialize()
    {
        $this->hasMany("id", __NAMESPACE__ . "\Tokens", "user_id", array(
            'foreignKey' => array(
                'action' => \Phalcon\Mvc\Model\Relation::ACTION_CASCADE
            )
        ));
        $this->hasMany("id", __NAMESPACE__ . "\RolesUsers", "user_id", array(
            'foreignKey' => array(
                'action' => \Phalcon\Mvc\Model\Relation::ACTION_CASCADE
            )
        ));
    }

    /**
     * Sign up User method
     *
     * @version     1.2
     */
    public function signup()
    {
        $validation = new \Phalcon\Validation();

        $validation->add('username', new \Phalcon\Validation\Validator\PresenceOf());
        $validation->add('username', new \Baseapp\Extension\UniqueValidator(array(
            'model' => 'Users',
        )));
        $validation->add('username', new \Phalcon\Validation\Validator\StringLength(array(
            'min' => 4,
        )));

        $validation->add('password', new \Phalcon\Validation\Validator\PresenceOf());
        $validation->add('repeatPassword', new \Phalcon\Validation\Validator\Confirmation(array(
            'with' => 'password'
        )));

        $validation->add('email', new \Phalcon\Validation\Validator\PresenceOf());
        $validation->add('email', new \Phalcon\Validation\Validator\Email());
        $validation->add('email', new \Baseapp\Extension\UniqueValidator(array(
            'model' => 'Users',
        )));

        $validation->add('repeatEmail', new \Phalcon\Validation\Validator\Confirmation(array(
            'with' => 'email'
        )));

        $messages = $validation->validate($_POST);

        if (count($messages)) {
            return $validation->getMessages();
        } else {
            $user = new Users();
            $user->username = $this->request->getPost('username');
            $user->password = Auth::instance()->hash($this->request->getPost('password'));
            $user->email = $this->request->getPost('email');
            $user->logins = 0;
            $user->create();

            $hash = md5($user->id . $user->email . $user->password . $this->config->auth->hash_key);

            $email = new Email();
            $email->prepare(__('Activation'), $this->request->getPost('email'), 'activation', array('username' => $this->request->getPost('username'), 'hash' => $hash));
            $email->Send();

            unset($_POST);
            return TRUE;
        }
    }

}
