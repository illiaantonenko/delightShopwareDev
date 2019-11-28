{extends file="parent:frontend/detail/data.tpl"}

{* Wrap reference price (updated by the JS plugin) *}
{block name='frontend_detail_data_price_unit_reference_content'}
    {if $mabpConfig.pluginEnabled and $sArticle.sBlockPrices}

        {block name='frontend_detail_data_price_unit_reference_content_mabp_include'}
            {include file="plugins/maxia_adv_block_prices/purchase_unit.tpl"}
        {/block}

    {else}
        {$smarty.block.parent}
    {/if}
{/block}

{* Replace default block prices table *}
{block name="frontend_detail_data_block_price_include"}
    {if $mabpConfig.pluginEnabled}

        {block name="frontend_detail_data_block_price_include_mabp_current_price"}
            {if $mabpConfig.showCurrentPrice}
                {include file="plugins/maxia_adv_block_prices/current_price.tpl"}
            {/if}
        {/block}

        {block name="frontend_detail_data_block_price_include_mabp_table"}
            {if $mabpConfig.position == 'default'}
                {include "plugins/maxia_adv_block_prices/block_prices.tpl" sArticle=$sArticle}
            {/if}
        {/block}
    {else}
        {$smarty.block.parent}
    {/if}
{/block}

{* Hide tax info in some cases *}
{block name='frontend_detail_data_tax'}
    {if not $mabpConfig.totalDisplayVisible or ($mabpConfig.totalDisplayVisible and not $mabpConfig.showTaxBelowTotal)}

        {if not $mabpConfig.pluginEnabled or not $mabpConfig.showCurrentPrice or not $sArticle.sBlockPrices}

            {if not isset($maxiaVariantsTable) or not $maxiaVariantsTable.showMinimalBuyBox or not $maxiaVariantsTable.pluginEnabled}
                {$smarty.block.parent}
            {/if}
        {/if}
    {/if}
{/block}

{* Hide unit price below the table in some cases *}
{block name='frontend_detail_data_price'}
    {if not $mabpConfig.pluginEnabled or not $mabpConfig.showCurrentPrice or not $sArticle.sBlockPrices}

        {if not isset($maxiaVariantsTable) or not $maxiaVariantsTable.showMinimalBuyBox or not $maxiaVariantsTable.pluginEnabled}
            {$smarty.block.parent}
        {/if}
    {/if}
{/block}

{* Hide delivery info below the table in some cases *}
{block name="frontend_detail_data_delivery"}
    {if not $mabpConfig.pluginEnabled or not $mabpConfig.showCurrentPrice or not $sArticle.sBlockPrices}

        {if not isset($maxiaVariantsTable) or not $maxiaVariantsTable.showMinimalBuyBox or not $maxiaVariantsTable.pluginEnabled}
            {$smarty.block.parent}
        {/if}
    {/if}
{/block}
