<?php

/**
 * Token Model
 * 
 * @package     base-app
 * @category    Model
 * @version     1.2
 */

namespace Baseapp\Models;

class Tokens extends \Phalcon\Mvc\Model
{

    public function getSource()
    {
        return "user_tokens";
    }

    /**
     * Token initialize
     *
     * @version     1.2
     */
    public function initialize()
    {
        $this->belongsTo("user_id", __NAMESPACE__."\Users", "id", array(
            "foreignKey" => true
        ));

        // Do garbage collection
        if (mt_rand(1, 100) === 1)
            $this->delete_expired();

        // This object has expired
        if (property_exists($this, 'expires') && $this->expires < time())
            $this->delete();
    }

    /**
     * Deletes all expired tokens
     *
     * @version     1.2
     */
    public function delete_expired()
    {
        foreach ($this->find(array('expires<:time:', 'bind' => array('time' => time()))) as $token)
            $token->delete();
    }

}
