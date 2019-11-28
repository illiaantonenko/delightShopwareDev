
{block name="frontend_detail_buy_quantity_select_maxia_number_input"}

    <div class="mabp-number-input is--{$mabpConfig.quantitySelectTheme}{if $mabpConfig.showNumberInputPopups and $mabpConfig.stylesPopupTheme == 'dark'} mabp--popup-dark{/if}"
        {block name="frontend_detail_buy_quantity_select_maxia_number_input_attributes"}
             data-mabp-number-input="true"
             data-max="{$max}"
             data-min="{$min}"
             data-step="{$step}"
             {if $mabpConfig.showNumberInputPopups}
                {s name="max_purchase_hint" namespace="frontend/plugins/maxia_adv_block_prices" assign="maxPurchaseHint"}{/s}
                {s name="min_purchase_hint" namespace="frontend/plugins/maxia_adv_block_prices" assign="minPurchaseHint"}{/s}
                data-showPopup="true"
                data-msgMaxPurchase="{$maxPurchaseHint|escape}"
                data-msgMinPurchase="{$minPurchaseHint|escape}"
             {/if}
        {/block}
    >
        {block name="frontend_detail_buy_quantity_select_maxia_number_input_minus_button"}
            <div class="mabp-number-input--button minus-button">
                <i class="icon--minus3"></i>
            </div>
        {/block}

        {block name="frontend_detail_buy_quantity_select_maxia_number_input_textfield"}
            <input type="text"
                   class="mabp-number-input--field"
                   {if $id}
                       id="{$id|escape}"
                   {else}
                       id="sQuantity"
                   {/if}
                   autocomplete="off"
                   name="sQuantity"
                   value="{if $value}{$value}{else}{$min}{/if}" />
        {/block}

        {block name="frontend_detail_buy_quantity_select_maxia_number_input_plus_button"}
            <div class="mabp-number-input--button plus-button">
                <i class="icon--plus3"></i>
            </div>
        {/block}
    </div>
{/block}