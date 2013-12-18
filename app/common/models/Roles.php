<?php

/**
 * Role Model
 * 
 * @package     base-app
 * @category    Model
 * @version     1.3
 */

namespace Baseapp\Models;

class Roles extends \Phalcon\Mvc\Model
{

    /**
     * Role initialize
     *
     * @version     1.3
     */
    public function initialize()
    {
        $this->hasMany("id", __NAMESPACE__."\RolesUsers", "role_id");
    }

}
