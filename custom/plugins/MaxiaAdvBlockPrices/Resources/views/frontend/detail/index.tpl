{extends file="parent:frontend/detail/index.tpl"}

{* Include sum (this block used for compatiblity with SwkweSetBuy only) *}
{block name="frontend_detail_configurator_wrapper"}
    {$smarty.block.parent}

    {block name="frontend_detail_configurator_wrapper_total_display_include_swkesetbuy"}
        {if $mabpConfig.pluginEnabled and ( $sArticle.swkwe_is_set_article === 'true' || $sArticle.swkwe_is_set_article === "1" ) }

            {if $mabpConfig.totalDisplayVisible}
                {include file="plugins/maxia_adv_block_prices/total_display.tpl"}
            {/if}

        {/if}
    {/block}
{/block}

{* Add meta data *}
{block name='frontend_detail_index_after_data'}

    {block name='frontend_detail_index_after_data_mabp_metadata_include'}
        {if $sArticle.sBlockPrices and not isset($maxiaTaxSwitchIsNet)}
            {include file="plugins/maxia_adv_block_prices/metadata.tpl"}
        {/if}
    {/block}

    {$smarty.block.parent}
{/block}