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
								$('#KepUrlEdit').val('');
								$('#KepLeirasEdit').val('');
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
					finder.startupPath='Images:'+path.substring(path.indexOf('/',1));
					finder.selectActionFunction = function( fileUrl, data ) {
						$kepurl.val(fileUrl);
					}
					finder.popup();
				});
				$('#KepDelButton,#KepBrowseButton').button();
				if (!$.browser.mobile) {
					$('.toFlyout').flyout();
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
				fields:['#nevfilter','#ckfilter']
			},
			tablebody:{
				url:'/admin/termekcimke/getlistbody'
			},
			karb:termekcimke
		});

		$('#maincheckbox').change(function(){
			$('.egyedcheckbox').attr('checked',$(this).attr('checked'));
		});
		$('#mattable-body').on('click','.menulathatocheckbox',function(e) {
			e.preventDefault();
			var $this=$(this),
				f=$this.closest('tr');
			$.ajax({
				url:'/admin/termekcimke/setmenulathato',
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