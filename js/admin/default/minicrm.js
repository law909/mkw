$(document).ready(function() {
	var dialogcenter=$('#dialogcenter');

    $('#mattkarb').mattkarb({
		independent:true,
		viewUrl:'/admin/getkarb',
		newWindowUrl:'/admin/viewkarb',
		saveUrl:'/admin/save',
		beforeShow:function() {

            $('.js-partnerimport').on('click', function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: $(this).attr('href'),
                    success: function(d) {
                        if (!d) {
                            alert('Kész.');
                        }
                        else {
                            var adat = JSON.parse(d);
                            if (adat.url) {
                                window.open(adat.url, '_blank');
                            }
                            else {
                                if (adat.msg) {
                                    alert(adat.msg);
                                }
                            }
                        }
                    }
                });
            }).button();

		},
		onSubmit:function() {
			$('#messagecenter')
				.html('A mentés sikerült.')
				.hide()
				.addClass('matt-messagecenter ui-widget ui-state-highlight')
				.one('click',messagecenterclick)
				.slideToggle('slow');
		}
	});
});