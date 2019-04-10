{extends file='parent:frontend/checkout/ajax_cart.tpl'}

{block name='frontend_checkout_ajax_cart_prices_container_inner' append}
    <div class="prices--articles">
        <span class="prices--articles-text">
            {s namespace="frontend/plugins/oss_shipping_cost_preview/main" name="ShippingCostLabel"}{/s}
        </span>
        <span class="prices--articles-amount">{$ossShippingCost|currency}</span>
    </div>
    <div class="prices--articles">
        <span class="prices--articles-text">
            {s namespace="frontend/plugins/oss_shipping_cost_preview/main" name="TotalLabel"}{/s}
        </span>
        <span class="prices--articles-amount">{$ossTotalCost|currency}</span>
    </div>
{/block}
