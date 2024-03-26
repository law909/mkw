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
            let termek = ui.item;
            if (termek) {
                let $this = $(this),
                    sorid = $this.attr('name').split('_')[1];
                $this.siblings().val(termek.id);
                $('input[name="tetelnev_' + sorid + '"]').val(termek.value);
            }
        }
    };
}


$(document).ready(function () {
    const dialogcenter = $('#dialogcenter');
    const mattkarbconfig = {
        container: '#mattkarb',
        viewUrl: '/admin/szallitasimod/getkarb',
        newWindowUrl: '/admin/szallitasimod/viewkarb',
        saveUrl: '/admin/szallitasimod/save',
        onSubmit: function () {
            $('#messagecenter')
                .html('A mentés sikerült.')
                .hide()
                .addClass('matt-messagecenter ui-widget ui-state-highlight')
                .one('click', messagecenterclick)
                .slideToggle('slow');
        },
        beforeShow: function () {
            const translationtab = $('#TranslationTab');
            $('.js-termekselect').autocomplete(termekAutocompleteConfig())
                .autocomplete("instance")._renderItem = termekAutocompleteRenderer;
            $('#AltalanosTab').on('click', '.js-termekclear', function (e) {
                e.preventDefault();
                $('.js-termekselect').val(null);
                $('.js-termekid').val(null);
            });
            translationtab.on('click', '.js-translationnewbutton', function (e) {
                const $this = $(this);
                e.preventDefault();
                $.ajax({
                    url: '/admin/szallitasimodtranslation/getemptyrow',
                    type: 'GET',
                    success: function (data) {
                        const tbody = $('#TranslationTab');
                        tbody.append(data);
                        $('.js-translationnewbutton,.js-translationdelbutton').button();
                        $this.remove();
                    }
                });
            })
                .on('click', '.js-translationdelbutton', function (e) {
                    e.preventDefault();
                    var translationgomb = $(this),
                        translationid = translationgomb.attr('data-id'),
                        egyedid = translationgomb.attr('data-egyedid');
                    if (translationgomb.attr('data-source') === 'client') {
                        $('#translationtable_' + translationid).remove();
                    } else {
                        dialogcenter.html('Biztos, hogy törli a fordítást?').dialog({
                            resizable: false,
                            height: 140,
                            modal: true,
                            buttons: {
                                'Igen': function () {
                                    $.ajax({
                                        url: '/admin/szallitasimodtranslation/save',
                                        type: 'POST',
                                        data: {
                                            id: translationid,
                                            egyedid: egyedid,
                                            oper: 'del'
                                        },
                                        success: function (data) {
                                            $('#translationtable_' + data).remove();
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
            $('.js-translationnewbutton,.js-translationdelbutton').button();
            $('#HatarTab').on('click', '.js-hatarnewbutton', function (e) {
                var $this = $(this);
                e.preventDefault();
                $.ajax({
                    url: '/admin/szallitasimodhatar/getemptyrow',
                    type: 'GET',
                    success: function (data) {
                        var tbody = $('#HatarTab');
                        tbody.append(data);
                        $('.js-hatarnewbutton,.js-hatardelbutton').button();
                        $this.remove();
                    }
                });
            })
                .on('click', '.js-hatardelbutton', function (e) {
                    e.preventDefault();
                    var hatargomb = $(this),
                        hatarid = hatargomb.attr('data-id');
                    if (hatargomb.attr('data-source') === 'client') {
                        $('#hatartable_' + hatarid).remove();
                    } else {
                        dialogcenter.html('Biztos, hogy törli a határértéket?').dialog({
                            resizable: false,
                            height: 140,
                            modal: true,
                            buttons: {
                                'Igen': function () {
                                    $.ajax({
                                        url: '/admin/szallitasimodhatar/save',
                                        type: 'POST',
                                        data: {
                                            id: hatarid,
                                            oper: 'del'
                                        },
                                        success: function (data) {
                                            $('#hatartable_' + data).remove();
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
            $('.js-hatarnewbutton,.js-hatardelbutton').button();

            $('#OrszagTab').on('click', '.js-orszagnewbutton', function (e) {
                var $this = $(this);
                e.preventDefault();
                $.ajax({
                    url: '/admin/szallitasimodorszag/getemptyrow',
                    type: 'GET',
                    success: function (data) {
                        var tbody = $('#OrszagTab');
                        tbody.append(data);
                        $('.js-orszagnewbutton,.js-orszagdelbutton').button();
                        $this.remove();
                    }
                });
            })
                .on('click', '.js-orszagdelbutton', function (e) {
                    e.preventDefault();
                    var orszaggomb = $(this),
                        orszagid = orszaggomb.attr('data-id');
                    if (orszaggomb.attr('data-source') === 'client') {
                        $('#orszagtable_' + orszagid).remove();
                    } else {
                        dialogcenter.html('Biztos, hogy törli a határértéket?').dialog({
                            resizable: false,
                            height: 140,
                            modal: true,
                            buttons: {
                                'Igen': function () {
                                    $.ajax({
                                        url: '/admin/szallitasimodorszag/save',
                                        type: 'POST',
                                        data: {
                                            id: orszagid,
                                            oper: 'del'
                                        },
                                        success: function (data) {
                                            $('#orszagtable_' + data).remove();
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
            $('.js-orszagnewbutton,.js-orszagdelbutton').button();

            $('#FizmodTab').on('click', '.js-fizmodnewbutton', function (e) {
                var $this = $(this);
                e.preventDefault();
                $.ajax({
                    url: '/admin/szallitasimodfizmodnovelo/getemptyrow',
                    type: 'GET',
                    success: function (data) {
                        var tbody = $('#FizmodTab');
                        tbody.append(data);
                        $('.js-fizmodnewbutton,.js-fizmoddelbutton').button();
                        $this.remove();
                    }
                });
            })
                .on('click', '.js-fizmoddelbutton', function (e) {
                    e.preventDefault();
                    var fizmodgomb = $(this),
                        fizmodid = fizmodgomb.attr('data-id');
                    if (fizmodgomb.attr('data-source') === 'client') {
                        $('#fizmodtable_' + fizmodid).remove();
                    } else {
                        dialogcenter.html('Biztos, hogy törli a növelőt?').dialog({
                            resizable: false,
                            height: 140,
                            modal: true,
                            buttons: {
                                'Igen': function () {
                                    $.ajax({
                                        url: '/admin/szallitasimodfizmodnovelo/save',
                                        type: 'POST',
                                        data: {
                                            id: fizmodid,
                                            oper: 'del'
                                        },
                                        success: function (data) {
                                            $('#fizmodtable_' + data).remove();
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
            $('.js-fizmodnewbutton,.js-fizmoddelbutton').button();
        }
    };

    if ($.fn.mattable) {
        $('#mattable-select').mattable({
            filter: {
                fields: ['#nevfilter']
            },
            tablebody: {
                url: '/admin/szallitasimod/getlistbody'
            },
            karb: mattkarbconfig
        });
        $('#maincheckbox').change(function () {
            $('.egyedcheckbox').attr('checked', $(this).attr('checked'));
        });
    } else {
        if ($.fn.mattkarb) {
            $('#mattkarb').mattkarb($.extend({}, mattkarbconfig, {independent: true}));
        }
    }
});