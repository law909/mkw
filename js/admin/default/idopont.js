$(document).ready(function () {
    const dialogcenter = $('#dialogcenter');

    const mattkarbconfig = new MattkarbConfig({
        entityName: 'idopont',
        beforeShow: function () {
            // a vég a kezdetet követi, amíg a felhasználó nem nyúl hozzá
            $('#KezdetEdit').on('change', function () {
                const $veg = $('#VegEdit');
                if (!$veg.val()) {
                    $veg.val($(this).val());
                }
            });

            // az egyszeri és az ismétlődő megadás kizárja egymást. A vég nem kötelező: az átvett
            // rendezvényeknek csak kezdő időpontjuk volt
            function toggleIsmetlodo() {
                const ismetlodo = $('#IsmetlodoCheck').is(':checked');
                $('.js-egyszeriblokk').toggle(!ismetlodo);
                $('.js-ismetlodoblokk').toggle(ismetlodo);
                $('#KezdetEdit').prop('required', !ismetlodo);
                $('#NapEdit, #KezdetidoEdit').prop('required', ismetlodo);
            }

            $('#IsmetlodoCheck').on('change', toggleIsmetlodo);
            toggleIsmetlodo();

            // a rendezvény-specifikus blokk (állapot, webcímek, órarend) csak ott kell
            function toggleTipus() {
                $('.js-rendezvenyblokk').toggle($('#TipusEdit').val() === 'rendezveny');
            }

            $('#TipusEdit').on('change', toggleTipus);
            toggleTipus();

            const doktab = $('#DokTab');
            doktab
                .on('click', '.js-doknewbutton', function (e) {
                    const $this = $(this);
                    e.preventDefault();
                    $.ajax({
                        url: '/admin/idopontdok/getemptyrow',
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
                    const $this = $(this);
                    dialogcenter.html('Biztos, hogy törli a dokumentumot?').dialog({
                        resizable: false,
                        height: 140,
                        modal: true,
                        buttons: {
                            'Igen': function () {
                                $.ajax({
                                    url: '/admin/idopontdok/del',
                                    type: 'POST',
                                    data: {id: $this.attr('data-id')},
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
                    const finder = new CKFinder(),
                        $dokpathedit = $('#DokPathEdit_' + $(this).attr('data-id')),
                        path = $dokpathedit.val();
                    finder.resourceType = 'Images';
                    if (path) {
                        finder.startupPath = path.substring(path.indexOf('/', 1));
                    }
                    finder.selectActionFunction = function (fileUrl) {
                        $dokpathedit.val(fileUrl);
                    };
                    finder.popup();
                });
            $('.js-doknewbutton,.js-dokbrowsebutton,.js-dokdelbutton,.js-dokopenbutton,.js-dokopen2button').button();
            mkwcomp.datumEdit.init('#EarlybirdvegeEdit');
            new ClipboardJS('.js-uidcopy');
        }
    });

    if ($.fn.mattable) {
        const datumtolfilter = $('#datumtolfilter'),
            datumigfilter = $('#datumigfilter');

        datumtolfilter.datepicker($.datepicker.regional['hu']);
        datumtolfilter.datepicker('option', 'dateFormat', 'yy.mm.dd');
        datumigfilter.datepicker($.datepicker.regional['hu']);
        datumigfilter.datepicker('option', 'dateFormat', 'yy.mm.dd');

        $('#mattable-select').mattable({
            filter: {
                fields: [
                    '#tipusfilter',
                    '#nevfilter',
                    '#datumtolfilter',
                    '#datumigfilter',
                    '#dolgozofilter',
                    '#idoponttemafilter',
                    '#jogahelyszinfilter',
                    '#idopontallapotfilter',
                    '#inaktivfilter',
                    '#ismetlodofilter'
                ]
            },
            tablebody: {
                url: '/admin/idopont/getlistbody',
                onStyle: function () {
                    new ClipboardJS('.js-uidcopy');
                    $('.js-emailkezdes').button();
                }
            },
            karb: mattkarbconfig
        });

        $('.js-maincheckbox').change(function () {
            $('.js-egyedcheckbox').prop('checked', $(this).prop('checked'));
        });

        $('#mattable-body')
            .on('click', '.js-flagcheckbox', function (e) {
                e.preventDefault();
                const $this = $(this);
                $.ajax({
                    url: '/admin/idopont/setflag',
                    type: 'POST',
                    data: {
                        id: $this.attr('data-id'),
                        flag: $this.attr('data-flag'),
                        kibe: !$this.is('.ui-state-hover')
                    },
                    success: function () {
                        $this.toggleClass('ui-state-hover');
                    }
                });
            })
            .on('click', '.js-emailkezdes', function (e) {
                e.preventDefault();
                const $gomb = $(this);
                $.ajax({
                    url: '/admin/idopont/email/kezdes',
                    type: 'POST',
                    data: {id: $gomb.data('egyedid')},
                    success: function (data) {
                        const d = JSON.parse(data);
                        dialogcenter.html(d.msg).dialog({
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
            });
    } else {
        if ($.fn.mattkarb) {
            $('#mattkarb').mattkarb(mattkarbconfig);
        }
    }
});
