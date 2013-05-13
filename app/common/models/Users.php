<?php
/**
 * User Model
 * 
 * @package     base-app
 * @category    Model
 * @version     1.0
 */
class Users extends Phalcon\Mvc\Model
{
    /**
     * User initialize
     *
     * @version     1.0
     */
    public function initialize()
    {
        $this->hasMany("id", "Tokens", "user_id");
        $this->hasMany("id", "RolesUsers", "user_id");
    }
}