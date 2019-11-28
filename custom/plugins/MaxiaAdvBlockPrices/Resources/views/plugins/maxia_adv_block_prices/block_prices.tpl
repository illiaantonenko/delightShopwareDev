{extends file="parent:frontend/detail/block_price.tpl"}

{block name='frontend_detail_data_block_prices_start'}
    {$hasReferencePrice = ($sArticle.referenceprice > 0 && $sArticle.referenceunit)}

    {block name="frontend_detail_data_block_prices_table"}
        <div class="mabp-block-prices--container block-price--{$sArticle.ordernumber} position-{$mabpConfig.position}{strip}
            {if $hidden && !$sArticle.selected} is--hidden{/if}
            {if $hasReferencePrice} has-referenceprice{/if}
            {if $mabpConfig.showTransparency} has-transparency{/if}
            {if $mabpConfig.scrollable} is--scrollable{/if}
            {if $mabpConfig.stylesScrollFadeActive} has--scroll-fade{/if}
            {if $mabpConfig.highlightPrice} has--active-indicator{/if}
            {block name="frontend_detail_data_block_prices_table_classes"}{/block}
        {/strip}"
             {if !($hidden && !$sArticle.selected)}
                 {block name="frontend_detail_data_block_prices_table_attributes"}
                     data-mabp-block-prices="true"
                     data-locale="{$Locale|replace:'_':'-'}"
                     data-symbol="{$Shop->getCurrency()->getSymbol()}"
                     data-clickable="{if $mabpConfig.blockPricesClickable}true{else}false{/if}"
                     data-unavailableText="{s namespace="frontend/plugins/maxia_adv_block_prices" name="is_unavailable"}Not available{/s}"
                     data-maxPurchase="{$sArticle.maxpurchase}"
                     data-minPurchase="{$sArticle.minpurchase}"
                     {if $mabpConfig.scrollable} data-maxHeight="{$mabpConfig.stylesMaxHeight}"{/if}
                 {/block}
             {/if}
        >
            {block name="frontend_detail_data_block_prices_table_head"}
                <div class="mabp-block-prices--heading">
                    <div class="mapd-block-prices--heading-quantity">
                        {s namespace="frontend/detail/data" name="DetailDataColumnQuantity"}{/s}
                    </div>
                    <div class="mapd-block-prices--heading-price">
                        {s namespace="frontend/detail/data" name="DetailDataColumnPrice"}{/s}
                    </div>
                </div>
            {/block}

            {block name="frontend_detail_data_block_prices_rows"}
                <div class="mabp-block-prices--rows">
                    {foreach $sArticle.sBlockPrices as $blockPrice}

                        {block name="frontend_detail_data_block_prices"}
                            <div class="mabp-block-prices--row{if !$blockPrice.isAvailable} is--unavailable{/if}{if $blockPrice@index === 0} js--selected{/if}"
                                 itemprop="offers"
                                 itemscope
                                 itemtype="http://schema.org/Offer"
                                 data-from="{$blockPrice.from}"
                                 data-to="{$blockPrice.to}"
                                 data-price="{$blockPrice.price|round:2}"
                                 data-referenceprice="{$blockPrice.referenceprice|round:2}"
                                 {if $blockPrice.pseudoprice > 0 and $blockPrice.pseudoprice != $blockPrice.price}
                                     data-pseudoprice="{$blockPrice.pseudoprice|round:2}"
                                 {/if}
                                 {if (!$blockPrice.isAvailable)}
                                     title="{s namespace="frontend/plugins/maxia_adv_block_prices" name="is_unavailable"}{/s}"
                                 {/if}
                            >
                                {block name="frontend_detail_data_block_prices_table_body_row"}
                                    {block name="frontend_detail_data_block_prices_table_body_cell_quantity"}
                                        <div class="mabp-block-prices--cell is--quantity-cell">

                                            {block name="frontend_detail_data_block_prices_table_body_meta"}
                                                <meta itemprop="priceCurrency" content="{$Shop->getCurrency()->getCurrency()}" />
                                                <meta itemprop="price" content="{$blockPrice.price}" />
                                                <link itemprop="availability" href="http://schema.org/InStock" />
                                            {/block}

                                            {* From - to *}
                                            {if $mabpConfig.showUntil and $blockPrice.from == 1}
                                                {block name="frontend_detail_data_block_prices_table_body_cell_quantity_until"}
                                                    <span class="mabp-block-prices--from">
                                                        {s namespace="frontend/detail/data" name="DetailDataInfoUntil"}{/s}
                                                    </span>
                                                    <span class="mabp-block-prices--quantity-number">
                                                        {$blockPrice.to}
                                                    </span>
                                                {/block}
                                            {else}
                                                {block name="frontend_detail_data_block_prices_table_body_cell_quantity_until_from"}
                                                    <span class="mabp-block-prices--from">
                                                        {s namespace="frontend/detail/data" name="DetailDataInfoFrom"}{/s}
                                                    </span>
                                                    <span class="mabp-block-prices--quantity-number">
                                                        {$blockPrice.from}
                                                    </span>
                                                {/block}
                                            {/if}

                                            {block name="frontend_detail_data_block_prices_table_body_cell_quantity_until_packunit"}
                                                {if $mabpConfig.showPackUnit and $sArticle.packunit}
                                                    <span class="mabp-block-prices--purchase-unit">
                                                        {$sArticle.packunit}
                                                    </span>
                                                {/if}
                                            {/block}
                                        </div>
                                    {/block}

                                    {block name="frontend_detail_data_block_prices_table_body_cell_price"}

                                        <div class="mabp-block-prices--cell is--prices-cell">
                                            {$showPseudoprice = $mabpConfig.showPseudoprice and $blockPrice.pseudoprice > 0 and $blockPrice.pseudoprice != $blockPrice.price}

                                            {block name="frontend_detail_data_block_prices_table_body_cell_price_price"}
                                                <div class="mabp-block-prices--price{if $mabpConfig.showPseudoprice and $blockPrice.pseudoprice > 0} price--pseudo{/if}">

                                                    {block name='frontend_detail_data_block_prices_table_body_cell_pseudoprice_content'}
                                                        {if $showPseudoprice}
                                                            <span class="pseudoprice-original price--line-through">
                                                                {$blockPrice.pseudoprice|currency} {s name="Star" namespace="frontend/listing/box_article"}{/s}
                                                            </span>
                                                        {/if}
                                                    {/block}

                                                    {block name='frontend_detail_data_block_prices_table_body_cell_price_content'}
                                                        {$blockPrice.price|currency} {s name="Star" namespace="frontend/listing/box_article"}{/s}
                                                    {/block}

                                                    {if $showPseudoprice}
                                                        {block name='frontend_detail_data_block_prices_table_body_cell_price_discount_icon'}
                                                            <span class="price--discount-icon">
                                                                <i class="icon--percent2"></i>
                                                            </span>
                                                        {/block}
                                                    {/if}
                                                </div>
                                            {/block}

                                            {block name="frontend_detail_data_block_prices_table_body_cell_price_info_include"}
                                                {if $mabpConfig.priceInfo != 'hidden'}
                                                    {include file="plugins/maxia_adv_block_prices/block_price_info.tpl"}
                                                {/if}
                                            {/block}
                                        </div>

                                        {block name='frontend_detail_data_block_prices_table_body_cell_discount_badge'}
                                            {if $mabpConfig.discountAsBadge and $blockPrice.savingsPercent}
                                                <div class="mabp-block-prices--cell is--discount-cell">
                                                    <div class="mabp-block-prices--discount">
                                                        <span class="mabp-block-prices--discount-percent">-{$blockPrice.savingsPercent}</span> %
                                                    </div>
                                                </div>
                                            {/if}
                                        {/block}
                                    {/block}
                                {/block}
                            </div>
                        {/block}

                    {/foreach}
                </div>
            {/block}

        </div>
    {/block}

{/block}
