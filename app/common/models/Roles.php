<?php
/**
 * Role Model
 * 
 * @package     base-app
 * @category    Model
 * @version     1.0
 */
class Roles extends Phalcon\Mvc\Model
{
    /**
     * Role initialize
     *
     * @version     1.0
     */
    public function initialize()
    {
        $this->hasMany("id", "RolesUsers", "role_id");
    }
}