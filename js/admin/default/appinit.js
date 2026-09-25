const isModernUi = () => document.body.classList.contains('modernui');

function pleaseWait(msg) {
    if (typeof (msg) !== 'string') {
        msg = 'Kérem várjon...';
    }
    if (isModernUi()) {
        $.blockUI({
            message: $('<div class="mkw-varakozas">').append('<span class="mkw-porgetyu"></span>', $('<span>').text(msg)),
            css: {border: 'none', padding: 0, background: 'transparent', width: 'auto', left: '50%', transform: 'translateX(-50%)'},
            overlayCSS: {backgroundColor: '#09090b', opacity: .2}
        });
        return;
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

/**
 * Hibaüzenet modális ablakban. A szerver a {ok:false, error:'…'} válaszban küldi a szöveget,
 * a globális ajaxError kezelő innen mutatja meg; kézből is hívható.
 */
function mkwHiba(uzenet) {
    if ($.unblockUI) {
        // a "Kérem várjon..." réteg alatt az ablak nem látszana
        $.unblockUI();
    }
    $('#dialogcenter')
        .empty()
        .append($('<div>').text(uzenet || 'A művelet nem sikerült.'))
        .dialog({
            title: 'Hiba',
            resizable: false,
            modal: true,
            width: 420,
            buttons: {
                'OK': function () {
                    $(this).dialog('close');
                }
            }
        });
}

function mkwToastTarolo() {
    let $tarolo = $('.mkw-toastok');
    if (!$tarolo.length) {
        $tarolo = $('<div class="mkw-toastok" aria-live="polite">').appendTo('body');
    }
    return $tarolo;
}

/** Rövid, magától eltűnő értesítés a jobb alsó sarokban (modern téma). */
function mkwToast(uzenet, tipus) {
    const $tarolo = mkwToastTarolo();
    const $toast = $('<div class="mkw-toast">').addClass(tipus === 'hiba' ? 'mkw-toast-hiba' : 'mkw-toast-siker')
        .text(uzenet).appendTo($tarolo);
    const eltuntet = () => $toast.addClass('mkw-toast-eltunik').delay(200).queue(() => $toast.remove());
    $toast.on('click', eltuntet);
    setTimeout(eltuntet, 4000);
}

/** Semleges/sikeres visszajelzés: modern témában toast, egyébként a lap tetején, kattintásra eltűnik. */
function mkwUzenet(uzenet) {
    if (isModernUi()) {
        mkwToast(uzenet);
        return;
    }
    $('#messagecenter')
        .text(uzenet)
        .hide()
        .addClass('matt-messagecenter ui-widget ui-state-highlight')
        .one('click', messagecenterclick)
        .slideToggle('slow');
}

/** Kiemeli az aktuális képernyő menüpontját; a karb oldal a listájához az /admin/<entitás>/ előtag alapján tartozik. */
function markActiveMenupont() {
    const dir = (path) => path.replace(/[^/]*$/, '');
    const here = window.location.pathname;
    const items = $('.menupont').toArray().filter((a) => /^\/admin\/./.test(a.getAttribute('href') || ''));
    const exact = items.find((a) => a.pathname === here);
    const active = exact || items.find((a) => dir(a.pathname) !== '/admin/' && dir(a.pathname) === dir(here));
    if (active) {
        // a „Gyakran használt" szakaszban ugyanaz a menüpont még egyszer szerepelhet
        $('.menupont').filter((i, a) => a.getAttribute('href') === active.getAttribute('href')).addClass('menupont-aktiv');
    }
    // csak a menüpont saját oldala számít megnyitásnak, a hozzá tartozó karbantartó nem
    if (exact) {
        $.ajax({url: '/admin/menuhasznalat', type: 'POST', global: false, data: {url: exact.getAttribute('href')}});
    }
}

/** A szerver JSON válasza a hibás kérésből, vagy null. */
function mkwAjaxValasz(xhr) {
    if (xhr.responseJSON) {
        return xhr.responseJSON;
    }
    if (xhr.responseText) {
        try {
            return JSON.parse(xhr.responseText);
        } catch (err) {
            return null;
        }
    }
    return null;
}

/** A hibás ajax válaszból a felhasználónak szóló szöveg. */
function mkwAjaxHibaUzenet(xhr) {
    let valasz = mkwAjaxValasz(xhr);
    // az `error` a közös kulcs; a `hiba` néhány régebbi képernyőn (UNAS) él tovább
    if (valasz && (valasz.error || valasz.hiba)) {
        return valasz.error || valasz.hiba;
    }
    switch (xhr.status) {
        case 0:
            return 'A kiszolgáló nem válaszol. Ellenőrizze a kapcsolatot, majd próbálja újra.';
        case 403:
            return 'Nincs jogosultsága a művelethez.';
        case 404:
            return 'A kért művelet nem található.';
        case 413:
            return 'A küldött adat túl nagy.';
        default:
            return 'A művelet nem sikerült (' + (xhr.status || 'ismeretlen hiba') + ').';
    }
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
        if (isModernUi()) {
            // a régi, lap tetejére szánt üzenetek is a toastok közé kerülnek
            msgcenter.appendTo(mkwToastTarolo());
        }

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
                // A megszakított kérés nem hiba: az autocomplete minden leütésnél lelövi az
                // előzőt, és az oldalelhagyás is megszakítja a futó kéréseket.
                if (exception === 'abort' || xhr.statusText === 'abort' || xhr.readyState === 0) {
                    return;
                }
                mkwHiba(mkwAjaxHibaUzenet(xhr));
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
        markActiveMenupont();
        $('.js-gyakranhasznalttoggle').on('click', function (e) {
            e.preventDefault();
            const $szakasz = $('.js-gyakranhasznalt'),
                nyitva = !$szakasz.is(':visible');
            $(this).children('.menu-titlebar-icon')
                .toggleClass('ui-icon-circle-triangle-n', nyitva)
                .toggleClass('ui-icon-circle-triangle-s', !nyitva);
            $szakasz.slideToggle(200);
            $.ajax({url: '/admin/setuipref', type: 'POST', global: false, data: {name: 'gyakrannyitva', value: nyitva ? 1 : 0}});
        });
        $('.js-oldalsavkapcsolo').on('click', function () {
            const rejtve = $('body').toggleClass('oldalsav-rejtve').hasClass('oldalsav-rejtve');
            $.ajax({
                url: '/admin/setuipref',
                type: 'POST',
                global: false,
                data: {name: 'oldalsavrejtve', value: rejtve ? 1 : 0}
            });
        });
        // Kiemelő szín választó (partials/uiaccentpicker.tpl): a minta a keverő mezőt állítja be.
        // Delegált, mert a dolgozó karbantartóba ajaxszal töltődik be.
        $(document).on('click', '.js-uiaccentpreset', function (e) {
            e.preventDefault();
            $(this).siblings('.js-uiaccentinput').val($(this).data('color')).trigger('change');
        });
        $(document).on('change', '.js-uiaccentinput', function () {
            const color = this.value.toLowerCase();
            $(this).siblings('.js-uiaccentpreset').each(function () {
                $(this).toggleClass('uiaccentpicker-minta-aktiv', String($(this).data('color')).toLowerCase() === color);
            });
        });
        // sysadmin téma- és színválasztó az oldalsáv alján; a színminta is change-et vált ki a keverő mezőn
        $('.js-sysadminmegjelenes').on('change', 'select, input', function () {
            const $box = $(this).closest('.js-sysadminmegjelenes');
            $.ajax({
                url: '/admin/setsysadminappearance',
                type: 'POST',
                global: false,
                data: {
                    uitheme: $box.find('select[name="uitheme"]').val(),
                    uiaccent: $box.find('input[name="uiaccent"]').val()
                },
                success: function () {
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
        // a menüpont a választót nyitja, de fájlkezelőként: nincs hívó, akinek választani kellene
        $('.js-mediatar').on('click', function (e) {
            e.preventDefault();
            const finder = new CKFinder();
            finder.resourceType = 'Images';
            finder.params = {manage: 1};
            finder.popup();
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
                        if (isModernUi()) {
                            mkwToast('Az árfolyamok letöltése sikerült.');
                            return;
                        }
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

        if ($('.js-noallapotbody').length) {
            $.ajax({
                url: '/admin/noallapot',
                success: function (data) {
                    $('.js-noallapotbody').replaceWith(data);
                }
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
                    if (isModernUi()) {
                        mkwToast('A népszerűség inicializálás sikerült.');
                        return;
                    }
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
            var sor = $(this).closest('tr');
            var doboz = $(this).closest('.js-apierrorlog');
            $.ajax({
                url: '/admin/apierrorlog/close',
                type: 'POST',
                data: {
                    id: $(this).data('id')
                },
                success: function () {
                    sor.remove();
                    var db = doboz.find('tbody tr').length;
                    if (db) {
                        doboz.find('.js-apierrorlogcount').text(db);
                    } else {
                        doboz.remove();
                    }
                },
                error: function () {
                    dialogcenter.html('A hiba lezárása nem sikerült.').dialog({
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