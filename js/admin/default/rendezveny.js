$(document).ready(function() {
    var dialogcenter = $('#dialogcenter');

    var rendezveny = {
        container: '#mattkarb',
        viewUrl: '/admin/rendezveny/getkarb',
        newWindowUrl: '/admin/rendezveny/viewkarb',
        saveUrl: '/admin/rendezveny/save',
        beforeShow: function() {
            var doktab = $('#DokTab');
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
            mkwcomp.datumEdit.init('#KezdodatumEdit');
        },
        onSubmit: function() {
            $('#messagecenter')
                    .html('A mentés sikerült.')
                    .hide()
                    .addClass('matt-messagecenter ui-widget ui-state-highlight')
                    .one('click', messagecenterclick)
                    .slideToggle('slow');
        }
    };

    if ($.fn.mattable) {
        $('#mattable-select').mattable({
            filter: {
                fields: ['#nevfilter', '#tanarfilter', '#teremfilter', '#allapotfilter', '#TolEdit', '#IgEdit']
            },
            tablebody: {
                url: '/admin/rendezveny/getlistbody',
                onStyle: function() {
                    new ClipboardJS('.js-uidcopy');
                }
            },
            karb: rendezveny
        });
        $('.js-maincheckbox').change(function() {
            $('.js-egyedcheckbox').prop('checked', $(this).prop('checked'));
        });
        $('#mattable-body').on('click', '.js-flagcheckbox', function (e) {
            function doit(succ) {
                var id = $this.attr('data-id'),
                    flag = $this.attr('data-flag'),
                    kibe = $this.is('.ui-state-hover');
                if (succ) {
                    succ();
                }
                $.ajax({
                    url: '/admin/rendezveny/setflag',
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
    }
    else {
        if ($.fn.mattkarb) {
            $('#mattkarb').mattkarb($.extend({}, rendezveny, {independent: true}));
        }
    }

    mkwcomp.datumEdit.init('#TolEdit');
    mkwcomp.datumEdit.init('#IgEdit');

});