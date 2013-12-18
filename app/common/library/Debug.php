<?php

/**
 * Debug Library
 *
 * @package     base-app
 * @category    Library
 * @version     1.3
 */

namespace Baseapp\Library;

abstract class Debug
{

    public static $style = array(
        'pre' => array('background-color' => '#f1f1f1', 'font-size' => '11px', 'padding' => '10px', 'border' => '1px solid #ccc', 'text-align' => 'left', 'color' => '#222'),
        'arr' => array('color' => 'red', 'font-weight' => 'bold'),
        '_arr' => array('color' => 'red'),
        'bool' => array('color' => 'orange', 'font-weight' => 'bold'),
        '_bool' => array('color' => 'orange'),
        'float' => array('color' => 'magenta', 'font-weight' => 'bold'),
        '_float' => array('color' => 'magenta'),
        'int' => array('color' => 'blue', 'font-weight' => 'bold'),
        '_int' => array('color' => 'blue'),
        'null' => array('color' => 'black', 'font-weight' => 'bold'),
        'num' => array('color' => 'green', 'font-weight' => 'bold'),
        '_num' => array('color' => 'gray'),
        'obj' => array('color' => 'purple', 'font-weight' => 'bold'),
        '_obj' => array('color' => 'purple'),
        'other' => array('color' => 'khaki'),
        'res' => array('color' => 'steelblue', 'font-weight' => 'bold'),
        '_res' => array('color' => 'steelblue'),
        'str' => array('color' => 'green', 'font-weight' => 'bold'),
        '_str' => array('color' => 'gray')
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
        foreach ($vars as $index => $value) {
            $out .= self::dump($value, 'var ' . ($index + 1));
        }
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
        $style = Debug::get_style(self::$style);
        return '<pre style="' . $style['pre'] . '">' . ($name != '' ? "$name : " : '') . Debug::_get_info_var($var, $name) . '</pre>';
    }

    /**
     * Prepare array of styles from style atribute.
     *
     * @param   array    array of styles
     * @return  array
     */
    private static function get_style(array $styles)
    {
        $arr = array();
        foreach ($styles as $type => $style) {
            $str = '';
            $space = FALSE;
            foreach ($style as $key => $value) {
                $str .= ($space ? ' ' : '') . $key . ': ' . $value . ';';
                $space = TRUE;
            }
            $arr[$type] = $str;
        }
        return $arr;
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

        $style = self::get_style(self::$style);
        $out = '';
        if (is_array($var)) {
            $out .= '<span style="' . $style['arr'] . '">Array</span> ' . count($var) . " (\n";
            foreach (array_keys($var) as $key) {
                $out .= $spc . '  [<span style="' . $style['_arr'] . '">' . $key . '</span>] => ';
                if (($indent == 0) && ($name != '') && (!is_int($key)) && ($name == $key)) {
                    $out .= "LOOP\n";
                } else {
                    $out .= self::_get_info_var($var[$key], '', $indent + 1);
                }
            }
            $out .= "$spc)";
        } else if (is_object($var)) {
            $class = get_class($var);
            $out .= '<span style="' . $style['obj'] . '"><b>Object</b></span> ' . $class;
            $parent = get_parent_class($var);
            $out .= $parent != '' ? ' <span style="' . $style['obj'] . '">extends</span> ' . $parent : '';
            $out .= " (\n";

            $arr = get_object_vars($var);
            if (!$arr)
                $arr = $var;

            while (list($prop, $val) = each($arr)) {
                $out .= "$spc  " . '-><span style="' . $style['_obj'] . '">' . $prop . '</span> = ';
                $out .= self::_get_info_var($val, $name != '' ? $prop : '', $indent + 1);
            }
            $arr = get_class_methods($var);
            $out .= $spc . '  ' . $class . ' <span style="' . $style['obj'] . '">methods</span>: ' . count($arr) . " ";
            if (in_array($class, $methods)) {
                $out .= "[already listed]\n";
            } else {
                $out .= "(\n";
                $methods[] = $class;
                while (list($prop, $val) = each($arr)) {
                    if ($val != $class && $val != '__construct') {
                        $out .= $indent_chars . $spc . '  ' . '-><span style="' . $style['_obj'] . '">' . $val . "</span>();\n";
                    } else {
                        $out .= $indent_chars . $spc . '  ' . "->$val(); [<b>constructor</b>]\n";
                    }
                }
                $out .= "$spc  " . ")\n";
            }
            $out .= "$spc)";
        } else if (is_resource($var)) {
            $out .= '<span style="' . $style['res'] . '"><b>Resource</b></span> [' . get_resource_type($var) . '] ( <span style="' . $style['_res'] . '">' . $var . '</span> )';
        } else if (is_int($var)) {
            $out .= '<span style="' . $style['int'] . '">Integer</span> (<span style="' . $style['_int'] . '">' . $var . '</span>)';
        } else if (is_float($var)) {
            $out .= '<span style="' . $style['float'] . '">Float</span> (<span style="' . $style['_float'] . '">' . $var . '</span>)';
        } else if (is_numeric($var)) {
            $out .= '<span style="' . $style['num'] . '">Numeric string</span> (' . strlen($var) . ') "<span style="' . $style['_num'] . '">' . $var . '</span>"';
        } else if (is_string($var)) {
            $out .= '<span style="' . $style['str'] . '">String</span> (' . strlen($var) . ') "<span style="' . $style['_str'] . '">' . nl2br(htmlentities($var, ENT_IGNORE, 'utf-8')) . '</span>"';
        } else if (is_bool($var)) {
            $out .= '<span style="' . $style['bool'] . '">Boolean</span> (<span style="' . $style['_bool'] . '">' . ($var ? 'TRUE' : 'FALSE') . '</span>)';
        } else if (!isset($var)) {
            $out .= '<span style="' . $style['null'] . '">NULL</span>';
        } else {
            $out .= '<span style="' . $style['other'] . '"> ( ' . $var . ' )';
        }

        return $out . "\n";
    }

}
