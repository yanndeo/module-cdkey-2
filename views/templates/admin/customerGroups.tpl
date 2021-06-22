{**
* PrestaShop module created by VEKIA, a guy from official PrestaShop community ;-)
*
* @author    VEKIA https://www.prestashop.com/forums/user/132608-vekia/
* @copyright 2010-2020 VEKIA
* @license   This program is not free software and you can't resell and redistribute it
*
* CONTACT WITH DEVELOPER
* support@mypresta.eu
*}

<div class="panel col-lg-12">
    <h3><i class="icon-wrench"></i> {l s='Groups of customers' mod='cdkeys'}</h3>
    <div class="alert alert-info">
        {l s='Select customer groups. Module will send cdkeys for users that are associated with at least one from selected groups.' mod='cdkeys'}
    </div>

    <div class="table-responsive-row clearfix">
        <table class="table configuration">
            <thead>
            <tr class="nodrag nodrop">
                <th class="fixed-width-xs text-center">
                    <span class="title_box">{l s='ID' mod='cdkeys'}</span>
                </th>
                <th class="">
                    <span class="title_box">{l s='Name' mod='cdkeys'}</span>
                </th>
                <th class="">
                    <span class="title_box">{l s='Select' mod='cdkeys'}</span>
                </th>
            </tr>
            </thead>
            <tbody>
            {foreach $cdkeys_customerGroups AS $cgroup}
                <tr class="pointer {if $cgroup@iteration is odd by 1}odd{/if}">
                    <td class="fixed-width-xs text-center">
                        {$cgroup.id_group}
                    </td>
                    <td class="fixed-width-xs text-left">
                        {$cgroup.name}
                    </td>
                    <td>
                        <input type="checkbox" name="cdkeys_groups[]" value="{$cgroup.id_group}" {if in_array($cgroup.id_group, $cdkeys_groups)}checked="checked"{/if}>
                    </td>
                </tr>
            {/foreach}
            </tbody>
        </table>
    </div>
</div>