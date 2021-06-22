{**
 * PrestaShop module created by VEKIA, a guy from official PrestaShop community ;-)
 *
 * @author    VEKIA https://www.prestashop.com/forums/user/132608-vekia/
 * @copyright 2010-2020 VEKIA
 * @license   This program is not free software and you can't resell and redistribute it
 *
 * CONTACT WITH DEVELOPER http://mypresta.eu
 * support@mypresta.eu
 *}
{extends file='customer/page.tpl'}
{block name='page_title'}
    {l s='CdKeys that you bought' mod='cdkeys'}
{/block}

{block name="page_content"}
    <div class="card">
        <div class="card-block">
            <p class="title_block"><a href="{Context::getContext()->link->getPageLink('my-account')}">&lsaquo; {l s='Back to my-account' mod='cdkeys'}</a></p>
            <table id="order-list" class="table table-bordered footab default footable-loaded footable cdkeyslist-myaccount">
                <thead>
                <tr>
                    <th>{l s='#' mod='cdkeys'}</th>
                    <th class="item"> {l s='Order date:' mod='cdkeys'}</th>
                    <th class="first_item">{l s='Code' mod='cdkeys'}</th>
                    <th class="item">{l s='Product:' mod='cdkeys'}</th>
                    <th class="item"> {l s='Order reference:' mod='cdkeys'}</th>
                </tr>
                </thead>
                <tbody>
                {if $cdkeys_count > 0}
                    {foreach from=$cdkeys key=id item=cdkey}
                        <tr class="first_item cdkeys_table_tr">
                            <td>{$cdkey@iteration}</td>
                            <td>{cdkeysmykeysModuleFrontController::getOrderDate($cdkey.id_order)}</td>
                            <td>{$cdkey.code|escape:'htmlall':'UTF-8'}</td>----
                            <td>{$cdkey.product|escape:'htmlall':'UTF-8'}</td>
                            <td>{cdkeysmykeysModuleFrontController::getOrderReference($cdkey.id_order)}</td>
                        </tr>
                    {/foreach}
                {else}
                    <tr>
                        <td colspan="5">
                            <div class="alert alert-info">
                                {l s='You do not have any cdkey currently' mod='cdkeys'}
                            </div>
                        </td>
                    </tr>
                {/if}
                </tbody>
            </table>
        </div>
    </div>
    {if $cdkeys_count > 0 && $cdkeys_count > (int)Configuration::get('cdkey_pagination_nb') }
        <div class="pagination">
            <div class="cdkeysPagination pagination page-list text-xs-center center"></div>
        </div>
    {/if}

{/block}