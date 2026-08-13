/**
 * Dokumentum fül – „Azonnali feltöltés”. A termék és a partner karb ugyanezt használja,
 * ezért él külön fájlban.
 *
 * A feltöltött fájl a config.ini path.dokumentum mappájába kerül, a válasz pedig egy
 * kész, oper=add állapotú dokumentum sor. A REKORD CSAK A KARB MENTÉSEKOR születik meg –
 * új terméknél/partnernél még nincs mihez kötni.
 */
(function ($) {
    'use strict';

    window.initDokumentumUpload = function (doktab) {
        const fileinput = $('<input type="file" style="display:none">').appendTo(doktab);

        doktab.on('click', '.js-dokuploadbutton', function (e) {
            e.preventDefault();
            if ($(this).hasClass('ui-state-disabled')) {
                return;
            }
            fileinput.val('').data('gomb', $(this)).click();
        });

        fileinput.on('change', function () {
            const file = this.files && this.files[0],
                gomb = fileinput.data('gomb');
            if (!file) {
                return;
            }
            const fd = new FormData();
            fd.append('file', file);
            gomb.addClass('ui-state-disabled');
            $.ajax({
                url: '/admin/dokumentumtar/quickupload',
                type: 'POST',
                data: fd,
                processData: false,
                contentType: false,
                dataType: 'json'
            }).always(function () {
                gomb.removeClass('ui-state-disabled');
            }).done(function (d) {
                if (!d || !d.ok) {
                    $('#dialogcenter').text((d && d.error) || 'A feltöltés nem sikerült.').dialog({
                        resizable: false,
                        modal: true,
                        buttons: {
                            'OK': function () {
                                $(this).dialog('close');
                            }
                        }
                    });
                    return;
                }
                // a válasz sor a saját „új” gombját is hozza, ezért a régit eldobjuk – ugyanaz
                // a minta, mint a getemptyrow-nál, így mindig pontosan egy „új” gomb marad
                doktab.find('.js-doknewbutton').remove();
                doktab.append(d.html);
                $('.js-doknewbutton,.js-dokdelbutton,.js-dokbrowsebutton,.js-dokopenbutton,.js-dokopen2button').button();
            }).fail(function () {
                $('#dialogcenter').text('A feltöltés nem sikerült.').dialog({
                    resizable: false,
                    modal: true,
                    buttons: {
                        'OK': function () {
                            $(this).dialog('close');
                        }
                    }
                });
            });
        });
    };
})(jQuery);
