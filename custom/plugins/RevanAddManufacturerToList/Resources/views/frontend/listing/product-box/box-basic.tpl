{extends file="parent:frontend/listing/product-box/box-basic.tpl"}

{block name="frontend_listing_box_article_description"}
    {$smarty.block.parent}
    <div class="manufacturer-box">
        <span class="slogan">{if isset($sArticle.supplierName)}Manufacturer: {$sArticle.supplierName}{/if}</span>
    </div>
{/block}