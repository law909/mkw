$(document).ready(function() {

    var rendezveny = {
        container: '#mattkarb',
        viewUrl: '/admin/rendezveny/getkarb',
        newWindowUrl: '/admin/rendezveny/viewkarb',
        saveUrl: '/admin/rendezveny/save',
        beforeShow: function() {
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
                url: '/admin/rendezveny/getlistbody'
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