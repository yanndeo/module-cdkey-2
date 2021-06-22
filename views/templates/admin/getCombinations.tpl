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

<div class="col-lg-12" id="id_product_attribute">
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-lg-3 get_combinations">
            <div class="btn btn-default col-lg-12">
                <i class="icon-refresh"></i>
                {l s='Get combinations' mod='cdkeys'}
            </div>
            <div class="combinations_result" style="margin-top:10px; clear:both;">
            </div>
        </div>
        <div class="col-lg-9">
            <input type="text" name="id_product_attribute" value="{$id_product_attribute}" class="id_product_attribute" required="required">
            <p class="help-block">{l s='Select combination by clicking on "get combinations" button or define the combination id' mod='cdkeys'}</p>
        </div>
    </div>
</div>