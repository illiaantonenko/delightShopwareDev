{extends file="parent:frontend/detail/content/buy_container.tpl"}

{block name="frontend_detail_buy_laststock"}
    {$smarty.block.parent}
    <div class="coming-date-box">
        <span class="slogan">{if !$sArticle.isAvailable && isset($sArticle.coming_date)}Will come: {$sArticle.coming_date}{/if}</span><br>
    </div>
{/block}

