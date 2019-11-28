{extends file="parent:frontend/detail/buy.tpl"}

{* Include quantity number input *}
{block name='frontend_detail_buy_quantity_select'}
    {if $mabpConfig.showNumberInput}

        {block name="frontend_detail_buy_mabp_quantity_select_mabp_include"}
            {$maxQuantity=$sArticle.maxpurchase}
            {if $sArticle.laststock && $sArticle.instock < $sArticle.maxpurchase}
                {$maxQuantity=$sArticle.instock}
            {/if}

            {include file="plugins/maxia_adv_block_prices/number_input.tpl" max=$maxQuantity min=$sArticle.minpurchase step=$sArticle.purchasesteps}
        {/block}
    {else}
        {$smarty.block.parent}
    {/if}
{/block}

{block name="frontend_detail_buy"}
    {* Include sum *}
    {block name="frontend_detail_buy_mabp_total_display_include"}
        {if $mabpConfig.totalDisplayVisible}
            {include file="plugins/maxia_adv_block_prices/total_display.tpl"}
        {/if}
    {/block}

    {$smarty.block.parent}

    {* Include block prices table (after buy button) *}
    {block name="frontend_detail_buy_mabp_after_buy_button_include"}
        {if $sArticle.sBlockPrices and $mabpConfig.pluginEnabled and $mabpConfig.position == 'afterBuyButton'}
            {include "plugins/maxia_adv_block_prices/block_prices.tpl" sArticle=$sArticle}
        {/if}
    {/block}
{/block}
