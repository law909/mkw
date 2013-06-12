$(document).ready(function(){
	var _txt={
			srch:'Keresés',
			srchtitle:'Keresés ki/be',
			srchicon:'ui-icon-search',
			clr:'Clear',
			clrtitle:'Clear search',
			clricon:'ui-icon-home'
		};
		var _szlat={
				grid:'#szamlatukorgrid',
				pager:'#szamlatukorgridpager'
		};
		var szamlatukorgrid=$(_szlat.grid).jqGrid({
			url:'/admin/szamlatukor/jsonlist',
			editurl:'/admin/szamlatukor/save',
			datatype: 'json',
			treeGrid:true,
			treeGridModel:'adjacency',
			ExpandColumn:'id',
			colModel:[
			          {name:'id',index:'id',label:'Főkönyvi szám',width:90},
			          {name:'nev',index:'nev',label:'Név',width:160},
			          {name:'afa',index:'afa',label:'ÁFA kulcs',width:25}],
			pager: _szlat.pager,
			gridview: false,
			caption:'Számlatükör'});
/*		$(_szlat.grid).jqGrid('navGrid',_szlat.pager,{edit:true,add:true,del:true,search:false},
			{reloadAfterSubmit:true,jqModal:false,closeOnEscape:true},
			{reloadAfterSubmit:true,jqModal:false,closeOnEscape:true},
			{reloadAfterSubmit:true});
		$(_szlat.grid).jqGrid('navButtonAdd',_szlat.pager,{caption:_txt.srch,title:_txt.srchtoggle,buttonicon:_txt.srchicon,
			onClickButton:function(){
				szamlatukorgrid[0].toggleToolbar();
			}
		});
		$(_szlat.grid).jqGrid('navButtonAdd',_szlat.pager,{caption:_txt.clr,title:_txt.clrtitle,buttonicon:_txt.clricon,
			onClickButton:function(){
				szamlatukorgrid[0].clearToolbar();
			}
		});
		$(_szlat.grid).jqGrid('filterToolbar');
*/		$(_szlat.pager+'_center').hide();
		$(_szlat.pager+'_right').hide();
});