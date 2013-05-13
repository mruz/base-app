<?php
/**
 * Token Model
 * 
 * @package     base-app
 * @category    Model
 * @version     1.0
 */
class Tokens extends Phalcon\Mvc\Model
{
    public function getSource()
    {
        return "user_tokens";
    }
    
    /**
     * Token initialize
     *
     * @version     1.0
     */
    public function initialize()
    {
        $this->belongsTo("user_id", "Users", "id", array(
            "foreignKey" => true
        ));
        
        if (mt_rand(1, 100) === 1)
        {
            // do garbage collection
            $this->delete_expired();
        }

        if ($this->expires < time())
        {
            // This object has expired
            $this->delete();
        }
    }
    
    /**
     * Deletes all expired tokens
     *
     * @version     1.0
     */
    public function delete_expired()
    {
        foreach($this->find(array('expires<:time:', 'bind' => array('time' => time()) )) as $token)
        {
            $token->delete();
        }
    }
}