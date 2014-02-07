<?php

namespace Baseapp\Extension;

/**
 * Library Classes in Volt
 *
 * @package     base-app
 * @category    Library
 * @version     2.0
 */
class VoltLibraryClasses
{

    public function compileFunction($name, $arguments)
    {
        if (strpos($name, '__')) {
            $path = explode('_', str_replace('__', '::', $name));
            if ($path) {
                array_walk($path, create_function('&$value', '$value = ucfirst($value);'));
                list($class, $function) = explode('::', array_pop($path));

                if (isset($class) && isset($function)) {
                $class = '\Baseapp\\' . implode('\\', $path) . '\\' . $class;

                    if (method_exists($class, $function)) {
                        return $class . '::' . $function . '(' . $arguments . ')';
                    }
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
