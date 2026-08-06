$(document).ready(function () {
    const dialogcenter = $('#dialogcenter');
    const mattkarbconfig = new MattkarbConfig({
        entityName: 'fizetesimod',
        beforeShow: function () {
            $('#HatarTab').on('click', '.js-hatarnewbutton', function (e) {
                var $this = $(this);
                e.preventDefault();
                $.ajax({
                    url: '/admin/fizmodhatar/getemptyrow',
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
                                        url: '/admin/fizmodhatar/save',
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

        }
    });

    if ($.fn.mattable) {
        $('#mattable-select').mattable({
            filter: {
                fields: ['#nevfilter']
            },
            tablebody: {
                url: '/admin/fizetesimod/getlistbody'
            },
            karb: mattkarbconfig
        });
        $('#maincheckbox').change(function () {
            $('.egyedcheckbox').prop('checked', $(this).prop('checked'));
        });
    } else {
        if ($.fn.mattkarb) {
            $('#mattkarb').mattkarb(mattkarbconfig);
        }
    }
});