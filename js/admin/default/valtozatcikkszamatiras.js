$(document).ready(() => {

    $('#mattkarb').mattkarb(new MattkarbConfig({
        beforeShow: () => {
            const $uzenet = $('.js-atirasuzenet');

            $('.js-atirasbutton').on('click', function (e) {
                e.preventDefault();
                const $gomb = $(this);
                $uzenet.text('Átírás folyik…');
                $gomb.button('disable');
                $.ajax({
                    type: 'POST',
                    url: $gomb.attr('href'),
                    dataType: 'json',
                    success: (d) => {
                        $uzenet.text((d && d.ok) ? d.msg : ((d && d.error) ? d.error : 'Az átírás nem sikerült.'));
                        if (d && d.ok) {
                            $('.js-erintett').text(d.erintett);
                        }
                        $gomb.button('enable');
                    },
                    error: () => {
                        $uzenet.text('Az átírás nem sikerült.');
                        $gomb.button('enable');
                    }
                });
            }).button();
        }
    }));
});
