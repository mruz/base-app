<?php

/**
 * User roles Model
 *
 * @package     base-app
 * @category    Model
 * @version     2.0
 */

namespace Baseapp\Models;

class RolesUsers extends \Phalcon\Mvc\Model
{

    /**
     * Roles Users initialize
     *
     * @version     2.0
     */
    public function initialize()
    {
        $this->belongsTo("user_id", __NAMESPACE__ . "\Users", "id", array(
            "foreignKey" => true
        ));
        $this->belongsTo("role_id", __NAMESPACE__ . "\Roles", "id", array(
            "foreignKey" => true
        ));
    }

}
