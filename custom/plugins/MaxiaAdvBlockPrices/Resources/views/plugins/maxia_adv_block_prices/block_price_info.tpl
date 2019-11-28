{block name="frontend_detail_data_block_prices_table_body_cell_price_info"}

    {assign var="savingsAbsolute" value=$blockPrice.savingsAbsolute}
    {assign var="savingsPercent" value=$blockPrice.savingsPercent}

    {capture priceInfo assign=priceInfo}

        {if $hasReferencePrice and ($mabpConfig.priceInfo === 'referenceprice' or $mabpConfig.priceInfo === 'referenceprice_only')}

            {* Base price *}
            {block name="frontend_detail_data_block_prices_table_body_cell_price_info_referenceprice"}
                <div class="mabp-block-prices--referenceprice">
                    {$blockPrice.referenceprice|currency}
                    {s name="Star" namespace="frontend/listing/box_article"}{/s} /
                    {$sArticle.referenceunit} {$sArticle.sUnit.description}
                </div>
            {/block}

        {elseif $savingsPercent > 0}
            {* Savings - percent / currency *}

            {if $mabpConfig.priceInfo != 'referenceprice_only'}

                {if $mabpConfig.priceInfo === 'savings_currency'}

                    {block name="frontend_detail_data_block_prices_table_body_cell_price_info_savings_currency"}
                        <div class="mabp-block-prices--savings-currency">
                            {s namespace="frontend/plugins/maxia_adv_block_prices" name="savings_absolute"}{/s}
                        </div>
                    {/block}

                {elseif $mabpConfig.priceInfo === 'savings_currency_percent'}

                    {block name="frontend_detail_data_block_prices_table_body_cell_price_info_savings_currency_percent"}
                        <div class="mabp-block-prices--savings-currency-percent">
                            {s namespace="frontend/plugins/maxia_adv_block_prices" name="savings_absolute_percent"}{/s}
                        </div>
                    {/block}
                {else}

                    {block name="frontend_detail_data_block_prices_table_body_cell_price_info_savings_percent"}
                        <div class="mabp-block-prices--savings-percent">
                            {s namespace="frontend/plugins/maxia_adv_block_prices" name="savings_percent"}{/s}
                        </div>
                    {/block}
                {/if}

            {/if}
        {/if}
    {/capture}

    {if trim($priceInfo)}
        <div class="mabp-block-prices--priceinfo">
            {$priceInfo}
        </div>
    {/if}
{/block}