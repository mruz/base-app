<?php

/**
 * Uniqueness Validator
 *
 * @package     base-app
 * @category    Extension
 * @version     2.0
 */

namespace Baseapp\Extension;

class Uniqueness extends \Phalcon\Validation\Validator implements \Phalcon\Validation\ValidatorInterface
{

    public function validate($validator, $attribute)
    {
        if (!$this->isSetOption('model'))
            return FALSE;

        $model = ucfirst($this->getOption('model'));        
        $value = $validator->getValue($attribute);

        $filtered = $model::findFirst(array($attribute . '=:atribute:', 'bind' => array('atribute' => $value)));


        if ($filtered) {

            $message = $this->getOption('message');
            if (!$message) {
                $message = __(':field must be unique', array(':field' => ucfirst($attribute)));
            }

            $validator->appendMessage(new \Phalcon\Validation\Message($message, $attribute, 'Unique'));

            return false;
        }

        return true;
    }

}
