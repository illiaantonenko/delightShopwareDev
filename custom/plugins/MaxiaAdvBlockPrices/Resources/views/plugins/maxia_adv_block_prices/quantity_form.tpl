{block name="frontend_checkout_cart_item_quantity_selection_mabp"}

    <div class="mabp-quantity-form--wrapper"
         data-mabp-quantity-form="true"
         data-submitMethod="{$mabpConfig.cartNumberInputSubmitMethod}"
         data-timeout="{$mabpConfig.cartNumberInputSubmitTimeout}"
    >
        {block name="frontend_checkout_cart_item_quantity_selection_mabp_form"}
            <form name="basket_change_quantity{$sBasketItem.id}"
                  class="mabp-quantity-form"
                  method="post"
                  action="{url action='changeQuantity' sTargetAction=$sTargetAction}"
            >
                {block name="frontend_checkout_cart_item_quantity_selection_mabp_inner"}

                    {block name="frontend_checkout_cart_item_quantity_selection_mabp_input_include"}
                        {include file="plugins/maxia_adv_block_prices/number_input.tpl"
                                 value=$sBasketItem.quantity
                                 min=$sBasketItem.minpurchase
                                 max=$sBasketItem.maxpurchase
                                 step=$sBasketItem.purchasesteps
                                 id="mabpQty-{$sBasketItem.id}"
                        }
                    {/block}

                    {block name="frontend_checkout_cart_item_quantity_selection_mabp_submit"}
                        <button type="submit" class="btn btn--change-quantity is--primary is--icon-center">
                            <i class="icon--check"></i>
                        </button>
                    {/block}

                    {block name="frontend_checkout_cart_item_quantity_selection_mabp_itemID"}
                        <input type="hidden" name="sArticle" value="{$sBasketItem.id}" />
                    {/block}

                {/block}
            </form>
        {/block}
    </div>
{/block}