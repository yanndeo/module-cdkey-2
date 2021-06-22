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

<script>
    function combination_field() {
        if ($('#for_attr option:selected').val() == 0) {
            $('#id_product_attribute').parent().parent().hide();
        } else {
            $('#id_product_attribute').parent().parent().show();
        }
    }

    function load_product_combinations(id_product) {
        $('.get_combinations i').addClass('icon-spin');
        if (id_product != 0) {
            $.ajax({
                type: "POST",
                url: '{Context::getContext()->link->getAdminLink('AdminCdKeysGroups', true)}&ajax=1&combinations=1&idp='+id_product
            }).done(function(data){
                $('.get_combinations i').removeClass('icon-spin');
                $('.combinations_result').html(data);
            }).fail(function(){
                $('.get_combinations i').removeClass('icon-spin');
            });
        } else {
            $('.get_combinations i').removeClass('icon-spin');
            $('.combinations_result').html("{l s='Define product first' mod='cdkeys'}");
        }
    }

    $(document).ready(function () {
        $('.get_combinations .btn').click(function () {
            load_product_combinations(+$('#id_product').val());
        });

        $('#for_attr').change(function () {
            combination_field();
        });
        combination_field();
    });
</script>