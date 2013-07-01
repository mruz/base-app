<?php

/**
 * User Model
 * 
 * @package     base-app
 * @category    Model
 * @version     1.2
 */

namespace Baseapp\Models;

class Users extends \Phalcon\Mvc\Model
{

    /**
     * User initialize
     *
     * @version     1.2
     */
    public function initialize()
    {
        $this->hasMany("id", __NAMESPACE__."\Tokens", "user_id", array(
            'foreignKey' => array(
                'acrion' => \Phalcon\Mvc\Model\Relation::ACTION_CASCADE
            )
        ));
        $this->hasMany("id", __NAMESPACE__."\RolesUsers", "user_id", array(
            'foreignKey' => array(
                'acrion' => \Phalcon\Mvc\Model\Relation::ACTION_CASCADE
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
        
        $validation->add('password', new \Phalcon\Validation\Validator\PresenceOf());
        $validation->add('email', new \Phalcon\Validation\Validator\PresenceOf());

        $validation->add('email', new \Phalcon\Validation\Validator\Email());

        $messages = $validation->validate($_POST);
        
        if(count($messages))
        {
            return $validation->getMessages();
        }
        else
        {
//            $date = date('Y-m-d H:i:s');
//            $user = new Users();
//            $user->username = $this->request->getPost('username');
//            $user->password = Auth::instance()->hash($this->request->getPost('password'));
//            $user->email = $this->request->getPost('email');
//            $user->date = $date;
//            $user->create();
//            
//            $hash = $user->hash = md5($this->request->getPost('email').$user->id.$date);
//            $user->update();
//            
//            // send e-mail with url to confirm
            
            return TRUE;
        }
    }

}