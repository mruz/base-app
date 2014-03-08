<?php

namespace Baseapp\Extension;

/**
 * Static functions in Volt
 *
 * @package     base-app
 * @category    Library
 * @version     2.0
 */
class VoltStaticFunctions
{

    /**
     * Compile static function call in a template
     *
     * @package     base-app
     * @version     2.0
     *
     * @param string $name function name
     * @param mixed $arguments function args
     *
     * @return string compiled function
     */
    public function compileFunction($name, $arguments)
    {
        if (strpos($name, '__')) {
            $path = explode('_', str_replace('__', '::', $name));
            if ($path) {
                // Prepare namespace - make first characters uppercase
                $path = array_map('ucfirst', $path);

                // Get class name and property
                list($class, $property) = explode('::', array_pop($path));

                if (isset($class) && isset($property)) {
                    $class = '\Baseapp\\' . implode('\\', $path) . '\\' . $class;

                    // Get static function if exist
                    if (method_exists($class, $property)) {
                        return $class . '::' . $property . '(' . $arguments . ')';
                    }

                    if (!$arguments) {
                        // Get constant if exist
                        if (defined($class . '::' . $property)) {
                            return $class . '::' . $property;
                        }

                        // Get static property if exist
                        if (property_exists($class, $property)) {
                            return $class . '::$' . $property;
                        }
                    }
                }
            }
        }
    }

    /**
     * Compile label filter
     *
     * @package     base-app
     * @version     2.0
     *
     * @param string $name filter name
     * @param mixed $arguments filter args
     *
     * @return string compiled filter
     */
    public function compileFilter($name, $arguments)
    {
        if ($name == 'label') {
            return '\Baseapp\Library\Tool::label(' . $arguments . ')';
        }
    }

}
