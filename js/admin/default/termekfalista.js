$(document).ready(function(){
	var dialogcenter=$('#dialogcenter');
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
						var valasztottid=$('#termekfa').jstree('get_selected').children('a').attr('id'),
								scrollPosition;
						if (!valasztottid) {
							dialogcenter.html('Válasszon szülő kategóriát').dialog({resizable:false,modal:true,buttons:{'OK':function() {$(this).dialog('close');}}});
							return false;
						}
						$.ajax({url:'/admin/termekfa/getkarb',
							data:{
								parentid:valasztottid.split('_')[1],
								oper:'add'
							},
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
								}
								$('#termekfakarb').mattkarb(karbsetup);
							}
						});
					}
				},
				_edit:{
					label:'Szerkeszt',
					action:function(obj) {
						var valasztottid=$('#termekfa').jstree('get_selected').children('a').attr('id');
						if (!valasztottid) {
							dialogcenter.html('Válasszon kategóriát').dialog({resizable:false,modal:true,buttons:{'OK':function() {$(this).dialog('close');}}});
							return false;
						}
						$.ajax({url:'/admin/termekfa/getkarb',
							data:{
								id:valasztottid.split('_')[1],
								oper:'edit'
							},
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
								}
								$('#termekfakarb').mattkarb(karbsetup);
							}
						});
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