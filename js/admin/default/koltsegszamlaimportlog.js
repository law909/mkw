$(document).ready(function () {

    var mattkarbconfig = new MattkarbConfig({
        entityName: 'koltsegszamlaimportlog'
    });

    if ($.fn.mattable) {
        $('#mattable-select').mattable({
            filter: {
                fields: ['#szamlaszamfilter', '#szallitofilter', '#statuszfilter', '#hibasfilter']
            },
            tablebody: {
                url: '/admin/koltsegszamlaimportlog/getlistbody'
            },
            karb: mattkarbconfig
        });
    } else {
        if ($.fn.mattkarb) {
            $('#mattkarb').mattkarb(mattkarbconfig);
        }
    }
});
