$(document).ready(function () {

    $('#mattkarb').mattkarb(new MattkarbConfig({
        beforeShow: function () {
            $('.js-grandoexport').button();
        },
    }));
});