
{block name="plugins_maxia_adv_block_prices_metadata"}
    <meta itemprop="priceCurrency" content="{$Shop->getCurrency()->getCurrency()}" />

    {$lowPrice = $sArticle.sBlockPrices[ count($sArticle.sBlockPrices)-1 ].price}
    {$highPrice = $sArticle.sBlockPrices[0].price}

    <div itemprop="PriceSpecification" itemscope itemtype="http://schema.org/PriceSpecification">
        <meta itemprop="priceCurrency" content="{$Shop->getCurrency()->getCurrency()}" />
        <meta itemprop="price" content="{$lowPrice}" />
        <meta itemprop="minPrice" content="{$lowPrice}" />
        <meta itemprop="maxPrice" content="{$highPrice}" />
        <meta itemprop="valueAddedTaxIncluded" content="true" />
    </div>
{/block}