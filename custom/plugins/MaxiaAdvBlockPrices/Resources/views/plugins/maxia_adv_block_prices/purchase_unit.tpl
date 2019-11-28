
{* This price is updated by the mabpBlockPrices plugin *}
{block name='frontend_detail_data_price_unit_reference_content_mabp'}
    (<span class="mabp-referenceprice">{$sArticle.referenceprice|currency}</span>
    {s name="Star" namespace="frontend/listing/box_article"}{/s}
    / {$sArticle.referenceunit} {$sArticle.sUnit.description})
{/block}