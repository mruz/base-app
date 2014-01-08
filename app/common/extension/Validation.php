<?php

/**
 * Validation
 *
 * @package     base-app
 * @category    Extension
 * @version     2.0
 */

namespace Baseapp\Extension;

class Validation extends \Phalcon\Validation
{

    public function getDefaultMessage($type)
    {
        // Translate dafault messages
        return __($this->_defaultMessages[$type]);
    }

}
