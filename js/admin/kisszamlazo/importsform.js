$(document).ready(function () {
    let dialogcenter = $('#dialogcenter');

    $('#mattkarb').mattkarb({
        independent: true,
        viewUrl: '/admin/getkarb',
        newWindowUrl: '/admin/viewkarb',
        saveUrl: '/admin/save',
        beforeShow: function () {

            $('.js-termekbevetimport').on('click', function (e) {
                e.preventDefault();
                let fileInput = $('input[name="toimport_termekbevet"]')[0];
                if (!fileInput || !fileInput.files || !fileInput.files[0]) {
                    alert('Válasszon fájlt.');
                    return;
                }
                let data = new FormData();
                data.append('toimport', fileInput.files[0]);
                $.ajax({
                    type: 'POST',
                    url: $(this).attr('href'),
                    processData: false,
                    contentType: false,
                    data: data,
                    success: function (response) {
                        alert(response);
                    },
                    error: function (xhr) {
                        alert('Hiba: ' + xhr.status + ' ' + xhr.statusText);
                    }
                });
            }).button();
        },
        onSubmit: function () {
            $('#messagecenter')
                .html('A mentés sikerült.')
                .hide()
                .addClass('matt-messagecenter ui-widget ui-state-highlight')
                .one('click', messagecenterclick)
                .slideToggle('slow');
        }
    });
});