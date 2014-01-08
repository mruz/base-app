<?php

/**
 * Lang Library
 *
 * @package     base-app
 * @category    Library
 * @version     2.0
 */

namespace Baseapp\Library;

class I18n
{

    private $_config = array(
        'lang' => 'en-gb',
        'dir' => '/app/common/i18n/'
    );
    protected $_cache = array();
    private static $_instance;

    public static function instance()
    {
        if (empty(self::$_instance)) {
            self::$_instance = new I18n;
        }

        return self::$_instance;
    }

    private function __construct()
    {
        // Overwrite _config from config.ini
        if ($_config = \Phalcon\DI::getDefault()->getShared('config')->i18n)
            foreach ($_config as $key => $value)
                $this->_config[$key] = $value;
    }

    private function __clone()
    {

    }

    public function lang($lang = NULL)
    {
        // Normalize the language
        if ($lang)
            $this->_config['lang'] = strtolower(str_replace(array(' ', '_'), '-', $lang));

        return $this->_config['lang'];
    }

    private function load($lang)
    {
        // Load from the cache
        if (isset($this->_cache[$lang]))
            return $this->_cache[$lang];

        $parts = explode('-', $lang);
        $subdir = implode(DIRECTORY_SEPARATOR, $parts);

        // Search for /en/gb.php, /en-gb.php, /en.php or gb.php
        foreach (array($subdir, $lang, $parts) as $tail) {
            if (!is_array($tail))
                $tail = array($tail);

            foreach ($tail as $found) {
                $path = ROOT_PATH . $this->_config['dir'] . $found . '.php';
                if (file_exists($path)) {
                    $messages = require $path;

                    // Stop searching
                    break;
                }
            }
        }

        $translate = new \Phalcon\Translate\Adapter\NativeArray(array(
            "content" => isset($messages) ? $messages : array()
        ));

        return $this->_cache[$lang] = $translate;
    }

    public function getCache()
    {
        return $this->_cache;
    }

    public function _($string, array $values = NULL)
    {
        $translate = $this->load($this->_config['lang']);
        $string = $translate->_($string, $values);

        return empty($values) ? $string : strtr($string, $values);
    }

}
