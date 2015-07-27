$(document).ready(function() {

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

});