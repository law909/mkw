$(document).ready(function(){
    var mattkarbconfig={
        container:'#mattkarb',
        viewUrl:'/admin/feketelista/getkarb',
        newWindowUrl:'/admin/feketelista/viewkarb',
        saveUrl:'/admin/feketelista/save',
        onSubmit:function() {
            $('#messagecenter')
                .html('A mentés sikerült.')
                .hide()
                .addClass('matt-messagecenter ui-widget ui-state-highlight')
                .one('click',messagecenterclick)
                .slideToggle('slow');
        }
    }

    if ($.fn.mattable) {
        $('#mattable-select').mattable({
            filter:{
                fields:['#emailfilter']
            },
            tablebody:{
                url:'/admin/feketelista/getlistbody'
            },
            karb:mattkarbconfig
        });
        $('#maincheckbox').change(function(){
            $('.egyedcheckbox').attr('checked',$(this).attr('checked'));
        });
    }
    else {
        if ($.fn.mattkarb) {
            $('#mattkarb').mattkarb($.extend({},mattkarbconfig,{independent:true}));
        }
    }
});