{extends file="parent:frontend/listing/product-box/box-basic.tpl"}

{block name="frontend_listing_box_article_price_info"}
    {$smarty.block.parent}
    {if $sArticle.in_bonus_program}
        <div class="token-box">
            <div class="price">{if !empty($sArticle.cost_in_token )}Cost in tokens: {$sArticle.cost_in_token }{/if}</div>
            <div class="price">{if !empty($sArticle.token_for_purchase )}Tokens for purchase: {$sArticle.token_for_purchase }{/if}</div>
        </div>
    {/if}
{*    {debug}*}
{/block}