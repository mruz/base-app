<?php

namespace Baseapp\Extension;

/**
 * Uniqueness Validator
 *
 * @package     base-app
 * @category    Extension
 * @version     2.0
 */
class Uniqueness extends \Phalcon\Validation\Validator implements \Phalcon\Validation\ValidatorInterface
{

    public function validate($validator, $field)
    {
        if (!$this->isSetOption('model'))
            return FALSE;

        $attribute = $this->getOption('attribute');

        if (empty($attribute))
            $attribute = $field;

        $model = $this->getOption('model');
        $value = $validator->getValue($field);
        $count = $model::count(array($attribute . '=:attribute:', 'bind' => array(':attribute' => $value)));

        if ($count) {
            $message = $this->getOption('message');
            if (!$message) {
                $message = __(':field must be unique', array(':field' => ucfirst($field)));
            }

            $validator->appendMessage(new \Phalcon\Validation\Message($message, $field, 'Unique'));

            return false;
        }

        return true;
    }

}
