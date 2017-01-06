<?php

namespace StripePayment\Hook;

use StripePayment\Model\StripeCustomerQuery;
use StripePayment\StripePayment;
use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;

/**
 * Class StripePaymentHook
 * @package StripePayment\Hook
 * @author Etienne Perriere - OpenStudio <eperriere@openstudio.fr>
 */
class StripePaymentHook extends BaseHook
{
    public function includeStripe(HookRenderEvent $event)
    {
        $stripeCustomer = StripeCustomerQuery::create()->findOneByCustomerId($this->getCustomer()->getId());

        $event->add($this->render(
            'assets/js/stripe-js.html',
            [
                'stripe_customer' => $stripeCustomer
            ]
        ));
    }

    public function declareStripeOnClickEvent(HookRenderEvent $event)
    {
        $publicKey = StripePayment::getConfigValue('publishable_key');
        $stripeCustomer = StripeCustomerQuery::create()->findOneByCustomerId($this->getCustomer()->getId());

        $event->add($this->render(
            'assets/js/order-invoice-after-js-include.html',
            [
                'stripe_module_id' => $this->getModule()->getModuleId(),
                'public_key' => $publicKey,
                'stripe_customer' => $stripeCustomer
            ]
        ));
    }

    public function includeStripeCardSaving(HookRenderEvent $event)
    {
        $stripeCustomer = StripeCustomerQuery::create()->findOneByCustomerId($this->getCustomer()->getId());

        $event->add($this->render(
            'stripe-card-saving.html',
            [
                'stripe_customer' => $stripeCustomer
            ]
        ));
    }

    public function includeStripeCardSavingOnClickEvent(HookRenderEvent $event)
    {
        $event->add($this->render(
            'assets/js/account-after-javascript-include.html',
            [
                'public_key' => StripePayment::getConfigValue('publishable_key')
            ]
        ));
    }

    public function onCustomerEditBottom(HookRenderEvent $event)
    {
        $event->add($this->render(
            'charge-customer.html',
            [
                'customer_id' => $event->getArgument('customer_id')
            ]
        ));
    }
}