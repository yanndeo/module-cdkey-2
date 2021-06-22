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

{block name='page_title'}
    {l s='CdKeys that you bought' mod='cdkeys'}
{/block}


{block name="page_content"}

    <div class="card">
        <div class="card-block">

                {if $cdkeys_count > 0}
                    {foreach from=$cdkeys key=id item=cdkey}
                       {$cdkey.code nofilter}
                    {/foreach}
                    {else}
                {/if}
        </div>
    </div>
{/block}

