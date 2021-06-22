/*
 * PrestaShop module created by VEKIA, a guy from official PrestaShop community ;-)
 *
 * @author    VEKIA https://www.prestashop.com/forums/user/132608-vekia/
 * @copyright 2010-2020 VEKIA
 * @license   This program is not free software and you can't resell and redistribute it
 *
 * CONTACT WITH DEVELOPER http://mypresta.eu
 * support@mypresta.eu
 */

$(document).ready(function () {
    if (typeof pagination !== 'undefined') {
        if ($('.cdkeyslist-myaccount').length > 0) {
            pagination();
        }
    }
});

function pagination() {
    if (typeof cdkey_pagination !== 'undefined') {
        if (cdkey_pagination == 1) {
            if (cdkey_pagination_nb == "" || cdkey_pagination_nb <= 0 || cdkey_pagination_nb == null) {
                cdkey_pagination_nb = 10;
            }
            var req_num_row = cdkey_pagination_nb;
            var $tr = $('.cdkeyslist-myaccount tr.cdkeys_table_tr');
            var total_num_row = $tr.length;
            var num_pages = 0;

            if (total_num_row % req_num_row == 0) {
                num_pages = total_num_row / req_num_row;
            }
            if (total_num_row % req_num_row >= 1) {
                num_pages = total_num_row / req_num_row;
                num_pages++;
                num_pages = Math.floor(num_pages++);
            }
            for (var i = 1; i <= num_pages; i++) {
                $('.cdkeysPagination').append("<li><a href='#'>" + i + "</a></li>");
            }

            $('.cdkeysPagination li:first-child').addClass('active current');
            $tr.each(function (i) {
                $(this).hide();
                if (i + 1 <= req_num_row) {
                    $tr.eq(i).show();
                }

            });

            $('.cdkeysPagination a').click(function (e) {
                $('.cdkeysPagination a').parent().removeClass('active current');
                $(this).parent().addClass('active current');
                e.preventDefault();
                $tr.hide();
                var page = $(this).text();
                var temp = page - 1;
                var start = temp * req_num_row;
                //alert(start);

                for (var i = 0; i < req_num_row; i++) {
                    $tr.eq(start + i).show();
                }
            });
        }
    }
}
