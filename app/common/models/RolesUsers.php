<?php
/**
 * User roles Model
 * 
 * @package     base-app
 * @category    Model
 * @version     1.0
 */
class RolesUsers extends Phalcon\Mvc\Model
{
    /**
     * Roles Users initialize
     *
     * @version     1.0
     */
    public function initialize()
    {
        $this->belongsTo("user_id", "Users", "id", array(
            "foreignKey" => true
        ));
        $this->belongsTo("role_id", "Roles", "id", array(
            "foreignKey" => true
        ));
    }
}