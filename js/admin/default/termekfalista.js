$(document).ready(function(){
	var dialogcenter=$('#dialogcenter');
	function _new_edit(uj){
		var valasztottid=$('#termekfa').jstree('get_selected').children('a').attr('id'),
				scrollPosition;
		if (!valasztottid) {
			if (uj) {
				dialogcenter.html('Válasszon szülő kategóriát').dialog({resizable:false,modal:true,buttons:{'OK':function() {$(this).dialog('close');}}});
			}
			else {
				dialogcenter.html('Válasszon kategóriát').dialog({resizable:false,modal:true,buttons:{'OK':function() {$(this).dialog('close');}}});
			}
			return false;
		}
		if (uj) {
			adat={
				parentid:valasztottid.split('_')[1],
				oper:'add'
			};
		}
		else {
			adat={
				id:valasztottid.split('_')[1],
				oper:'edit'
			};
		}
		$.ajax({url:'/admin/termekfa/getkarb',
			data:adat,
			success:function(data) {
				scrollPosition=$(document).scrollTop();
				$(document).scrollTop(0);
				$('#termekfa').hide();
				$('#termekfakarb').append(data);
				var karbsetup={
					name:'',
					independent:false,
					header:'#fakarb-header',
					footer:'#fakarb-footer',
					form:'#fakarb-form',
					tab:'#fakarb-tabs',
					page:'.fakarb-page',
					titlebar:'.fakarb-titlebar',
					cancel:'#fakarb-cancelbutton',
					ok:'#fakarb-okbutton',
					viewUrl:'/admin/termekfa/getkarb',
					saveUrl:'/admin/termekfa/save',
					beforeShow:function() {
						if (!$.browser.mobile) {
							$('#LeirasEdit').ckeditor();
						}
						$('#AltalanosTab').on('click','#KepDelButton',function(e) {
							e.preventDefault();
							dialogcenter.html('Biztos, hogy törli a képet?').dialog({
								resizable: false,
								height:140,
								modal: true,
								buttons: {
									'Igen': function() {
										var kep=$('.js-termekfakep');
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
							finder.startupPath='Images:'+path.substring(path.indexOf('/',1));
							finder.selectActionFunction = function( fileUrl, data ) {
								var kep=$('.js-termekfakep');
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
						$('#KepDelButton,#KepBrowseButton').button();
						if (!$.browser.mobile) {
							$('.js-toFlyout').flyout();
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
					onSubmit:function(data) {
						var resp=JSON.parse(data);
						switch (resp.oper) {
							case 'edit':
								break;
							case 'add':
								break;
						}
						$('#termekfakarb').empty().hide();
						$('#termekfa').jstree('refresh');
						$('#termekfa').show();
						$(document).scrollTop(scrollPosition);
					},
					onCancel:function() {
						$('#termekfakarb').empty().hide();
						$('#termekfa').show();
						$(document).scrollTop(scrollPosition);
					}
				};
				$('#termekfakarb').mattkarb(karbsetup);
			}
		});
	};

	$('#termekfa')
	.bind('loaded.jstree refresh.jstree',function(e,d){
		d.inst.open_all();
	})
	.jstree({
		core:{animation:100},
		plugins:['themeroller','json_data','contextmenu','ui','checkbox'],
		themeroller:{item:''},
		json_data:{
			ajax:{url:'/admin/termekfa/jsonlist'}
		},
		ui:{select_limit:1},
		contextmenu:{
			select_node:true,
			items:{
				create:false,rename:false,remove:false,ccp:false,
				_new:{
					label:'Új',
					action:function(obj) {
						_new_edit(true);
					}
				},
				_edit:{
					label:'Szerkeszt',
					action:function(obj) {
						_new_edit(false);
					}
				},
				_del:{
					label:'Töröl',
					action:function(obj) {
						var valasztottid=$('#termekfa').jstree('get_selected').children('a').attr('id');
						if (!valasztottid) {
							dialogcenter.html('Válasszon kategóriát').dialog({resizable:false,modal:true,buttons:{'OK': function() {$(this).dialog('close');}}});
							return false;
						}
						$.ajax({url:'/admin/termekfa/isdeletable',
							data:{
								id:valasztottid.split('_')[1]
							},
							success:function(data) {
								if (data==='1') {
									dialogcenter.html('Biztosan törli a kategóriát?').dialog({
										modal:true,
										buttons:{
											'Igen':function() {
												$(this).dialog('close');
												$.ajax({url:'/admin/termekfa/save',
													data:{
														id:valasztottid.split('_')[1],
														oper:'del'
													},
													success:function(data) {
														$('#termekfa').jstree('refresh');
													}
												});
											},
											'Nem':function() {
												$(this).dialog('close');
											}
										}
									});
								}
								else {
									dialogcenter.html('A kategória nem törölhető.').dialog({modal:true,buttons:{'OK':function() {$(this).dialog('close');}}});
								}
							}
						});
					}
				},
				_move:{
					label:'Áthelyez',
					action:function(obj) {
						var valasztottid=$('#termekfa').jstree('get_selected').children('a').attr('id');
						if (!valasztottid) {
							dialogcenter.html('Válasszon kategóriát').dialog({resizable:false,modal:true,buttons:{'OK': function() {$(this).dialog('close');}}});
							return false;
						}
						dialogcenter.jstree({
							core:{animation:100},
							plugins:['themeroller','json_data','ui'],
							themeroller:{item:''},
							json_data:{
								ajax:{url:'/admin/termekfa/jsonlist'}
							},
							ui:{select_limit:1}
						})
						.bind('loaded.jstree',function(event,data) {
							dialogcenter.jstree('open_node',$('#termekfa_1',dialogcenter).parent());
						});
						dialogcenter.dialog({
							resizable: true,
							height:340,
							modal: true,
							buttons: {
								'OK': function() {
									var ideid=dialogcenter.jstree('get_selected').children('a').attr('id');
									$.ajax({
										url:'/admin/termekfa/move',
										data:{
											eztid:valasztottid.split('_')[1],
											ideid:ideid.split('_')[1]
										},
										success:function(data) {
											$('#termekfa').jstree('refresh');
										}
									});
									$(this).dialog('close');
								},
								'Bezár': function() {
									$(this).dialog('close');
								}
							}
						});
					}
				}
			}
		}
	})
	.bind('change_state.jstree',function(e,data) {
		$termekfa=$(this);
		$('li',$termekfa).each(function(i) {
			$this=$(this);
			if ($this.hasClass('jstree-unchecked')) {
				$('ins.jstree-checkbox',$this).removeClass('ui-icon ui-icon-circle-check ui-icon-check');
			}
			else if ($this.hasClass('jstree-checked')) {
				$('ins.jstree-checkbox',$this).removeClass('ui-icon ui-icon-circle-check ui-icon-check').addClass('ui-icon ui-icon-circle-check');
			}
			else if ($this.hasClass('jstree-undetermined')) {
				$('ins.jstree-checkbox',$this).removeClass('ui-icon ui-icon-circle-check ui-icon-check').addClass('ui-icon ui-icon-check');
			}
		});
	});
});