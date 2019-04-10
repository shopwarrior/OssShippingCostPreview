<?php

namespace OssShippingCostPreview\Subscriber;

use Enlight\Event\SubscriberInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class Frontend
 * @package OssShippingCostPreview\Subscriber
 * @author  Odessite <alexey.palamar@odessite.com.ua>
 */
class Frontend implements SubscriberInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var \Enlight_Template_Manager
     */
    private $templateManager;

    /**
     * @param ContainerInterface $container
     * @param \Enlight_Template_Manager $templateManager
     */
    public function __construct(ContainerInterface $container, \Enlight_Template_Manager $templateManager)
    {
        $this->container = $container;
        $this->templateManager = $templateManager;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PreDispatch' => 'onPreDispatch',
            'Enlight_Controller_Action_PostDispatch_Frontend_Checkout'  =>  'onPostDispatchCheckout'
        ];
    }

    public function onPreDispatch()
    {
        $this->templateManager->addTemplateDir(
            $this->container->getParameter('oss_shipping_cost_preview.plugin_dir') . '/Resources/views'
        );
    }

    /**
     * @param \Enlight_Controller_ActionEventArgs $arguments
     */
    public function onPostDispatchCheckout(\Enlight_Controller_ActionEventArgs $arguments)
    {
        /**@var $controller \Shopware_Controllers_Frontend_Checkout */
        $controller = $arguments->getSubject();
        $request  = $controller->Request();
        $response = $controller->Response();
        $action = $request->getActionName();
        $view = $controller->View();

        if (
            $action !== 'ajaxCart' ||
            !$request->isDispatched() || $response->isException() || !$view->hasTemplate()
        ) {
            return;
        }

        $country = Shopware()->Modules()->Admin()->sGetCountry(
            Shopware()->Session()->sCountry ?
                Shopware()->Session()->sCountry :
                Shopware()->Config()->getByNamespace('OssShippingCostPreview', 'country')
        );
        $payment = Shopware()->Session()->sPaymentID?
            Shopware()->Session()->sPaymentID:
            Shopware()->Config()->getByNamespace('OssShippingCostPreview', 'payment');
        $dispatch = Shopware()->Session()->sDispatch?
            Shopware()->Session()->sDispatch:
            Shopware()->Config()->getByNamespace('OssShippingCostPreview', 'dispatch');

        Shopware()->Session()->sDispatch = $dispatch;
        Shopware()->Session()->sPaymentID = $payment;
        $sc = Shopware()->Modules()->Admin()->sGetPremiumShippingcosts($country);
        $ossShippingCost = isset($sc['brutto'])? $sc['brutto'] :0;

        $view->assign('ossShippingCost', $ossShippingCost);
        $view->assign('ossTotalCost', $view->sBasket['AmountNumeric']);
    }
}
