{*
 * PrestaShop module created by VEKIA, a guy from official PrestaShop community ;-)
 *
 * @author    VEKIA https://www.prestashop.com/forums/user/132608-vekia/
 * @copyright 2010-2020 VEKIA
 * @license   This program is not free software and you can't resell and redistribute it
 *
 * Email Templates Manager
 * version 1.6.1
 *
 * CONTACT WITH DEVELOPER http://mypresta.eu
 * support@mypresta.eu
*}

{foreach $etm_select as $select => $value}
    <option value="{$value.name}">{$value.name}</option>
{/foreach}