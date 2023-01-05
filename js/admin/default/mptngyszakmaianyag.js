$(document).ready(function () {

    function szimpoziumShow() {
        let sb = document.getElementById('tipusEdit'),
            so = sb.options[sb.selectedIndex];
        if (so.dataset['szimpozium']) {
            document.querySelectorAll('.onlyszimpozium').forEach((elem) => {
                elem.classList.remove('hidden');
            });
        }
        else {
            document.querySelectorAll('.onlyszimpozium').forEach((elem) => {
                elem.classList.add('hidden');
            });
        }
    }

    let mattkarbconfig = {
        container: '#mattkarb',
        viewUrl: '/admin/mptngyszakmaianyag/getkarb',
        newWindowUrl: '/admin/mptngyszakmaianyag/viewkarb',
        saveUrl: '/admin/mptngyszakmaianyag/save',
        beforeShow: function () {
            mkwcomp.datumEdit.init('#kezdodatumEdit');
            szimpoziumShow();
            let sb = document.getElementById('tipusEdit');
            sb.addEventListener('change', (event) => {
                szimpoziumShow();
            })
        },
        onSubmit: function () {
            $('#messagecenter')
                .html('A mentés sikerült.')
                .hide()
                .addClass('matt-messagecenter ui-widget ui-state-highlight')
                .one('click', messagecenterclick)
                .slideToggle('slow');
        }
    }

    if ($.fn.mattable) {
        $('#mattable-select').mattable({
            filter: {
                fields: ['#cimfilter']
            },
            tablebody: {
                url: '/admin/mptngyszakmaianyag/getlistbody'
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