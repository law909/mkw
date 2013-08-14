$(document).ready(function() {
	$('#mattkarb').mattkarb({
		independent:true,
		viewUrl:'/admin/megrendelesfej/getkarb',
		newWindowUrl:'/admin/megrendelesfej/viewkarb',
		saveUrl:'/admin/megrendelesfej/save',
		beforeShow:function() {
			$('#TulajirszamEdit').autocomplete({
				minLength: 2,
				source: function(req,resp) {
					$.ajax({
						url: '/admin/irszam',
						type: 'GET',
						data: {
							term: req.term,
							tip: 1
						},
						success: function(data) {
							var d=JSON.parse(data);
							resp(d);
						},
						error: function() {
							resp();
						}
					});
				},
				select: function(event,ui) {
					$('input[name="tulajvaros"]').val(ui.item.nev);
				}
			});
			$('input[name="tulajvaros"]').autocomplete({
				minLength: 4,
				source: function(req,resp) {
					$.ajax({
						url: '/admin/varos',
						type: 'GET',
						data: {
							term: req.term,
							tip: 1
						},
						success: function(data) {
							var d=JSON.parse(data);
							resp(d);
						},
						error: function() {
							resp();
						}
					});
				},
				select: function(event,ui) {
					$('#TulajirszamEdit').val(ui.item.szam);
				}
			});
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