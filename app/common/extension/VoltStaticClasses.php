<?php

namespace Baseapp\Extension;

/**
 * Static Classes in Volt
 *
 * @package     base-app
 * @category    Library
 * @version     2.0
 */
class VoltStaticClasses
{

    public function compileFunction($name, $arguments)
    {
        if (strpos($name, '__')) {
            $path = explode('_', str_replace('__', '::', $name));
            if ($path) {
                // Prepare namespace - make first characters uppercase
                array_walk($path, create_function('&$value', '$value = ucfirst($value);'));

                // Get class name and property
                list($class, $property) = explode('::', array_pop($path));

                if (isset($class) && isset($property)) {
                    $class = '\Baseapp\\' . implode('\\', $path) . '\\' . $class;

                    // Get static function if exist
                    if (method_exists($class, $property))
                        return $class . '::' . $property . '(' . $arguments . ')';

                    if (!$arguments) {
                        // Get constant if exist
                        if (defined($class . '::' . $property))
                            return $class . '::' . $property;

                        // Get static property if exist
                        if (property_exists($class, $property))
                            return $class . '::$' . $property;
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
