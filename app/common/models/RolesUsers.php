<?php

/**
 * User roles Model
 * 
 * @package     base-app
 * @category    Model
 * @version     1.1
 */

namespace Baseapp\Models;

class RolesUsers extends \Phalcon\Mvc\Model
{

    /**
     * Roles Users initialize
     *
     * @version     1.1
     */
    public function initialize()
    {
        $this->belongsTo("user_id", __NAMESPACE__."\Users", "id", array(
            "foreignKey" => true
        ));
        $this->belongsTo("role_id", __NAMESPACE__."\Roles", "id", array(
            "foreignKey" => true
        ));
    }

}