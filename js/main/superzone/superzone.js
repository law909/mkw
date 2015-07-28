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
        input.val(input.val() * 1 - 1);
    });

});