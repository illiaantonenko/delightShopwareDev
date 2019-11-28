
{block name="frontend_detail_buy_maxia_total_display"}
    <div class="mabp-total-display {if !$sArticle.sBlockPrices} no-block-prices{/if}{if $mabpConfig.showTotalAsPopup} mabp--popup{/if}{if $mabpConfig.stylesPopupTheme == 'dark'} mabp--popup-dark{/if}"
        {block name="frontend_detail_buy_maxia_total_display_attributes"}
             data-mabp-total-display="true"
             data-initialPrice="{$sArticle.price|replace:',':'.'|round:2}"
             data-initialQuantity="{$sArticle.minpurchase}"
             data-locale="{$Locale|replace:'_':'-'}"
             {if $mabpConfig.showTotalAsPopup}
                 data-showPopup="true"
                 x-placement="bottom"
                 role="tooltip"
             {/if}
        {/block}
    >
        {block name="frontend_detail_buy_maxia_total_display_inner"}

            {block name="frontend_detail_buy_maxia_total_display_arrow"}
                {if $mabpConfig.showTotalAsPopup}
                    <span class="mabp--popup-arrow" x-arrow></span>
                {/if}
            {/block}

            {block name="frontend_detail_buy_maxia_total_display_sum"}
                <div class="mabp-total-display--sum">
                    {block name="frontend_detail_buy_maxia_total_display_sum_label"}
                        <span class="mabp-total-display--sum-label">
                        {s namespace="frontend/plugins/maxia_adv_block_prices" name="total_label"}{/s}:
                    </span>
                    {/block}

                    {block name="frontend_detail_buy_maxia_total_display_sum_value"}
                        <span class="mabp-total-display--sum-value"></span>
                        {$Shop->getCurrency()->getSymbol()}
                        {s name="Star" namespace="frontend/listing/box_article"}{/s}
                    {/block}
                </div>
            {/block}

            {if $sArticle.sBlockPrices and $mabpConfig.showTotalSavings}
                {block name="frontend_detail_buy_maxia_total_display_savings"}

                    <div class="mabp-total-display--savings" style="display:none">
                        <span class="mabp-total-display--savings-label">{s namespace="frontend/plugins/maxia_adv_block_prices" name="total_savings"}{/s}:</span>
                        <span class="mabp-total-display--savings-value"></span>
                        {$Shop->getCurrency()->getSymbol()}
                        {s name="Star" namespace="frontend/listing/box_article"}{/s}
                    </div>

                {/block}
            {/if}

        {/block}
    </div>
{/block}

{block name="frontend_detail_buy_maxia_total_display_tax"}
    {if $mabpConfig.showTaxBelowTotal}
        <p class="product--tax" data-content="" data-modalbox="true" data-targetSelector="a" data-mode="ajax">
            {s name="DetailDataPriceInfo" namespace="frontend/detail/data"}{/s}
        </p>
    {/if}
{/block}
