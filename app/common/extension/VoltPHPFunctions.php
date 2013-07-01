<?php

/**
 * PHP Functions in Volt
 * 
 * @package     base-app
 * @category    Library
 * @version     1.2
 */

namespace Baseapp\Extension;

class VoltPHPFunctions
{

    public function compileFunction($name, $arguments)
    {
        if (function_exists($name)) {
            return $name . '(' . $arguments . ')';
        }
    }

}

?>
