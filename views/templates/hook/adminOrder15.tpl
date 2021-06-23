{if $cdkeys != false}
    <fieldset style="margin-top:10px;">
        <legend>{l s='Cdkeys' mod='cdkeys'} <span style="display:inline-block; border-radius: 20px; background:#FFF; padding:2px; border:1px solid #c0c0c0;">{count($cdkeys)}</span></legend>
        <table class="table table-bordered" style="width:100%">
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
                    <td>{$cdkey.cdkeypwd}</td>
                </tr>
            {/foreach}
            </tfoot>
        </table>
    </fieldset>
{/if}