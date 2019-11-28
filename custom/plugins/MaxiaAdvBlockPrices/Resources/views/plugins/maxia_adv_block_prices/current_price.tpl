
{block name='plugins_maxia_adv_block_prices_current_price'}
    {$blockPrice = $sArticle.firstAvailablePrice}
    {$showPseudoPrice = $blockPrice.pseudoprice > 0 and $blockPrice.pseudoprice != $blockPrice.price}

    <div class="mabp-current-price"
         data-mabp-current-price="true"
         data-locale="{$Locale|replace:'_':'-'}"
         data-symbol="{$Shop->getCurrency()->getSymbol()}"
         data-price="{$blockPrice.price}"
         {if $showPseudoPrice}
            data-pseudoprice="{$blockPrice.pseudoprice|round:2}"
         {/if}
    >
        {block name='plugins_maxia_adv_block_prices_current_price_inner'}
            <span class="product--price price--default{if $showPseudoPrice} price--discount{/if}">
                {* Regular price *}
                {block name='plugins_maxia_adv_block_prices_current_price_default'}
                    <span class="price--content content--default">
                        <meta itemprop="price" content="{$blockPrice.price|replace:',':'.'}">
                        {if $sArticle.priceStartingFrom}
                            {s name='ListingBoxArticleStartsAt' namespace="frontend/listing/box_article"}{/s}
                        {/if}
                        <span class="mabp-price">{$blockPrice.price|currency}</span>
                        {s name="Star" namespace="frontend/listing/box_article"}{/s}
                    </span>
                {/block}

                {* Discount price *}
                {block name='plugins_maxia_adv_block_prices_current_price_pseudo'}

                    {if not $showPseudoPrice}
                        {$pseudoPrice = 0}
                        {$savingsPercent = 0}
                    {else}
                        {$pseudoPrice = $blockPrice.pseudoprice}
                        {$savingsPercent = round(($pseudoPrice - $blockPrice.price) * 100 / $pseudoPrice, 1)}
                    {/if}

                    <span class="mabp-pseudoprice-container"{if not $showPseudoPrice} style="display:none"{/if}>
                        {block name='frontend_detail_data_pseudo_price_discount_icon'}
                            <span class="price--discount-icon">
                                <i class="icon--percent2"></i>
                            </span>
                        {/block}

                        {* Discount price content *}
                        {block name='frontend_detail_data_pseudo_price_discount_content'}
                            <span class="content--discount">
                                {block name='frontend_detail_data_pseudo_price_discount_before'}
                                    {s name="priceDiscountLabel" namespace="frontend/detail/data"}{/s}
                                {/block}
                                <span class="price--line-through">
                                    <span class="mabp-pseudoprice">{$pseudoPrice|currency}</span>
                                    {s name="Star" namespace="frontend/listing/box_article"}{/s}</span>

                                {block name='frontend_detail_data_pseudo_price_discount_after'}
                                    {s name="priceDiscountInfo" namespace="frontend/detail/data"}{/s}
                                {/block}

                                {* Percentage discount *}
                                {block name='frontend_detail_data_pseudo_price_discount_content_percentage'}
                                    <span class="price--discount-percentage">
                                        (<span class="mabp-pseudoprice-percent">{$savingsPercent|number}</span>% {s name="DetailDataInfoSavePercent" namespace="frontend/detail/data"}{/s})
                                    </span>
                                {/block}
                            </span>
                        {/block}
                    </span>
                {/block}
            </span>

            {* Unit price *}
            {if $sArticle.purchaseunit}
                {block name='plugins_maxia_adv_block_prices_current_price_unit'}
                    <div class='product--price price--unit'>

                        {* Unit price label *}
                        {block name='frontend_detail_data_price_unit_label'}
                            <span class="price--label label--purchase-unit">
                                {s name="DetailDataInfoContent" namespace="frontend/detail/data"}{/s}
                            </span>
                        {/block}

                        {* Unit price content *}
                        {block name='frontend_detail_data_price_unit_content'}
                            {$sArticle.purchaseunit} {$sArticle.sUnit.description}
                        {/block}

                        {* Unit price is based on a reference unit *}
                        {if $sArticle.purchaseunit && $sArticle.referenceunit && $sArticle.purchaseunit != $sArticle.referenceunit}
                            {* Reference unit price content *}
                            {block name='frontend_detail_data_price_unit_reference_content_mabp_include'}
                                {include file="plugins/maxia_adv_block_prices/purchase_unit.tpl"}
                            {/block}
                        {/if}
                    </div>
                {/block}
            {/if}

            {* Tax information *}
            {block name='plugins_maxia_adv_block_prices_current_price_tax'}
                {if not $mabpConfig.showTaxBelowTotal}
                    <p class="product--tax" data-content="" data-modalbox="true" data-targetSelector="a" data-mode="ajax">
                        {s name="DetailDataPriceInfo" namespace="frontend/detail/data"}{/s}
                    </p>
                {/if}
            {/block}

            {* Delivery informations *}
            {block name='plugins_maxia_adv_block_prices_current_price_delivery'}
                {if ($sArticle.sConfiguratorSettings.type != 1 && $sArticle.sConfiguratorSettings.type != 2) || $activeConfiguratorSelection == true}
                    {include file="frontend/plugins/index/delivery_informations.tpl" sArticle=$sArticle}
                {/if}
            {/block}
        {/block}
    </div>
{/block}