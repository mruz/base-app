<?php

/**
 * Validation
 *
 * @package     base-app
 * @category    Extension
 * @version     1.3
 */

namespace Baseapp\Extension;

class Validation extends \Phalcon\Validation
{

    public function getMessages()
    {
        foreach (parent::getMessages() as $message) {
            $message->setMessage(__($message->getMessage()));
        }
        return parent::getMessages();
    }

}
