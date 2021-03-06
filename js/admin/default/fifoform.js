$(document).ready(function() {
	var dialogcenter=$('#dialogcenter');

    $('#mattkarb').mattkarb({
		independent:true,
		viewUrl:'/admin/getkarb',
		newWindowUrl:'/admin/viewkarb',
		saveUrl:'/admin/save',
		beforeShow:function() {

            $('.js-fifoalapadat,.js-keszletertek').on('click', function(e) {
                e.preventDefault();
                $ff = $('#fifoexport');
                $ff.attr('action', $(this).attr('href'));
                $ff.submit();
            }).button();

            $('.js-fifocalc').on('click', function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    data: {
                        storno: $('input[name="storno"]').prop('checked')
                    },
                    url: $(this).attr('href'),
                    success: function() {
                        alert('Kész.');
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