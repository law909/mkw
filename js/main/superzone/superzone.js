$(document).ready(function() {

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

    $('.js-kosarbabtn').on('click', function(e) {
        var ids = [], vals = [], self = $(this);
        e.preventDefault();
        $('.js-mennyiseginput').each(function(index, elem) {
            var el = $(elem);
            if (el.val()) {
                ids.push(el.data('id'));
                vals.push(el.val());
                el.val('');
            }
        });
        var opt = {
            url: self.attr('href'),
            type: 'POST',
            data: {
                termek: self.data('termekid'),
                ids: ids,
                values: vals
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

    if (cart) {
        cart.initUI();
    }
});