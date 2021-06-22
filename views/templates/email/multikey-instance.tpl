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

<table class="multikey">
    <tr>
        <th>{l s='Product' mod='cdkeys'}</th>
        <th>{l s='CdKey' mod='cdkeys'}</th>
        <th>{l s='Details' mod='cdkeys'}</th>
    </tr>
    {foreach $multikey AS $k=>$cd}
        <tr>
            <td>
                <strong>{$cd.product}</strong><br/>
                {$cd.product_description_short nofilter}<br/>
                <h4>{l s='Additional informations' mod='cdkeys'}</h4>
                {$cd.cdkeys_group_desc nofilter}
            </td>
            <td><h3>{$cd.code}</h3></td>
            <td><a href="{$cd.group_url}">{$cd.group_url}</a></td>
        </tr>
    {/foreach}
</table>
<style>
    {literal}
    .multikey {width:100%; border-collapse: collapse;}
    .multikey td {vertical-align:top; border:1px solid #cecece; background:#FFF; padding:5px;}
    .multikey th {vertical-align:middle; border-collapse: collapse; border:1px solid #000; background:#000; color:#FFF;}
    {/literal}
</style>