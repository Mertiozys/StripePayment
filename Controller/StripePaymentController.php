<?php

namespace StripePayment\Controller;

use StripePayment\Model\StripeCustomer;
use StripePayment\Model\StripeCustomerQuery;
use StripePayment\StripePayment;
use Thelia\Core\HttpFoundation\Request;
use Thelia\Model\CustomerQuery;
use Thelia\Module\BasePaymentModuleController;
use Thelia\Tools\URL;

/**
 * Class StripePaymentController
 * @package StripePayment\Controller
 * @author Etienne Perriere - OpenStudio <eperriere@openstudio.fr>
 */
class StripePaymentController extends BasePaymentModuleController
{
    /**
     * Save Stripe customer token
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createStripeCustomerAction(Request $request)
    {
        if (null !== $customerId = $request->getSession()->getCustomerUser()->getId()) {

            $stripeToken = $request->get('stripe_token');

            \Stripe\Stripe::setApiKey(StripePayment::getConfigValue('secret_key'));

            $stripeCustomer = \Stripe\Customer::create(
                [
                    "source" => $stripeToken,
                    "description" => CustomerQuery::create()->filterById($customerId)->select('email')->findOne()
                ]
            );

            if (null !== $stripeCustomerSaved = StripeCustomerQuery::create()->findOneByCustomerId($customerId)) {
                $stripeCustomerSaved
                    ->setStripeCustomerId($stripeCustomer->id)
                    ->save()
                ;
            } else {
                (new StripeCustomer())
                    ->setCustomerId($customerId)
                    ->setStripeCustomerId($stripeCustomer->id)
                    ->save()
                ;
            }
        }

        return $this->generateRedirect(URL::getInstance()->absoluteUrl('account'));
    }

    /**
     * Charge customer thanks to his Stripe customer token
     * @param Request $request
     */
    public function chargeStripeCustomerAction(Request $request)
    {
        \Stripe\Stripe::setApiKey(StripePayment::getConfigValue('secret_key'));

        $stripeCustomer = StripeCustomerQuery::create()->findOneByCustomerId($request->get('customer_id'));

        \Stripe\Charge::create(
            [
                "amount"   => $request->get('charge_amount') * 100,
                "currency" => "eur",
                "customer" => $stripeCustomer->getStripeCustomerId()
            ]
        );
    }
    
    /**
     * Return a module identifier used to calculate the name of the log file,
     * and in the log messages.
     *
     * @return string the module code
     */
    protected function getModuleCode()
    {
        return 'StripePayment';
    }
}