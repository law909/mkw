var cart = (function ($) {

    function submitMennyEdit(f) {
        let db = $('input[name="mennyiseg"]', f).val(),
            menny = Math.round(db);
        let wasActive = $('.side-cart').hasClass('active');
        $.ajax({
            url: f.attr('action'),
            type: 'POST',
            data: {
                id: $('input[name="id"]', f).val(),
                mennyiseg: menny
            },
            beforeSend: function () {
                //mkw.showMessage(mkwmsg.KosarMennyisegModositas);
            },
            success: function (data) {
                var d = JSON.parse(data);
                $('#minikosar').html(d.minikosar);
                $('#minikosaringyenes').html(d.minikosaringyenes);
                //$('table').html(d.tetellist);
                //mkw.initTooltips();
                $('#ertek_' + $('input[name="id"]', f).val()).text(d.tetelertek);
                $('#kosarsum').text(d.kosarertek);
                if (wasActive) {
                    $('.side-cart').addClass('active');
                }
            },
            complete: function () {
                //mkw.closeMessage();
            }
        });
    }

    function initUI() {
        $(document)
            .on('input', '.js-cart input[name="mennyiseg"]', $.debounce(300, function (e) {
                e.preventDefault();
                console.log('cart input 1');
                var $this = $(this);
                if ((Math.round($this.val()) !== 0)) {
                    submitMennyEdit($this.parents('form.kosarform'));
                }
            }))
            .on('blur', '.js-cart input[name="mennyiseg"]', function (e) {
                e.preventDefault();
                var $this = $(this);
                console.log('cart input blur 1');
                if (Math.round($this.val()) === 0) {
                    $this.val($this.data('org'));
                    submitMennyEdit($this.parents('form.kosarform'));
                    mkw.showDialog(mkwmsg.KosarMennyisegNulla);
                } else {
                    submitMennyEdit($this.parents('form.kosarform'));
                }
            })
            .on('submit', '.js-cart .kosarform', function () {
                console.log('cart input submit 1');
                submitMennyEdit($(this));
                return false;
            })
            .on('click', '.side-cart .js-kosardelbtn', function (e) {
                e.preventDefault();
                var wasActive = $('.side-cart').hasClass('active');
                $.ajax({
                    url: $(this).attr('href'),
                    type: 'POST',
                    data: {
                        jax: 1
                    },
                    success: function (data) {
                        let d = JSON.parse(data);
                        $('#minikosar').html(d.minikosar);
                        $('#minikosaringyenes').html(d.minikosaringyenes);
                        if (wasActive) {
                            $('.side-cart').addClass('active');
                        }
                    }
                });
                return false;
            });
    }

    return {
        initUI: initUI
    };

})(jQuery);