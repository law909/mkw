$(document).ready(function() {
    var dialogcenter=$('#dialogcenter');

    $('#mattkarb').mattkarb({
        independent: true,
        beforeShow: function() {

            $('.js-emailbutton, .js-downloadbutton').button();

            $('.js-downloadbutton').on('click', function(e) {
                var $ff;
                e.preventDefault();
                $ff = $('#pdfszamlaexport');
                $ff.attr('action', $(this).attr('href'));
                $ff.submit();
            });

            $('.js-emailbutton').on('click', function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: $(this).attr('href'),
                    data: {
                        utolsoszamla: $('#utolsoszamlainput').val(),
                        utolsoesetiszamla: $('#utolsoesetiszamlainput').val()
                    },
                    success: function(d) {
                        if (!d) {
                            alert('Kész.');
                        }
                        else {
                            var adat = JSON.parse(d);
                            if (adat.url) {
                                document.location = adat.url;
                            }
                            else {
                                if (adat.msg) {
                                    alert(adat.msg);
                                }
                            }
                        }
                    }
                });
            });

        }
    });
});