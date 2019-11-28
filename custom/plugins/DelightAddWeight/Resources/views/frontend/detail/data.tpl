{extends file="parent:frontend/detail/data.tpl"}

{block name='frontend_detail_data_tax'}
    {if $sArticle.weight}
        Weight: {$sArticle.weight}
    {/if}
    {$smarty.block.parent}
{/block}