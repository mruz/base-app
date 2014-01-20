<?php

/**
 * Library Classes in Volt
 *
 * @package     base-app
 * @category    Library
 * @version     2.0
 */

namespace Baseapp\Extension;

class VoltLibraryClasses
{

    public function compileFunction($name, $arguments)
    {
        if (strpos($name, '__')) {
            list($class, $function) = explode('__', $name);

            if (isset($class) && isset($function)) {
                $class = '\Baseapp\Library\\' . ucfirst($class);

                if (method_exists($class, $function)) {
                    return $class . '::' . $function . '(' . $arguments . ')';
                }
            }
        }
    }

    public function compileFilter($name, $arguments)
    {
        if ($name == 'label') {
            return '\Baseapp\Library\Tool::label(' . $arguments . ')';
        }
    }

}
