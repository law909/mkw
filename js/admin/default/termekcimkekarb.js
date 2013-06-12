$(document).ready(function(){
	var dialogcenter=$('#dialogcenter');
	$('#mattkarb').mattkarb({
		name:'cimke',
		independent:true,
		viewUrl:'/admin/termekcimke/getkarb',
		newWindowUrl:'/admin/termekcimke/viewkarb',
		saveUrl:'/admin/termekcimke/save',
		beforeShow:function() {
			function morphKepEdit() {
				var kepedit=$('#KepEdit');
				if (kepedit.length>0) {
					kepedit.button();
					var aj=new AjaxUpload(kepedit,{
						action:'/admin/termekcimke/savepicture',
						onSubmit:function(file,ext) {
							if (ext&&/^(jpg|jpeg|png)$/.test(ext)) {
								this.setData({
									id:$('#mattkarb-form').attr('data-id'),
									nev:$('#KepNevEdit').val(),
									leiras:$('#KepLeirasEdit').val()
								});
							}
						},
						onComplete:function(file,response) {
							$('#FoImageEdit').remove();
							$('#AltalanosTab').append(response);
							$('.mattkarb-delimage').button();
							if (!$.browser.mobile) {
								$('.toFlyout').flyout();
							}
						}
					});
				}
			}
			$('#AltalanosTab').on('click','.mattkarb-delimage',function(e) {
				e.preventDefault();
				dialogcenter.html('Biztos, hogy törli a képet?').dialog({
					resizable: false,
					height:140,
					modal: true,
					buttons: {
						'Igen': function() {
							$.ajax({
								url:'/admin/termekcimke/delpicture',
								data:{
									id:$('#mattkarb-form').attr('data-id')
								},
								success:function(data) {
									$('#FoImageEdit').replaceWith(data);
									morphKepEdit();
								}
							});
							$(this).dialog('close');
						},
						'Nem':function() {
							$(this).dialog('close');
						}
					}
				});
			});
			$('.mattkarb-delimage').button();
			morphKepEdit();
			if (!$.browser.mobile) {
				$('.toFlyout').flyout();
				$('#LeirasEdit').ckeditor();
			}
		},
		onSubmit:function() {
			$('#messagecenter')
				.html('A mentés sikerült.')
				.hide()
				.addClass('matt-messagecenter ui-widget ui-state-highlight')
				.one('click',messagecenterclick)
				.slideToggle('slow');
			//setTimeout('$("#messagecenter").unbind(messagecenterclick).slideToggle("slow");',5000);
		}
	});
});