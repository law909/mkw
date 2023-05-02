$(document).ready(function () {
    var dialogcenter = $('#dialogcenter');

    var orarend = {
        container: '#mattkarb',
        viewUrl: '/admin/orarend/getkarb',
        newWindowUrl: '/admin/orarend/viewkarb',
        saveUrl: '/admin/orarend/save',
        beforeShow: function (form, opt) {
            $('#JogateremEdit').on('blur', function (e) {
                var $mfe = $('#MaxferohelyEdit');
                if ($mfe.val() * 1 === 0) {
                    $mfe.val($('option:selected', $(this)).data('maxferohely'));
                }
            });
            $('#JogaoratipusEdit').on('blur', function (e) {
                var $mfe = $('#NevEdit');
                if ($mfe.val() === '') {
                    $mfe.val($('option:selected', $(this)).text());
                }
            });
        },
        beforeSerialize: function (form, opt) {
            return true;
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
            name: 'orarend',
            onGetTBody: function () {
            },
            filter: {
                fields: ['#nevfilter', '#inaktivfilter', '#multilangfilter', '#napfilter', '#jogateremfilter', '#jogaoratipusfilter', '#dolgozofilter']
            },
            tablebody: {
                url: '/admin/orarend/getlistbody',
                onStyle: function () {
                },
                onDoEditLink: function () {
                }
            },
            karb: orarend
        });

        $('.mattable-batchbtn').on('click', function (e) {
            var cbs,
                tomb = [];
            e.preventDefault();
            cbs = $('.js-egyedcheckbox:checked');
            if (cbs.length) {
                cbs.closest('tr').each(function (index, elem) {
                    tomb.push($(elem).data('egyedid'));
                });
                switch ($('.mattable-batchselect').val()) {
                    case 'arexport':
                        href = '/admin/orarend/arexport?ids=' + tomb.join(',');
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
                    case 'tcsset':
                        dialogcenter.html($('#tcsset').show()).dialog({
                            resizable: false,
                            height: 140,
                            modal: true,
                            buttons: {
                                'OK': function () {
                                    var dia = $(this);
                                    href = '/admin/orarend/tcsset?ids=' + tomb.join(',');
                                    $.ajax({
                                        url: '/admin/orarend/tcsset',
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
                }
            } else {
                dialogcenter.html('Válasszon ki legalább egy órát!').dialog({
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
                    url: '/admin/orarend/setflag',
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


            if ($this.attr('data-flag') === 'multilang') {
                doit();
            }
            if ($this.attr('data-flag') === 'bejelentkezeskell') {
                doit();
            }
            if ($this.attr('data-flag') === 'bejelentkezesertesitokell') {
                doit();
            }
            if ($this.attr('data-flag') === 'lemondhato') {
                doit();
            }
            if ($this.attr('data-flag') === 'orarendbennincs') {
                doit();
            }
            if ($this.attr('data-flag') === 'inaktiv') {
                if (!$this.is('.ui-state-hover')) {
                    dialogcenter.html('Biztos, hogy inaktívvá teszi az órát? Az óra ezután a naptárból is kikerül.').dialog({
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
                    doit();
                }
            }
        })

    } else {
        if ($.fn.mattkarb) {
            $('#mattkarb').mattkarb($.extend({}, orarend, {independent: true}));
        }
    }
});