{extends file="parent:frontend/listing/product-box/product-badges.tpl"}

{* Discount badge *}
{block name='frontend_listing_box_article_badges_inner'}
    {$smarty.block.parent}
    {if $sArticle.in_bonus_program}
        <div class="product--badge bonus-token-badge">
            BONUS
        </div>
    {/if}
{/block}