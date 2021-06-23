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

{if $cdkeys != false}
    <div class="box">
        <h1 class="page-heading">{l s='Cdkeys' mod='cdkeys'}</h1>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th class="first_item">{l s='Product' mod='cdkeys'}</th>
                <th class="item">{l s='Code' mod='cdkeys'}</th>
                <th class="item">{l s='Cdkeypwd' mod='cdkeys'}</th>
            </tr>
            </thead>
            <tfoot>
            {foreach $cdkeys AS $cdkey}
                <tr class="item">
                    <td>{$cdkey.product}</td>
                    <td>{$cdkey.code}</td>
                    <td>{$cdkey.cdkeypwd}</td>---
                </tr>
            {/foreach}
            </tfoot>
        </table>
    </div>
{/if}