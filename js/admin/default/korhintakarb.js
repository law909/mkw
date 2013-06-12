$(document).ready(function(){
	$('#mattkarb').mattkarb({
		independent:true,
		viewUrl:'/admin/korhinta/getkarb',
		newWindowUrl:'/admin/korhinta/viewkarb',
		saveUrl:'/admin/korhinta/save',
		beforeShow:function() {
			function morphFoKepUploadButton() {
				var kepedit=$('#FoKepUploadButton');
				if (kepedit.length>0) {
					kepedit.button();
					new AjaxUpload(kepedit,{
						action:'/admin/korhinta/savepicture',
						onSubmit:function(file,ext) {
							var kepnev=$('#KepNevEdit').val();
							if (kepnev) {
								if (ext&&/^(jpg|jpeg|png)$/.test(ext)) {
									this.setData({
										id:$('#mattkarb-form').attr('data-id'),
										nev:kepnev,
										leiras:$('#KepLeirasEdit').val()
									});
								}
								else {
									dialogcenter.html('Csak jpg,jpeg,png fájlokat tölthet fel.').dialog({resizable:false,modal:true,buttons:{'OK':function() {$(this).dialog('close');}}});
									return false;
								}
							}
							else {
								dialogcenter.html('Adja meg a kép nevét.').dialog({resizable:false,modal:true,buttons:{'OK':function() {$(this).dialog('close');}}});
								return false;
							}
						},
						onComplete:function(file,response) {
							$('#FoImageEdit').remove();
							$('#AltalanosTab').append(response);
							$('#FoKepDelButton').button();
						}
					});
				}
			}
			$('#FoKepDelButton').on('click',function(e) {
				e.preventDefault();
				dialogcenter.html('Biztos, hogy törli a képet?').dialog({
					resizable: false,
					height:140,
					modal: true,
					buttons: {
						'Igen': function() {
							$.ajax({
								url:'/admin/korhinta/delpicture',
								data:{
									id:$('#mattkarb-form').attr('data-id')
								},
								success:function(data) {
									$('#FoImageEdit').replaceWith(data);
									morphFoKepUploadButton();
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
			morphFoKepUploadButton();
			$('#FoKepDelButton').button();
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