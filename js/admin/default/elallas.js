$(document).ready(function(){
    var mattkarbconfig={
        container:'#mattkarb',
        viewUrl:'/admin/elallas/getkarb',
        newWindowUrl:'/admin/elallas/viewkarb',
        saveUrl:'/admin/elallas/save',
        beforeShow:function() {
            var naplotab = $('#NaploTab');
            naplotab.on('click', '.js-naplonewbutton', function (e) {
                var $this = $(this);
                e.preventDefault();
                $.ajax({
                    url: '/admin/elallasnaplo/getemptyrow',
                    type: 'GET',
                    success: function (data) {
                        naplotab.append(data);
                        $('.js-naplonewbutton,.js-naplodelbutton').button();
                        $this.remove();
                    }
                });
            })
                .on('click', '.js-naplodelbutton', function (e) {
                    e.preventDefault();
                    var $this = $(this);
                    dialogcenter.html('Biztos, hogy törli a napló bejegyzést?').dialog({
                        resizable: false,
                        height: 140,
                        modal: true,
                        buttons: {
                            'Igen': function () {
                                $.ajax({
                                    url: '/admin/elallasnaplo/del',
                                    type: 'POST',
                                    data: {
                                        id: $this.attr('data-id')
                                    },
                                    success: function (data) {
                                        $('#naplotable_' + data).remove();
                                    }
                                });
                                $(this).dialog('close');
                            },
                            'Nem': function () {
                                $(this).dialog('close');
                            }
                        }
                    });
                });
            $('.js-naplonewbutton,.js-naplodelbutton').button();
        },
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
                fields:['#idfilter']
            },
            tablebody:{
                url:'/admin/elallas/getlistbody'
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
