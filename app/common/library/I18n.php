<?php

class I18n {
    
    private $_config = array(
        'lang' => 'en-gb',
        'source' => 'en-gb',
        'dir' => '/app/common/i18n/'
    );
    
    protected $_cache = array();

    private static $_instance;
    
    public static function instance()
    {
        if( empty(self::$_instance) )
        {
            self::$_instance = new I18n;
        }

        return self::$_instance;
    }
    
    private function __construct()
    {
        // Overwrite _config from config.ini
        if ($_config = \Phalcon\DI::getDefault()->getShared('config')->lang)
            foreach ($_config as $key => $value)
                $this->_config[$key] = $value;
    }
    
    private function __clone(){}
    
    
    public function lang($lang = NULL)
    {
        if ($lang)
        {
            // Normalize the language
            $this->_config['lang'] = strtolower(str_replace(array(' ', '_'), '-', $lang));
        }

        return $this->_config['lang'];
    }
    
    private function load($lang)
    {
        if (isset($this->_cache[$lang]))
        {
            return $this->_cache[$lang];
        }

        $parts = explode('-', $lang);
        $path = implode(DIRECTORY_SEPARATOR, $parts);
        
        if ($lang != $this->_config['source'])
        {
            if (file_exists(ROOT_PATH . $this->_config['dir'].$path.'.php'))
            {
                $messages = require ROOT_PATH . $this->_config['dir'].$path.'.php';
            }
            elseif (file_exists(ROOT_PATH . $this->_config['dir'].$lang.'.php'))
            {
                $messages = require ROOT_PATH . $this->_config['dir'].$lang.'.php';
            }
            elseif (file_exists(ROOT_PATH . $this->_config['dir'].$parts[0].'.php'))
            {
                $messages = require ROOT_PATH . $this->_config['dir'].$parts[0].'.php';
            }
        }

        $translate = new \Phalcon\Translate\Adapter\NativeArray(array(
            "content" => isset($messages) ? $messages : array()
        ));
        
        return $this->_cache[$lang] = $translate;
    }
    
    public function _($string, array $values = NULL)
    {
        if ($this->_config['lang'] != $this->_config['source'])
        {
            $translate = $this->load($this->_config['lang']);
            $string = $translate->_($string, $values);
        }
        
        return empty($values) ? $string : strtr($string, $values);
    }
}

if ( ! function_exists('__'))
{
    function __($string, array $values = NULL)
    {
        return I18n::instance()->_($string, $values);
    }
}