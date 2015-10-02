$(document).ready(function() {

    accounting.settings.number.decimal = ',';

    $(document).on('click', function(event) {
        var et = $(event.target);
        if ((!et.hasClass('js-menupont')) && (!et.closest('.submenu').length)) {
            $('ul.navbar-nav li').removeClass('activenav');
            $('ul.navbar-nav li .submenu').hide();
        }
    });

    $('ul.navbar-nav li').on('click', function(e) {
        var $this = $(this),
            active = $this.hasClass('activenav'),
            navlink = $this.children('a');
        e.preventDefault();
        if ($this.data('termekfa')) {
            $('ul.navbar-nav li').removeClass('activenav');
            $('ul.navbar-nav li .submenu').hide();
            if (!active) {
                $this.addClass('activenav');
                $this.children('.submenu').toggle();
            }
        }
        else {
            if (navlink.length > 0) {
                document.location = navlink.attr('href');
            }
        }
    });

    $('.js-headerbtn').on('click', function(e) {
        e.preventDefault();
        document.location = $(this).attr('href');
    });

    $('.js-mennyincrement').on('click', function(e) {
        var input = $('input[name="' + $(this).data('name') + '"]');
        e.preventDefault();
        input.val(input.val() * 1 + 1);
    });
    $('.js-mennydecrement').on('click', function(e) {
        var input = $('input[name="' + $(this).data('name') + '"]');
        e.preventDefault();
        if (input.val() * 1 > 0) {
            input.val(input.val() * 1 - 1);
        }
    });
    $('.js-kedvezmenyinput').on('change', function(e) {
        var $this = $(this),
            kedv = accounting.unformat($this.val()),
            ujar = $this.data('eredetiar') * (100 - kedv) / 100;
        $this.val(kedv);
        $('.js-ar' + $this.data('id')).text(accounting.formatNumber(superz.round(ujar, -2), 2, ' '));
    });
    $('.js-changepartner').on('click', function(e) {

        function doChange(pkod) {
            $.ajax({
                url: '/changepartner',
                type: 'POST',
                data: {
                    partner: pkod
                },
                beforeSend: function() {
                    superz.showMessage('Please wait');
                }
            })
            .done(function(data) {
                window.location.reload(true);
            })
            .fail(function() {
                superz.closeMessage();
            });
        }

        var partnerkod = $('.js-uzletkotopartnerselect').val();
        if ($('#minikosar>span').data('empty') == 1) {
            doChange(partnerkod);
        }
        else {
            superz.showQuestion('We will remove all items from your cart. Are you sure you change customer?',
                {
                    onYes: function() {
                        doChange(partnerkod);
                    }

            });
        }
    });

    $('.js-kosarbabtn').on('click', function(e) {
        var ids = [], vals = [], kedvezmenyek = [], self = $(this);
        e.preventDefault();
        $('.js-mennyiseginput').each(function(index, elem) {
            var el = $(elem),
                kedvinput = $('input[name="kedvezmeny_' + el.data('id') + '"]');
            if (el.val()) {
                ids.push(el.data('id'));
                vals.push(el.val());
                kedvezmenyek.push(kedvinput.val());
                el.val('');
                kedvinput.val(kedvinput.data('eredetikedvezmeny'));
                kedvinput.change();
            }
        });
        var opt = {
            url: self.attr('href'),
            type: 'POST',
            data: {
                termek: self.data('termekid'),
                ids: ids,
                values: vals,
                kedv: kedvezmenyek
            },
            beforeSend: function() {
                superz.showMessage('Please wait');
            }
        };
        $.ajax(opt)
        .done(function(data) {
            var d = JSON.parse(data);
            $('#minikosar').html(d.minikosar);
        })
        .always(function() {
            superz.closeMessage();
        });

    });

    if (typeof cart !== 'undefined') {
        cart.initUI();
    }

    if (typeof checkout !== 'undefined') {
        checkout.initUI();
    }
});