$().ready(
	function() {
		var _txt={
			req:'A csillaggal jelölt mezők kitöltése kötelező.',
			srch:'Keresés',
			srchtitle:'Keresés ki/be',
			srchicon:'ui-icon-search',
			clr:'Clear',
			clrtitle:'Clear search',
			clricon:'ui-icon-home'
		};
		// AFA grid
		var _afa={
				grid:'#afagrid',
				pager:'#afagridpager'
		};
		var afagrid=$(_afa.grid).jqGrid({
			url:'/admin/afa/jsonlist',
			editurl:'/admin/afa/save',
			datatype: 'json',
			colModel:[
					{name:'nev',index:'nev',label:'Név',width:160,fixed:true,
							editable:true,
							editoptions:{size:25,maxlength:255},
							editrules:{required:true},
							formoptions:{rowpos:1,label:'Név:',elmsuffix:'*'}},
					{name:'ertek',index:'ertek',label:'ÁFA kulcs',width:25,align:'right',
							editable:true,
							editoptions:{size:2},
							editrules:{integer:true,required:true},
							formoptions:{rowpos:2,label:'ÁFA kulcs:',elmsuffix:'*'}}],
			rowNum:100000,
			rowList:[10,20,30],
			pager: _afa.pager,
			sortname: 'nev',
			sortorder: 'asc',
			viewrecords: true,
			loadonce: false,
			gridview: true,
			height: 100,
			width: 320,
			hiddengrid: true,
			caption:'ÁFA kulcsok'});
		$(_afa.grid).jqGrid('navGrid',_afa.pager,{edit:true,add:true,del:true,search:false},
			{reloadAfterSubmit:true,jqModal:false,closeOnEscape:true,bottominfo:_txt.req},
			{reloadAfterSubmit:true,jqModal:false,closeOnEscape:true,bottominfo:_txt.req},
			{reloadAfterSubmit:true});
		$(_afa.grid).jqGrid('navButtonAdd',_afa.pager,{caption:_txt.srch,title:_txt.srchtoggle,buttonicon:_txt.srchicon,
			onClickButton:function(){
				afagrid[0].toggleToolbar();
			}
		});
		$(_afa.grid).jqGrid('navButtonAdd',_afa.pager,{caption:_txt.clr,title:_txt.clrtitle,buttonicon:_txt.clricon,
			onClickButton:function(){
				afagrid[0].clearToolbar();
			}
		});
		$(_afa.grid).jqGrid('filterToolbar');
		$(_afa.pager+'_center').hide();
		$(_afa.pager+'_right').hide();

		// Bankszamla grid
		var _bszla={
				grid:'#bankszamlagrid',
				pager:'#bankszamlagridpager'
		};
		var bankszamlagrid=$(_bszla.grid).jqGrid({
			url:'/admin/bankszamla/jsonlist',
			editurl:'/admin/bankszamla/save',
			datatype: 'json',
			colModel:[
					{name:'banknev',index:'banknev',label:'Bank neve',width:160,
						editable:true,
						editoptions:{size:25,maxlength:50},
						formoptions:{rowpos:1,label:'Bank neve:'}},
					{name:'bankcim',index:'bankcim',label:'Bank címe',width:25,align:'right',
						editable:true,
						editoptions:{size:40,maxlength:70},
						formoptions:{rowpos:2,label:'Bank címe:'}},
					{name:'szamlaszam',index:'szamlaszam',label:'Számlaszám',width:160,fixed:true,
						editable:true,
						editoptions:{size:40,maxlength:255},
						editrules:{required:true},
						formoptions:{rowpos:3,label:'Számlaszám:',elmsuffix:'*'}},
					{name:'swift',index:'swift',label:'SWIFT',width:25,
						editable:true,
						editoptions:{size:20,maxlength:20},
						formoptions:{rowpos:4,label:'SWIFT:'}},
					{name:'iban',index:'iban',label:'IBAN',width:160,
						editable:true,
						editoptions:{size:20,maxlength:20},
						formoptions:{rowpos:5,label:'IBAN:'}},
					{name:'valutanem',index:'valutanem',label:'Valutanem',width:60,
						editable:true,
						edittype:'select',
						editoptions:{size:4,dataUrl:'/admin/valutanem/htmllist'},
						formoptions:{rowpos:6,label:'Valutanem:'}}],
			rowNum:100000,
			rowList:[10,20,30],
			pager: _bszla.pager,
			sortname: 'banknev',
			sortorder: 'asc',
			viewrecords: true,
			loadonce: false,
			gridview: true,
			height: 100,
			width: 647,
			hiddengrid: true,
			caption:'Bankszámlák'});
		$(_bszla.grid).jqGrid('navGrid',_bszla.pager,{edit:true,add:true,del:true,search:false},
			{reloadAfterSubmit:true,jqModal:false,closeOnEscape:true,bottominfo:_txt.req},
			{reloadAfterSubmit:true,jqModal:false,closeOnEscape:true,bottominfo:_txt.req},
			{reloadAfterSubmit:true});
		$(_bszla.grid).jqGrid('navButtonAdd',_bszla.pager,{caption:_txt.srch,title:_txt.srchtoggle,buttonicon:_txt.srchicon,
			onClickButton:function(){
				bankszamlagrid[0].toggleToolbar();
			}
		});
		$(_bszla.grid).jqGrid('navButtonAdd',_bszla.pager,{caption:_txt.clr,title:_txt.clrtitle,buttonicon:_txt.clricon,
			onClickButton:function(){
				bankszamlagrid[0].clearToolbar();
			}
		});
		$(_bszla.grid).jqGrid('filterToolbar');
		$(_bszla.pager+'_center').hide();
		$(_bszla.pager+'_right').hide();

		// Fizmod grid
		var _fm={
				grid:'#fizmodgrid',
				pager:'#fizmodgridpager'
		};
		var fizmodgrid=$(_fm.grid).jqGrid({
			url:'/admin/fizmod/jsonlist',
			editurl:'/admin/fizmod/save',
			datatype: 'json',
			colModel:[
					{name:'nev',index:'nev',label:'Név',width:160,fixed:true,
						editable:true,
						editoptions:{size:25,maxlength:255},
						editrules:{required:true},
						formoptions:{rowpos:1,label:'Név:',elmsuffix:'*'}},
					{name:'tipus',index:'tipus',label:'Típus',width:25,align:'right',
						editable:true,
						edittype:'select',
						editoptions:{size:2,value:'B:Bank;P:Pénztár'},
						editrules:{required:true},
						formoptions:{rowpos:2,label:'Típus:',elmsuffix:'*'}},
					{name:'haladek',index:'haladek',label:'Haladék',width:25,align:'right',
						editable:true,
						editoptions:{integer:true,size:2},
						formoptions:{rowpos:3,label:'Haladék:'}},
					{name:'webes',index:'webes',label:'Webes',width:25,align:'center',
						formatter:'checkbox',
						editable:true,
						edittype:'checkbox',
						editoptions:{value:'1:0'},
						editrules:{},
						formoptions:{rowpos:4,label:'Webes:'}}],
			rowNum:100000,
			rowList:[10,20,30],
			pager: _fm.pager,
			sortname: 'nev',
			sortorder: 'asc',
			viewrecords: true,
			loadonce: false,
			gridview: true,
			height: 100,
			width: 320,
			hiddengrid: true,
			caption:'Fizetési módok'});
		$(_fm.grid).jqGrid('navGrid',_fm.pager,{edit:true,add:true,del:true,search:false},
			{reloadAfterSubmit:true,jqModal:false,closeOnEscape:true,bottominfo:_txt.req},
			{reloadAfterSubmit:true,jqModal:false,closeOnEscape:true,bottominfo:_txt.req},
			{reloadAfterSubmit:true});
		$(_fm.grid).jqGrid('navButtonAdd',_fm.pager,{caption:_txt.srch,title:_txt.srchtoggle,buttonicon:_txt.srchicon,
			onClickButton:function(){
				fizmodgrid[0].toggleToolbar();
			}
		});
		$(_fm.grid).jqGrid('navButtonAdd',_fm.pager,{caption:_txt.clr,title:_txt.clrtitle,buttonicon:_txt.clricon,
			onClickButton:function(){
				fizmodgrid[0].clearToolbar();
			}
		});
		$(_fm.grid).jqGrid('filterToolbar');
		$(_fm.pager+'_center').hide();
		$(_fm.pager+'_right').hide();

		// Valutanem grid
		var _vn={
			grid:'#valutanemgrid',
			pager:'#valutanemgridpager'
		};
		var valutanemgrid=$(_vn.grid).jqGrid({
			url:'/admin/valutanem/jsonlist',
			editurl:'/admin/valutanem/save',
			datatype: 'json',
			colModel:[
					{name:'nev',index:'nev',label:'Név',width:60,fixed:true,
						editable:true,
						editoptions:{size:25,maxlength:255},
						editrules:{required:true},
						formoptions:{rowpos:1,label:'Név:',elmsuffix:'*'}},
					{name:'kerekit',index:'kerekit',label:'Kerekít',width:25,align:'right',
						formatter:'checkbox',
						editable:true,
						edittype:'checkbox',
						editoptions:{value:'1:0'},
						formoptions:{rowpos:2,label:'Kerekít:'}},
					{name:'hivatalos',index:'hivatalos',label:'Hivatalos',width:25,align:'right',
						formatter:'checkbox',
						editable:true,
						edittype:'checkbox',
						editoptions:{value:'1:0'},
						formoptions:{rowpos:3,label:'Hivatalos:'}},
					{name:'mincimlet',index:'mincimlet',label:'Legkisebb címlet',width:25,align:'center',
						editable:true,
						editoptions:{size:2},
						editrules:{integer:true},
						formoptions:{rowpos:4,label:'Legkisebb címlet:'}},
					{name:'bankszamla',index:'bankszamla',label:'Bankszámla',width:160,fixed:true,
						editable:true,
						edittype:'select',
						editoptions:{size:4,dataUrl:'/admin/bankszamla/htmllist'},
						formoptions:{rowpos:5,label:'Bankszámla:'}}],
			rowNum:100000,
			rowList:[10,20,30],
			pager: _vn.pager,
			sortname: 'nev',
			sortorder: 'asc',
			viewrecords: true,
			loadonce: false,
			gridview: true,
			height: 100,
			width: 320,
			hiddengrid: true,
			caption:'Valutanemek'});
		$(_vn.grid).jqGrid('navGrid',_vn.pager,{edit:true,add:true,del:true,search:false},
			{reloadAfterSubmit:true,jqModal:false,closeOnEscape:true,bottominfo:_txt.req},
			{reloadAfterSubmit:true,jqModal:false,closeOnEscape:true,bottominfo:_txt.req},
			{reloadAfterSubmit:true});
		$(_vn.grid).jqGrid('navButtonAdd',_vn.pager,{caption:_txt.srch,title:_txt.srchtoggle,buttonicon:_txt.srchicon,
			onClickButton:function(){
				valutanemgrid[0].toggleToolbar();
			}
		});
		$(_vn.grid).jqGrid('navButtonAdd',_vn.pager,{caption:_txt.clr,title:_txt.clrtitle,buttonicon:_txt.clricon,
			onClickButton:function(){
				valutanemgrid[0].clearToolbar();
			}
		});
		$(_vn.grid).jqGrid('filterToolbar');
		$(_vn.pager+'_center').hide();
		$(_vn.pager+'_right').hide();

		// Arfolyam grid
		var _arf={
				grid:'#arfolyamgrid',
				pager:'#arfolyamgridpager'
		};
		var arfolyamgrid=$(_arf.grid).jqGrid({
			url:'/admin/arfolyam/jsonlist',
			editurl:'/admin/arfolyam/save',
			datatype: 'json',
			colModel:[
					{name:'datum',index:'datum',label:'Dátum',width:60,
						sorttype:'date',
						formatter:'date',
						formatoptions:{srcformat:'Y-m-d',newformat:'Y.m.d',reformatAfterEdit:true},
						editable:true,
						editoptions:{size:15},
						editrules:{required:true,date:true},
						formoptions:{rowpos:1,label:'Dátum:',elmsuffix:'*'}},
					{name:'valutanem',index:'valutanem',label:'Valutanem',width:60,
						editable:true,
						edittype:'select',
						editrules:{required:true},
						editoptions:{size:4,dataUrl:'/admin/valutanem/htmllist'},
						formoptions:{rowpos:2,label:'Valutanem:',elmsuffix:'*'}},
					{name:'arfolyam',index:'arfolyam',label:'Árfolyam',width:60,align:'left',
						sorttype:'float',
						formatter:'number',
						editable:true,
						editoptions:{size:10},
						editrules:{required:true},
						formoptions:{rowpos:3,label:'Árfolyam:',elmsuffix:'*'}}],
			rowNum:100000,
			rowList:[10,20,30],
			pager: _arf.pager,
			sortname: 'datum',
			sortorder: 'asc',
			viewrecords: true,
			loadonce: false,
			gridview: true,
			height: 100,
			width: 320,
			hiddengrid: true,
			caption:'Árfolyamok'});
		$(_arf.grid).jqGrid('navGrid',_arf.pager,{edit:true,add:true,del:true,search:false},
			{reloadAfterSubmit:true,jqModal:false,closeOnEscape:true,bottominfo:_txt.req},
			{reloadAfterSubmit:true,jqModal:false,closeOnEscape:true,bottominfo:_txt.req},
			{reloadAfterSubmit:true});
		$(_arf.grid).jqGrid('navButtonAdd',_arf.pager,{caption:_txt.srch,title:_txt.srchtoggle,buttonicon:_txt.srchicon,
			onClickButton:function(){
				arfolyamgrid[0].toggleToolbar();
			}
		});
		$(_arf.grid).jqGrid('navButtonAdd',_arf.pager,{caption:_txt.clr,title:_txt.clrtitle,buttonicon:_txt.clricon,
			onClickButton:function(){
				arfolyamgrid[0].clearToolbar();
			}
		});
		$(_arf.grid).jqGrid('filterToolbar');
		$(_arf.pager+'_center').hide();
		$(_arf.pager+'_right').hide();

		// VTSZ grid
		var _vtsz={
			grid:'#vtszgrid',
			pager:'#vtszgridpager'
		}
		var vtszgrid=$(_vtsz.grid).jqGrid({
			url:'/admin/vtsz/jsonlist',
			editurl:'/admin/vtsz/save',
			datatype: 'json',
			colModel:[
					{name:'nev',index:'nev',label:'Név',width:160,fixed:true,
							editable:true,
							editoptions:{size:25,maxlength:255},
							editrules:{required:true},
							formoptions:{rowpos:1,label:'Név:',elmsuffix:'*'}},
					{name:'afa',index:'afa',label:'ÁFA kulcs',width:25,align:'right',
							editable:true,
							edittype:'select',
							editoptions:{size:4,dataUrl:'/admin/afa/htmllist'},
							editrules:{required:true},
							formoptions:{rowpos:2,label:'ÁFA kulcs:',elmsuffix:'*'}},
					{name:'kozvetitett',index:'kozvetitett',label:'Közvetített szolg.',width:25,align:'center',
							formatter:'checkbox',
							editable:true,
							edittype:'checkbox',
							editoptions:{value:'1:0'},
							editrules:{},
							formoptions:{rowpos:3,label:'Közvetített szolg.:'}}],
			rowNum:100000,
			rowList:[10,20,30],
			pager: _vtsz.pager,
			sortname: 'nev',
			sortorder: 'asc',
			viewrecords: true,
			loadonce: false,
			gridview: true,
			height: 100,
			width: 320,
			hiddengrid: true,
			caption:'VTSZ'});
		$(_vtsz.grid).jqGrid('navGrid',_vtsz.pager,{edit:true,add:true,del:true,search:false},
			{reloadAfterSubmit:true,jqModal:false,closeOnEscape:true,bottominfo:_txt.req},
			{reloadAfterSubmit:true,jqModal:false,closeOnEscape:true,bottominfo:_txt.req},
			{reloadAfterSubmit:true});
		$(_vtsz.grid).jqGrid('navButtonAdd',_vtsz.pager,{caption:_txt.srch,title:_txt.srchtoggle,buttonicon:_txt.srchicon,
			onClickButton:function(){
				vtszgrid[0].toggleToolbar();
			}
		});
		$(_vtsz.grid).jqGrid('navButtonAdd',_vtsz.pager,{caption:_txt.clr,title:_txt.clrtitle,buttonicon:_txt.clricon,
			onClickButton:function(){
				vtszgrid[0].clearToolbar();
			}
		});
		$(_vtsz.grid).jqGrid('filterToolbar');
		$(_vtsz.pager+'_center').hide();
		$(_vtsz.pager+'_right').hide();

		// TermekCimkeKat grid
		var _tck={
				grid:'#termekcimkekatgrid',
				pager:'#termekcimkekatgridpager'
		};
		var termekcimkekatgrid=$(_tck.grid).jqGrid({
			url:'/admin/termekcimkekat/jsonlist',
			editurl:'/admin/termekcimkekat/save',
			datatype: 'json',
			colModel:[
					{name:'nev',index:'nev',label:'Név',width:160,fixed:true,
							editable:true,
							editoptions:{size:25,maxlength:255},
							editrules:{required:true},
							formoptions:{rowpos:1,label:'Név:',elmsuffix:'*'}},
					{name:'sorrend',index:'sorrend',label:'Sorrend',width:25,align:'right',
							editable:true,
							editoptions:{},
							editrules:{integer:true},
							formoptions:{rowpos:2,label:'Sorrend:'}},
					{name:'termeklaponlathato',index:'termeklaponlathato',label:'Terméklapon látható',width:25,align:'center',
							formatter:'checkbox',
							editable:true,
							edittype:'checkbox',
							editoptions:{value:'1:0'},
							editrules:{},
							formoptions:{rowpos:3,label:'Terméklapon látható:'}},
					{name:'termekszurobenlathato',index:'termekszurobenlathato',label:'Termékszűrőben látható',width:25,align:'center',
							formatter:'checkbox',
							editable:true,
							edittype:'checkbox',
							editoptions:{value:'1:0'},
							editrules:{},
							formoptions:{rowpos:4,label:'Termékszűrőben látható:'}},
					{name:'termeklistabanlathato',index:'termeklistabanlathato',label:'Terméklistában látható',width:25,align:'center',
							formatter:'checkbox',
							editable:true,
							edittype:'checkbox',
							editoptions:{value:'1:0'},
							editrules:{},
							formoptions:{rowpos:5,label:'Terméklistában látható:'}},
					{name:'termekakciodobozbanlathato',index:'termekakciodobozbanlathato',label:'Termék akciódobozban látható',width:25,align:'center',
							formatter:'checkbox',
							editable:true,
							edittype:'checkbox',
							editoptions:{value:'1:0'},
							editrules:{},
							formoptions:{rowpos:6,label:'Termék akciódobozban látható:'}}],
			rowNum:100000,
			rowList:[10,20,30],
			pager: _tck.pager,
			sortname: 'nev',
			sortorder: 'asc',
			viewrecords: true,
			loadonce: false,
			gridview: true,
			height: 100,
			width: 320,
			hiddengrid: true,
			caption:'Termékcímke csoportok'});
		$(_tck.grid).jqGrid('navGrid',_tck.pager,{edit:true,add:true,del:true,search:false},
			{reloadAfterSubmit:true,jqModal:false,closeOnEscape:true,bottominfo:_txt.req},
			{reloadAfterSubmit:true,jqModal:false,closeOnEscape:true,bottominfo:_txt.req},
			{reloadAfterSubmit:true});
		$(_tck.grid).jqGrid('navButtonAdd',_tck.pager,{caption:_txt.srch,title:_txt.srchtoggle,buttonicon:_txt.srchicon,
			onClickButton:function(){
				termekcimkekatgrid[0].toggleToolbar();
			}
		});
		$(_tck.grid).jqGrid('navButtonAdd',_tck.pager,{caption:_txt.clr,title:_txt.clrtitle,buttonicon:_txt.clricon,
			onClickButton:function(){
				termekcimkekatgrid[0].clearToolbar();
			}
		});
		$(_tck.grid).jqGrid('filterToolbar');
		$(_tck.pager+'_center').hide();
		$(_tck.pager+'_right').hide();

		// PartnerCimkeKat grid
		var _pck={
				grid:'#partnercimkekatgrid',
				pager:'#partnercimkekatgridpager'
		};
		var partnercimkekatgrid=$(_pck.grid).jqGrid({
			url:'/admin/partnercimkekat/jsonlist',
			editurl:'/admin/partnercimkekat/save',
			datatype: 'json',
			colModel:[
					{name:'nev',index:'nev',label:'Név',width:160,fixed:true,
							editable:true,
							editoptions:{size:25,maxlength:255},
							editrules:{required:true},
							formoptions:{rowpos:1,label:'Név:',elmsuffix:'*'}},
					{name:'lathato',index:'lathato',label:'Látható',width:25,align:'center',
							formatter:'checkbox',
							editable:true,
							edittype:'checkbox',
							editoptions:{value:'1:0'},
							editrules:{},
							formoptions:{rowpos:2,label:'Látható:'}}],
			rowNum:100000,
			rowList:[10,20,30],
			pager: _pck.pager,
			sortname: 'nev',
			sortorder: 'asc',
			viewrecords: true,
			loadonce: false,
			gridview: true,
			height: 100,
			width: 320,
			hiddengrid: true,
			caption:'Partnercímke csoportok'});
		$(_pck.grid).jqGrid('navGrid',_pck.pager,{edit:true,add:true,del:true,search:false},
			{reloadAfterSubmit:true,jqModal:false,closeOnEscape:true,bottominfo:_txt.req},
			{reloadAfterSubmit:true,jqModal:false,closeOnEscape:true,bottominfo:_txt.req},
			{reloadAfterSubmit:true});
		$(_pck.grid).jqGrid('navButtonAdd',_pck.pager,{caption:_txt.srch,title:_txt.srchtoggle,buttonicon:_txt.srchicon,
			onClickButton:function(){
				partnercimkekatgrid[0].toggleToolbar();
			}
		});
		$(_pck.grid).jqGrid('navButtonAdd',_pck.pager,{caption:_txt.clr,title:_txt.clrtitle,buttonicon:_txt.clricon,
			onClickButton:function(){
				partnercimkekatgrid[0].clearToolbar();
			}
		});
		$(_pck.grid).jqGrid('filterToolbar');
		$(_pck.pager+'_center').hide();
		$(_pck.pager+'_right').hide();

		/** KontaktCimkeKat grid
		var _kck={
				grid:'#kontaktcimkekatgrid',
				pager:'#kontaktcimkekatgridpager'
		};
		var kontaktcimkekatgrid=$(_kck.grid).jqGrid({
			url:'/admin/kontaktcimkekat/jsonlist',
			editurl:'/admin/kontaktcimkekat/save',
			datatype: 'json',
			colModel:[
					{name:'nev',index:'nev',label:'Név',width:160,fixed:true,
							editable:true,
							editoptions:{size:25,maxlength:255},
							editrules:{required:true},
							formoptions:{rowpos:1,label:'Név:',elmsuffix:'*'}},
					{name:'lathato',index:'lathato',label:'Látható',width:25,align:'center',
							formatter:'checkbox',
							editable:true,
							edittype:'checkbox',
							editoptions:{value:'1:0'},
							editrules:{},
							formoptions:{rowpos:2,label:'Látható:'}}],
			rowNum:100000,
			rowList:[10,20,30],
			pager: _kck.pager,
			sortname: 'nev',
			sortorder: 'asc',
			viewrecords: true,
			loadonce: false,
			gridview: true,
			height: 100,
			width: 320,
			hiddengrid: true,
			caption:'Kontaktcímke csoportok'});
		$(_kck.grid).jqGrid('navGrid',_kck.pager,{edit:true,add:true,del:true,search:false},
			{reloadAfterSubmit:true,jqModal:false,closeOnEscape:true,bottominfo:_txt.req},
			{reloadAfterSubmit:true,jqModal:false,closeOnEscape:true,bottominfo:_txt.req},
			{reloadAfterSubmit:true});
		$(_kck.grid).jqGrid('navButtonAdd',_kck.pager,{caption:_txt.srch,title:_txt.srchtoggle,buttonicon:_txt.srchicon,
			onClickButton:function(){
				kontaktcimkekatgrid[0].toggleToolbar();
			}
		});
		$(_kck.grid).jqGrid('navButtonAdd',_kck.pager,{caption:_txt.clr,title:_txt.clrtitle,buttonicon:_txt.clricon,
			onClickButton:function(){
				kontaktcimkekatgrid[0].clearToolbar();
			}
		});
		$(_kck.grid).jqGrid('filterToolbar');
		$(_kck.pager+'_center').hide();
		$(_kck.pager+'_right').hide();
*/
		// PartnerCsoport grid
		var _pcs={
				grid:'#partnercsoportgrid',
				pager:'#partnercsoportgridpager'
		};
		var partnercsoportgrid=$(_pcs.grid).jqGrid({
			url:'/admin/partnercsoport/jsonlist',
			editurl:'/admin/partnercsoport/save',
			datatype: 'json',
			colModel:[
					{name:'nev',index:'nev',label:'Név',width:160,fixed:true,
						editable:true,
						editoptions:{size:25,maxlength:255},
						editrules:{required:true},
						formoptions:{rowpos:1,label:'Név:',elmsuffix:'*'}},
					{name:'tipus',index:'tipus',label:'Típus',width:25,
						editable:true,
						edittype:'select',
						editoptions:{size:20,value:'vevo:Vevő;szallito:Szállító'},
						editrules:{required:true},
						formoptions:{rowpos:2,label:'Típus:',elmsuffix:'*'}},
					{name:'fkviszam',index:'fkviszam',label:'Főkönyvi szám',width:25,
						editable:true,
						editoptions:{size:8,maxlength:8},
						formoptions:{rowpos:3,label:'Főkönyvi szám:'}}],
			rowNum:100000,
			rowList:[10,20,30],
			pager: _pcs.pager,
			sortname: 'nev',
			sortorder: 'asc',
			viewrecords: true,
			loadonce: false,
			gridview: true,
			height: 100,
			width: 320,
			hiddengrid: true,
			caption:'Partnercsoportok'});
		$(_pcs.grid).jqGrid('navGrid',_pcs.pager,{edit:true,add:true,del:true,search:false},
			{reloadAfterSubmit:true,jqModal:false,closeOnEscape:true,bottominfo:_txt.req},
			{reloadAfterSubmit:true,jqModal:false,closeOnEscape:true,bottominfo:_txt.req},
			{reloadAfterSubmit:true});
		$(_pcs.grid).jqGrid('navButtonAdd',_pcs.pager,{caption:_txt.srch,title:_txt.srchtoggle,buttonicon:_txt.srchicon,
			onClickButton:function(){
				partnercsoportgrid[0].toggleToolbar();
			}
		});
		$(_pcs.grid).jqGrid('navButtonAdd',_pcs.pager,{caption:_txt.clr,title:_txt.clrtitle,buttonicon:_txt.clricon,
			onClickButton:function(){
				partnercsoportgrid[0].clearToolbar();
			}
		});
		$(_pcs.grid).jqGrid('filterToolbar');
		$(_pcs.pager+'_center').hide();
		$(_pcs.pager+'_right').hide();

		// TermekCsoport grid
		var _tcs={
				grid:'#termekcsoportgrid',
				pager:'#termekcsoportgridpager'
		};
		var termekcsoportgrid=$(_tcs.grid).jqGrid({
			url:'/admin/termekcsoport/jsonlist',
			editurl:'/admin/termekcsoport/save',
			datatype: 'json',
			colModel:[
					{name:'nev',index:'nev',label:'Név',width:160,fixed:true,
						editable:true,
						editoptions:{size:25,maxlength:255},
						editrules:{required:true},
						formoptions:{rowpos:1,label:'Név:',elmsuffix:'*'}},
					{name:'keszletfkviszam',index:'keszletfkviszam',label:'Készlet főkönyvi szám',width:25,
						editable:true,
						editoptions:{size:8,maxlength:8},
						formoptions:{rowpos:2,label:'Készlet főkönyvi szám:'}},
					{name:'arbevetelfkviszam',index:'arbevetelfkviszam',label:'Árbevétel főkönyvi szám',width:25,
						editable:true,
						editoptions:{size:8,maxlength:8},
						formoptions:{rowpos:3,label:'Árbevétel főkönyvi szám:'}},
					{name:'elabefkviszam',index:'elabefkviszam',label:'ELÁBÉ főkönyvi szám',width:25,
						editable:true,
						editoptions:{size:8,maxlength:8},
						formoptions:{rowpos:4,label:'ELÁBÉ főkönyvi szám:'}}],
			rowNum:100000,
			rowList:[10,20,30],
			pager: _tcs.pager,
			sortname: 'nev',
			sortorder: 'asc',
			viewrecords: true,
			loadonce: false,
			gridview: true,
			height: 100,
			width: 320,
			hiddengrid: true,
			caption:'Termékcsoportok'});
		$(_tcs.grid).jqGrid('navGrid',_tcs.pager,{edit:true,add:true,del:true,search:false},
			{reloadAfterSubmit:true,jqModal:false,closeOnEscape:true,bottominfo:_txt.req},
			{reloadAfterSubmit:true,jqModal:false,closeOnEscape:true,bottominfo:_txt.req},
			{reloadAfterSubmit:true});
		$(_tcs.grid).jqGrid('navButtonAdd',_tcs.pager,{caption:_txt.srch,title:_txt.srchtoggle,buttonicon:_txt.srchicon,
			onClickButton:function(){
				termekcsoportgrid[0].toggleToolbar();
			}
		});
		$(_tcs.grid).jqGrid('navButtonAdd',_tcs.pager,{caption:_txt.clr,title:_txt.clrtitle,buttonicon:_txt.clricon,
			onClickButton:function(){
				termekcsoportgrid[0].clearToolbar();
			}
		});
		$(_tcs.grid).jqGrid('filterToolbar');
		$(_tcs.pager+'_center').hide();
		$(_tcs.pager+'_right').hide();

		// TargyieszkozCsoport grid
		var _tecs={
				grid:'#targyieszkozcsoportgrid',
				pager:'#targyieszkozcsoportgridpager'
		};
		var tecsoportgrid=$(_tecs.grid).jqGrid({
			url:'/admin/targyieszkozcsoport/jsonlist',
			editurl:'/admin/targyieszkozcsoport/save',
			datatype: 'json',
			colModel:[
					{name:'nev',index:'nev',label:'Név',width:160,fixed:true,
						editable:true,
						editoptions:{size:25,maxlength:255},
						editrules:{required:true},
						formoptions:{rowpos:1,label:'Név:',elmsuffix:'*'}},
					{name:'beszerzesiktgfkviszam',index:'beszerzesiktgfkviszam',label:'Beszerzési ktg. főkönyvi szám',width:25,
						editable:true,
						editoptions:{size:8,maxlength:8},
						formoptions:{rowpos:2,label:'Beszerzési ktg. főkönyvi szám:'}},
					{name:'ecsleirasfkviszam',index:'ecsleirasfkviszam',label:'ÉCS leírás főkönyvi szám',width:25,
						editable:true,
						editoptions:{size:8,maxlength:8},
						formoptions:{rowpos:3,label:'ÉCS leírás főkönyvi szám:'}},
					{name:'ecsktgfkviszam',index:'ecsktgfkviszam',label:'ÉCS ktg. főkönyvi szám',width:25,
						editable:true,
						editoptions:{size:8,maxlength:8},
						formoptions:{rowpos:4,label:'ÉCS ktg. főkönyvi szám:'}}],
			rowNum:100000,
			rowList:[10,20,30],
			pager: _tecs.pager,
			sortname: 'nev',
			sortorder: 'asc',
			viewrecords: true,
			loadonce: false,
			gridview: true,
			height: 100,
			width: 320,
			hiddengrid: true,
			caption:'Tárgyieszköz csoportok'});
		$(_tecs.grid).jqGrid('navGrid',_tecs.pager,{edit:true,add:true,del:true,search:false},
			{reloadAfterSubmit:true,jqModal:false,closeOnEscape:true,bottominfo:_txt.req},
			{reloadAfterSubmit:true,jqModal:false,closeOnEscape:true,bottominfo:_txt.req},
			{reloadAfterSubmit:true});
		$(_tecs.grid).jqGrid('navButtonAdd',_tecs.pager,{caption:_txt.srch,title:_txt.srchtoggle,buttonicon:_txt.srchicon,
			onClickButton:function(){
				tecsoportgrid[0].toggleToolbar();
			}
		});
		$(_tecs.grid).jqGrid('navButtonAdd',_tecs.pager,{caption:_txt.clr,title:_txt.clrtitle,buttonicon:_txt.clricon,
			onClickButton:function(){
				tecsoportgrid[0].clearToolbar();
			}
		});
		$(_tecs.grid).jqGrid('filterToolbar');
		$(_tecs.pager+'_center').hide();
		$(_tecs.pager+'_right').hide();

		// Felhasználó grid
		var _felhasznalo={
				grid:'#felhasznalogrid',
				pager:'#felhasznalogridpager'
		};
		var felhasznalogrid=$(_felhasznalo.grid).jqGrid({
			url:'/admin/felhasznalo/jsonlist',
			editurl:'/admin/felhasznalo/save',
			datatype: 'json',
			colModel:[
					{name:'nev',index:'nev',label:'Név',width:160,
							editable:true,
							editoptions:{size:25,maxlength:255},
							editrules:{required:true},
							formoptions:{rowpos:1,label:'Név:',elmsuffix:'*'}},
					{name:'felhasznalonev',index:'felhasznalonev',label:'Felhasználónév',width:160,
							editable:true,
							editoptions:{size:25,maxlength:255},
							editrules:{required:true},
							formoptions:{rowpos:2,label:'Felhasználónév:',elmsuffix:'*'}},
					{name:'jelszo',index:'jelszo',label:'Jelszó',width:160,
							editable:true,
							editoptions:{size:25,maxlength:255},
							editrules:{required:true},
							formoptions:{rowpos:3,label:'Jelszó:',elmsuffix:'*'}},
					{name:'uzletkoto',index:'uzletkoto',label:'Üzletkötő',width:25,
							editable:true,
							edittype:'select',
							editoptions:{size:4,dataUrl:'/admin/uzletkoto/htmllist'},
							formoptions:{rowpos:4,label:'Üzletkötő:',elmsuffix:'*'}}
							],
			rowNum:100000,
			rowList:[10,20,30],
			pager: _felhasznalo.pager,
			sortname: 'nev',
			sortorder: 'asc',
			viewrecords: true,
			loadonce: false,
			gridview: true,
			height: 100,
			width: 320,
			hiddengrid: true,
			caption:'Felhasználók'});
		$(_felhasznalo.grid).jqGrid('navGrid',_felhasznalo.pager,{edit:true,add:true,del:true,search:false},
			{reloadAfterSubmit:true,jqModal:false,closeOnEscape:true,bottominfo:_txt.req},
			{reloadAfterSubmit:true,jqModal:false,closeOnEscape:true,bottominfo:_txt.req},
			{reloadAfterSubmit:true});
		$(_felhasznalo.grid).jqGrid('navButtonAdd',_felhasznalo.pager,{caption:_txt.srch,title:_txt.srchtoggle,buttonicon:_txt.srchicon,
			onClickButton:function(){
				felhasznalogrid[0].toggleToolbar();
			}
		});
		$(_felhasznalo.grid).jqGrid('navButtonAdd',_felhasznalo.pager,{caption:_txt.clr,title:_txt.clrtitle,buttonicon:_txt.clricon,
			onClickButton:function(){
				felhasznalogrid[0].clearToolbar();
			}
		});
		$(_felhasznalo.grid).jqGrid('filterToolbar');
		$(_felhasznalo.pager+'_center').hide();
		$(_felhasznalo.pager+'_right').hide();

		// TermekValtozatAdatTipus grid
		var _tvat={
				grid:'#termekvaltozatadattipusgrid',
				pager:'#termekvaltozatadattipusgridpager'
		};
		var termekvaltozatadattipusgrid=$(_tvat.grid).jqGrid({
			url:'/admin/termekvaltozatadattipus/jsonlist',
			editurl:'/admin/termekvaltozatadattipus/save',
			datatype: 'json',
			colModel:[
					{name:'nev',index:'nev',label:'Név',width:160,fixed:true,
							editable:true,
							editoptions:{size:25,maxlength:255},
							editrules:{required:true},
							formoptions:{rowpos:1,label:'Név:',elmsuffix:'*'}}],
			rowNum:100000,
			rowList:[10,20,30],
			pager: _tvat.pager,
			sortname: 'nev',
			sortorder: 'asc',
			viewrecords: true,
			loadonce: false,
			gridview: true,
			height: 100,
			width: 320,
			hiddengrid: true,
			caption:'Termékváltozat adattípusok'});
		$(_tvat.grid).jqGrid('navGrid',_tvat.pager,{edit:true,add:true,del:true,search:false},
			{reloadAfterSubmit:true,jqModal:false,closeOnEscape:true,bottominfo:_txt.req},
			{reloadAfterSubmit:true,jqModal:false,closeOnEscape:true,bottominfo:_txt.req},
			{reloadAfterSubmit:true});
		$(_tvat.grid).jqGrid('navButtonAdd',_tvat.pager,{caption:_txt.srch,title:_txt.srchtoggle,buttonicon:_txt.srchicon,
			onClickButton:function(){
				termekvaltozatadattipusgrid[0].toggleToolbar();
			}
		});
		$(_tvat.grid).jqGrid('navButtonAdd',_tvat.pager,{caption:_txt.clr,title:_txt.clrtitle,buttonicon:_txt.clricon,
			onClickButton:function(){
				termekvaltozatadattipusgrid[0].clearToolbar();
			}
		});
		$(_tvat.grid).jqGrid('filterToolbar');
		$(_tvat.pager+'_center').hide();
		$(_tvat.pager+'_right').hide();

		// Munkakor grid
		var _mkor={
				grid:'#munkakorgrid',
				pager:'#munkakorgridpager'
		};
		var mkorgrid=$(_mkor.grid).jqGrid({
			url:'/admin/munkakor/jsonlist',
			editurl:'/admin/munkakor/save',
			datatype: 'json',
			colModel:[
					{name:'nev',index:'nev',label:'Név',width:160,fixed:true,
							editable:true,
							editoptions:{size:25,maxlength:255},
							editrules:{required:true},
							formoptions:{rowpos:1,label:'Név:',elmsuffix:'*'}}],
			rowNum:100000,
			rowList:[10,20,30],
			pager: _mkor.pager,
			sortname: 'nev',
			sortorder: 'asc',
			viewrecords: true,
			loadonce: false,
			gridview: true,
			height: 100,
			width: 320,
			hiddengrid: true,
			caption:'Munkakörök'});
		$(_mkor.grid).jqGrid('navGrid',_mkor.pager,{edit:true,add:true,del:true,search:false},
			{reloadAfterSubmit:true,jqModal:false,closeOnEscape:true,bottominfo:_txt.req},
			{reloadAfterSubmit:true,jqModal:false,closeOnEscape:true,bottominfo:_txt.req},
			{reloadAfterSubmit:true});
		$(_mkor.grid).jqGrid('navButtonAdd',_mkor.pager,{caption:_txt.srch,title:_txt.srchtoggle,buttonicon:_txt.srchicon,
			onClickButton:function(){
				mkorgrid[0].toggleToolbar();
			}
		});
		$(_mkor.grid).jqGrid('navButtonAdd',_mkor.pager,{caption:_txt.clr,title:_txt.clrtitle,buttonicon:_txt.clricon,
			onClickButton:function(){
				mkorgrid[0].clearToolbar();
			}
		});
		$(_mkor.grid).jqGrid('filterToolbar');
		$(_mkor.pager+'_center').hide();
		$(_mkor.pager+'_right').hide();

		// Jelenlettipus grid
		var _jlt={
				grid:'#jelenlettipusgrid',
				pager:'#jelenlettipusgridpager'
		};
		var jltgrid=$(_jlt.grid).jqGrid({
			url:'/admin/jelenlettipus/jsonlist',
			editurl:'/admin/jelenlettipus/save',
			datatype: 'json',
			colModel:[
					{name:'nev',index:'nev',label:'Név',width:160,fixed:true,
							editable:true,
							editoptions:{size:25,maxlength:255},
							editrules:{required:true},
							formoptions:{rowpos:1,label:'Név:',elmsuffix:'*'}}],
			rowNum:100000,
			rowList:[10,20,30],
			pager: _jlt.pager,
			sortname: 'nev',
			sortorder: 'asc',
			viewrecords: true,
			loadonce: false,
			gridview: true,
			height: 100,
			width: 320,
			hiddengrid: true,
			caption:'Jelenlét tipusok'});
		$(_jlt.grid).jqGrid('navGrid',_jlt.pager,{edit:true,add:true,del:true,search:false},
			{reloadAfterSubmit:true,jqModal:false,closeOnEscape:true,bottominfo:_txt.req},
			{reloadAfterSubmit:true,jqModal:false,closeOnEscape:true,bottominfo:_txt.req},
			{reloadAfterSubmit:true});
		$(_jlt.grid).jqGrid('navButtonAdd',_jlt.pager,{caption:_txt.srch,title:_txt.srchtoggle,buttonicon:_txt.srchicon,
			onClickButton:function(){
				jltgrid[0].toggleToolbar();
			}
		});
		$(_jlt.grid).jqGrid('navButtonAdd',_jlt.pager,{caption:_txt.clr,title:_txt.clrtitle,buttonicon:_txt.clricon,
			onClickButton:function(){
				jltgrid[0].clearToolbar();
			}
		});
		$(_jlt.grid).jqGrid('filterToolbar');
		$(_jlt.pager+'_center').hide();
		$(_jlt.pager+'_right').hide();

		// Raktar grid
		var _rkt={
				grid:'#raktargrid',
				pager:'#raktargridpager'
		};
		var rktgrid=$(_rkt.grid).jqGrid({
			url:'/admin/raktar/jsonlist',
			editurl:'/admin/raktar/save',
			datatype: 'json',
			colModel:[
					{name:'nev',index:'nev',label:'Név',width:160,fixed:true,
							editable:true,
							editoptions:{size:25,maxlength:50},
							editrules:{required:true},
							formoptions:{rowpos:1,label:'Név:',elmsuffix:'*'}},
					{name:'mozgat',index:'mozgat',label:'Készletet mozgat',width:25,align:'center',
						formatter:'checkbox',
						editable:true,
						edittype:'checkbox',
						editoptions:{value:'1:0'},
						editrules:{},
						formoptions:{rowpos:2,label:'Készletet mozgat:'}}],
			rowNum:100000,
			rowList:[10,20,30],
			pager: _rkt.pager,
			sortname: 'nev',
			sortorder: 'asc',
			viewrecords: true,
			loadonce: false,
			gridview: true,
			height: 100,
			width: 320,
			hiddengrid: true,
			caption:'Raktárak'});
		$(_rkt.grid).jqGrid('navGrid',_rkt.pager,{edit:true,add:true,del:true,search:false},
			{reloadAfterSubmit:true,jqModal:false,closeOnEscape:true,bottominfo:_txt.req},
			{reloadAfterSubmit:true,jqModal:false,closeOnEscape:true,bottominfo:_txt.req},
			{reloadAfterSubmit:true});
		$(_rkt.grid).jqGrid('navButtonAdd',_rkt.pager,{caption:_txt.srch,title:_txt.srchtoggle,buttonicon:_txt.srchicon,
			onClickButton:function(){
				rktgrid[0].toggleToolbar();
			}
		});
		$(_rkt.grid).jqGrid('navButtonAdd',_rkt.pager,{caption:_txt.clr,title:_txt.clrtitle,buttonicon:_txt.clricon,
			onClickButton:function(){
				rktgrid[0].clearToolbar();
			}
		});
		$(_rkt.grid).jqGrid('filterToolbar');
		$(_rkt.pager+'_center').hide();
		$(_rkt.pager+'_right').hide();

		// Kapcsolatfelveteltema grid
		var _kft={
				grid:'#kapcsolatfelveteltemagrid',
				pager:'#kapcsolatfelveteltemagridpager'
		};
		var afagrid=$(_kft.grid).jqGrid({
			url:'/admin/kapcsolatfelveteltema/jsonlist',
			editurl:'/admin/kapcsolatfelveteltema/save',
			datatype: 'json',
			colModel:[
					{name:'nev',index:'nev',label:'Név',width:160,fixed:true,
							editable:true,
							editoptions:{size:25,maxlength:255},
							editrules:{required:true},
							formoptions:{rowpos:1,label:'Név:',elmsuffix:'*'}}],
			rowNum:100000,
			rowList:[10,20,30],
			pager: _kft.pager,
			sortname: 'nev',
			sortorder: 'asc',
			viewrecords: true,
			loadonce: false,
			gridview: true,
			height: 100,
			width: 320,
			hiddengrid: true,
			caption:'Kapcsolatfelvétel témák'});
		$(_kft.grid).jqGrid('navGrid',_kft.pager,{edit:true,add:true,del:true,search:false},
			{reloadAfterSubmit:true,jqModal:false,closeOnEscape:true,bottominfo:_txt.req},
			{reloadAfterSubmit:true,jqModal:false,closeOnEscape:true,bottominfo:_txt.req},
			{reloadAfterSubmit:true});
		$(_kft.grid).jqGrid('navButtonAdd',_kft.pager,{caption:_txt.srch,title:_txt.srchtoggle,buttonicon:_txt.srchicon,
			onClickButton:function(){
				afagrid[0].toggleToolbar();
			}
		});
		$(_kft.grid).jqGrid('navButtonAdd',_kft.pager,{caption:_txt.clr,title:_txt.clrtitle,buttonicon:_txt.clricon,
			onClickButton:function(){
				afagrid[0].clearToolbar();
			}
		});
		$(_kft.grid).jqGrid('filterToolbar');
		$(_kft.pager+'_center').hide();
		$(_kft.pager+'_right').hide();

		// Altalanos
		$('.ui-search-toolbar').hide();
		$('.ui-jqgrid-titlebar').on('click',function(e) {
			e.preventDefault();
			$('.ui-jqgrid-titlebar-close',this).click();
		});
});