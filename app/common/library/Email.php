<?php

/**
 * Email Library
 * 
 * @package     base-app
 * @category    Library
 * @version     1.1
 */

namespace Baseapp\Library;

require_once __DIR__ . '/Email/class.phpmailer.php';


class Email extends \PHPMailer // Phalcon\Mvc\User\Component
{
    private $_config  = array();

    public function __construct($config = array())
    {
        $email = new \PHPMailer();
        
        if ($_config = \Phalcon\DI::getDefault()->getShared('config')->email)
            foreach ($_config as $key => $value)
                $this->_config[$key] = $value;
        
        foreach (array_merge($this->_config, $config) as $key => $value)
            $this->$key = $value;
        
        return $email;
    }
    
    public function getTemplate($name, $params = array())
    {
        $parameters = array_merge(array(
            'site_name' => \Phalcon\DI::getDefault()->getShared('config')->site->name,
            'site_url' => \Phalcon\DI::getDefault()->getShared('config')->site->url
        ), $params);

        return \Phalcon\DI::getDefault()->getShared('view')->getRender('email', $name, $parameters, function($view){
            $view->setRenderLevel(\Phalcon\Mvc\View::LEVEL_LAYOUT);
        });
        return $view->getContent();
    }
    
    public function prepare($subject, $to, $view, $params = array())
    {
        $this->Subject = $subject;
        $this->AddAddress($to);
        
        $body = $this->getTemplate($view, $params);
        
        $this->MsgHTML($body);
        
        return $body;
    }
}
?>
