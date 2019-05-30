$(document).ready(function () {
    var dialogcenter = $('#dialogcenter');

    var orarendhelyettesites = {
        container: '#mattkarb',
        viewUrl: '/admin/orarendhelyettesites/getkarb',
        newWindowUrl: '/admin/orarendhelyettesites/viewkarb',
        saveUrl: '/admin/orarendhelyettesites/save',
        beforeShow: function (form, opt) {
            mkwcomp.datumEdit.init('#DatumEdit');
            $('#DatumEdit').on('change', function(e) {
                var d = $('#DatumEdit').datepicker('getDate');
                $.ajax({
                    url: '/admin/orarend/getlistforhelyettesites',
                    type: 'GET',
                    data: {
                        datum: d.getFullYear() + '.' + (d.getMonth() + 1) + '.' + d.getDate()
                    },
                    success: function(data) {
                        if (data) {
                            $('#OrarendEdit').html(data);
                        }
                    }
                });
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
                fields: ['#inaktivfilter', '#elmaradfilter']
            },
            tablebody: {
                url: '/admin/orarendhelyettesites/getlistbody',
                onStyle: function() {
                },
                onDoEditLink: function() {
                }
            },
            karb: orarendhelyettesites
        });

        $('.js-maincheckbox').change(function () {
            $('.js-egyedcheckbox').prop('checked', $(this).prop('checked'));
        });

    }
    else {
        if ($.fn.mattkarb) {
            $('#mattkarb').mattkarb($.extend({}, orarendhelyettesites, {independent: true}));
        }
    }
});