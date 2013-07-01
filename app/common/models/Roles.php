<?php

/**
 * Role Model
 * 
 * @package     base-app
 * @category    Model
 * @version     1.2
 */

namespace Baseapp\Models;

class Roles extends \Phalcon\Mvc\Model
{

    /**
     * Role initialize
     *
     * @version     1.2
     */
    public function initialize()
    {
        $this->hasMany("id", __NAMESPACE__."\RolesUsers", "role_id");
    }

}