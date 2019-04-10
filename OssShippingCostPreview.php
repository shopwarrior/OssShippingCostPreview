<?php

namespace OssShippingCostPreview;

use Shopware\Components\Plugin;
use Shopware\Components\Plugin\Context\ActivateContext;
use Shopware\Components\Plugin\Context\DeactivateContext;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class OssShippingCostPreview
 *
 * @package OssShippingCostPreview
 * @author  Odessite <alexey.palamar@odessite.com.ua>
 */
class OssShippingCostPreview extends Plugin
{
    /**
     * This method can be overridden
     *
     * @param ActivateContext $context
     */
    public function activate(ActivateContext $context)
    {
        $context->scheduleClearCache(ActivateContext::CACHE_LIST_ALL);
    }

    /**
     * This method can be overridden
     *
     * @param DeactivateContext $context
     */
    public function deactivate(DeactivateContext $context)
    {
        $context->scheduleClearCache(DeactivateContext::CACHE_LIST_ALL);
    }

    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        $container->setParameter('oss_shipping_cost_preview.plugin_dir', $this->getPath());

        parent::build($container);
    }
}
