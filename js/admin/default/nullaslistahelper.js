function initHatarido() {
	var hatido=$('#hatarido');
	hatido.datepicker($.datepicker.regional['hu']);
	hatido.datepicker('option','dateFormat','yy.mm.dd');
}
function initGrid() {
	var _txt={
			req:'A csillaggal jelölt mezők kitöltése kötelező.',
			srch:'Keresés',
			srchtitle:'Keresés ki/be',
			srchicon:'ui-icon-search',
			clr:'Clear',
			clrtitle:'Clear search',
			clricon:'ui-icon-home'
		};
	var _tetel={
			grid:'#TetelGrid',
			pager:'#TetelGridPager'
	};
	var TetelGrid=$(_tetel.grid).jqGrid({
		url:'/admin/nullaslistatetel/jsonlist',
		editurl:'/admin/nullaslistatetel/save',
		datatype: 'json',
		colModel:[
		        {name:'vevokod',index:'vevokod',label:'Vevőkód',width:60,
					editable:true,
					editoptions:{width:30},
					formoptions:{rowpos:1,label:'Vevőkód:'}},
		        {name:'serial',index:'serial',label:'Serial',width:20,
					editable:true,
					editoptions:{width:10},
					formoptions:{rowpos:2,label:'Serial:'}},
				{name:'termek',index:'termek',label:'Termék',width:160,
					editable:true,
					edittype:'select',
					editoptions:{width:83,dataUrl:'/admin/termek/htmllist'},
					formoptions:{rowpos:3,label:'Termék:'}},
				{name:'mennyiseg',index:'mennyiseg',label:'Mennyiség',width:25,align:'right',
					editable:true,
					editoptions:{size:14},
					editrules:{required:true},
					formoptions:{rowpos:4,label:'Mennyiség:',elmsuffix:'*'}},
				{name:'hatarido',index:'hatarido',label:'Határidő',width:25,
					editable:true,
					sorttype:'date',
					datefmt:'Y.mm.dd',
					editoptions:{size:12},
					editrules:{date:true},
					formoptions:{rowpos:5}}],
		rowNum:100000,
		rowList:[10,20,30],
		pager: _tetel.pager,
		sortname: 'termeknev',
		sortorder: 'asc',
		viewrecords: true,
		loadonce: false,
		gridview: true,
		height: 350,
		width: 647,
		hiddengrid: true,
		recreateForm:true,
		caption:'',
		beforeRequest:function() {
			var f=$(_tetel.grid);
			f[0].p.postData['bizonylatid']=$('#IdEdit').val();
		}
	});
	$(_tetel.grid).jqGrid('navGrid',_tetel.pager,{edit:true,add:true,del:true,search:false},
		{reloadAfterSubmit:true,jqModal:false,closeOnEscape:true,bottominfo:_txt.req,beforeShowForm:function(formid) {initHatarido();}},
		{reloadAfterSubmit:true,jqModal:false,closeOnEscape:true,bottominfo:_txt.req,beforeShowForm:function(formid) {initHatarido();}},
		{reloadAfterSubmit:true});
	$(_tetel.grid).jqGrid('navButtonAdd',_tetel.pager,{caption:_txt.srch,title:_txt.srchtoggle,buttonicon:_txt.srchicon,
		onClickButton:function(){
			TetelGrid[0].toggleToolbar();
		}
	});
	$(_tetel.grid).jqGrid('navButtonAdd',_tetel.pager,{caption:_txt.clr,title:_txt.clrtitle,buttonicon:_txt.clricon,
		onClickButton:function(){
			TetelGrid[0].clearToolbar();
		}
	});
	$(_tetel.grid).jqGrid('filterToolbar');
	$(_tetel.pager+'_center').hide();
	$(_tetel.pager+'_right').hide();

	$('.ui-search-toolbar').hide();
	$('.ui-jqgrid-titlebar').on('click',function(e) {
		e.preventDefault();
		$('.ui-jqgrid-titlebar-close',this).click();
	});
}