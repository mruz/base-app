<?php

namespace Baseapp\Extension;

/**
 * PHP Functions in Volt
 *
 * @package     base-app
 * @category    Library
 * @version     2.0
 */
class VoltPHPFunctions
{

    public function compileFunction($name, $arguments)
    {
        if (function_exists($name)) {
            return $name . '(' . $arguments . ')';
        }
    }

    public function compileFilter($name, $arguments)
    {
        if ($name == 'isset') {
            return '(isset(' . $arguments . ') ? ' . $arguments . ' : NULL)';
        }
    }

}
