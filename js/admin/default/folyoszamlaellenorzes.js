$(document).ready(function () {

    $('#mattkarb').mattkarb(new MattkarbConfig({
        beforeShow: function () {

            $('.js-regeneratebutton').on('click', function (e) {
                const url = $(this).attr('href');
                e.preventDefault();
                $('#dialogcenter')
                    .html('Újraképezzük az elavult folyószámla sorokat? A bizonylatok adatai nem változnak,'
                        + ' csak a belőlük származtatott folyószámla sorok állnak elő újra.')
                    .dialog({
                        title: 'Folyószámla újraképzés',
                        resizable: false,
                        width: 480,
                        modal: true,
                        buttons: {
                            'Újraképzés': function () {
                                $(this).dialog('close');
                                $.ajax({
                                    url: url,
                                    type: 'POST',
                                    dataType: 'json',
                                    success: function (d) {
                                        $('#dialogcenter')
                                            .html('Kész. Újraképzett pénztárbizonylat: ' + (d.penztar || 0)
                                                + ', bankbizonylat: ' + (d.bank || 0) + '.')
                                            .dialog({title: 'Folyószámla újraképzés', modal: true});
                                    },
                                    error: function () {
                                        $('#dialogcenter').html('Az újraképzés nem sikerült.')
                                            .dialog({title: 'Folyószámla újraképzés', modal: true});
                                    }
                                });
                            },
                            'Mégsem': function () {
                                $(this).dialog('close');
                            }
                        }
                    });
            }).button();

            $('.js-okbutton, .js-exportbutton').on('click', function (e) {
                let $ff;
                e.preventDefault();
                $ff = $('#folyoszamlaellenorzes');
                $ff.attr('action', $(this).attr('href'));
                $ff.submit();
            }).button();

        }
    }));
});
