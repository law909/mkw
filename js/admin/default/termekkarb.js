$(document).ready(function(){
	var dialogcenter=$('#dialogcenter');

	function createImageSelectable(n,m) {
		$(n).selectable({
			unselected: function(){
				$('.ui-state-highlight', this).removeClass('ui-state-highlight');
			},
			selected: function(){
				$('.ui-selected', this).each(function(){
					var $this=$(this);
					$this.addClass('ui-state-highlight');
					$(m+$this.attr('data-valtozatid')).val($this.attr('data-value'));
				});
			}
		});
	}

	function getSorNetto(o,n) {
		var id=$('#mattkarb-form').attr('data-id');
		var sorid=o.attr('name').split('_')[1]||'';
		$.ajax({
			url:'/admin/termek/getnetto',
			type:'GET',
			data:{
				id:id,
				value:o.val(),
				afakod:$('#AfaEdit').val()
			},
			success:function(data) {
				$('input[name="'+n+sorid+'"]').val(data);
			}
		});
	}

	function getSorBrutto(o,n) {
		var id=$('#mattkarb-form').attr('data-id');
		var sorid=o.attr('name').split('_')[1]||'';
		$.ajax({
			url:'/admin/termek/getbrutto',
			type:'GET',
			data:{
				id:id,
				value:o.val(),
				afakod:$('#AfaEdit').val()
			},
			success:function(data) {
				$('input[name="'+n+sorid+'"]').val(data);
			}
		});
	}

	function getNetto(o,n) {
		var id=$('#mattkarb-form').attr('data-id');
		$.ajax({
			url:'/admin/termek/getnetto',
			type:'GET',
			data:{
				id:id,
				value:o.val(),
				afakod:$('#AfaEdit').val()
			},
			success:function(data) {
				$(n).val(data);
			}
		});
	}

	function getBrutto(o,n) {
		var id=$('#mattkarb-form').attr('data-id');
		$.ajax({
			url:'/admin/termek/getbrutto',
			type:'GET',
			data:{
				id:id,
				value:o.val(),
				afakod:$('#AfaEdit').val()
			},
			success:function(data) {
				$(n).val(data);
			}
		});
	}

	$('#mattkarb').mattkarb({
		name:'termek',
		independent:true,
		viewUrl:'/admin/termek/getkarb',
		newWindowUrl:'/admin/termek/viewkarb',
		saveUrl:'/admin/termek/save',
		beforeShow:function() {
			var keptab=$('#KepTab');
			var recepttab=$('#RecepturaTab');
			var kapcsolodotab=$('#KapcsolodoTab');
			var valtozattab=$('#ValtozatTab');
			var akciostartedit=$('#AkcioStartEdit'),
				akciostopedit=$('#AkcioStopEdit');
			keptab.on('click','#FoKepDelButton',function(e) {
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
			.on('click','#FoKepBrowseButton',function(e){
				e.preventDefault();
				var finder=new CKFinder(),
					$kepurl=$('#KepUrlEdit'),
					path=$kepurl.val();
				finder.startupPath='Images:'+path.substring(path.indexOf('/',1));
				finder.selectActionFunction = function( fileUrl, data ) {
					$kepurl.val(fileUrl);
				};
				finder.popup();
			})
			.on('click','.KepNewButton',function(e) {
				var $this=$(this);
				e.preventDefault();
				$.ajax({
					url:'/admin/termekkep/getemptyrow',
					type:'GET',
					success:function(data) {
						keptab.append(data);
						$('.KepNewButton,.KepDelButton,.KepBrowseButton').button();
						$this.remove();
					}
				});
			})
			.on('click','.KepDelButton',function(e){
				e.preventDefault();
				var $this=$(this);
				dialogcenter.html('Biztos, hogy törli a képet?').dialog({
					resizable: false,
					height:140,
					modal: true,
					buttons: {
						'Igen': function() {
							$.ajax({
								url:'/admin/termekkep/del',
								data:{
									id:$this.attr('data-id')
								},
								success:function(data) {
									$('#keptable_'+data).remove();
								}
							});
							$(this).dialog('close');
						},
						'Nem':function() {
							$(this).dialog('close');
						}
					}
				});
			})
			.on('click','.KepBrowseButton',function(e){
				e.preventDefault();
				var finder=new CKFinder(),
					$kepurledit=$('#KepUrlEdit_'+$(this).attr('data-id')),
					path=$kepurledit.val();
				finder.startupPath='Images:'+path.substring(path.indexOf('/',1));
				finder.selectActionFunction = function( fileUrl, data ) {
					$kepurledit.val(fileUrl);
				};
				finder.popup();
			});
			$('#FoKepDelButton,#FoKepBrowseButton,.KepNewButton,.KepBrowseButton,.KepDelButton').button();
			if (!$.browser.mobile) {
				$('.toFlyout').flyout();
			}
			$('#cimkekarbcontainer').mattaccord({
				header:'#cimkekarbcontainerhead',
				page:'.cimkekarbpage',
				closeUp:'.cimkekarbcloseupbutton',
				collapse:'#cimkekarbcollapse'
			})
			.on('click','.cimkekarb',function(e) {
				e.preventDefault();
				$(this).toggleClass('selectedcimke ui-state-hover');
			});
			$('.cimkeadd').on('click',function(e) {
				e.preventDefault();
				var ref=$(this).attr('data-refcontrol');
				var cimkenev=$(ref).val(),
					katkod=ref.split('_')[1];
				$.ajax({
					url:'/admin/termekcimke/add',
					type:'POST',
					data:{
						cimkecsoport:katkod,
						nev:cimkenev
					},
					success:function(data) {
						$(ref).val('');
						$(ref).before(data);
					}
				});
			});
			recepttab.on('click','.receptnewbutton',function(e) {
				var $this=$(this);
				e.preventDefault();
				$.ajax({
					url:'/admin/termekrecept/getemptyrow',
					type:'GET',
					success:function(data) {
						var tbody=$('#RecepturaTab');
						tbody.append(data);
						$('.receptnewbutton,.receptdelbutton').button();
						$this.remove();
					}
				});
			})
			.on('click','.receptdelbutton',function(e) {
				e.preventDefault();
				var receptgomb=$(this),
					receptid=receptgomb.attr('data-id');
				if (receptgomb.attr('data-source')==='client') {
					$('#recepttable_'+receptid).remove();
				}
				else {
					$('#dialogcenter').html('Biztos, hogy törli az árat?').dialog({
						resizable: false,
						height:140,
						modal: true,
						buttons: {
							'Igen': function() {
								$.ajax({
									url:'/admin/termekrecept/save',
									type:'POST',
									data:{
										id:receptid,
										oper:'del'
									},
									success:function(data) {
										$('#recepttable_'+data).remove();
									}
								});
								$(this).dialog('close');
							},
							'Nem': function() {
								$(this).dialog('close');
							}
						}
					});
				}
			});
			$('.receptnewbutton,.receptdelbutton').button();
			kapcsolodotab.on('click','.kapcsolodonewbutton',function(e) {
				var $this=$(this);
				e.preventDefault();
				$.ajax({
					url:'/admin/termekkapcsolodo/getemptyrow',
					type:'GET',
					success:function(data) {
						var tbody=$('#KapcsolodoTab');
						tbody.append(data);
						$('.kapcsolodonewbutton,.kapcsolododelbutton').button();
						$this.remove();
					}
				});
			})
			.on('click','.kapcsolododelbutton',function(e) {
				e.preventDefault();
				var kapcsgomb=$(this),
					kapcsid=kapcsgomb.attr('data-id');
				if (kapcsgomb.attr('data-source')==='client') {
					$('#kapcsolodotable_'+kapcsid).remove();
				}
				else {
					$('#dialogcenter').html('Biztos, hogy törli az árat?').dialog({
						resizable: false,
						height:140,
						modal: true,
						buttons: {
							'Igen': function() {
								$.ajax({
									url:'/admin/termekkapcsolodo/save',
									type:'POST',
									data:{
										id:kapcsid,
										oper:'del'
									},
									success:function(data) {
										$('#kapcsolodotable_'+data).remove();
									}
								});
								$(this).dialog('close');
							},
							'Nem': function() {
								$(this).dialog('close');
							}
						}
					});
				}
			});
			$('.kapcsolodonewbutton,.kapcsolododelbutton').button();
			valtozattab.on('click','.valtozatnewbutton',function(e) {
				var $this=$(this);
				e.preventDefault();
				$.ajax({
					url:'/admin/termekvaltozat/getemptyrow',
					type:'GET',
					data:{
						termekid:$this.attr('data-termekid')
					},
					success:function(data) {
						var tbody=$('#ValtozatTab');
						tbody.append(data);
						$('.valtozatnewbutton,.valtozatdelbutton').button();
						createImageSelectable('.valtozatkepedit','#ValtozatKepId_');
						$this.remove();
					}
				});
			})
			.on('click','.valtozatdelbutton',function(e) {
				e.preventDefault();
				var gomb=$(this),
					vid=gomb.attr('data-id');
				if (gomb.attr('data-source')==='client') {
					$('#valtozattable_'+vid).remove();
				}
				else {
					dialogcenter.html('Biztos, hogy törli a változatot?').dialog({
						resizable: false,
						height:140,
						modal: true,
						buttons: {
							'Igen': function() {
//								pleaseWait();
								$.ajax({
									url:'/admin/termekvaltozat/save',
									type:'POST',
									data:{
										id:vid,
										oper:'del'
									},
									success:function(data) {
										$('#valtozattable_'+data).remove();
									}
								});
								$(this).dialog('close');
							},
							'Nem': function() {
								$(this).dialog('close');
							}
						}
					});
				}
			})
			.on('blur','.valtozatnetto',function(e) {
				e.preventDefault();
				getSorBrutto($(this),'valtozatbrutto_');
			})
			.on('blur','.valtozatbrutto',function(e) {
				e.preventDefault();
				getSorNetto($(this),'valtozatnetto_');
			})
			.on('blur','.valtozatnettogen',function(e) {
				e.preventDefault();
				getSorBrutto($(this),'valtozatbruttogen');
			})
			.on('blur','.valtozatbruttogen',function(e) {
				e.preventDefault();
				getSorNetto($(this),'valtozatnettogen');
			})
			.on('click','input[name^="valtozatelerheto_"]',function(e) {
				var $this=$(this);
				if ($this.attr('checked')!=='checked') {
					return $('input[name="valtozatlathato_'+$this.attr('name').split('_')[1]+'"]').attr('checked')!=='checked';
				}
				return true;
			})
			.on('click','input[name^="valtozatlathato_"]',function(e) {
				var $this=$(this);
				if ($this.attr('checked')==='checked') {
					return $('input[name="valtozatelerheto_'+$this.attr('name').split('_')[1]+'"]').attr('checked')==='checked';
				}
				return true;
			});
			$('#valtozatgeneratorform').ajaxForm({
				type:'POST',
				beforeSubmit:function(arr,form,opt) {
//					pleaseWait();
					arr.push({name:'termekid',value:form.data('id')});
				},
				success:function(data) {
					$('.valtozattable').remove();
					$('#valtozatgenerator').after(data);
					$('.valtozatdelbutton').button();
				}
			});
			$('.valtozatdelallbutton').button().on('click',function(e) {
				var $this=$(this);
				dialogcenter.html('Biztos, hogy törli az összes változatot?').dialog({
					resizable: false,
					height:140,
					modal: true,
					buttons: {
						'Igen': function() {
//							pleaseWait();
							$.ajax({
								url:'/admin/termekvaltozat/delall',
								type:'POST',
								data:{
									termekid:$this.data('termekid')
								},
								success:function() {
									$('.valtozattable').remove();
								}
							});
							$(this).dialog('close');
						},
						'Nem': function() {
							$(this).dialog('close');
						}
					}
				});
				return false;
			});
			$('.valtozatnewbutton,.valtozatdelbutton,#valtozatgeneratorbutton').button();
			createImageSelectable('.valtozatkepedit','#ValtozatKepId_');
			$('#NettoEdit').on('blur',function(e) {
				e.preventDefault();
				getBrutto($(this),'#BruttoEdit');
			});
			$('#BruttoEdit').on('blur',function(e) {
				e.preventDefault();
				getNetto($(this),'#NettoEdit');
			});
			$('#AkciosNettoEdit').on('blur',function(e) {
				e.preventDefault();
				getBrutto($(this),'#AkciosBruttoEdit');
			});
			$('#AkciosBruttoEdit').on('blur',function(e) {
				e.preventDefault();
				getNetto($(this),'#AkciosNettoEdit');
			});
			akciostartedit.datepicker($.datepicker.regional['hu']);
			akciostartedit.datepicker('option','dateFormat','yy.mm.dd');
			akciostartedit.datepicker('setDate',akciostartedit.attr('data-datum'));
			akciostopedit.datepicker($.datepicker.regional['hu']);
			akciostopedit.datepicker('option','dateFormat','yy.mm.dd');
			akciostopedit.datepicker('setDate',akciostopedit.attr('data-datum'));
			$('.termekfabutton').on('click',function(e) {
				var edit=$(this),dialogcenter=$('#dialogcenter');
				e.preventDefault();
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
					height:240,
					modal: true,
					buttons: {
						'Töröl':function() {
							edit.attr('data-value',0);
							$('span',edit).text(edit.attr('data-text'));
							$(this).dialog('close');
						},
						'OK': function() {
							dialogcenter.jstree('get_selected').each(function() {
								var treenode=$(this).children('a');
								edit.attr('data-value',treenode.attr('id').split('_')[1]);
								$('span',edit).text(treenode.text());
							});
							$(this).dialog('close');
						},
						'Bezár': function() {
							$(this).dialog('close');
						}
					}
				});
			})
			.button();
			if (!$.browser.mobile) {
				$('#LeirasEdit').ckeditor();
			}
		},
		beforeSerialize:function(form,opt) {
			var cimkek=new Array();
			$('.cimkekarb').filter('.selectedcimke').each(function() {
				cimkek.push($(this).attr('data-id'));
			});
			var x={};
			x['cimkek[]']=cimkek;
			$('.termekfabutton').each(function() {
				$this=$(this);
				x[$this.attr('data-name')]=$this.attr('data-value');
			});
			opt['data']=x;
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