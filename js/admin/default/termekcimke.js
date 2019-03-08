$(document).ready(function(){
	var dialogcenter=$('#dialogcenter');
	var termekcimke={
			container:'#mattkarb',
			viewUrl:'/admin/termekcimke/getkarb',
			newWindowUrl:'/admin/termekcimke/viewkarb',
			saveUrl:'/admin/termekcimke/save',
			beforeShow:function() {
				$('#AltalanosTab').on('click','#KepDelButton',function(e) {
					e.preventDefault();
					dialogcenter.html('Biztos, hogy törli a képet?').dialog({
						resizable: false,
						height:140,
						modal: true,
						buttons: {
							'Igen': function() {
								var kep=$('.js-cimkekep');
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
				})
				.on('click','#KepBrowseButton',function(e){
					e.preventDefault();
					var finder=new CKFinder(),
						$kepurl=$('#KepUrlEdit'),
						path=$kepurl.val();
					if (path) {
						finder.startupPath='Images:'+path.substring(path.indexOf('/',1));
					}
					finder.selectActionFunction = function( fileUrl, data ) {
						var kep=$('.js-cimkekep');
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
					}
					finder.popup();
				});
				$('#KepDelButton,#KepBrowseButton').button();
				if (!$.browser.mobile) {
					$('.js-toFlyout').flyout();
					CKFinder.setupCKEditor( null, '/ckfinder/' );
					$('#LeirasEdit').ckeditor();
				}
			},
			beforeHide:function() {
				if (!$.browser.mobile) {
					editor=$('#LeirasEdit').ckeditorGet();
					if (editor) {
						editor.destroy();
					}
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
	};

	if ($.fn.mattable) {
		$('#mattable-select').mattable({
			name:'cimke',
			onGetTBody:function() {
				if (!$.browser.mobile) {
					$('.toFlyout').flyout();
				}
			},
			filter:{
				fields:['#nevfilter','#ckfilter','#gyartofilter']
			},
			tablebody:{
				url:'/admin/termekcimke/getlistbody'
			},
			karb:termekcimke
		});

		$('.js-maincheckbox').change(function(){
			$('.js-egyedcheckbox').prop('checked',$(this).prop('checked'));
		});
		$('#mattable-body').on('click','.js-menulathatocheckbox',function(e) {
			e.preventDefault();
			var $this=$(this),
				f=$this.closest('tr');
			$.ajax({
				url:'/admin/termekcimke/setmenulathato',
                type: 'POST',
				data:{
					id:f.attr('data-cimkeid'),
					num:$this.attr('data-num'),
					kibe:!$this.is('.ui-state-hover')
				},
				success:function() {
					$this.toggleClass('ui-state-hover');
				}
			});
		});
	}
	else {
		if ($.fn.mattkarb) {
			$('#mattkarb').mattkarb($.extend({},termekcimke,{independent:true}));
		}
	}
});