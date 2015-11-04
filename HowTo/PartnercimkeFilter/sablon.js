



$('').mattable({
    filter:{
        onClear:function() {

            $('.js-cimkefilter').removeClass('ui-state-hover');

        },
        onFilter:function(obj) {

            var cimkek = new Array();
            $('.js-cimkefilter').filter('.ui-state-hover').each(function() {
                cimkek.push($(this).attr('data-id'));
            });
            if (cimkek.length>0) {
                obj['cimkefilter'] = cimkek;
            }

        }
    },
});

$('#cimkefiltercontainer').mattaccord({
    header: '',
    page: '.js-cimkefilterpage',
    closeUp: '.js-cimkefiltercloseupbutton'
});
$('.js-cimkefilter').on('click', function(e) {
    e.preventDefault();
    $(this).toggleClass('ui-state-hover');
});
