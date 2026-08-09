function pleaseWait(msg) {
    if (typeof (msg) !== 'string') {
        msg = 'Kérem várjon...';
    }
    $.blockUI({
        message: msg,
        css: {
            border: 'none',
            padding: '15px',
            backgroundColor: '#000',
            '-webkit-border-radius': '10px',
            '-moz-border-radius': '10px',
            opacity: .5,
            color: '#fff'
        }
    });
}

function messagecenterclick(e) {
    e.preventDefault();
    $(this)
        .slideToggle('slow', function () {
            $(this).removeClass('matt-messagecenter ui-widget ui-state-highlight');
            $('#termekkarb').hide();
        });
}

function messagecenterclickonerror(e) {
    e.preventDefault();
    $(this)
        .slideToggle('slow', function () {
            $(this).removeClass('matt-messagecenter ui-widget ui-state-error');
        });
}

$(document).ready(
    function () {

        var msgcenter = $('#messagecenter').hide(),
            dialogcenter = $('#dialogcenter');

        // Bizonylatra ugró ikon a #dialogcenter párbeszédekben (pl. kiegyenlítetlen
        // bizonylat választó). A sorra kattintás ott kijelölést jelent, és a sor-kezelő
        // preventDefault()-ot hív – ami elnyelné a link navigációját. Ugyanezen a
        // delegálási gyökéren regisztrálunk, így a mélyebb találat (az ikon) előbb fut:
        // a stopPropagation() megakadályozza a kijelölést, az ablakot pedig magunk
        // nyitjuk meg, hogy a kezelők sorrendjétől függetlenül biztos működjön.
        dialogcenter.on('click', '.js-bizlink', function (e) {
            e.preventDefault();
            e.stopPropagation();
            window.open($(this).attr('href'));
        });

        $(document)
            .ajaxStart(pleaseWait)
            .ajaxStop($.unblockUI)
            .ajaxError(function (e, xhr, settings, exception) {
                alert('error in: ' + settings.url + ' \n' + 'error:\n' + exception);
            })
            // A párbeszédek tartalmát négy különböző oldal tölti be ajaxszal, ezért a
            // gomb-megjelenést itt, központilag adjuk rá – a beszúrt html már a helyén
            // van, mire az ajaxComplete lefut. A :not(.ui-button) az ismételt
            // inicializálást kerüli el, ha ugyanaz a párbeszéd újra megnyílik.
            .ajaxComplete(function () {
                $('#dialogcenter .js-bizlink:not(.ui-button)').button();
            });
        // A bal oldali menü menücsoportjai: a fejlécre kattintva nyílnak/záródnak.
        // A kezdeti állapotot a sablon rendereli (base.tpl), a változást dolgozónként
        // mentjük (dolgozoparameterek tábla). A mentés global:false, hogy a globális
        // ajaxStart/ajaxStop ne villantsa fel a "Kérem várjon..." réteget.
        $('.js-menucsoporttoggle').on('click', function (e) {
            e.preventDefault();
            var titlebar = $(this),
                mcsid = titlebar.data('mcsid'),
                csoport = $('.js-menucsoport[data-mcsid="' + mcsid + '"]'),
                nyitva = !csoport.is(':visible');
            titlebar.children('.menu-titlebar-icon')
                .toggleClass('ui-icon-circle-triangle-n', nyitva)
                .toggleClass('ui-icon-circle-triangle-s', !nyitva);
            csoport.slideToggle(200);
            $.ajax({
                url: '/admin/setmenucsoportnyitva',
                type: 'POST',
                global: false,
                data: {mcsid: mcsid, value: nyitva ? 1 : 0}
            });
        });
        $('#ThemeSelect').change(function (e) {
            $.ajax({
                url: '/admin/setuitheme',
                data: {uitheme: this.options[this.selectedIndex].value},
                success: function (data) {
                    window.location.reload();
                }
            });
        });
        $('.js-regeneratekarkod').on('click', function (e) {
            e.preventDefault();
            $.ajax({
                url: '/admin/regeneratekarkod'
            });
        });
        $('.js-regeneratemenukarkod').on('click', function (e) {
            e.preventDefault();
            $.ajax({
                url: '/admin/regeneratemenukarkod'
            });
        });
        $('.js-orarendprint').each(function () {
            $(this).attr('target', '_blank');
        });

        var $arfdatumedit = $('#ArfolyamDatumEdit');
        if ($arfdatumedit) {
            mkwcomp.datumEdit.init($arfdatumedit);
            $('.js-arfolyamdownload').on('click', function (e) {
                e.preventDefault();
                var arfdatum = $arfdatumedit.datepicker('getDate');
                arfdatum = arfdatum.getFullYear() + '.' + (arfdatum.getMonth() + 1) + '.' + arfdatum.getDate();
                $.ajax({
                    url: '/admin/arfolyam/download',
                    type: 'POST',
                    data: {
                        datum: arfdatum
                    },
                    success: function () {
                        dialogcenter.html('Az árfolyamok letöltése sikerült.').dialog({
                            resizable: false,
                            height: 140,
                            modal: true,
                            buttons: {
                                'OK': function () {
                                    $(this).dialog('close');
                                }
                            }
                        });
                    }
                })
            });
        }

        const $napijelentesdatumedit = $('#NapijelentesDatumEdit'),
            $napijelentesdatumigedit = $('#NapijelentesDatumigEdit');
        if ($napijelentesdatumedit.length && $napijelentesdatumigedit.length) {
            mkwcomp.datumEdit.init($napijelentesdatumedit);
            mkwcomp.datumEdit.init($napijelentesdatumigedit);
            const loadNapijelentes = function () {
                $.ajax({
                    url: '/admin/napijelentes',
                    type: 'POST',
                    data: {
                        datum: mkwcomp.datumEdit.getDate($napijelentesdatumedit),
                        datumig: mkwcomp.datumEdit.getDate($napijelentesdatumigedit)
                    },
                    success: function (data) {
                        $('.js-napijelentesbody').replaceWith(data);
                    }
                })
            };
            $('.js-napijelentes').on('click', function (e) {
                e.preventDefault();
                loadNapijelentes();
            });
            loadNapijelentes();
        }

        const $napijelentes2datumedit = $('#Napijelentes2DatumEdit'),
            $napijelentes2datumigedit = $('#Napijelentes2DatumigEdit');
        if ($napijelentes2datumedit.length && $napijelentes2datumigedit.length) {
            mkwcomp.datumEdit.init($napijelentes2datumedit);
            mkwcomp.datumEdit.init($napijelentes2datumigedit);
            const loadNapijelentes2 = function () {
                $.ajax({
                    url: '/admin/napijelentes2',
                    type: 'POST',
                    data: {
                        datum: mkwcomp.datumEdit.getDate($napijelentes2datumedit),
                        datumig: mkwcomp.datumEdit.getDate($napijelentes2datumigedit),
                        raktar: $('#Napijelentes2RaktarEdit').val(),
                        letrehozo: $('#Napijelentes2LetrehozoEdit').val()
                    },
                    success: function (data) {
                        $('.js-napijelentes2body').replaceWith(data);
                    }
                })
            };
            $('.js-napijelentes2').on('click', function (e) {
                e.preventDefault();
                loadNapijelentes2();
            });
            loadNapijelentes2();
        }

        $('.js-refreshkintlevoseg').on('click', function (e) {
            e.preventDefault();
            $.ajax({
                url: '/admin/refreshkintlevoseg',
                success: function (data) {
                    $('.js-kintlevoseg').replaceWith(data);
                }
            });
        });

        $('.js-refreshspanyolkintlevoseg').on('click', function (e) {
            e.preventDefault();
            $.ajax({
                url: '/admin/refreshspanyolkintlevoseg',
                success: function (data) {
                    $('.js-spanyolkintlevoseg').replaceWith(data);
                }
            });
        });

        $('.js-refreshteljesithetobackorderek').on('click', function (e) {
            e.preventDefault();
            $.ajax({
                url: '/admin/refreshteljesithetobackorderek',
                success: function (data) {
                    $('.js-teljesithetobackorderek').replaceWith(data);
                    $('.js-backorder').button();
                }
            });
        });

        $('.js-nepszerusegclear').on('click', function (e) {
            e.preventDefault();
            $.ajax({
                url: '/admin/nepszeruseg/clear',
                type: 'POST',
                success: function (data) {
                    dialogcenter.html('A népszerűség inicializálás sikerült.').dialog({
                        resizable: false,
                        height: 140,
                        modal: true,
                        buttons: {
                            'OK': function () {
                                $(this).dialog('close');
                            }
                        }
                    });
                }
            })
        });

        $('.js-boltbannincstermekfabutton').on('click', function (e) {
            var edit = $(this),
                input = $('.js-boltbannincstermekfainput');
            e.preventDefault();
            dialogcenter.jstree({
                core: {animation: 100},
                plugins: ['themeroller', 'json_data', 'ui'],
                themeroller: {item: ''},
                json_data: {
                    ajax: {url: '/admin/termekfa/jsonlist'}
                },
                ui: {select_limit: 1}
            })
                .on('loaded.jstree', function (event, data) {
                    dialogcenter.jstree('open_node', $('#termekfa_1', dialogcenter).parent());
                });
            dialogcenter.dialog({
                resizable: true,
                height: 340,
                modal: true,
                buttons: {
                    'Töröl': function () {
                        edit.attr('data-value', 0);
                        edit.buttonLabel(edit.attr('data-text'));
                        input.val(0);
                        $(this).dialog('close');
                    },
                    'OK': function () {
                        dialogcenter.jstree('get_selected').each(function () {
                            var treenode = $(this).children('a'),
                                id = treenode.attr('id').split('_')[1];
                            edit.attr('data-value', id);
                            input.val(id);
                            edit.buttonLabel(treenode.text());
                        });
                        $(this).dialog('close');
                    },
                    'Bezár': function () {
                        $(this).dialog('close');
                    }
                }
            });
        })
            .button();

        $(document).on('click', '.js-teljesithetobackorderek .js-backorder', function (e) {
            e.preventDefault();
            $.ajax({
                url: '/admin/megrendelesfej/backorder',
                type: 'POST',
                data: {
                    id: $(this).data('egyedid')
                },
                success: function (data) {
                    var d = JSON.parse(data);
                    if (d.refresh) {
                        dialogcenter.html('A backorder rendelés elkészült.').dialog({
                            resizable: false,
                            height: 140,
                            modal: true,
                            buttons: {
                                'OK': function () {
                                    $('.mattable-tablerefresh').click();
                                    $(this).dialog('close');
                                }
                            }
                        });
                    } else {
                        dialogcenter.html('A rendelés teljesíthető.').dialog({
                            resizable: false,
                            height: 140,
                            modal: true,
                            buttons: {
                                'OK': function () {
                                    $('.mattable-tablerefresh').click();
                                    $(this).dialog('close');
                                }
                            }
                        });
                    }
                }
            });
        });
        $('.js-backorder').button();

        $(document).on('click', '.js-apierrorlogclose', function (e) {
            e.preventDefault();
            $.ajax({
                url: '/admin/apierrorlog/close',
                type: 'POST',
                data: {
                    id: $(this).data('id')
                },
                success: function (data) {
                    dialogcenter.html('Kész. Frissítsd az oldalt.').dialog({
                        resizable: false,
                        height: 140,
                        modal: true,
                        buttons: {
                            'OK': function () {
                                $(this).dialog('close');
                            }
                        }
                    })
                }
            });
        });

    }
);