<?php

namespace Baseapp\Frontend\Controllers;

use Baseapp\Library\Auth;
use Baseapp\Library\I18n;
use Baseapp\Library\Payment;
use Baseapp\Models\Payments;

/**
 * Frontend Payment Controller
 *
 * @package     base-app
 * @category    Controller
 * @version     2.0
 */
class PaymentController extends IndexController
{

    /**
     * Cancel the payment Action
     *
     * @package     base-app
     * @version     2.0
     */
    public function cancelAction()
    {
        $params = $this->router->getParams();

        if (isset($params[0]) && $adapter = $params[0]) {
            $this->view->pick('msg');

            switch ($adapter) {
                case 'paypal':
                    $paypal = Payment::instance('paypal');
                    // The token will be provided in the query string from Paypal.
                    if ($token = $paypal->check()) {
                        // Get the customers details from Paypal
                        $paypal->call('GetExpressCheckoutDetails', array('TOKEN' => $token));

                        if ($payment = Payments::findFirst(array('control=:control:', 'bind' => array(':control' => $token)))) {
                            if ($paypal->get('ACK') === 'Success') {
                                // Update the payment
                                $payment->state = 'CANCEL';
                                $payment->date_update = date("Y-m-d H:i:s");
                                $payment->response = json_encode($paypal->get());
                                $payment->update();

                                $this->tag->setTitle(__('Transaction canceled'));
                                $this->view->setVar('title', __('Canceled'));
                                $this->flashSession->notice($this->tag->linkTo(array('#', 'class' => 'close', 'title' => __("Close"), '×')) . '<strong>' . __('Notice') . '!</strong> ' . __("Your account has not been charged the amount of the transaction."));
                            } else {
                                // Update the payment
                                $payment->state = 'ERROR';
                                $payment->date_update = date("Y-m-d H:i:s");
                                $payment->response = json_encode($paypal->get());
                                $payment->update();

                                $this->tag->setTitle(__('Transaction aborted'));
                                $this->view->setVar('title', __('Please try again later'));
                                $this->flashSession->error($this->tag->linkTo(array('#', 'class' => 'close', 'title' => __("Close"), '×')) . '<strong>' . __('Error') . '!</strong> ' . __('If the message appears again, we would be grateful for reporting this issue. If you have questions or concerns please :contact.', array(':contact' => $this->tag->linkTo('contact', __('contact us')))));
                            }
                        }
                    }
                    break;
                default:
                    $this->response->redirect(NULL);
                    break;
            }
        }
    }

    /**
     * Checkout payment Action
     *
     * @package     base-app
     * @version     2.0
     */
    public function checkoutAction()
    {
        if ($checkout = $this->session->get('checkout')) {
            $this->tag->setTitle(__('Payment') . ' ' . __('Checkout'));
            $this->view->setVar('checkout', $checkout);
        } else {
            $this->response->redirect('buy');
        }
    }

    /**
     * New payment Action
     *
     * @package     base-app
     * @version     2.0
     */
    public function newAction()
    {
        $params = $this->router->getParams();

        if ($this->auth->logged_in() && isset($params[0]) && $adapter = $params[0]) {
            if ($checkout = $this->session->get('checkout')) {

                switch ($adapter) {
                    case 'dotpay':
                        $this->view->setVar('adapter', $this->tag->linkTo(array('http://www.dotpay.pl', $this->tag->image(array('img/dotpay.png', 'alt' => 'dotpay')), 'target' => '_blank', 'local' => false)));
                        break;
                    default :
                        $this->view->setVar('adapter', $this->tag->linkTo(array('http://www.paypal.com', $this->tag->image(array('img/paypal.png', 'alt' => 'dotpay')), 'target' => '_blank', 'local' => false)));
                        break;
                }

                if ($this->request->isPost() === true && $this->request->hasPost('submit')) {
                    $payment = new Payments();
                    $payment = $payment->add($checkout);

                    // Check if new payment was valid and added
                    if ($payment instanceof \Baseapp\Models\Payments) {
                        // Switch to payment adapter
                        switch ($adapter) {
                            case 'dotpay':
                                $dotpay = Payment::instance('dotpay');
                                $fields = array(
                                    'currency' => 'USD',
                                    'amount' => number_format($payment->total, 2, '.', ''),
                                    'lang' => substr($this->i18n->lang(), 0, 2),
                                    'description' => __('Chocolate') . ' ' . $this->config->app->name,
                                    'control' => $payment->control,
                                    'type' => 3,
                                    'buttontext' => __('Back to site'),
                                    'email' => $payment->email,
                                    'firstname' => $payment->firstname,
                                    'lastname' => $payment->lastname,
                                );

                                $this->view->pick('msg');
                                $this->tag->setTitle(__('Redirect'));
                                $this->view->setVar('title', __('Redirect'));
                                $this->flashSession->notice($this->tag->linkTo(array('#', 'class' => 'close', 'title' => __("Close"), '×')) . '<strong>' . __('Notice') . '!</strong> ' . __("Redirecting to dotpay"));
                                $this->view->setVar('content', $dotpay->process($fields));
                                break;
                            default :
                                $paypal = Payment::instance('paypal');
                                $fields = array(
                                    'AMT' => $payment->total,
                                    'CURRENCYCODE' => 'USD',
                                    'LOCALECODE' => substr($this->i18n->lang(), 0, 2),
                                    'NOSHIPPING' => '1',
                                    'HDRIMG' => $this->url->getStatic('img/logo.png'),
                                    'EMAIL' => $this->config->app->admin,
                                    'ALLOWNOTE' => '0',
                                    'PAYMENTREQUEST_0_AMT' => $payment->total,
                                    'PAYMENTREQUEST_0_CURRENCYCODE' => 'USD',
                                    'PAYMENTREQUEST_0_DESC' => $this->config->app->name,
                                    'L_PAYMENTREQUEST_0_NAME0' => __('Chocolate') . ' ' . $this->config->app->name,
                                    'L_PAYMENTREQUEST_0_AMT0' => $payment->amount,
                                    'L_PAYMENTREQUEST_0_QTY0' => $payment->quantity,
                                );

                                $paypal->process($fields);

                                // Check that the response from the Paypal server is ok.
                                if ($paypal->get('ACK') === 'Success') {
                                    // Store token in SESSION
                                    $this->session->set('paypal_token_' . $paypal->get('TOKEN'), $fields['AMT']);

                                    // We now send the user to the Paypal site for them to provide their details
                                    $fields['token'] = $paypal->get('TOKEN');
                                    unset($fields['PAYMENTACTION']);

                                    $payment->control = $fields['token'];
                                    $payment->date_update = date('Y-m-d H:i:s');
                                    $payment->response = json_encode($paypal->get());
                                    $payment->save();

                                    $url = $paypal->redirectURL('express-checkout', $fields);
                                    $this->response->redirect($url, true);
                                }

                                break;
                        }
                    } else {
                        $this->view->setVar('errors', $payment);
                        $this->flashSession->warning($this->tag->linkTo(array('#', 'class' => 'close', 'title' => __("Close"), '×')) . '<strong>' . __('Warning') . '!</strong> ' . __("Please correct the errors."));
                    }
                }
            } else {
                $this->response->redirect('order');
            }
        } else {
            $this->response->redirect('user/signin');
        }
    }

    /**
     * Return Action for payments
     *
     * @package     base-app
     * @version     2.0
     */
    public function returnAction()
    {
        $params = $this->router->getParams();

        if (isset($params[0]) && $adapter = $params[0]) {
            $this->view->pick('msg');

            switch ($adapter) {
                case 'dotpay':
                    if ($_POST['status'] === 'OK') {
                        $this->tag->setTitle(__('Transaction completed'));
                        $this->view->setVar('title', __('Thank you for your payment'));
                        $this->flashSession->success($this->tag->linkTo(array('#', 'class' => 'close', 'title' => __("Close"), '×')) . '<strong>' . __('Success') . '!</strong> ' . __("Time of transfer realization is usually immediate (online), sometimes (eg. credit card payments) this time is up to 24 hours."));
                    } else {
                        $this->tag->setTitle(__('Transaction aborted'));
                        $this->view->setVar('title', __('Please try again later'));
                        $this->flashSession->notice($this->tag->linkTo(array('#', 'class' => 'close', 'title' => __("Close"), '×')) . '<strong>' . __('Notice') . '!</strong> ' . __("Your account has not been charged the amount of the transaction."));
                    }
                    break;
                case 'paypal':
                    $paypal = Payment::instance('paypal');

                    if ($token = $paypal->check()) {
                        if ($payment = Payments::findFirst(array('control=:control:', 'bind' => array(':control' => $token)))) {
                            // Get the customers details from Paypal
                            $paypal->call('GetExpressCheckoutDetails', array('TOKEN' => $token));

                            if ($paypal->get('ACK') === 'Success') {
                                // Perform any calculations to determine the final charging price
                                $params = array(
                                    'TOKEN' => $token,
                                    'PAYERID' => $paypal->get('PAYERID'),
                                    'AMT' => $this->session->get('paypal_token_' . $token),
                                    'CURRENCYCODE' => 'USD',
                                );
                                // Process the payment
                                $paypal->call('DoExpressCheckoutPayment', $params);

                                if ($paypal->get('ACK') === 'Success' && $paypal->get('PAYMENTSTATUS') === 'Completed') {
                                    // Update the payment
                                    $payment->state = 'SUCCESS';
                                    $payment->total_response = $paypal->get('AMT');
                                    $payment->date_update = date("Y-m-d H:i:s");
                                    $payment->response = json_encode($paypal->get());
                                    $payment->update();

                                    $this->tag->setTitle(__('Transaction completed'));
                                    $this->view->setVar('title', __('Thank you for your payment'));
                                    $this->flashSession->success($this->tag->linkTo(array('#', 'class' => 'close', 'title' => __("Close"), '×')) . '<strong>' . __('Success') . '!</strong> ' . __('Time of transfer realization is usually immediate (online), sometimes (eg. credit card payments) this time is up to 24 hours.'));
                                } else {
                                    // Update the payment
                                    $payment->state = 'ERROR';
                                    $payment->date_update = date("Y-m-d H:i:s");
                                    $payment->response = json_encode($paypal->get());
                                    $payment->update();

                                    $this->tag->setTitle(__('Transaction aborted'));
                                    $this->view->setVar('title', __('Please try again later'));
                                    $this->flashSession->notice($this->tag->linkTo(array('#', 'class' => 'close', 'title' => __("Close"), '×')) . '<strong>' . __('Notice') . '!</strong> ' . __("Your account has not been charged the amount of the transaction."));
                                }
                            } else {
                                // Update the payment
                                $payment->state = 'ERROR';
                                $payment->date_update = date("Y-m-d H:i:s");
                                $payment->response = json_encode($paypal->get());
                                $payment->update();

                                $this->tag->setTitle(__('Transaction aborted'));
                                $this->view->setVar('title', __('Please try again later'));
                                $this->flashSession->error($this->tag->linkTo(array('#', 'class' => 'close', 'title' => __("Close"), '×')) . '<strong>' . __('Error') . '!</strong> ' . __('If the message appears again, we would be grateful for reporting this issue. If you have questions or concerns please :contact.', array(':contact' => $this->tag->linkTo('contact', __('contact us')))));
                            }
                        }
                    }
                    break;
                default:
                    $this->response->redirect(NULL);
                    break;
            }
        }
    }

    /**
     * Update status Action
     *
     * @package     base-app
     * @version     2.0
     */
    public function statusAction()
    {
        $params = $this->router->getParams();

        if (isset($params[0]) && $adapter = $params[0]) {
            $this->view->setRenderLevel(\Phalcon\Mvc\View::LEVEL_ACTION_VIEW);

            switch ($adapter) {
                case 'dotpay':
                    $dotpay = Payment::instance('dotpay');

                    if ($dotpay->get('control') && $payment = Payments::findFirst(array('control=:control:', 'bind' => array(':control' => $dotpay->get('control'))))) {
                        switch ($dotpay->get('t_status')) {
                            case 1: $state = 'NEW';
                                break;
                            case 2: $state = 'SUCCESS';
                                $payment->total_response = $dotpay->get('amount');
                                break;
                            case 3: $state = 'REFUSAL';
                                break;
                            case 4: $state = 'CANCEL/RETURN';
                                break;
                            case 5: $state = 'RECLAMATION';
                                break;
                        }

                        $payment->state = $state;
                        $payment->date_update = date('Y-m-d H:i:s');
                        $payment->response = json_encode($dotpay->get());
                        $payment->save();

                        return $this->response->setContent('OK');
                    } else {
                        return $this->response->setContent('FAIL');
                    }
                    break;
                default:
                    $this->response->redirect(NULL);
                    break;
            }
        }
    }

}
