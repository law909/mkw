function irszamAutocomplete(irszam, varos) {
    $(irszam).autocomplete({
        minLength: 2,
        source: function (req, resp) {
            $.ajax({
                url: '/admin/irszam',
                type: 'GET',
                data: {
                    term: req.term,
                    tip: 1
                },
                success: function (data) {
                    var d = JSON.parse(data);
                    resp(d);
                },
                error: function () {
                    resp();
                }
            });
        },
        select: function (event, ui) {
            $(varos).val(ui.item.nev);
        }
    });
}

function varosAutocomplete(irszam, varos) {
    $(varos).autocomplete({
        minLength: 4,
        source: function (req, resp) {
            $.ajax({
                url: '/admin/varos',
                type: 'GET',
                data: {
                    term: req.term,
                    tip: 1
                },
                success: function (data) {
                    var d = JSON.parse(data);
                    resp(d);
                },
                error: function () {
                    resp();
                }
            });
        },
        select: function (event, ui) {
            $(irszam).val(ui.item.szam);
        }
    });
}

function termekAutocompleteRenderer(ul, item) {
    if (item.nemlathato) {
        return $('<li>')
            .append('<a class="nemelerhetovaltozat">' + item.label + '</a>')
            .appendTo(ul);
    } else {
        return $('<li>')
            .append('<a>' + item.label + '</a>')
            .appendTo(ul);
    }
}

function termekAutocompleteConfig() {
    return {
        minLength: 4,
        autoFocus: true,
        source: '/admin/bizonylattetel/gettermeklist',
        select: function (event, ui) {
            var termek = ui.item;
            if (termek) {
                var $this = $(this),
                    sorid = $this.attr('name').split('_')[1];
                $this.siblings().val(termek.id);
            }
        }
    };
}


$(document).ready(function () {
    var dialogcenter = $('#dialogcenter');
    var partner = {
        container: '#mattkarb',
        viewUrl: '/admin/partner/getkarb',
        newWindowUrl: '/admin/partner/viewkarb',
        saveUrl: '/admin/partner/save',
        beforeShow: function () {
            var szuletesiidoedit = $('#SzuletesiidoEdit'),
                mpt_tagsagdateedit = $('#MPTTagsagdateEdit'),
                mptngybefdatumedit = $('#MptngyBefdatumEdit'),
                termekcsoportkedvezmenytab = $('#KedvezmenyTab'),
                termekkedvezmenytab = $('#TermekKedvezmenyTab'),
                doktab = $('#DokTab'),
                mijszokleveltab = $('#MIJSZOklevelTab');

            mkwcomp.datumEdit.init(mpt_tagsagdateedit);
            mkwcomp.datumEdit.init(szuletesiidoedit);
            mkwcomp.datumEdit.init(mptngybefdatumedit);

            $('#EmailEdit').on('change', function (e) {
                $('.js-email').text($(this).val());
            });
            $('#cimkekarbcontainer').mattaccord({
                header: '',
                page: '.js-cimkekarbpage',
                closeUp: '.js-cimkekarbcloseupbutton'
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
                        url: '/admin/partnercimke/add',
                        type: 'POST',
                        data: {
                            cimkecsoport: katkod,
                            nev: cimkenev
                        },
                        success: function (data) {
                            $(ref).val('');
                            $(ref).before(data);
                        }
                    });
                }
            });
            doktab
                .on('click', '.js-doknewbutton', function (e) {
                    var $this = $(this);
                    e.preventDefault();
                    $.ajax({
                        url: '/admin/rendezvenydok/getemptyrow',
                        type: 'GET',
                        success: function (data) {
                            doktab.append(data);
                            $('.js-doknewbutton,.js-dokdelbutton,.js-dokbrowsebutton,.js-dokopenbutton').button();
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
                                    url: '/admin/rendezvenydok/del',
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
                    var finder = new CKFinder(),
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
            $('.js-doknewbutton,.js-dokbrowsebutton,.js-dokdelbutton,.js-dokopenbutton').button();
            termekcsoportkedvezmenytab.on('click', '.js-termekcsoportkedvezmenynewbutton', function (e) {
                var $this = $(this);
                e.preventDefault();
                $.ajax({
                    url: '/admin/partnertermekcsoportkedvezmeny/getemptyrow',
                    type: 'GET',
                    success: function (data) {
                        var tbody = $('#KedvezmenyTab');
                        tbody.append(data);
                        $('.js-termekcsoportkedvezmenynewbutton,.js-termekcsoportkedvezmenydelbutton').button();
                        $this.remove();
                    }
                });
            })
                .on('click', '.js-termekcsoportkedvezmenydelbutton', function (e) {
                    e.preventDefault();
                    var argomb = $(this),
                        arid = argomb.attr('data-id');
                    if (argomb.attr('data-source') === 'client') {
                        $('#kedvezmenytable_' + arid).remove();
                    } else {
                        dialogcenter.html('Biztos, hogy törli a kedvezményt?').dialog({
                            resizable: false,
                            height: 140,
                            modal: true,
                            buttons: {
                                'Igen': function () {
                                    $.ajax({
                                        url: '/admin/partnertermekcsoportkedvezmeny/save',
                                        type: 'POST',
                                        data: {
                                            id: arid,
                                            oper: 'del'
                                        },
                                        success: function (data) {
                                            $('#kedvezmenytable_' + data).remove();
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
            $('.js-termekcsoportkedvezmenynewbutton, .js-termekcsoportkedvezmenydelbutton').button();
            termekkedvezmenytab.on('click', '.js-termekkedvezmenynewbutton', function (e) {
                var $this = $(this);
                e.preventDefault();
                $.ajax({
                    url: '/admin/partnertermekkedvezmeny/getemptyrow',
                    type: 'GET',
                    success: function (data) {
                        var tbody = $('#TermekKedvezmenyTab');
                        tbody.append(data);
                        $('.js-termekkedvezmenynewbutton,.js-termekkedvezmenydelbutton').button();
                        $('.js-termekkedvezmenytermekselect').autocomplete(termekAutocompleteConfig())
                            .autocomplete("instance")._renderItem = termekAutocompleteRenderer;
                        $this.remove();
                    }
                });
            })
                .on('click', '.js-termekkedvezmenydelbutton', function (e) {
                    e.preventDefault();
                    var argomb = $(this),
                        arid = argomb.attr('data-id');
                    if (argomb.attr('data-source') === 'client') {
                        $('#termekkedvezmenytable_' + arid).remove();
                    } else {
                        dialogcenter.html('Biztos, hogy törli a kedvezményt?').dialog({
                            resizable: false,
                            height: 140,
                            modal: true,
                            buttons: {
                                'Igen': function () {
                                    $.ajax({
                                        url: '/admin/partnertermekkedvezmeny/save',
                                        type: 'POST',
                                        data: {
                                            id: arid,
                                            oper: 'del'
                                        },
                                        success: function (data) {
                                            $('#termekkedvezmenytable_' + data).remove();
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
            $('.js-termekkedvezmenynewbutton, .js-termekkedvezmenydelbutton').button();
            $('.js-termekkedvezmenytermekselect').autocomplete(termekAutocompleteConfig())
                .autocomplete("instance")._renderItem = termekAutocompleteRenderer;

            mijszokleveltab.on('click', '.js-mijszoklevelnewbutton', function (e) {
                var $this = $(this);
                e.preventDefault();
                $.ajax({
                    url: '/admin/partnermijszoklevel/getemptyrow',
                    type: 'GET',
                    success: function (data) {
                        var tbody = $('#MIJSZOklevelTab');
                        tbody.append(data);
                        $('.js-mijszoklevelnewbutton,.js-mijszokleveldelbutton').button();
                        $this.remove();
                    }
                });
            })
                .on('click', '.js-mijszokleveldelbutton', function (e) {
                    e.preventDefault();
                    var argomb = $(this),
                        arid = argomb.attr('data-id');
                    if (argomb.attr('data-source') === 'client') {
                        $('#mijszokleveltable_' + arid).remove();
                    } else {
                        dialogcenter.html('Biztos, hogy törli az oklevelet?').dialog({
                            resizable: false,
                            height: 140,
                            modal: true,
                            buttons: {
                                'Igen': function () {
                                    $.ajax({
                                        url: '/admin/partnermijszoklevel/save',
                                        type: 'POST',
                                        data: {
                                            id: arid,
                                            oper: 'del'
                                        },
                                        success: function (data) {
                                            $('#mijszokleveltable_' + data).remove();
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
            $('.js-mijszoklevelnewbutton,.js-mijszokleveldelbutton').button();

            irszamAutocomplete('#IrszamEdit', '#VarosEdit');
            varosAutocomplete('#IrszamEdit', '#VarosEdit');
            irszamAutocomplete('#SzamlaIrszamEdit', '#SzamlaVarosEdit');
            varosAutocomplete('#SzamlaIrszamEdit', '#SzamlaVarosEdit');
            irszamAutocomplete('#SzallIrszamEdit', '#SzallVarosEdit');
            varosAutocomplete('#SzallIrszamEdit', '#SzallVarosEdit');
        },
        beforeSerialize: function (form, opt) {
            var cimkek = [],
                j1 = $('#Jelszo1Edit').val(),
                j2 = $('#Jelszo2Edit').val();
            $('.js-cimkekarb').filter('.js-selectedcimke').each(function () {
                cimkek.push($(this).attr('data-id'));
            });
            var x = {};
            x['cimkek[]'] = cimkek;
            opt['data'] = x;
            if ((j1 || j2) && j1 !== j2) {
                dialogcenter.html('A két jelszó nem egyezik meg!').dialog({
                    resizable: false,
                    height: 140,
                    modal: true,
                    buttons: {
                        'Ok': function () {
                            $(this).dialog('close');
                        }
                    }
                });
                return false;
            }
        },
        onSubmit: function () {
            $('#messagecenter')
                .html('A mentés sikerült.')
                .hide()
                .addClass('matt-messagecenter ui-widget ui-state-highlight')
                .one('click', messagecenterclick)
                .slideToggle('slow');
            //setTimeout('$("#messagecenter").unbind(messagecenterclick).slideToggle("slow");',5000);
        }
    };

    if ($.fn.mattable) {
        $('#mattable-select').mattable({
            name: 'partner',
            filter: {
                fields: [
                    '#nevfilter',
                    '#emailfilter',
                    '#szallitasiirszamfilter',
                    '#szallitasivarosfilter',
                    '#szallitasiutcafilter',
                    '#szamlazasiirszamfilter',
                    '#szamlazasivarosfilter',
                    '#szamlazasiutcafilter',
                    '#beszallitofilter',
                    '#partnertipusfilter',
                    '#orszagfilter',
                    '#inaktivfilter',
                    '#mptngyreszvetelfilter',
                    '#mptngydiakfilter',
                ],
                onClear: function () {
                    $('.js-cimkefilter').removeClass('ui-state-hover');
                },
                onFilter: function (obj) {
                    var cimkek = new Array();
                    $('.js-cimkefilter').filter('.ui-state-hover').each(function () {
                        cimkek.push($(this).attr('data-id'));
                    });
                    if (cimkek.length > 0) {
                        obj['cimkefilter'] = cimkek;
                    }
                }
            },
            tablebody: {
                url: '/admin/partner/getlistbody',
                onStyle: function () {
                    $('.js-anonym').button();
                },
                onDoEditLink: function () {
                    $('#mattable-table').on('.js-anonym', 'click', function (e) {
                        var $this = $(this);
                        e.preventDefault();
                        dialogcenter.html('Biztos, hogy anonymizálja a partnert?').dialog({
                            resizable: false,
                            height: 140,
                            modal: true,
                            buttons: {
                                'Igen': function () {
                                    $.ajax({
                                        url: '/admin/partner/anonym/check',
                                        type: 'POST',
                                        data: {
                                            id: $this.data('partnerid')
                                        },
                                        success: function (data) {
                                            $('#mijszokleveltable_' + data).remove();
                                        }
                                    });
                                    $(this).dialog('close');
                                },
                                'Nem': function () {
                                    $(this).dialog('close');
                                }
                            }
                        });
                    });

                }
            },
            karb: partner
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
                    url: '/admin/partner/setflag',
                    type: 'POST',
                    data: {
                        id: id,
                        flag: flag,
                        kibe: kibe
                    },
                    success: function () {
                        $this.toggleClass('ui-state-hover');
                    }
                });
            }

            e.preventDefault();
            var $this = $(this);
            doit();
        })
        $('.mattable-batchbtn').on('click', function (e) {
            var cbs;
            e.preventDefault();
            cbs = $('.js-egyedcheckbox:checked');
            if (cbs.length) {
                var tomb = [], $exportform, $sel;
                cbs.closest('tr').each(function (index, elem) {
                    tomb.push($(elem).data('egyedid'));
                });
                switch ($('.mattable-batchselect').val()) {
                    case 'mijszexportin':
                        $exportform = $('#exportform');
                        $exportform.attr('action', '/admin/partner/mijszexport');
                        $('input[name="ids"]', $exportform).val(tomb);
                        $('input[name="country"]', $exportform).val('in');
                        $exportform.submit();
                        break;
                    case 'mijszexportus':
                        $exportform = $('#exportform');
                        $exportform.attr('action', '/admin/partner/mijszexport');
                        $('input[name="ids"]', $exportform).val(tomb);
                        $('input[name="country"]', $exportform).val('us');
                        $exportform.submit();
                        break;
                    case 'megjegyzesexport':
                        $exportform = $('#exportform');
                        $exportform.attr('action', '/admin/partner/megjegyzesexport');
                        $('input[name="ids"]', $exportform).val(tomb);
                        $exportform.submit();
                        break;
                    case 'hirlevelexport':
                        $exportform = $('#exportform');
                        $exportform.attr('action', '/admin/partner/hirlevelexport');
                        $exportform.submit();
                        break;
                    case 'roadrecordexport':
                        $exportform = $('#exportform');
                        $exportform.attr('action', '/admin/partner/roadrecordexport');
                        if (tomb) {
                            $('input[name="ids"]', $exportform).val(tomb);
                        }
                        $exportform.submit();
                        break;
                    case 'arsavcsere':
                        $sel = $('select.js-arsavselect');
                        dialogcenter.html($('#arsavcsere').show()).dialog({
                            resizable: false,
                            height: 140,
                            modal: true,
                            buttons: {
                                'OK': function () {
                                    var $this = $(this);
                                    $.ajax({
                                        url: '/admin/partner/arsavcsere',
                                        type: 'POST',
                                        data: {
                                            ids: tomb,
                                            arsav: $('option:selected', $sel).val()
                                        },
                                        success: function (data) {
                                            $('option:selected', $sel).val(data);
                                            $this.dialog('close');
                                            $('#arsavcsere').hide();
                                            $('.mattable-tablerefresh').click();
                                        }
                                    });
                                },
                                'Mégsem': function () {
                                    $(this).dialog('close');
                                    $('#arsavcsere').hide();
                                }
                            }
                        });
                        break;
                    case 'termekcsoportkedvezmenyedit':
                        $sel = $('select[name="tcsktermekcsoport"]');
                        dialogcenter.html($('#termekcsoportkedvezmenyedit').show()).dialog({
                            resizable: false,
                            height: 140,
                            modal: true,
                            buttons: {
                                'OK': function () {
                                    var $this = $(this);
                                    $.ajax({
                                        url: '/admin/partner/tcskedit',
                                        type: 'POST',
                                        data: {
                                            ids: tomb,
                                            tcs: $('option:selected', $sel).val(),
                                            kedv: $('input.js-tcskkedvvaltozas').val()
                                        },
                                        success: function (data) {
                                            $('option:selected', $sel).val(data);
                                            $this.dialog('close');
                                            $('#termekcsoportkedvezmenyedit').hide();
                                            $('.mattable-tablerefresh').click();
                                        }
                                    });
                                },
                                'Mégsem': function () {
                                    $(this).dialog('close');
                                    $('#termekcsoportkedvezmenyedit').hide();
                                }
                            }
                        });
                        break;
                    case 'sendemailsablon':
                        let $dia = $('#emailsablondialog');
                        $dia.dialog({
                            title: 'Email sablon',
                            resizable: true,
                            height: 140,
                            modal: true,
                            buttons: {
                                'OK': function () {
                                    var dial = $(this),
                                        sablon = $('select[name="emailsablon"]').val();
                                    $('select[name="emailsablon"]').val('');
                                    $.ajax({
                                        url: '/admin/partner/sendemailsablonok',
                                        type: 'POST',
                                        data: {
                                            ids: tomb,
                                            sablon: sablon
                                        },
                                        success: function () {
                                            dial.dialog('close');
                                        }
                                    });
                                },
                                'Mégsem': function () {
                                    $(this).dialog('close');
                                }
                            }
                        });
                        break;
                }
            } else {
                dialogcenter.html('Válasszon ki legalább egy partnert!').dialog({
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
        $('#mattable-body').on('click', '.js-anonym', function (e) {
            var $this = $(this);
            e.preventDefault();
            dialogcenter.html('Biztos, hogy anonymizálja a partnert? A folyamat NEM fordítható vissza.').dialog({
                resizable: false,
                height: 140,
                modal: true,
                buttons: {
                    'Igen': function () {
                        $.ajax({
                            url: '/admin/partner/anonym/do',
                            type: 'POST',
                            data: {
                                id: $this.data('partnerid')
                            },
                            success: function (data) {
                                dialogcenter.html('Az anonymizálás kész.').dialog({
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
                        $(this).dialog('close');
                    },
                    'Nem': function () {
                        $(this).dialog('close');
                    }
                }
            });
        });
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
        $('.js-maincheckbox').change(function () {
            $('.js-egyedcheckbox').prop('checked', $(this).prop('checked'));
        });
    } else {
        if ($.fn.mattkarb) {
            $('#mattkarb').mattkarb($.extend({}, partner, {independent: true}));
        }
    }
});