{block name="frontend_revan_product_list_list"}

                <div class="product--box" id="item-{$item['id']}">
                    <div class="product--info">
                        <div class="product--title">
                            {$item['name']}
                        </div>
                        <div class="details">
                            {foreach $item['details'] as $detail}
                                <form name="sAddToBasket"
                                      method="post"
                                      action="{$url}"
                                      class="buybox--form"
                                      data-add-article="true"
                                      data-eventName="submit"
                                        {if $theme.offcanvasCart}
                                    data-showModal="false"
                                    data-addArticleUrl="{url controller=checkout action=ajaxAddArticleCart}"
                                        {/if}>

                                    {block name="frontend_listing_product_box_button_buy_order_number"}
                                        <input type="hidden" name="sAdd" value="{$detail['number']}"/>
                                    {/block}

                                    {block name="frontend_listing_product_box_button_buy_button"}
                                        <button class="buybox--button block btn is--primary is--icon-right is--center is--large"
                                                aria-label="{s namespace="frontend/listing/box_article" name="ListingBuyActionAddText"}{/s}">
                                            {block name="frontend_listing_product_box_button_buy_button_text"}
                                                {$detail['number']}
                                                <i class="icon--basket"></i>
                                                <i class="icon--arrow-right"></i>
                                            {/block}
                                        </button>
                                    {/block}
                                </form>
                            {/foreach}
                        </div>
                    </div>
                </div>
{/block}