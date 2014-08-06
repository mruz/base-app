<?php
// Global translation function
if (!function_exists('__')) {

    /**
     * Translate message
     *
     * @package     base-app
     * @version     2.0
     *
     * @param string $string string to translate
     * @param array $values replace substrings
     *
     * @return string translated string
     */
    function __($string, array $values = NULL)
    {
        return \Baseapp\Library\I18n::instance()->_($string, $values);
    }

}

if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__));
}

require_once ROOT_PATH . '/app/Bootstrap.php';

$app = new \Baseapp\Bootstrap(new \Phalcon\DI\FactoryDefault());
   