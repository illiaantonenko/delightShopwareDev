{extends file="parent:frontend/detail/content/buy_container.tpl"}

{block name="frontend_detail_buy_laststock"}
    {$smarty.block.parent}
    {if $sArticle.in_bonus_program}
        <div class="token-box">
            <div class="price">{if !empty($sArticle.cost_in_token )}Cost in tokens: {$sArticle.cost_in_token }{/if}</div>
            <div class="price">{if !empty($sArticle.token_for_purchase )}Tokens for purchase: {$sArticle.token_for_purchase }{/if}</div>
        </div>
    {/if}
{/block}

