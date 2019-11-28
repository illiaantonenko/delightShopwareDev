{extends file="parent:frontend/index/index.tpl"}

{block name="frontend_index_content"}
    <h1>Revan Product List</h1>

    <div class="listing--wrapper visible--xl visible--l visible--m visible--s visible--xs" id="lazy">
        <div class="infinite--actions"></div>
        <div class="listing--container">
            <div class="listing"
                 data-ajax-wishlist="true"
                 data-compare-ajax="true"
                    {if $theme.infiniteScrolling}
                data-infinite-scrolling="true"
                data-loadPreviousSnippet="{s name="ListingActionsLoadPrevious"}{/s}"
                data-loadMoreSnippet="{s name="ListingActionsLoadMore"}{/s}"
                data-categoryId="{$sCategoryContent.id}"
                data-pages="{$pages}"
                data-threshold="{$theme.infiniteThreshold}"
                data-pageShortParameter="{$shortParameters.sPage}"
                    {/if}>
            {$url = {url controller=checkout action=addArticle} }
            {foreach $items as $item}
                {include file='frontend/productlist/list.tpl'}
            {/foreach}
                </div>
        </div>
        <div class="infinite--actions"></div>
    </div>
{/block}