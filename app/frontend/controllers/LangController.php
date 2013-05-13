<?php
/**
 * Frontend Lang Controller
 * 
 * @package     base-app
 * @category    Controller
 * @version     1.0
 */
namespace Modules\Frontend\Controllers;

class LangController extends IndexController
{
    /**
     * Set language Action
     *
     * @package     base-app
     * @version     1.0
     */
    public function setAction($id)
    {
        // Store lang in session and cookie
        $this->session->set('lang', $id);
        $this->cookies->set('lang', $id, time() + 365 * 86400);
        
        // Go to the last place
        $referer = $this->request->getHTTPReferer();
        if (strpos($referer, $this->request->getHttpHost()."/") !== false)
        {
            return $this->response->setHeader("Location", $referer);
        }
        else
        {
            return $this->dispatcher->forward(array('controller' => 'index', 'action' => 'index'));
        }
    }
}