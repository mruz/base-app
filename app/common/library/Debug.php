<?php

/**
 * Debug Library
 *
 * @package     base-app
 * @category    Library
 * @version     2.0
 */

namespace Baseapp\Library;

abstract class Debug
{

    public static $_style = array(
        'pre' => 'background-color:#f3f3f3;font-size:11px;padding:10px; border:1px solid #ccc; text-align:left; color:#333',
        'arr' => 'color:red;',
        'bool' => 'color:green;',
        'float' => 'color:fuchsia',
        'int' => 'color:blue;',
        'null' => 'color:black',
        'num' => 'color:navy',
        'obj' => 'color:purple;',
        'other' => 'color:maroon',
        'res' => 'color:lime',
        'str' => 'color:teal'
    );

    /**
     * Returns an HTML string of debugging information about any number of
     * variables, each wrapped in a "pre" tag:
     *
     *     // Displays the type and value of each variable
     *     echo Debug::vars($foo, $bar, $baz);
     *
     * @param   mixed   variable to debug
     * @param   ...
     * @return  string
     */
    public static function vars()
    {
        $vars = func_get_args();
        $out = '';
        foreach ($vars as $index => $value)
            $out .= self::dump($value, 'var ' . ($index + 1));

        return $out;
    }

    /**
     * Returns an HTML string of information about a single variable.
     *
     * @param   mixed    variable to dump
     * @param   string   name of variable
     * @return  string
     */
    public static function dump($var, $name = '')
    {
        return '<pre style="' . self::$_style['pre'] . '">' . ($name != '' ? "$name : " : '') . self::_get_info_var($var, $name) . '</pre>';
    }

    /**
     * Prepare an HTML string of information about a single variable.
     *
     * @param   mixed    variable to dump
     * @param   string   name of variable
     * @return  string
     */
    private static function _get_info_var($var, $name = '', $indent = 0)
    {
        static $methods = array();
        $indent > 0 or $methods = array();

        $indent_chars = '  ';
        $spc = $indent > 0 ? str_repeat($indent_chars, $indent) : '';

        $out = '';
        if (is_array($var)):
            $out .= '<b style="' . self::$_style['arr'] . '">Array</b> ' . count($var) . " (\n";
            foreach (array_keys($var) as $key) {
                $out .= $spc . '  [<span style="' . self::$_style['arr'] . '">' . $key . '</span>] => ';
                if (($indent == 0) && ($name != '') && (!is_int($key)) && ($name == $key)) {
                    $out .= "LOOP\n";
                } else {
                    $out .= self::_get_info_var($var[$key], '', $indent + 1);
                }
            }
            $out .= "$spc)";
            
        elseif (is_object($var)):
            $class = get_class($var);
            $out .= '<b style="' . self::$_style['obj'] . '">Object</b> ' . $class;
            $parent = get_parent_class($var);
            $out .= $parent != '' ? ' <b style="' . self::$_style['obj'] . '">extends</b> ' . $parent : '';
            $out .= " (\n";

            $arr = get_object_vars($var);
            if (!$arr)
                $arr = $var;

            while (list($prop, $val) = each($arr)) {
                $out .= "$spc  " . '-><span style="' . self::$_style['obj'] . '">' . $prop . '</span> = ';
                $out .= self::_get_info_var($val, $name != '' ? $prop : '', $indent + 1);
            }
            $arr = get_class_methods($var);
            $out .= $spc . '  ' . $class . ' <b style="' . self::$_style['obj'] . '">methods</b>: ' . count($arr) . " ";
            if (in_array($class, $methods)) {
                $out .= "[already listed]\n";
            } else {
                $out .= "(\n";
                $methods[] = $class;
                while (list($prop, $val) = each($arr)) {
                    if ($val != $class && $val != '__construct') {
                        $out .= $indent_chars . $spc . '  ' . '-><span style="' . self::$_style['obj'] . '">' . $val . "</span>();\n";
                    } else {
                        $out .= $indent_chars . $spc . '  ' . "->$val(); [<b>constructor</b>]\n";
                    }
                }
                $out .= "$spc  " . ")\n";
            }
            $out .= "$spc)";

        elseif (is_resource($var)):
            $out .= '<b style="' . self::$_style['res'] . '">Resource</b> [' . get_resource_type($var) . '] ( <span style="' . self::$_style['res'] . '">' . $var . '</span> )';
        elseif (is_int($var)):
            $out .= '<b style="' . self::$_style['int'] . '">Integer</b> (<span style="' . self::$_style['int'] . '">' . $var . '</span>)';
        elseif (is_float($var)):
            $out .= '<b style="' . self::$_style['float'] . '">Float</b> (<span style="' . self::$_style['float'] . '">' . $var . '</span>)';
        elseif (is_numeric($var)):
            $out .= '<b style="' . self::$_style['num'] . '">Numeric string</b> (' . strlen($var) . ') "<span style="' . self::$_style['num'] . '">' . $var . '</span>"';
        elseif (is_string($var)):
            $out .= '<b style="' . self::$_style['str'] . '">String</b> (' . strlen($var) . ') "<span style="' . self::$_style['str'] . '">' . nl2br(htmlentities($var, ENT_IGNORE, 'utf-8')) . '</span>"';
        elseif (is_bool($var)):
            $out .= '<b style="' . self::$_style['bool'] . '">Boolean</b> (<span style="' . self::$_style['bool'] . '">' . ($var ? 'TRUE' : 'FALSE') . '</span>)';
        elseif (is_null($var)):
            $out .= '<b style="' . self::$_style['null'] . '">NULL</b>';
        else:
            $out .= '<span style="' . self::$_style['other'] . '"> ( ' . $var . ' )';
        endif;

        return $out . "\n";
    }

}
