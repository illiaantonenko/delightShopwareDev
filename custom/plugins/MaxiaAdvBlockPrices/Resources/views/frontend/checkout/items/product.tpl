{extends file="parent:frontend/checkout/items/product.tpl"}

{block name='frontend_checkout_cart_item_quantity_selection'}

    {if $mabpConfig.showNumberInputCart}
        {block name="frontend_checkout_cart_item_quantity_selection_mabp_include"}

            {capture priceInfo assign=fallback}
                {$smarty.block.parent}
            {/capture}

            {if !$sBasketItem.additional_details.laststock || ($sBasketItem.additional_details.laststock && $sBasketItem.additional_details.instock > 0)}
                {include file="plugins/maxia_adv_block_prices/quantity_form.tpl" fallback=$fallback}
            {else}
                {s name="CartColumnQuantityEmpty" namespace="frontend/checkout/cart_item"}{/s}
            {/if}
        {/block}
    {else}
        {$smarty.block.parent}
    {/if}

{/block}