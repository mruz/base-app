<?php

/**
 * Frontend Lang Controller
 * 
 * @package     base-app
 * @category    Controller
 * @version     1.1
 */

namespace Baseapp\Frontend\Controllers;

class LangController extends IndexController
{

    /**
     * Set language Action
     *
     * @package     base-app
     * @version     1.1
     */
    public function setAction($lang)
    {
        // Store lang in session and cookie
        $this->session->set('lang', $lang);
        $this->cookies->set('lang', $lang, time() + 365 * 86400);

        // Go to the last place
        $referer = $this->request->getHTTPReferer();
        if (strpos($referer, $this->request->getHttpHost() . "/") !== false) {
            return $this->response->setHeader("Location", $referer);
        } else {
            return $this->dispatcher->forward(array('controller' => 'index', 'action' => 'index'));
        }
    }

}