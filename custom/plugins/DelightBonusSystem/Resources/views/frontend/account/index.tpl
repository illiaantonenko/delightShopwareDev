{extends file="parent:frontend/account/index.tpl"}

{block name="frontend_account_index_info"}
    <div data-panel-auto-resizer="true" class="account-info--container">
        <div class="account--info account--box panel has--border is--rounded">
            <h2 class="panel--title is--underline">{s name="AccountHeaderTokens"}{/s} Tokens</h2>

            <div class="panel--body is--wide">
                <p> Tokens earned: {$sUserData.additional.user.tokens} </p>
            </div>
            <div class="panel--actions is--wide">

            </div>
        </div>
    </div>
    {debug}
    {$smarty.block.parent}
{/block}