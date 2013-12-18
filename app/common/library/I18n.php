<?php

/**
 * Lang Library
 *
 * @package     base-app
 * @category    Library
 * @version     1.3
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
        if ($lang)
        // Normalize the language
            $this->_config['lang'] = strtolower(str_replace(array(' ', '_'), '-', $lang));

        return $this->_config['lang'];
    }

    private function load($lang)
    {
        if (isset($this->_cache[$lang]))
        // Load from the cache
            return $this->_cache[$lang];

        $parts = explode('-', $lang);
        $path = implode(DIRECTORY_SEPARATOR, $parts);

        if (file_exists(ROOT_PATH . $this->_config['dir'] . $path . '.php'))
            $messages = require ROOT_PATH . $this->_config['dir'] . $path . '.php';
        elseif (file_exists(ROOT_PATH . $this->_config['dir'] . $lang . '.php'))
            $messages = require ROOT_PATH . $this->_config['dir'] . $lang . '.php';
        elseif (file_exists(ROOT_PATH . $this->_config['dir'] . $parts[0] . '.php'))
            $messages = require ROOT_PATH . $this->_config['dir'] . $parts[0] . '.php';

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
