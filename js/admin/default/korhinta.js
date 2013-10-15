$(document).ready(function(){
	var dialogcenter=$('#dialogcenter');
	var korhinta={
			container:'#mattkarb',
			viewUrl:'/admin/korhinta/getkarb',
			newWindowUrl:'/admin/korhinta/viewkarb',
			saveUrl:'/admin/korhinta/save',
			beforeShow:function() {
				$('#FoKepBrowseButton').on('click',function(e){
					e.preventDefault();
					var finder=new CKFinder(),
						$kepurl=$('#KepUrlEdit'),
						path=$kepurl.val();
					if (path) {
						finder.startupPath='Images:'+path.substring(path.indexOf('/',1));
					}
					finder.selectActionFunction = function( fileUrl, data ) {
						var kep=$('.js-korhintakep');
						$.ajax({
							url:'/admin/getsmallurl',
							type:'GET',
							data:{
								url:fileUrl
							},
							success:function(data) {
								$kepurl.val(fileUrl);
								kep.attr('src',data);
								kep.parent().attr('href',fileUrl);
							}
						});
					};
					finder.popup();
				});
				$('#FoKepDelButton').on('click',function(e) {
					e.preventDefault();
					dialogcenter.html('Biztos, hogy törli a képet?').dialog({
						resizable: false,
						height:140,
						modal: true,
						buttons: {
							'Igen': function() {
								var kep=$('.js-korhintakep');
								$('#KepUrlEdit').val('');
								$('#KepLeirasEdit').val('');
								kep.attr('src','/');
								kep.parent().attr('href','');
								$(this).dialog('close');
							},
							'Nem':function() {
								$(this).dialog('close');
							}
						}
					});
				});
				$('#FoKepBrowseButton,#FoKepDelButton').button();
				if (!$.browser.mobile) {
					$('.js-toFlyout').flyout();
				}

			},
			onSubmit:function() {
				$('#messagecenter')
					.html('A mentés sikerült.')
					.hide()
					.addClass('matt-messagecenter ui-widget ui-state-highlight')
					.one('click',messagecenterclick)
					.slideToggle('slow');
			}
	};

	if ($.fn.mattable) {
		$('#mattable-select').mattable({
			filter:{
				fields:['#nevfilter']
			},
			tablebody:{
				url:'/admin/korhinta/getlistbody'
			},
			karb:korhinta
		});
		$('#mattable-body').on('click','.js-flagcheckbox',function(e) {
			e.preventDefault();
			var $this=$(this);
			$.ajax({
				url:'/admin/korhinta/setflag',
				type:'POST',
				data:{
					id:$this.attr('data-id'),
					flag:$this.attr('data-flag'),
					kibe:!$this.is('.ui-state-hover')
				},
				success:function() {
					$this.toggleClass('ui-state-hover');
				}
			});
		});
		$('.js-maincheckbox').change(function(){
			$('.js-egyedcheckbox').prop('checked',$(this).prop('checked'));
		});
	}
	else {
		if ($.fn.mattkarb) {
			$('#mattkarb').mattkarb($.extend({},korhinta,{independent:true}));
		}
	}
});