$(document).ready(function () {
    const dialogcenter = $('#dialogcenter');

    /** A szerkesztett termék id-ja; új, még nem mentett terméken üres. */
    function termekId() {
        const id = $('#mattkarb-form input[name="id"]').val();
        return (id && id * 1) ? id : '';
    }

    /**
     * Médiatár a termék karbantartóból: a választó megkapja a termék id-ját, és ha van,
     * felkínálja a kijelölt képek felvételét a termék képei közé. A felvétel után a
     * Képek lap újratöltődik – a form többi, még nem mentett adata megmarad.
     */
    function termekFinder() {
        const finder = new CKFinder();
        finder.params = {termekid: termekId()};
        finder.doneActionFunction = function () {
            reloadKepek();
        };
        return finder;
    }

    function reloadKepek() {
        const id = termekId();
        if (!id) {
            return;
        }
        $.ajax({
            url: '/admin/termekkep/getrows',
            type: 'GET',
            data: {termek: id},
            success: function (data) {
                $('#KepTab').find('[id^="keptable_"], .js-kepnewbutton').remove();
                $('#KepTab').append(data);
                $('#KepTab').append('<a class="js-kepnewbutton" href="#" title="Új"><span class="ui-icon ui-icon-circle-plus"></span></a>');
                $('.js-kepnewbutton,.js-kepdelbutton,.js-kepbrowsebutton').button();
                if (!window.mkwIsMobile) {
                    $('#KepTab .js-toflyout').flyout();
                }
            }
        });
    }

    function termekAutocompleteConfig() {
        return {
            minLength: 4,
            autoFocus: true,
            source: '/admin/termek/getkapcsolodolist',
            select: function (event, ui) {
                var termek = ui.item;
                if (termek) {
                    var $this = $(this);
                    $this.siblings().val(termek.id);
                }
            }
        };
    }

    function szinAutocompleteConfig() {
        return {
            minLength: 2,
            autoFocus: true,
            source: '/admin/szin/getautocomplete',
            select: function (event, ui) {
                let szin = ui.item;
                if (szin) {
                    let $this = $(this);
                    $this.siblings('.js-szinid').val(szin.id);
                }
            }
        };
    }

    function createImageSelectable(n, m) {
        $(n).selectable({
            unselected: function () {
                $('.ui-state-highlight', this).each(function () {
                    var $this = $(this);
                    $this.removeClass('ui-state-highlight');
                    $(m + $this.attr('data-valtozatid')).val('');
                });
            },
            selected: function () {
                $('.ui-selected', this).each(function () {
                    var $this = $(this);
                    $this.addClass('ui-state-highlight');
                    $(m + $this.attr('data-valtozatid')).val($this.attr('data-value'));
                });
            }
        });
    }

    function updateMultiImageInputs($list) {
        var $wrapper = $list.closest('.ui-widget').find('.js-szinkepinput');
        var inputName = $wrapper.data('inputname');
        var sorrendInputName = $wrapper.data('sorrendinputname');
        $wrapper.empty();
        $('.ui-selected', $list).each(function () {
            var $item = $(this);
            $item.addClass('ui-state-highlight');
            $('<input>').attr({
                type: 'hidden',
                name: inputName,
                value: $item.attr('data-value')
            }).appendTo($wrapper);
            if (sorrendInputName) {
                $('<input>').attr({
                    type: 'hidden',
                    name: sorrendInputName,
                    value: $item.find('.js-szinkepsorrend').val()
                }).appendTo($wrapper);
            }
        });
        $('.ui-state-highlight', $list).not('.ui-selected').removeClass('ui-state-highlight');
    }

    function createMultiImageSelectable(n) {
        $(n).each(function () {
            var $list = $(this);
            $list.on('click', 'li', function (e) {
                if ($(e.target).hasClass('js-szinkepsorrend')) {
                    return;
                }
                e.preventDefault();
                $(this).toggleClass('ui-selected ui-state-highlight');
                updateMultiImageInputs($list);
            });
            $list.on('change', '.js-szinkepsorrend', function (e) {
                updateMultiImageInputs($list);
            });
            updateMultiImageInputs($list);
        });
    }

    function getSorNetto(o, n) {
        var id = $('#mattkarb-form').attr('data-id');
        var sorid = o.attr('name').split('_')[1] || '';
        $.ajax({
            url: '/admin/termek/getnetto',
            type: 'GET',
            data: {
                id: id,
                value: o.val(),
                afakod: $('#AfaEdit').val()
            },
            success: function (data) {
                $('input[name="' + n + sorid + '"]').val(data);
            }
        });
    }

    function getSorBrutto(o, n) {
        var id = $('#mattkarb-form').attr('data-id');
        var sorid = o.attr('name').split('_')[1] || '';
        $.ajax({
            url: '/admin/termek/getbrutto',
            type: 'GET',
            data: {
                id: id,
                value: o.val(),
                afakod: $('#AfaEdit').val()
            },
            success: function (data) {
                $('input[name="' + n + sorid + '"]').val(data);
            }
        });
    }

    function getNetto(o, n) {
        var id = $('#mattkarb-form').attr('data-id');
        $.ajax({
            url: '/admin/termek/getnetto',
            type: 'GET',
            data: {
                id: id,
                value: o.val(),
                afakod: $('#AfaEdit').val()
            },
            success: function (data) {
                $(n).val(data);
            }
        });
    }

    function getBrutto(o, n) {
        var id = $('#mattkarb-form').attr('data-id');
        $.ajax({
            url: '/admin/termek/getbrutto',
            type: 'GET',
            data: {
                id: id,
                value: o.val(),
                afakod: $('#AfaEdit').val()
            },
            success: function (data) {
                $(n).val(data);
            }
        });
    }

    /**
     * A készletsorok gombjai: a készlet linkek raktárankénti bontást nyitnak, a foglalt és az
     * érkező mennyiség a foglaló / érkeztető bizonylatokat, a címke gomb a darabszámot kérdezi
     * meg – ugyanaz a terméklista készlet oszlopában és a termék karbantartó Készlet fülén
     * (mindkettő a termekkeszletsorok.tpl-t rendereli).
     */
    function bindKeszletRows($root) {
        mkwcomp.keszletBizonylatok.bind($root);
        $root
            .on('click', '.js-keszletreszletezobutton', function (e) {
                e.preventDefault();
                $.ajax({
                    url: '/admin/termek/getkeszletbyraktar',
                    data: {
                        termekid: $(this).data('id')
                    },
                    success: function (data) {
                        dialogcenter.html(data).dialog({
                            modal: true,
                            buttons: {
                                'OK': function () {
                                    dialogcenter.dialog('close');
                                }
                            }
                        });
                    }
                });
            })
            .on('click', '.js-valtozatkeszletreszletezobutton', function (e) {
                e.preventDefault();
                $.ajax({
                    url: '/admin/termekvaltozat/getkeszletbyraktar',
                    data: {
                        valtozatid: $(this).data('id')
                    },
                    success: function (data) {
                        const d = JSON.parse(data);
                        dialogcenter.html(d.html).dialog({
                            modal: true,
                            title: d.title,
                            buttons: {
                                'OK': function () {
                                    dialogcenter.dialog('close');
                                }
                            }
                        });
                    }
                });
            })
            .on('click', '.js-termekcimke', function (e) {
                e.preventDefault();
                const url = $(this).attr('href'),
                    keszlet = Math.max(1, Math.floor(parseFloat($(this).data('keszlet')) || 0));
                dialogcenter
                    .html('<label>Hány címke készüljön? <input type="number" min="1" step="1" class="js-cimkedb" value="' + keszlet + '"></label>')
                    .dialog({
                        resizable: false,
                        height: 160,
                        modal: true,
                        title: 'Címke nyomtatás',
                        open: function () {
                            const $db = $('.js-cimkedb', this);
                            $db.trigger('focus').trigger('select');
                            $db.on('keydown', function (ev) {
                                if (ev.key === 'Enter') {
                                    ev.preventDefault();
                                    $('.ui-dialog-buttonpane button:first').trigger('click');
                                }
                            });
                        },
                        buttons: {
                            'Nyomtat': function () {
                                const db = Math.max(1, parseInt($('.js-cimkedb', this).val(), 10) || 1);
                                window.open(url + '&db=' + db, '_blank');
                                $(this).dialog('close');
                            },
                            'Mégsem': function () {
                                $(this).dialog('close');
                            }
                        }
                    });
            });
    }

    const termek = new MattkarbConfig({
        entityName: 'termek',
        beforeShow: function () {
            var artab = $('#ArsavTab');
            var keptab = $('#KepTab');
            var kapcsolodotab = $('#KapcsolodoTab');
            var valtozattab = $('#ValtozatTab');
            var doktab = $('#DokTab');
            initDokumentumUpload(doktab);
            bindKeszletRows($('#KeszletTab'));
            $('.js-saveas').on('click', function (e) {
                e.preventDefault();
                $('input[name="oper"]').val('add');
                $('input[name="id"]').val(0);
                $('input[name^="kepoper_"]').val('add');
                $('table[id^="keptable_"]').attr('data-oper', 'add');
                $('input[name^="valtozatoper_"]').val('add');
                $('input[name^="kapcsolodooper_"]').val('add');
                $('input[name^="aroper_"]').val('add');
                $('#mattkarb-okbutton').click();
            });
            $('.js-saveandreopen').on('click', function (e) {
                e.preventDefault();
                $('input[name="oper"]').val('addreopen');
                $('input[name="id"]').val(0);
                $('#mattkarb-okbutton').click();
            });
            doktab
                .on('click', '.js-doknewbutton', function (e) {
                    var $this = $(this);
                    e.preventDefault();
                    $.ajax({
                        url: '/admin/termekdok/getemptyrow',
                        type: 'GET',
                        success: function (data) {
                            doktab.append(data);
                            $('.js-doknewbutton,.js-dokdelbutton,.js-dokbrowsebutton,.js-dokopenbutton,.js-dokopen2button').button();
                            $this.remove();
                        }
                    });
                })
                .on('click', '.js-dokdelbutton', function (e) {
                    e.preventDefault();
                    var $this = $(this);
                    dialogcenter.html('Biztos, hogy törli a dokumentumot?').dialog({
                        resizable: false,
                        height: 140,
                        modal: true,
                        buttons: {
                            'Igen': function () {
                                $.ajax({
                                    url: '/admin/termekdok/del',
                                    type: 'POST',
                                    data: {
                                        id: $this.attr('data-id')
                                    },
                                    success: function (data) {
                                        $('#doktable_' + data).remove();
                                    }
                                });
                                $(this).dialog('close');
                            },
                            'Nem': function () {
                                $(this).dialog('close');
                            }
                        }
                    });
                })
                .on('click', '.js-dokbrowsebutton', function (e) {
                    e.preventDefault();
                    var finder = termekFinder(),
                        $dokpathedit = $('#DokPathEdit_' + $(this).attr('data-id')),
                        path = $dokpathedit.val();
                    finder.resourceType = 'Images';
                    if (path) {
                        finder.startupPath = path.substring(path.indexOf('/', 1));
                    }
                    finder.selectActionFunction = function (fileUrl, data) {
                        $dokpathedit.val(fileUrl);
                    };
                    finder.popup();
                });
            $('.js-doknewbutton,.js-dokbrowsebutton,.js-dokdelbutton,.js-dokopenbutton,.js-dokopen2button').button();
            keptab.on('click', '#FoKepDelButton', function (e) {
                e.preventDefault();
                dialogcenter.html('Biztos, hogy törli a képet?').dialog({
                    resizable: false,
                    height: 140,
                    modal: true,
                    buttons: {
                        'Igen': function () {
                            $('#KepUrlEdit').val('');
                            $('#KepLeirasEdit').val('');
                            $(this).dialog('close');
                        },
                        'Nem': function () {
                            $(this).dialog('close');
                        }
                    }
                });
            })
                .on('click', '#FoKepBrowseButton', function (e) {
                    e.preventDefault();
                    var finder = termekFinder(),
                        $kepurl = $('#KepUrlEdit'),
                        path = $kepurl.val();
                    if (path) {
                        finder.startupPath = 'Images:' + path.substring(path.indexOf('/', 1));
                    }
                    finder.selectActionFunction = function (fileUrl, data) {
                        $kepurl.val(fileUrl);
                    };
                    finder.popup();
                })
                .on('click', '.js-kepnewbutton', function (e) {
                    var $this = $(this);
                    e.preventDefault();
                    $.ajax({
                        url: '/admin/termekkep/getemptyrow',
                        type: 'GET',
                        success: function (data) {
                            keptab.append(data);
                            $('.js-kepnewbutton,.js-kepdelbutton,.js-kepbrowsebutton').button();
                            $this.remove();
                        }
                    });
                })
                .on('click', '.js-kepdelbutton', function (e) {
                    e.preventDefault();
                    var $this = $(this);
                    dialogcenter.html('Biztos, hogy törli a képet?').dialog({
                        resizable: false,
                        height: 140,
                        modal: true,
                        buttons: {
                            'Igen': function () {
                                $.ajax({
                                    url: '/admin/termekkep/del',
                                    type: 'POST',
                                    data: {
                                        id: $this.attr('data-id')
                                    },
                                    success: function (data) {
                                        $('#keptable_' + data).remove();
                                    }
                                });
                                $(this).dialog('close');
                            },
                            'Nem': function () {
                                $(this).dialog('close');
                            }
                        }
                    });
                })
                .on('click', '.js-kepbrowsebutton', function (e) {
                    e.preventDefault();
                    var finder = termekFinder(),
                        $kepurledit = $('#KepUrlEdit_' + $(this).attr('data-id')),
                        path = $kepurledit.val();
                    if (path) {
                        finder.startupPath = 'Images:' + path.substring(path.indexOf('/', 1));
                    }
                    finder.selectActionFunction = function (fileUrl, data) {
                        $kepurledit.val(fileUrl);
                    };
                    finder.popup();
                });
            $('#FoKepDelButton,#FoKepBrowseButton,.js-kepnewbutton,.js-kepbrowsebutton,.js-kepdelbutton').button();
            if (!window.mkwIsMobile) {
                $('.js-toflyout').flyout();
            }
            $('#cimkekarbcontainer').mattaccord({
                header: '#cimkekarbcontainerhead',
                page: '.js-cimkekarbpage',
                closeUp: '.js-cimkekarbcloseupbutton',
                collapse: '#cimkekarbcollapse'
            })
                .on('click', '.js-cimkekarb', function (e) {
                    e.preventDefault();
                    $(this).toggleClass('js-selectedcimke ui-state-hover');
                });
            $('.js-cimkeadd').on('click', function (e) {
                e.preventDefault();
                var ref = $(this).attr('data-refcontrol');
                var cimkenev = $(ref).val(),
                    katkod = ref.split('_')[1];
                if (cimkenev.length > 0) {
                    $.ajax({
                        url: '/admin/termekcimke/add',
                        type: 'POST',
                        data: {
                            cimkecsoport: katkod,
                            nev: cimkenev,
                            menu1lathato: true
                        },
                        success: function (data) {
                            $(ref).val('');
                            $(ref).before(data);
                        }
                    });
                }
            });
            artab.on('click', '.js-arnewbutton', function (e) {
                var $this = $(this);
                e.preventDefault();
                $.ajax({
                    url: '/admin/termekar/getemptyrow',
                    type: 'GET',
                    success: function (data) {
                        var tbody = $('#ArsavTab');
                        tbody.append(data);
                        $('.js-arnewbutton,.js-ardelbutton,.js-arrecalcbutton').button();
                        $this.remove();
                    }
                });
            })
                .on('click', '.js-ardelbutton', function (e) {
                    e.preventDefault();
                    var argomb = $(this),
                        arid = argomb.attr('data-id');
                    if (argomb.attr('data-source') === 'client') {
                        $('#artable_' + arid).remove();
                    } else {
                        dialogcenter.html('Biztos, hogy törli az ársávot?').dialog({
                            resizable: false,
                            height: 140,
                            modal: true,
                            buttons: {
                                'Igen': function () {
                                    $.ajax({
                                        url: '/admin/termekar/save',
                                        type: 'POST',
                                        data: {
                                            id: arid,
                                            oper: 'del'
                                        },
                                        success: function (data) {
                                            $('#artable_' + data).remove();
                                        }
                                    });
                                    $(this).dialog('close');
                                },
                                'Nem': function () {
                                    $(this).dialog('close');
                                }
                            }
                        });
                    }
                })
                .on('change', '.js-arkepletes', function () {
                    let $this = $(this);
                    $('.js-arkepletrow_' + $this.data('id')).toggle($this.prop('checked'));
                })
                .on('click', '.js-arrecalcbutton', function (e) {
                    e.preventDefault();
                    // a formon álló (mentetlen) sorokból számol, az eredményt a nettó/bruttó
                    // mezőkbe írja – menteni a szokásos OK gombbal kell
                    $.ajax({
                        url: '/admin/termek/recalcarak',
                        type: 'POST',
                        data: $('#mattkarb-form').serialize(),
                        success: function (data) {
                            let d = JSON.parse(data);
                            $.each(d.arak, function (i, ar) {
                                $('input[name="arnetto_' + ar.id + '"]').val(ar.netto);
                                $('input[name="arbrutto_' + ar.id + '"]').val(ar.brutto);
                            });
                            $('.js-arrecalchibak').html(d.hibak.join('<br>'));
                        }
                    });
                });
            $('.js-arnewbutton,.js-ardelbutton,.js-arrecalcbutton').button();
            kapcsolodotab.on('click', '.js-kapcsolodonewbutton', function (e) {
                var $this = $(this);
                e.preventDefault();
                $.ajax({
                    url: '/admin/termekkapcsolodo/getemptyrow',
                    type: 'GET',
                    success: function (data) {
                        var tbody = $('#KapcsolodoTab');
                        tbody.append(data);
                        $('.js-kapcsolodonewbutton,.js-kapcsolododelbutton').button();
                        $('.js-kapcsolodoselect').autocomplete(termekAutocompleteConfig());
                        $this.remove();
                    }
                });
            })
                .on('click', '.js-kapcsolododelbutton', function (e) {
                    e.preventDefault();
                    var kapcsgomb = $(this),
                        kapcsid = kapcsgomb.attr('data-id');
                    if (kapcsgomb.attr('data-source') === 'client') {
                        $('#kapcsolodotable_' + kapcsid).remove();
                    } else {
                        dialogcenter.html('Biztos, hogy törli a kapcsolódó terméket?').dialog({
                            resizable: false,
                            height: 140,
                            modal: true,
                            buttons: {
                                'Igen': function () {
                                    $.ajax({
                                        url: '/admin/termekkapcsolodo/save',
                                        type: 'POST',
                                        data: {
                                            id: kapcsid,
                                            oper: 'del'
                                        },
                                        success: function (data) {
                                            $('#kapcsolodotable_' + data).remove();
                                        }
                                    });
                                    $(this).dialog('close');
                                },
                                'Nem': function () {
                                    $(this).dialog('close');
                                }
                            }
                        });
                    }
                });
            $('.js-kapcsolodonewbutton,.js-kapcsolododelbutton').button();
            valtozattab.on('click', '.js-valtozatnewbutton', function (e) {
                var $this = $(this);
                e.preventDefault();
                $.ajax({
                    url: '/admin/termekvaltozat/getemptyrow',
                    type: 'GET',
                    data: {
                        termekid: $this.attr('data-termekid')
                    },
                    success: function (data) {
                        var tbody = $('#ValtozatTab');
                        tbody.append(data);
                        $('.js-valtozatnewbutton,.js-valtozatdelbutton').button();
                        createImageSelectable('.js-valtozatkepedit', '#ValtozatKepId_');
                        createMultiImageSelectable('.js-szinkepedit');
                        $this.remove();
                    }
                });
            })
                .on('click', '.js-valtozatdelbutton', function (e) {
                    e.preventDefault();
                    var gomb = $(this),
                        vid = gomb.attr('data-id');
                    if (gomb.attr('data-source') === 'client') {
                        $('#valtozattable_' + vid).remove();
                    } else {
                        dialogcenter.html('Biztos, hogy törli a változatot?').dialog({
                            resizable: false,
                            height: 140,
                            modal: true,
                            buttons: {
                                'Igen': function () {
                                    $.ajax({
                                        url: '/admin/termekvaltozat/save',
                                        type: 'POST',
                                        data: {
                                            id: vid,
                                            oper: 'del'
                                        },
                                        success: function (data) {
                                            $('#valtozattable_' + data).remove();
                                        }
                                    });
                                    $(this).dialog('close');
                                },
                                'Nem': function () {
                                    $(this).dialog('close');
                                }
                            }
                        });
                    }
                })
                .on('blur', '.js-valtozatnetto', function (e) {
                    e.preventDefault();
                    getSorBrutto($(this), 'valtozatbrutto_');
                })
                .on('blur', '.js-valtozatbrutto', function (e) {
                    e.preventDefault();
                    getSorNetto($(this), 'valtozatnetto_');
                })
                .on('blur', '.js-valtozatnettogen', function (e) {
                    e.preventDefault();
                    getSorBrutto($(this), 'valtozatbruttogen');
                })
                .on('blur', '.js-valtozatbruttogen', function (e) {
                    e.preventDefault();
                    getSorNetto($(this), 'valtozatnettogen');
                })
                // inaktiváláskor rákérdez, hogy a változat elérhető/látható pipáit is kikapcsolja-e (minden webshopét)
                .on('change', 'input[name^="valtozatinaktiv_"]', function () {
                    const $inaktiv = $(this);
                    if (!$inaktiv.is(':checked')) {
                        return;
                    }
                    const $pipak = $inaktiv.closest('.valtozattable')
                        .find('input[name^="valtozatelerheto"], input[name^="valtozatlathato"]')
                        .filter(':checked');
                    if (!$pipak.length) {
                        return;
                    }
                    dialogcenter.html('Az inaktív változat Elérhető és Látható pipáit is kikapcsoljuk?').dialog({
                        resizable: false,
                        height: 160,
                        modal: true,
                        buttons: {
                            'Igen': function () {
                                $pipak.prop('checked', false);
                                $(this).dialog('close');
                            },
                            'Nem': function () {
                                $(this).dialog('close');
                            }
                        }
                    });
                });
            $('#valtozatgeneratorform').ajaxForm({
                type: 'POST',
                beforeSubmit: function (arr, form, opt) {
//						pleaseWait();
                    arr.push({name: 'termekid', value: form.data('id')});
                },
                success: function (data) {
                    $('.valtozattable').remove();
                    $('#valtozatgenerator').after(data);
                    $('.js-valtozatdelbutton').button();
                }
            });
            $('.js-valtozatdelallbutton').button().on('click', function (e) {
                var $this = $(this);
                dialogcenter.html('Biztos, hogy törli az összes változatot?').dialog({
                    resizable: false,
                    height: 140,
                    modal: true,
                    buttons: {
                        'Igen': function () {
//								pleaseWait();
                            $.ajax({
                                url: '/admin/termekvaltozat/delall',
                                type: 'POST',
                                data: {
                                    termekid: $this.data('termekid')
                                },
                                success: function () {
                                    $('.valtozattable').remove();
                                }
                            });
                            $(this).dialog('close');
                        },
                        'Nem': function () {
                            $(this).dialog('close');
                        }
                    }
                });
                return false;
            });
            $('.js-kapcsolodoselect').autocomplete(termekAutocompleteConfig());
            $('.js-szinautocomplete').autocomplete(szinAutocompleteConfig());

            createImageSelectable('.js-valtozatkepedit', '#ValtozatKepId_');
            createMultiImageSelectable('.js-szinkepedit');
            $('.js-valtozatnewbutton,.js-valtozatdelbutton,#valtozatgeneratorbutton').button();

            // Min. készlet mátrix tömeges kitöltése: a sor eleji gomb a sort, a felső sor gombja
            // az oszlopot, a bal felső az egész rácsot tölti ki. A rejtett és a zárolt (változatos
            // termék termék sora) mezőket nem bántjuk.
            $('#MinKeszletTab').on('click', '.js-minkeszletfill', function () {
                var $gomb = $(this),
                    $cella = $gomb.closest('td'),
                    ertek = $cella.find('.js-minkeszletfillvalue').val(),
                    $tbody = $gomb.closest('table').children('tbody'),
                    $mezok;
                switch ($gomb.attr('data-scope')) {
                    case 'row':
                        $mezok = $gomb.closest('tr').find('input[name]');
                        break;
                    case 'col':
                        $mezok = $tbody.children('tr').find('td:nth-child(' + ($cella.index() + 1) + ') input[name]');
                        break;
                    default:
                        $mezok = $tbody.find('input[name]');
                }
                $mezok.not(':disabled').not('[type="hidden"]').val(ertek);
            });
            $('.js-minkeszletfill').button();

            $('#NettoEdit').on('blur', function (e) {
                e.preventDefault();
                getBrutto($(this), '#BruttoEdit');
            });
            $('#BruttoEdit').on('blur', function (e) {
                e.preventDefault();
                getNetto($(this), '#NettoEdit');
            });
            $('#AkciosNettoEdit').on('blur', function (e) {
                e.preventDefault();
                getBrutto($(this), '#AkciosBruttoEdit');
            });
            $('#AkciosBruttoEdit').on('blur', function (e) {
                e.preventDefault();
                getNetto($(this), '#AkciosNettoEdit');
            });
            $('#NemkaphatoCheck').on('click', function (e) {
                var $this = $(this);
                if ($this.prop('checked')) {
                    dialogcenter.html('Biztos, hogy nem kaphatóvá teszi a terméket? A változatok automatikusan nem elérhetők lesznek.').dialog({
                        resizable: false,
                        height: 200,
                        modal: true,
                        buttons: {
                            'Igen': function () {
                                $('input[name^="valtozatelerheto_"]').prop('checked', false);
                                $('input[name="ajanlott"]').prop('checked', false);
                                $('input[name="kiemelt"]').prop('checked', false);
                                $('input[name="uj"]').prop('checked', false);
                                $(this).dialog('close');
                            },
                            'Nem': function () {
                                $this.prop('checked', false);
                                $(this).dialog('close');
                            }
                        }
                    });
                } else {
                    dialogcenter.html('Ne felejtse el beállítani az elérhető változatokat!').dialog({
                        resizable: false,
                        height: 200,
                        modal: true,
                        buttons: {
                            'OK': function () {
                                $(this).dialog('close');
                            }
                        }
                    });
                }
            });
            mkwcomp.datumEdit.init('#AkcioStartEdit');
            mkwcomp.datumEdit.init('#AkcioStopEdit');
            $('.js-valtozatbeerkezesdatumedit').each(function () {
                mkwcomp.datumEdit.init($(this));
            });

            $('.js-termekfabutton').on('click', function (e) {
                var edit = $(this);
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
                            $(this).dialog('close');
                        },
                        'OK': function () {
                            dialogcenter.jstree('get_selected').each(function () {
                                var treenode = $(this).children('a');
                                edit.attr('data-value', treenode.attr('id').split('_')[1]);
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
            $('.js-termekmenubutton').on('click', function (e) {
                var edit = $(this);
                e.preventDefault();
                dialogcenter.jstree({
                    core: {animation: 100},
                    plugins: ['themeroller', 'json_data', 'ui'],
                    themeroller: {item: ''},
                    json_data: {
                        // melyik menüfa: a gomb data-url-je dönti el (menü 1 / menü 2)
                        ajax: {url: edit.attr('data-url') || '/admin/termekmenu/jsonlist'}
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
                            $(this).dialog('close');
                        },
                        'OK': function () {
                            dialogcenter.jstree('get_selected').each(function () {
                                var treenode = $(this).children('a');
                                edit.attr('data-value', treenode.attr('id').split('_')[1]);
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
            if (!window.mkwIsMobile) {
                CKFinder.setupCKEditor(null, '/ckfinder/');
                $('.js-ckeditor').each(function () {
                    $(this).ckeditor();
                });
            }
        },
        beforeSerialize: function (form, opt) {
            var netto = $('#AkciosNettoEdit').val() * 1,
                brutto = $('#AkciosBruttoEdit').val() * 1,
                astart = $('#AkcioStartEdit').val(),
                astop = $('#AkcioStopEdit').val();
            if ((netto || brutto) && (!astart && !astop)) {
                alert('Adja meg az akció kezdetét vagy végét.');
                return false;
            }
            var cimkek = new Array();
            $('.js-cimkekarb').filter('.js-selectedcimke').each(function () {
                cimkek.push($(this).attr('data-id'));
            });
            var x = {};
            x['cimkek[]'] = cimkek;
            $('.js-termekfabutton').each(function () {
                $this = $(this);
                x[$this.attr('data-name')] = $this.attr('data-value');
            });
            $('.js-termekmenubutton').each(function () {
                $this = $(this);
                x[$this.attr('data-name')] = $this.attr('data-value');
            });
            opt['data'] = x;
            return true;
        },
        beforeSubmit: function (arr) {
            // A min.bolti készlet mátrix önmagában több száz mező (változat × raktár), és a PHP
            // a max_input_vars fölött csendben csonkolja a POST-ot. Az üres cellákat kihagyni
            // biztonságos: a „hiányzik ⇒ törlés" és az „üres ⇒ törlés" ugyanaz a szabály.
            // A rácsot leíró rejtett tömböket soha nem szűrjük.
            var minboltimezo = /^(termekraktariminkeszlet_|valtozatraktariminkeszlet_|valtozatminkeszlet_)/;
            for (var i = arr.length - 1; i >= 0; i--) {
                if (arr[i].value === '' && minboltimezo.test(arr[i].name)) {
                    arr.splice(i, 1);
                }
            }
            return true;
        },
        beforeHide: function () {
            var editor;
            if (!window.mkwIsMobile) {
                $('.js-ckeditor').each(function () {
                    editor = $(this).ckeditorGet();
                    if (editor) {
                        editor.destroy();
                    }
                });
            }
        },
    });

    if ($.fn.mattable) {
        var lfilternames = ['#gyartofilter', '#nevfilter', '#kepurlfilter', '#lathatofilter', '#nemkaphatofilter', '#fuggobenfilter', '#inaktivfilter',
            '#ajanlottfilter', '#kiemeltfilter', '#akciosfilter'];
        for (var cikl = 2; cikl <= 15; cikl++) {
            lfilternames.push('#lathato' + cikl + 'filter');
        }
        $('#mattable-select').mattable({
            name: 'termek',
            onGetTBody: function () {
                if (!window.mkwIsMobile) {
                    $('.js-toflyout').flyout();
                }
            },
            filter: {
                fields: lfilternames,
                extraFields: ['cimkefilter', 'fafilter', 'menufilter'],
                onClear: function () {
                    $('.js-cimkefilter').removeClass('ui-state-hover');
                    mkwcomp.termekfaFilter.clearChecks('#termekfa');
                    mkwcomp.termekmenuFilter.clearChecks('#termekmenu');
                },
                onApplyUrl: function (urlParams) {
                    mkwcomp.partnercimkeFilter.setFilter(
                        '.js-cimkefilter',
                        urlParams.has('cimkefilter') ? urlParams.get('cimkefilter').split(',') : []
                    );
                    mkwcomp.termekfaFilter.setChecks(
                        '#termekfa',
                        urlParams.has('fafilter') ? urlParams.get('fafilter').split(',') : []
                    );
                    mkwcomp.termekmenuFilter.setChecks(
                        '#termekmenu',
                        urlParams.has('menufilter') ? urlParams.get('menufilter').split(',') : []
                    );
                },
                onFilter: function (obj) {
                    var cimkek = new Array(), fak;
                    $('.js-cimkefilter').filter('.ui-state-hover').each(function () {
                        cimkek.push($(this).attr('data-id'));
                    });
                    if (cimkek.length > 0) {
                        obj['cimkefilter'] = cimkek;
                    }
                    fak = mkwcomp.termekfaFilter.getFilter('#termekfa');
                    if (fak.length > 0) {
                        obj['fafilter'] = fak;
                    }
                    menuk = mkwcomp.termekmenuFilter.getFilter('#termekmenu');
                    if (menuk.length > 0) {
                        obj['menufilter'] = menuk;
                    }
                }
            },
            tablebody: {
                url: '/admin/termek/getlistbody',
                onStyle: function () {
                    $('.js-karton').button();
                },
                onDoEditLink: function () {
                    $('.js-karton').each(function () {
                        var $this = $(this);
                        $this.attr('href', '/admin/termekkarton/view?id=' + $this.data('termekid'));
                    });
                }
            },
            karb: termek
        });

        $('.mattable-batchbtn').on('click', function (e) {
            var cbs,
                batch,
                tomb = [];
            e.preventDefault();
            cbs = $('.js-egyedcheckbox:checked');
            batch = $('.mattable-batchselect').val();
            // a minimum készlet export kijelölés nélkül a teljes törzsre megy
            if (cbs.length || (batch === 'minkeszletexport')) {
                cbs.closest('tr').each(function (index, elem) {
                    tomb.push($(elem).data('egyedid'));
                });
                switch (batch) {
                    case 'arexport':
                        href = '/admin/termek/arexport?ids=' + tomb.join(',');
                        dialogcenter.html('<a href="' + href + '" target="_blank">Letöltés</a>').dialog({
                            resizable: false,
                            height: 140,
                            modal: true,
                            buttons: {
                                'Bezár': function () {
                                    $(this).dialog('close');
                                }
                            }
                        });
                        break;
                    case 'fcmotoexport':
                        href = '/admin/termek/fcmotoexport?ids=' + tomb.join(',') + '&p=fcmoto';
                        dialogcenter.html('<a href="' + href + '" target="_blank">Letöltés</a>').dialog({
                            resizable: false,
                            height: 140,
                            modal: true,
                            buttons: {
                                'Bezár': function () {
                                    $(this).dialog('close');
                                }
                            }
                        });
                        break;
                    case 'maximomotoexport':
                        href = '/admin/termek/fcmotoexport?ids=' + tomb.join(',') + '&p=maximomoto';
                        dialogcenter.html('<a href="' + href + '" target="_blank">Letöltés</a>').dialog({
                            resizable: false,
                            height: 140,
                            modal: true,
                            buttons: {
                                'Bezár': function () {
                                    $(this).dialog('close');
                                }
                            }
                        });
                        break;
                    case 'gs1export':
                        href = '/admin/termek/gs1export?ids=' + tomb.join(',');
                        dialogcenter.html('<a href="' + href + '" target="_blank">Letöltés</a>').dialog({
                            resizable: false,
                            height: 140,
                            modal: true,
                            buttons: {
                                'Bezár': function () {
                                    $(this).dialog('close');
                                }
                            }
                        });
                        break;
                    case 'colorexport':
                        href = '/admin/termek/colorexport';
                        dialogcenter.html('<a href="' + href + '" target="_blank">Letöltés</a>').dialog({
                            resizable: false,
                            height: 140,
                            modal: true,
                            buttons: {
                                'Bezár': function () {
                                    $(this).dialog('close');
                                }
                            }
                        });
                        break;
                    case 'cikkszamosexport':
                        href = '/admin/termek/cikkszamosexport?ids=' + tomb.join(',');
                        dialogcenter.html('<a href="' + href + '" target="_blank">Letöltés</a>').dialog({
                            resizable: false,
                            height: 140,
                            modal: true,
                            buttons: {
                                'Bezár': function () {
                                    $(this).dialog('close');
                                }
                            }
                        });
                        break;
                    case 'minkeszletexport':
                        href = '/admin/termek/minkeszletexport?ids=' + tomb.join(',');
                        dialogcenter.html('<a href="' + href + '" target="_blank">Letöltés</a>'
                            + (tomb.length ? '' : ' (a teljes terméktörzs)')).dialog({
                            resizable: false,
                            height: 140,
                            modal: true,
                            buttons: {
                                'Bezár': function () {
                                    $(this).dialog('close');
                                }
                            }
                        });
                        break;
                    case 'tcsset':
                        dialogcenter.html($('#tcsset').show()).dialog({
                            resizable: false,
                            height: 140,
                            modal: true,
                            buttons: {
                                'OK': function () {
                                    var dia = $(this);
                                    href = '/admin/termek/tcsset?ids=' + tomb.join(',');
                                    $.ajax({
                                        url: '/admin/termek/tcsset',
                                        type: 'POST',
                                        data: {
                                            ids: tomb,
                                            tcs: $('.js-tcsset').val()
                                        },
                                        success: function () {
                                            dia.dialog('close');
                                            $('#tcsset').hide();
                                            $('.mattable-tablerefresh').click();
                                        }
                                    });
                                },
                                'Mégsem': function () {
                                    $(this).dialog('close');
                                    $('#tcsset').hide();
                                }
                            }

                        });
                        break;
                    case 'kategoriaset':
                        // Termék kategória fa felugró ablakban; a kiválasztott faágba tesszük a kipipált termékeket.
                        dialogcenter.empty().jstree({
                            core: {animation: 100},
                            plugins: ['themeroller', 'json_data', 'ui'],
                            themeroller: {item: ''},
                            json_data: {
                                ajax: {url: '/admin/termekfa/jsonlist'}
                            },
                            ui: {select_limit: 1}
                        })
                            .on('loaded.jstree', function () {
                                dialogcenter.jstree('open_node', $('#termekfa_1', dialogcenter).parent());
                            });
                        dialogcenter.dialog({
                            title: 'Termék kategória módosítás',
                            resizable: true,
                            height: 400,
                            width: 400,
                            modal: true,
                            buttons: {
                                'OK': function () {
                                    var dia = $(this),
                                        faid = 0;
                                    dialogcenter.jstree('get_selected').each(function () {
                                        faid = $(this).children('a').attr('id').split('_')[1];
                                    });
                                    if (!faid) {
                                        return;
                                    }
                                    $.ajax({
                                        url: '/admin/termek/kategoriaset',
                                        type: 'POST',
                                        data: {
                                            ids: tomb,
                                            fa: faid
                                        },
                                        success: function () {
                                            dia.dialog('close');
                                            $('.mattable-tablerefresh').click();
                                        }
                                    });
                                },
                                'Mégsem': function () {
                                    $(this).dialog('close');
                                }
                            }
                        });
                        break;
                    case 'leirastisztitas':
                        // A kijelölt termékek leírásából kiszedjük a html tag-eken lévő style és class attributumokat.
                        dialogcenter.html('Biztos, hogy tisztítja ' + tomb.length + ' termék leírását? A html tag-ekről lekerülnek a style és class attributumok.').dialog({
                            resizable: false,
                            height: 200,
                            modal: true,
                            buttons: {
                                'Igen': function () {
                                    var dia = $(this);
                                    $.ajax({
                                        url: '/admin/termek/leirastisztitas',
                                        type: 'POST',
                                        data: {
                                            ids: tomb
                                        },
                                        success: function () {
                                            dia.dialog('close');
                                            $('.mattable-tablerefresh').click();
                                        }
                                    });
                                },
                                'Nem': function () {
                                    $(this).dialog('close');
                                }
                            }
                        });
                        break;
                }
            } else {
                dialogcenter.html('Válasszon ki legalább egy terméket!').dialog({
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
        });

        $('.js-maincheckbox').change(function () {
            $('.js-egyedcheckbox').prop('checked', $(this).prop('checked'));
        });
        $('#mattable-body').on('click', '.js-flagcheckbox', function (e) {
            function doit(succ) {
                var id = $this.attr('data-id'),
                    flag = $this.attr('data-flag'),
                    kibe = !$this.is('.ui-state-hover');
                if (succ) {
                    succ();
                }
                $.ajax({
                    url: '/admin/termek/setflag',
                    type: 'POST',
                    data: {
                        id: id,
                        flag: flag,
                        kibe: kibe
                    },
                    success: function () {
                        $this.toggleClass('ui-state-hover');
                        if (kibe && (flag == 'nemkaphato')) {
                            $('a[data-id="' + id + '"][data-flag="kiemelt"]').removeClass('ui-state-hover');
                            $('a[data-id="' + id + '"][data-flag="ajanlott"]').removeClass('ui-state-hover');
                            $('a[data-id="' + id + '"][data-flag="uj"]').removeClass('ui-state-hover');
                        }
                    }
                });
            }

            e.preventDefault();
            var $this = $(this);
            if ($this.attr('data-flag') === 'nemkaphato') {
                if (!$this.is('.ui-state-hover')) {
                    dialogcenter.html('Biztos, hogy nem kaphatóvá teszi a terméket? A változatok automatikusan nem elérhetők lesznek.').dialog({
                        resizable: false,
                        height: 200,
                        modal: true,
                        buttons: {
                            'Igen': function () {
                                doit(function () {
                                    dialogcenter.dialog('close');
                                });
                            },
                            'Nem': function () {
                                $(this).dialog('close');
                            }
                        }
                    });
                } else {
                    dialogcenter.html('Ne felejtse el beállítani az elérhető változatokat!').dialog({
                        resizable: false,
                        height: 200,
                        modal: true,
                        buttons: {
                            'OK': function () {
                                doit(function () {
                                    dialogcenter.dialog('close');
                                });
                            }
                        }
                    });
                }
            } else {
                doit();
            }
        });
        bindKeszletRows($('#mattable-body'));

        $('#cimkefiltercontainer').mattaccord({
            header: '#cimkefiltercontainerhead',
            page: '.accordpage',
            closeUp: '.js-cimkefiltercloseupbutton',
            collapse: '#cimkefiltercollapse'
        });
        $('.js-cimkefilter').on('click', function (e) {
            e.preventDefault();
            $(this).toggleClass('ui-state-hover');
        });
        mkwcomp.termekfaFilter.init('#termekfa');
        mkwcomp.termekmenuFilter.init('#termekmenu');
    } else {
        if ($.fn.mattkarb) {
            $('#mattkarb').mattkarb(termek);
        }
    }
});