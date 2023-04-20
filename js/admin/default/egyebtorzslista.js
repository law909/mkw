$().ready(
    function () {
        var _txt = {
            req: 'A csillaggal jelölt mezők kitöltése kötelező.',
            srch: 'Keresés',
            srchtitle: 'Keresés ki/be',
            srchicon: 'ui-icon-search',
            clr: 'Clear',
            clrtitle: 'Clear search',
            clricon: 'ui-icon-home'
        };

        function createNav(obj, grid) {
            $(obj.grid).jqGrid('navGrid', obj.pager, {edit: true, add: true, del: true, search: false},
                {reloadAfterSubmit: true, jqModal: false, closeOnEscape: true, bottominfo: _txt.req},
                {reloadAfterSubmit: true, jqModal: false, closeOnEscape: true, bottominfo: _txt.req},
                {reloadAfterSubmit: true});
            $(obj.grid).jqGrid('navButtonAdd', obj.pager, {
                caption: _txt.srch, title: _txt.srchtoggle, buttonicon: _txt.srchicon,
                onClickButton: function () {
                    grid[0].toggleToolbar();
                }
            });
            $(obj.grid).jqGrid('navButtonAdd', obj.pager, {
                caption: _txt.clr, title: _txt.clrtitle, buttonicon: _txt.clricon,
                onClickButton: function () {
                    grid[0].clearToolbar();
                }
            });
            $(obj.grid).jqGrid('filterToolbar');
            $(obj.pager + '_center').hide();
            $(obj.pager + '_right').hide();
        }


        // AFA grid
        var _afa = {
            grid: '#afagrid',
            pager: '#afagridpager'
        };
        var afagrid = $(_afa.grid).jqGrid({
            url: '/admin/afa/jsonlist',
            editurl: '/admin/afa/save',
            datatype: 'json',
            colModel: [
                {
                    name: 'nev', index: 'nev', label: 'Név', width: 160, fixed: true,
                    editable: true,
                    editoptions: {size: 25, maxlength: 255},
                    editrules: {required: true},
                    formoptions: {rowpos: 1, label: 'Név:', elmsuffix: '*'}
                },
                {
                    name: 'ertek', index: 'ertek', label: 'ÁFA kulcs', width: 25, align: 'right',
                    editable: true,
                    editoptions: {size: 2},
                    editrules: {integer: true, required: true},
                    formoptions: {rowpos: 2, label: 'ÁFA kulcs:', elmsuffix: '*'}
                },
                {
                    name: 'navcase', index: 'navcase', label: 'NAV case', width: 60,
                    editable: true,
                    edittype: 'select',
                    editoptions: {size: 4, dataUrl: '/admin/afa/navcaselist'},
                    formoptions: {rowpos: 3, label: 'NAV case:'}
                },
                {
                    name: 'rlbkod', index: 'rlbkod', label: 'RLB kód', width: 25, align: 'right',
                    editable: true,
                    editoptions: {size: 2},
                    editrules: {integer: true, required: false},
                    formoptions: {rowpos: 4, label: 'RLB kód:'}
                },
                {
                    name: 'emagid', index: 'emagid', label: 'eMAG id', width: 25, align: 'right',
                    editable: true,
                    editoptions: {size: 2},
                    editrules: {integer: true, required: false},
                    formoptions: {rowpos: 5, label: 'eMAG id:'}
                }],
            rowNum: 100000,
            rowList: [10, 20, 30],
            pager: _afa.pager,
            sortname: 'nev',
            sortorder: 'asc',
            viewrecords: true,
            loadonce: false,
            gridview: true,
            height: 100,
            width: 320,
            hiddengrid: true,
            caption: 'ÁFA kulcsok'
        });
        createNav(_afa, afagrid);

        // Bankszamla grid
        var _bszla = {
            grid: '#bankszamlagrid',
            pager: '#bankszamlagridpager'
        };
        var bankszamlagrid = $(_bszla.grid).jqGrid({
            url: '/admin/bankszamla/jsonlist',
            editurl: '/admin/bankszamla/save',
            datatype: 'json',
            colModel: [
                {
                    name: 'banknev', index: 'banknev', label: 'Bank neve', width: 160,
                    editable: true,
                    editoptions: {size: 25, maxlength: 50},
                    formoptions: {rowpos: 1, label: 'Bank neve:'}
                },
                {
                    name: 'bankcim', index: 'bankcim', label: 'Bank címe', width: 25, align: 'right',
                    editable: true,
                    editoptions: {size: 40, maxlength: 70},
                    formoptions: {rowpos: 2, label: 'Bank címe:'}
                },
                {
                    name: 'szamlaszam', index: 'szamlaszam', label: 'Számlaszám', width: 160, fixed: true,
                    editable: true,
                    editoptions: {size: 40, maxlength: 255},
                    editrules: {required: true},
                    formoptions: {rowpos: 3, label: 'Számlaszám:', elmsuffix: '*'}
                },
                {
                    name: 'swift', index: 'swift', label: 'SWIFT', width: 25,
                    editable: true,
                    editoptions: {size: 20, maxlength: 20},
                    formoptions: {rowpos: 4, label: 'SWIFT:'}
                },
                {
                    name: 'iban', index: 'iban', label: 'IBAN', width: 160,
                    editable: true,
                    editoptions: {size: 20, maxlength: 20},
                    formoptions: {rowpos: 5, label: 'IBAN:'}
                },
                {
                    name: 'valutanem', index: 'valutanem', label: 'Valutanem', width: 60,
                    editable: true,
                    edittype: 'select',
                    editoptions: {size: 4, dataUrl: '/admin/valutanem/htmllist'},
                    formoptions: {rowpos: 6, label: 'Valutanem:'}
                }],
            rowNum: 100000,
            rowList: [10, 20, 30],
            pager: _bszla.pager,
            sortname: 'banknev',
            sortorder: 'asc',
            viewrecords: true,
            loadonce: false,
            gridview: true,
            height: 100,
            width: 647,
            hiddengrid: true,
            caption: 'Bankszámlák'
        });
        createNav(_bszla, bankszamlagrid);

        // Valutanem grid
        var _vn = {
            grid: '#valutanemgrid',
            pager: '#valutanemgridpager'
        };
        var valutanemgrid = $(_vn.grid).jqGrid({
            url: '/admin/valutanem/jsonlist',
            editurl: '/admin/valutanem/save',
            datatype: 'json',
            colModel: [
                {
                    name: 'nev', index: 'nev', label: 'Név', width: 60, fixed: true,
                    editable: true,
                    editoptions: {size: 25, maxlength: 255},
                    editrules: {required: true},
                    formoptions: {rowpos: 1, label: 'Név:', elmsuffix: '*'}
                },
                {
                    name: 'kerekit', index: 'kerekit', label: 'Kerekít', width: 25, align: 'right',
                    formatter: 'checkbox',
                    editable: true,
                    edittype: 'checkbox',
                    editoptions: {value: '1:0'},
                    formoptions: {rowpos: 2, label: 'Kerekít:'}
                },
                {
                    name: 'hivatalos', index: 'hivatalos', label: 'Hivatalos', width: 25, align: 'right',
                    formatter: 'checkbox',
                    editable: true,
                    edittype: 'checkbox',
                    editoptions: {value: '1:0'},
                    formoptions: {rowpos: 3, label: 'Hivatalos:'}
                },
                {
                    name: 'mincimlet', index: 'mincimlet', label: 'Legkisebb címlet', width: 25, align: 'center',
                    editable: true,
                    editoptions: {size: 2},
                    editrules: {integer: true},
                    formoptions: {rowpos: 4, label: 'Legkisebb címlet:'}
                },
                {
                    name: 'bankszamla', index: 'bankszamla', label: 'Bankszámla', width: 160, fixed: true,
                    editable: true,
                    edittype: 'select',
                    editoptions: {size: 4, dataUrl: '/admin/bankszamla/htmllist'},
                    formoptions: {rowpos: 5, label: 'Bankszámla:'}
                }],
            rowNum: 100000,
            rowList: [10, 20, 30],
            pager: _vn.pager,
            sortname: 'nev',
            sortorder: 'asc',
            viewrecords: true,
            loadonce: false,
            gridview: true,
            height: 100,
            width: 320,
            hiddengrid: true,
            caption: 'Valutanemek'
        });
        createNav(_vn, valutanemgrid);

        // Arfolyam grid
        var _arf = {
            grid: '#arfolyamgrid',
            pager: '#arfolyamgridpager'
        };
        var arfolyamgrid = $(_arf.grid).jqGrid({
            url: '/admin/arfolyam/jsonlist',
            editurl: '/admin/arfolyam/save',
            datatype: 'json',
            colModel: [
                {
                    name: 'datum', index: 'datum', label: 'Dátum', width: 60,
                    sorttype: 'date',
                    formatter: 'date',
                    formatoptions: {srcformat: 'Y-m-d', newformat: 'Y.m.d', reformatAfterEdit: true},
                    editable: true,
                    editoptions: {size: 15},
                    editrules: {required: true, date: true},
                    formoptions: {rowpos: 1, label: 'Dátum:', elmsuffix: '*'}
                },
                {
                    name: 'valutanem', index: 'valutanem', label: 'Valutanem', width: 60,
                    editable: true,
                    edittype: 'select',
                    editrules: {required: true},
                    editoptions: {size: 4, dataUrl: '/admin/valutanem/htmllist'},
                    formoptions: {rowpos: 2, label: 'Valutanem:', elmsuffix: '*'}
                },
                {
                    name: 'arfolyam', index: 'arfolyam', label: 'Árfolyam', width: 60, align: 'left',
                    sorttype: 'float',
                    formatter: 'number',
                    editable: true,
                    editoptions: {size: 10},
                    editrules: {required: true},
                    formoptions: {rowpos: 3, label: 'Árfolyam:', elmsuffix: '*'}
                }],
            rowNum: 100000,
            rowList: [10, 20, 30],
            pager: _arf.pager,
            sortname: 'datum',
            sortorder: 'asc',
            viewrecords: true,
            loadonce: false,
            gridview: true,
            height: 100,
            width: 320,
            hiddengrid: true,
            caption: 'Árfolyamok'
        });
        createNav(_arf, arfolyamgrid);

        // VTSZ grid
        var _vtsz = {
            grid: '#vtszgrid',
            pager: '#vtszgridpager'
        }
        var vtszgrid = $(_vtsz.grid).jqGrid({
            url: '/admin/vtsz/jsonlist',
            editurl: '/admin/vtsz/save',
            datatype: 'json',
            colModel: [
                {
                    name: 'szam', index: 'szam', label: 'Szám', width: 160, fixed: true,
                    editable: true,
                    editoptions: {size: 25, maxlength: 255},
                    editrules: {required: true},
                    formoptions: {rowpos: 1, label: 'Szám:', elmsuffix: '*'}
                },
                {
                    name: 'nev', index: 'nev', label: 'Név', width: 160, fixed: true,
                    editable: true,
                    editoptions: {size: 25, maxlength: 255},
                    editrules: {required: false},
                    formoptions: {rowpos: 2, label: 'Név:', elmsuffix: '*'}
                },
                {
                    name: 'afa', index: 'afa', label: 'ÁFA kulcs', width: 25, align: 'right',
                    editable: true,
                    edittype: 'select',
                    editoptions: {size: 4, dataUrl: '/admin/afa/htmllist'},
                    editrules: {required: true},
                    formoptions: {rowpos: 3, label: 'ÁFA kulcs:', elmsuffix: '*'}
                },
                {
                    name: 'csk', index: 'csk', label: 'CSK szám:', width: 25, align: 'right',
                    editable: true,
                    edittype: 'select',
                    editoptions: {size: 4, dataUrl: '/admin/csk/htmllist'},
                    formoptions: {rowpos: 4, label: 'CSK szám:'}
                },
                {
                    name: 'kt', index: 'kt', label: 'KT kód', width: 25, align: 'right',
                    editable: true,
                    edittype: 'select',
                    editoptions: {size: 4, dataUrl: '/admin/csk/htmllist'},
                    formoptions: {rowpos: 5, label: 'KT kód:'}
                }],
            rowNum: 100000,
            rowList: [10, 20, 30],
            pager: _vtsz.pager,
            sortname: 'szam',
            sortorder: 'asc',
            viewrecords: true,
            loadonce: false,
            gridview: true,
            height: 100,
            width: 320,
            hiddengrid: true,
            caption: 'VTSZ'
        });
        createNav(_vtsz, vtszgrid);

        // TermekCimkeKat grid
        var _tck = {
            grid: '#termekcimkekatgrid',
            pager: '#termekcimkekatgridpager'
        };
        var termekcimkekatgrid = $(_tck.grid).jqGrid({
            url: '/admin/termekcimkekat/jsonlist',
            editurl: '/admin/termekcimkekat/save',
            datatype: 'json',
            colModel: [
                {
                    name: 'nev', index: 'nev', label: 'Név', width: 160, fixed: true,
                    editable: true,
                    editoptions: {size: 25, maxlength: 255},
                    editrules: {required: true},
                    formoptions: {rowpos: 1, label: 'Név:', elmsuffix: '*'}
                },
                {
                    name: 'sorrend', index: 'sorrend', label: 'Sorrend', width: 25, align: 'right',
                    editable: true,
                    editoptions: {},
                    editrules: {integer: true},
                    formoptions: {rowpos: 2, label: 'Sorrend:'}
                },
                {
                    name: 'emagid', index: 'emagid', label: 'EMAG id', width: 25, align: 'right',
                    editable: true,
                    editoptions: {},
                    editrules: {integer: true},
                    formoptions: {rowpos: 3, label: 'EMAG id:'}
                },
                {
                    name: 'termeklaponlathato', index: 'termeklaponlathato', label: 'Terméklapon látható', width: 25, align: 'center',
                    formatter: 'checkbox',
                    editable: true,
                    edittype: 'checkbox',
                    editoptions: {value: '1:0'},
                    editrules: {},
                    formoptions: {rowpos: 4, label: 'Terméklapon látható:'}
                },
                {
                    name: 'termekszurobenlathato', index: 'termekszurobenlathato', label: 'Termékszűrőben látható', width: 25, align: 'center',
                    formatter: 'checkbox',
                    editable: true,
                    edittype: 'checkbox',
                    editoptions: {value: '1:0'},
                    editrules: {},
                    formoptions: {rowpos: 5, label: 'Termékszűrőben látható:'}
                },
                {
                    name: 'termeklistabanlathato', index: 'termeklistabanlathato', label: 'Terméklistában látható', width: 25, align: 'center',
                    formatter: 'checkbox',
                    editable: true,
                    edittype: 'checkbox',
                    editoptions: {value: '1:0'},
                    editrules: {},
                    formoptions: {rowpos: 6, label: 'Terméklistában látható:'}
                },
                {
                    name: 'termekakciodobozbanlathato', index: 'termekakciodobozbanlathato', label: 'Termék akciódobozban látható', width: 25, align: 'center',
                    formatter: 'checkbox',
                    editable: true,
                    edittype: 'checkbox',
                    editoptions: {value: '1:0'},
                    editrules: {},
                    formoptions: {rowpos: 7, label: 'Termék akciódobozban látható:'}
                }],
            rowNum: 100000,
            rowList: [10, 20, 30],
            pager: _tck.pager,
            sortname: 'nev',
            sortorder: 'asc',
            viewrecords: true,
            loadonce: false,
            gridview: true,
            height: 100,
            width: 320,
            hiddengrid: true,
            caption: 'Termékcímke csoportok'
        });
        createNav(_tck, termekcimkekatgrid);

        // PartnerCimkeKat grid
        var _pck = {
            grid: '#partnercimkekatgrid',
            pager: '#partnercimkekatgridpager'
        };
        var partnercimkekatgrid = $(_pck.grid).jqGrid({
            url: '/admin/partnercimkekat/jsonlist',
            editurl: '/admin/partnercimkekat/save',
            datatype: 'json',
            colModel: [
                {
                    name: 'nev', index: 'nev', label: 'Név', width: 160, fixed: true,
                    editable: true,
                    editoptions: {size: 25, maxlength: 255},
                    editrules: {required: true},
                    formoptions: {rowpos: 1, label: 'Név:', elmsuffix: '*'}
                },
                {
                    name: 'lathato', index: 'lathato', label: 'Látható', width: 25, align: 'center',
                    formatter: 'checkbox',
                    editable: true,
                    edittype: 'checkbox',
                    editoptions: {value: '1:0'},
                    editrules: {},
                    formoptions: {rowpos: 2, label: 'Látható:'}
                }],
            rowNum: 100000,
            rowList: [10, 20, 30],
            pager: _pck.pager,
            sortname: 'nev',
            sortorder: 'asc',
            viewrecords: true,
            loadonce: false,
            gridview: true,
            height: 100,
            width: 320,
            hiddengrid: true,
            caption: 'Partnercímke csoportok'
        });
        createNav(_pck, partnercimkekatgrid);

        // Felhasználó grid
        var _felhasznalo = {
            grid: '#felhasznalogrid',
            pager: '#felhasznalogridpager'
        };
        var felhasznalogrid = $(_felhasznalo.grid).jqGrid({
            url: '/admin/felhasznalo/jsonlist',
            editurl: '/admin/felhasznalo/save',
            datatype: 'json',
            colModel: [
                {
                    name: 'nev', index: 'nev', label: 'Név', width: 160,
                    editable: true,
                    editoptions: {size: 25, maxlength: 255},
                    editrules: {required: true},
                    formoptions: {rowpos: 1, label: 'Név:', elmsuffix: '*'}
                },
                {
                    name: 'felhasznalonev', index: 'felhasznalonev', label: 'Felhasználónév', width: 160,
                    editable: true,
                    editoptions: {size: 25, maxlength: 255},
                    editrules: {required: true},
                    formoptions: {rowpos: 2, label: 'Felhasználónév:', elmsuffix: '*'}
                },
                {
                    name: 'jelszo', index: 'jelszo', label: 'Jelszó', width: 160,
                    editable: true,
                    editoptions: {size: 25, maxlength: 255},
                    editrules: {required: true},
                    formoptions: {rowpos: 3, label: 'Jelszó:', elmsuffix: '*'}
                },
                {
                    name: 'uzletkoto', index: 'uzletkoto', label: 'Üzletkötő', width: 25,
                    editable: true,
                    edittype: 'select',
                    editoptions: {size: 4, dataUrl: '/admin/uzletkoto/htmllist'},
                    formoptions: {rowpos: 4, label: 'Üzletkötő:', elmsuffix: '*'}
                }
            ],
            rowNum: 100000,
            rowList: [10, 20, 30],
            pager: _felhasznalo.pager,
            sortname: 'nev',
            sortorder: 'asc',
            viewrecords: true,
            loadonce: false,
            gridview: true,
            height: 100,
            width: 320,
            hiddengrid: true,
            caption: 'Felhasználók'
        });
        createNav(_felhasznalo, felhasznalogrid);

        // TermekValtozatAdatTipus grid
        var _tvat = {
            grid: '#termekvaltozatadattipusgrid',
            pager: '#termekvaltozatadattipusgridpager'
        };
        var termekvaltozatadattipusgrid = $(_tvat.grid).jqGrid({
            url: '/admin/termekvaltozatadattipus/jsonlist',
            editurl: '/admin/termekvaltozatadattipus/save',
            datatype: 'json',
            colModel: [
                {
                    name: 'nev', index: 'nev', label: 'Név', width: 160, fixed: true,
                    editable: true,
                    editoptions: {size: 25, maxlength: 255},
                    editrules: {required: true},
                    formoptions: {rowpos: 1, label: 'Név:', elmsuffix: '*'}
                }],
            rowNum: 100000,
            rowList: [10, 20, 30],
            pager: _tvat.pager,
            sortname: 'nev',
            sortorder: 'asc',
            viewrecords: true,
            loadonce: false,
            gridview: true,
            height: 100,
            width: 320,
            hiddengrid: true,
            caption: 'Termékváltozat adattípusok'
        });
        createNav(_tvat, termekvaltozatadattipusgrid);

        // Munkakor grid
        var _mkor = {
            grid: '#munkakorgrid',
            pager: '#munkakorgridpager'
        };
        var mkorgrid = $(_mkor.grid).jqGrid({
            url: '/admin/munkakor/jsonlist',
            editurl: '/admin/munkakor/save',
            datatype: 'json',
            colModel: [
                {
                    name: 'nev', index: 'nev', label: 'Név', width: 160, fixed: true,
                    editable: true,
                    editoptions: {size: 25, maxlength: 255},
                    editrules: {required: true},
                    formoptions: {rowpos: 1, label: 'Név:', elmsuffix: '*'}
                },
                {
                    name: 'jog', index: 'jog', label: 'Jog', width: 25, align: 'right',
                    editable: true,
                    editoptions: {},
                    editrules: {integer: true},
                    formoptions: {rowpos: 2, label: 'Jog:'}
                }],
            rowNum: 100000,
            rowList: [10, 20, 30],
            pager: _mkor.pager,
            sortname: 'nev',
            sortorder: 'asc',
            viewrecords: true,
            loadonce: false,
            gridview: true,
            height: 100,
            width: 320,
            hiddengrid: true,
            caption: 'Munkakörök'
        });
        createNav(_mkor, mkorgrid);

        // Jelenlettipus grid
        var _jlt = {
            grid: '#jelenlettipusgrid',
            pager: '#jelenlettipusgridpager'
        };
        var jltgrid = $(_jlt.grid).jqGrid({
            url: '/admin/jelenlettipus/jsonlist',
            editurl: '/admin/jelenlettipus/save',
            datatype: 'json',
            colModel: [
                {
                    name: 'nev', index: 'nev', label: 'Név', width: 160, fixed: true,
                    editable: true,
                    editoptions: {size: 25, maxlength: 255},
                    editrules: {required: true},
                    formoptions: {rowpos: 1, label: 'Név:', elmsuffix: '*'}
                }],
            rowNum: 100000,
            rowList: [10, 20, 30],
            pager: _jlt.pager,
            sortname: 'nev',
            sortorder: 'asc',
            viewrecords: true,
            loadonce: false,
            gridview: true,
            height: 100,
            width: 320,
            hiddengrid: true,
            caption: 'Jelenlét típusok'
        });
        createNav(_jlt, jltgrid);

        // Raktar grid
        var _rkt = {
            grid: '#raktargrid',
            pager: '#raktargridpager'
        };
        var rktgrid = $(_rkt.grid).jqGrid({
            url: '/admin/raktar/jsonlist',
            editurl: '/admin/raktar/save',
            datatype: 'json',
            colModel: [
                {
                    name: 'nev', index: 'nev', label: 'Név', width: 160, fixed: true,
                    editable: true,
                    editoptions: {size: 25, maxlength: 50},
                    editrules: {required: true},
                    formoptions: {rowpos: 1, label: 'Név:', elmsuffix: '*'}
                },
                {
                    name: 'mozgat', index: 'mozgat', label: 'Készletet mozgat', width: 25, align: 'center',
                    formatter: 'checkbox',
                    editable: true,
                    edittype: 'checkbox',
                    editoptions: {value: '1:0'},
                    editrules: {},
                    formoptions: {rowpos: 2, label: 'Készletet mozgat:'}
                },
                {
                    name: 'archiv', index: 'archiv', label: 'Archív', width: 25, align: 'center',
                    formatter: 'checkbox',
                    editable: true,
                    edittype: 'checkbox',
                    editoptions: {value: '1:0'},
                    editrules: {},
                    formoptions: {rowpos: 3, label: 'Archív:'}
                },
                {
                    name: 'idegenkod', index: 'idegenkod', label: 'Idegen kód', width: 160,
                    editable: true,
                    editoptions: {size: 25, maxlength: 50},
                    formoptions: {rowpos: 4, label: 'Idegen kód:'}
                }
            ],
            rowNum: 100000,
            rowList: [10, 20, 30],
            pager: _rkt.pager,
            sortname: 'nev',
            sortorder: 'asc',
            viewrecords: true,
            loadonce: false,
            gridview: true,
            height: 100,
            width: 320,
            hiddengrid: true,
            caption: 'Raktárak'
        });
        createNav(_rkt, rktgrid);

        // Kapcsolatfelveteltema grid
        var _kft = {
            grid: '#kapcsolatfelveteltemagrid',
            pager: '#kapcsolatfelveteltemagridpager'
        };
        var kftgrid = $(_kft.grid).jqGrid({
            url: '/admin/kapcsolatfelveteltema/jsonlist',
            editurl: '/admin/kapcsolatfelveteltema/save',
            datatype: 'json',
            colModel: [
                {
                    name: 'nev', index: 'nev', label: 'Név', width: 160, fixed: true,
                    editable: true,
                    editoptions: {size: 25, maxlength: 255},
                    editrules: {required: true},
                    formoptions: {rowpos: 1, label: 'Név:', elmsuffix: '*'}
                }],
            rowNum: 100000,
            rowList: [10, 20, 30],
            pager: _kft.pager,
            sortname: 'nev',
            sortorder: 'asc',
            viewrecords: true,
            loadonce: false,
            gridview: true,
            height: 100,
            width: 320,
            hiddengrid: true,
            caption: 'Kapcsolatfelvétel témák'
        });
        createNav(_kft, kftgrid);

        // Irszam grid
        var _irszam = {
            grid: '#irszamgrid',
            pager: '#irszamgridpager'
        };
        var irszamgrid = $(_irszam.grid).jqGrid({
            url: '/admin/irszam/jsonlist',
            editurl: '/admin/irszam/save',
            datatype: 'json',
            colModel: [
                {
                    name: 'szam', index: 'szam', label: 'Ir.szám', width: 60, fixed: true,
                    editable: true,
                    editoptions: {size: 10, maxlength: 10},
                    editrules: {required: true},
                    formoptions: {rowpos: 1, label: 'Ir.szám:', elmsuffix: '*'}
                },
                {
                    name: 'nev', index: 'nev', label: 'Név', width: 160, fixed: true,
                    editable: true,
                    editoptions: {size: 25, maxlength: 255},
                    editrules: {required: true},
                    formoptions: {rowpos: 2, label: 'Név:', elmsuffix: '*'}
                }],
            rowNum: 100000,
            rowList: [10, 20, 30],
            pager: _irszam.pager,
            sortname: 'nev',
            sortorder: 'asc',
            viewrecords: true,
            loadonce: false,
            gridview: true,
            height: 100,
            width: 320,
            hiddengrid: true,
            caption: 'Irányítószámok'
        });
        createNav(_irszam, irszamgrid);

        // Rewrite301 grid
        var _rw301 = {
            grid: '#rw301grid',
            pager: '#rw301gridpager'
        };
        var rw301grid = $(_rw301.grid).jqGrid({
            url: '/admin/rw301/jsonlist',
            editurl: '/admin/rw301/save',
            datatype: 'json',
            colModel: [
                {
                    name: 'fromurl', index: 'fromurl', label: 'From',
                    editable: true,
                    editoptions: {size: 25, maxlength: 255},
                    editrules: {required: true},
                    formoptions: {rowpos: 1, label: 'From:', elmsuffix: '*'}
                },
                {
                    name: 'tourl', index: 'tourl', label: 'To',
                    editable: true,
                    editoptions: {size: 25, maxlength: 255},
                    editrules: {required: true},
                    formoptions: {rowpos: 2, label: 'To:', elmsuffix: '*'}
                }],
            rowNum: 100000,
            rowList: [10, 20, 30],
            pager: _rw301.pager,
            sortname: 'fromurl',
            sortorder: 'asc',
            viewrecords: true,
            loadonce: false,
            gridview: true,
            height: 100,
            width: 647,
            hiddengrid: true,
            caption: 'Rewrite 301'
        });
        createNav(_rw301, rw301grid);

        // Termekcsoport grid
        var _termekcsoport = {
            grid: '#termekcsoportgrid',
            pager: '#termekcsoportgridpager'
        };
        var termekcsoportgrid = $(_termekcsoport.grid).jqGrid({
            url: '/admin/termekcsoport/jsonlist',
            editurl: '/admin/termekcsoport/save',
            datatype: 'json',
            colModel: [
                {
                    name: 'nev', index: 'nev', label: 'Név', width: 160, fixed: true,
                    editable: true,
                    editoptions: {size: 25, maxlength: 255},
                    editrules: {required: true},
                    formoptions: {rowpos: 1, label: 'Név:', elmsuffix: '*'}
                }],
            rowNum: 100000,
            rowList: [10, 20, 30],
            pager: _termekcsoport.pager,
            sortname: 'nev',
            sortorder: 'asc',
            viewrecords: true,
            loadonce: false,
            gridview: true,
            height: 100,
            width: 320,
            hiddengrid: true,
            caption: 'Termékcsoportok'
        });
        createNav(_termekcsoport, termekcsoportgrid);

        // Jogcim grid
        var _jogcim = {
            grid: '#jogcimgrid',
            pager: '#jogcimgridpager'
        };
        var jogcimgrid = $(_jogcim.grid).jqGrid({
            url: '/admin/jogcim/jsonlist',
            editurl: '/admin/jogcim/save',
            datatype: 'json',
            colModel: [
                {
                    name: 'nev', index: 'nev', label: 'Név', width: 160, fixed: true,
                    editable: true,
                    editoptions: {size: 25, maxlength: 50},
                    editrules: {required: true},
                    formoptions: {rowpos: 1, label: 'Név:', elmsuffix: '*'}
                }
            ],
            rowNum: 100000,
            rowList: [10, 20, 30],
            pager: _jogcim.pager,
            sortname: 'nev',
            sortorder: 'asc',
            viewrecords: true,
            loadonce: false,
            gridview: true,
            height: 100,
            width: 320,
            hiddengrid: true,
            caption: 'Jogcímek'
        });
        createNav(_jogcim, jogcimgrid);

        // mptszekcio grid
        var _mptszekcio = {
            grid: '#mptszekciogrid',
            pager: '#mptszekciogridpager'
        };
        var mptszekciogrid = $(_mptszekcio.grid).jqGrid({
            url: '/admin/mptszekcio/jsonlist',
            editurl: '/admin/mptszekcio/save',
            datatype: 'json',
            colModel: [
                {
                    name: 'nev', index: 'nev', label: 'Név', width: 160, fixed: true,
                    editable: true,
                    editoptions: {size: 25, maxlength: 255},
                    editrules: {required: true},
                    formoptions: {rowpos: 1, label: 'Név:', elmsuffix: '*'}
                }
            ],
            rowNum: 100000,
            rowList: [10, 20, 30],
            pager: _mptszekcio.pager,
            sortname: 'nev',
            sortorder: 'asc',
            viewrecords: true,
            loadonce: false,
            gridview: true,
            height: 100,
            width: 320,
            hiddengrid: true,
            caption: 'MPT szekciók'
        });
        createNav(_mptszekcio, mptszekciogrid);

        // mptngytemakor grid
        var _mptngytemakor = {
            grid: '#mptngytemakorgrid',
            pager: '#mptngytemakorgridpager'
        };
        var mptngytemakorgrid = $(_mptngytemakor.grid).jqGrid({
            url: '/admin/mptngytemakor/jsonlist',
            editurl: '/admin/mptngytemakor/save',
            datatype: 'json',
            colModel: [
                {
                    name: 'nev', index: 'nev', label: 'Név', width: 160, fixed: true,
                    editable: true,
                    editoptions: {size: 25, maxlength: 255},
                    editrules: {required: true},
                    formoptions: {rowpos: 1, label: 'Név:', elmsuffix: '*'}
                }
            ],
            rowNum: 100000,
            rowList: [10, 20, 30],
            pager: _mptngytemakor.pager,
            sortname: 'nev',
            sortorder: 'asc',
            viewrecords: true,
            loadonce: false,
            gridview: true,
            height: 100,
            width: 320,
            hiddengrid: true,
            caption: 'MPT NGY témakörök'
        });
        createNav(_mptngytemakor, mptngytemakorgrid);

        // mptngytema grid
        var _mptngytema = {
            grid: '#mptngytemagrid',
            pager: '#mptngytemagridpager'
        };
        var mptngytemagrid = $(_mptngytema.grid).jqGrid({
            url: '/admin/mptngytema/jsonlist',
            editurl: '/admin/mptngytema/save',
            datatype: 'json',
            colModel: [
                {
                    name: 'nev', index: 'nev', label: 'Név', width: 160, fixed: true,
                    editable: true,
                    editoptions: {size: 25, maxlength: 255},
                    editrules: {required: true},
                    formoptions: {rowpos: 1, label: 'Név:', elmsuffix: '*'}
                },
                {
                    name: 'elnok', index: 'elnok', label: 'Elnök', width: 160, fixed: true,
                    editable: true,
                    editoptions: {size: 25, maxlength: 255},
                    editrules: {required: true},
                    formoptions: {rowpos: 2, label: 'Elnök:', elmsuffix: '*'}
                }
            ],
            rowNum: 100000,
            rowList: [10, 20, 30],
            pager: _mptngytema.pager,
            sortname: 'nev',
            sortorder: 'asc',
            viewrecords: true,
            loadonce: false,
            gridview: true,
            height: 100,
            width: 320,
            hiddengrid: true,
            caption: 'MPT NGY témák'
        });
        createNav(_mptngytema, mptngytemagrid);

        // Terem grid
        var _terem = {
            grid: '#teremgrid',
            pager: '#teremgridpager'
        };
        var teremgrid = $(_terem.grid).jqGrid({
            url: '/admin/jogaterem/jsonlist',
            editurl: '/admin/jogaterem/save',
            datatype: 'json',
            colModel: [
                {
                    name: 'nev', index: 'nev', label: 'Név', width: 160, fixed: true,
                    editable: true,
                    editoptions: {size: 25, maxlength: 255},
                    editrules: {required: true},
                    formoptions: {rowpos: 1, label: 'Név:', elmsuffix: '*'}
                },
                {
                    name: 'maxferohely', index: 'maxferohely', label: 'Max. férőhely', width: 25, align: 'right',
                    editable: true,
                    editoptions: {size: 2},
                    editrules: {integer: true, required: false},
                    formoptions: {rowpos: 2, label: 'Max. férőhely:'}
                },
                {
                    name: 'inaktiv', index: 'inaktiv', label: 'Inaktív', width: 25, align: 'right',
                    formatter: 'checkbox',
                    editable: true,
                    edittype: 'checkbox',
                    editoptions: {value: '1:0'},
                    formoptions: {rowpos: 3, label: 'Inaktív:'}
                },
                {
                    name: 'orarendclass', index: 'orarendclass', label: 'Class', width: 160, fixed: true,
                    editable: true,
                    editoptions: {size: 25, maxlength: 255},
                    editrules: {required: true},
                    formoptions: {rowpos: 4, label: 'Class:', elmsuffix: '*'}
                }],
            rowNum: 100000,
            rowList: [10, 20, 30],
            pager: _terem.pager,
            sortname: 'nev',
            sortorder: 'asc',
            viewrecords: true,
            loadonce: false,
            gridview: true,
            height: 100,
            width: 320,
            hiddengrid: true,
            caption: 'Termek'
        });
        createNav(_terem, teremgrid);

        // mptngyszerepkor grid
        var _mptngyszerepkor = {
            grid: '#mptngyszerepkorgrid',
            pager: '#mptngyszerepkorgridpager'
        };
        var mptngyszerepkorgrid = $(_mptngyszerepkor.grid).jqGrid({
            url: '/admin/mptngyszerepkor/jsonlist',
            editurl: '/admin/mptngyszerepkor/save',
            datatype: 'json',
            colModel: [
                {
                    name: 'nev', index: 'nev', label: 'Név', width: 160, fixed: true,
                    editable: true,
                    editoptions: {size: 25, maxlength: 255},
                    editrules: {required: true},
                    formoptions: {rowpos: 1, label: 'Név:', elmsuffix: '*'}
                }
            ],
            rowNum: 100000,
            rowList: [10, 20, 30],
            pager: _mptngyszerepkor.pager,
            sortname: 'nev',
            sortorder: 'asc',
            viewrecords: true,
            loadonce: false,
            gridview: true,
            height: 100,
            width: 320,
            hiddengrid: true,
            caption: 'MPT NGY szerepkörök'
        });
        createNav(_mptngyszerepkor, mptngyszerepkorgrid);

        // mptngyszakmaianyagtipus grid
        var _mptngyszakmaianyagtipus = {
            grid: '#mptngyszakmaianyagtipusgrid',
            pager: '#mptngyszakmaianyagtipusgridpager'
        };
        var mptngyszakmaianyagtipusgrid = $(_mptngyszakmaianyagtipus.grid).jqGrid({
            url: '/admin/mptngyszakmaianyagtipus/jsonlist',
            editurl: '/admin/mptngyszakmaianyagtipus/save',
            datatype: 'json',
            colModel: [
                {
                    name: 'nev', index: 'nev', label: 'Név', width: 160, fixed: true,
                    editable: true,
                    editoptions: {size: 25, maxlength: 255},
                    editrules: {required: true},
                    formoptions: {rowpos: 1, label: 'Név:', elmsuffix: '*'}
                }
            ],
            rowNum: 100000,
            rowList: [10, 20, 30],
            pager: _mptngyszakmaianyagtipus.pager,
            sortname: 'nev',
            sortorder: 'asc',
            viewrecords: true,
            loadonce: false,
            gridview: true,
            height: 100,
            width: 320,
            hiddengrid: true,
            caption: 'MPT NGY szakmai anyag típus'
        });
        createNav(_mptngyszakmaianyagtipus, mptngyszakmaianyagtipusgrid);

        // mpttagozat grid
        var _mpttagozat = {
            grid: '#mpttagozatgrid',
            pager: '#mpttagozatgridpager'
        };
        var mpttagozatgrid = $(_mpttagozat.grid).jqGrid({
            url: '/admin/mpttagozat/jsonlist',
            editurl: '/admin/mpttagozat/save',
            datatype: 'json',
            colModel: [
                {
                    name: 'nev', index: 'nev', label: 'Név', width: 160, fixed: true,
                    editable: true,
                    editoptions: {size: 25, maxlength: 255},
                    editrules: {required: true},
                    formoptions: {rowpos: 1, label: 'Név:', elmsuffix: '*'}
                }
            ],
            rowNum: 100000,
            rowList: [10, 20, 30],
            pager: _mpttagozat.pager,
            sortname: 'nev',
            sortorder: 'asc',
            viewrecords: true,
            loadonce: false,
            gridview: true,
            height: 100,
            width: 320,
            hiddengrid: true,
            caption: 'MPT tagozatok'
        });
        createNav(_mpttagozat, mpttagozatgrid);

        // mpttagsagforma grid
        var _mpttagsagforma = {
            grid: '#mpttagsagformagrid',
            pager: '#mpttagsagformagridpager'
        };
        var mpttagsagformagrid = $(_mpttagsagforma.grid).jqGrid({
            url: '/admin/mpttagsagforma/jsonlist',
            editurl: '/admin/mpttagsagforma/save',
            datatype: 'json',
            colModel: [
                {
                    name: 'nev', index: 'nev', label: 'Név', width: 160, fixed: true,
                    editable: true,
                    editoptions: {size: 25, maxlength: 255},
                    editrules: {required: true},
                    formoptions: {rowpos: 1, label: 'Név:', elmsuffix: '*'}
                }
            ],
            rowNum: 100000,
            rowList: [10, 20, 30],
            pager: _mpttagsagforma.pager,
            sortname: 'nev',
            sortorder: 'asc',
            viewrecords: true,
            loadonce: false,
            gridview: true,
            height: 100,
            width: 320,
            hiddengrid: true,
            caption: 'MPT tagság formák'
        });
        createNav(_mpttagsagforma, mpttagsagformagrid);

        // Partnertipus grid
        var _partnertipus = {
            grid: '#partnertipusgrid',
            pager: '#partnertipusgridpager'
        };
        var partnertipusgrid = $(_partnertipus.grid).jqGrid({
            url: '/admin/partnertipus/jsonlist',
            editurl: '/admin/partnertipus/save',
            datatype: 'json',
            colModel: [
                {
                    name: 'nev', index: 'nev', label: 'Név', width: 160, fixed: true,
                    editable: true,
                    editoptions: {size: 25, maxlength: 255},
                    editrules: {required: true},
                    formoptions: {rowpos: 1, label: 'Név:', elmsuffix: '*'}
                },
                {
                    name: 'belephet', index: 'belephet', label: 'Beléphet', width: 25, align: 'right',
                    formatter: 'checkbox',
                    editable: true,
                    edittype: 'checkbox',
                    editoptions: {value: '1:0'},
                    formoptions: {rowpos: 2, label: 'Beléphet:'}
                },
                {
                    name: 'belephet2', index: 'belephet2', label: 'Beléphet 2', width: 25, align: 'right',
                    formatter: 'checkbox',
                    editable: true,
                    edittype: 'checkbox',
                    editoptions: {value: '1:0'},
                    formoptions: {rowpos: 3, label: 'Beléphet 2:'}
                },
                {
                    name: 'belephet3', index: 'belephet3', label: 'Beléphet 3', width: 25, align: 'right',
                    formatter: 'checkbox',
                    editable: true,
                    edittype: 'checkbox',
                    editoptions: {value: '1:0'},
                    formoptions: {rowpos: 4, label: 'Beléphet 3:'}
                },
                {
                    name: 'belephet4', index: 'belephet4', label: 'Beléphet 4', width: 25, align: 'right',
                    formatter: 'checkbox',
                    editable: true,
                    edittype: 'checkbox',
                    editoptions: {value: '1:0'},
                    formoptions: {rowpos: 5, label: 'Beléphet 4:'}
                },
                {
                    name: 'belephet5', index: 'belephet5', label: 'Beléphet 5', width: 25, align: 'right',
                    formatter: 'checkbox',
                    editable: true,
                    edittype: 'checkbox',
                    editoptions: {value: '1:0'},
                    formoptions: {rowpos: 6, label: 'Beléphet 5:'}
                },
                {
                    name: 'belephet6', index: 'belephet6', label: 'Beléphet 6', width: 25, align: 'right',
                    formatter: 'checkbox',
                    editable: true,
                    edittype: 'checkbox',
                    editoptions: {value: '1:0'},
                    formoptions: {rowpos: 7, label: 'Beléphet 6:'}
                },
                {
                    name: 'belephet7', index: 'belephet7', label: 'Beléphet 7', width: 25, align: 'right',
                    formatter: 'checkbox',
                    editable: true,
                    edittype: 'checkbox',
                    editoptions: {value: '1:0'},
                    formoptions: {rowpos: 8, label: 'Beléphet 7:'}
                },
                {
                    name: 'belephet8', index: 'belephet8', label: 'Beléphet 8', width: 25, align: 'right',
                    formatter: 'checkbox',
                    editable: true,
                    edittype: 'checkbox',
                    editoptions: {value: '1:0'},
                    formoptions: {rowpos: 9, label: 'Beléphet 8:'}
                },
                {
                    name: 'belephet9', index: 'belephet9', label: 'Beléphet 9', width: 25, align: 'right',
                    formatter: 'checkbox',
                    editable: true,
                    edittype: 'checkbox',
                    editoptions: {value: '1:0'},
                    formoptions: {rowpos: 10, label: 'Beléphet 9:'}
                },
                {
                    name: 'belephet10', index: 'belephet10', label: 'Beléphet 10', width: 25, align: 'right',
                    formatter: 'checkbox',
                    editable: true,
                    edittype: 'checkbox',
                    editoptions: {value: '1:0'},
                    formoptions: {rowpos: 11, label: 'Beléphet 10:'}
                },
                {
                    name: 'belephet11', index: 'belephet11', label: 'Beléphet 11', width: 25, align: 'right',
                    formatter: 'checkbox',
                    editable: true,
                    edittype: 'checkbox',
                    editoptions: {value: '1:0'},
                    formoptions: {rowpos: 12, label: 'Beléphet 11:'}
                },
                {
                    name: 'belephet12', index: 'belephet12', label: 'Beléphet 12', width: 25, align: 'right',
                    formatter: 'checkbox',
                    editable: true,
                    edittype: 'checkbox',
                    editoptions: {value: '1:0'},
                    formoptions: {rowpos: 13, label: 'Beléphet 12:'}
                },
                {
                    name: 'belephet13', index: 'belephet13', label: 'Beléphet 13', width: 25, align: 'right',
                    formatter: 'checkbox',
                    editable: true,
                    edittype: 'checkbox',
                    editoptions: {value: '1:0'},
                    formoptions: {rowpos: 14, label: 'Beléphet 13:'}
                },
                {
                    name: 'belephet14', index: 'belephet14', label: 'Beléphet 14', width: 25, align: 'right',
                    formatter: 'checkbox',
                    editable: true,
                    edittype: 'checkbox',
                    editoptions: {value: '1:0'},
                    formoptions: {rowpos: 15, label: 'Beléphet 14:'}
                },
                {
                    name: 'belephet15', index: 'belephet15', label: 'Beléphet 15', width: 25, align: 'right',
                    formatter: 'checkbox',
                    editable: true,
                    edittype: 'checkbox',
                    editoptions: {value: '1:0'},
                    formoptions: {rowpos: 16, label: 'Beléphet 15:'}
                }
            ],
            rowNum: 100000,
            rowList: [10, 20, 30],
            pager: _partnertipus.pager,
            sortname: 'nev',
            sortorder: 'asc',
            viewrecords: true,
            loadonce: false,
            gridview: true,
            height: 100,
            width: 320,
            hiddengrid: true,
            caption: 'Partner típusok'
        });
        createNav(_partnertipus, partnertipusgrid);

        // Orszag grid
        var _orszag = {
            grid: '#orszaggrid',
            pager: '#orszaggridpager'
        };
        var orszaggrid = $(_orszag.grid).jqGrid({
            url: '/admin/orszag/jsonlist',
            editurl: '/admin/orszag/save',
            datatype: 'json',
            colModel: [
                {
                    name: 'nev', index: 'nev', label: 'Név', width: 160, fixed: true,
                    editable: true,
                    editoptions: {size: 25, maxlength: 255},
                    editrules: {required: true},
                    formoptions: {rowpos: 1, label: 'Név:', elmsuffix: '*'}
                },
                {
                    name: 'iso3166', index: 'iso3166', label: 'ISO 3166', width: 160, fixed: true,
                    editable: true,
                    editoptions: {size: 5, maxlength: 255},
                    editrules: {required: true},
                    formoptions: {rowpos: 2, label: 'ISO 3166:', elmsuffix: '*'}
                },
                {
                    name: 'valutanem', index: 'valutanem', label: 'Valutanem', width: 60,
                    editable: true,
                    edittype: 'select',
                    editoptions: {size: 4, dataUrl: '/admin/valutanem/htmllist'},
                    formoptions: {rowpos: 3, label: 'Valutanem:'}
                },
                {
                    name: 'lathato', index: 'lathato', label: 'Látható', width: 25, align: 'center',
                    formatter: 'checkbox',
                    editable: true,
                    edittype: 'checkbox',
                    editoptions: {value: '1:0'},
                    editrules: {},
                    formoptions: {rowpos: 4, label: 'Látható:'}
                },
                {
                    name: 'lathato2', index: 'lathato2', label: 'Látható 2', width: 25, align: 'center',
                    formatter: 'checkbox',
                    editable: true,
                    edittype: 'checkbox',
                    editoptions: {value: '1:0'},
                    editrules: {},
                    formoptions: {rowpos: 5, label: 'Látható 2:'}
                },
                {
                    name: 'lathato3', index: 'lathato3', label: 'Látható 3', width: 25, align: 'center',
                    formatter: 'checkbox',
                    editable: true,
                    edittype: 'checkbox',
                    editoptions: {value: '1:0'},
                    editrules: {},
                    formoptions: {rowpos: 6, label: 'Látható 3:'}
                },
                {
                    name: 'lathato4', index: 'lathato4', label: 'Látható 4', width: 25, align: 'center',
                    formatter: 'checkbox',
                    editable: true,
                    edittype: 'checkbox',
                    editoptions: {value: '1:0'},
                    editrules: {},
                    formoptions: {rowpos: 7, label: 'Látható 4:'}
                },
                {
                    name: 'lathato5', index: 'lathato5', label: 'Látható 5', width: 25, align: 'center',
                    formatter: 'checkbox',
                    editable: true,
                    edittype: 'checkbox',
                    editoptions: {value: '1:0'},
                    editrules: {},
                    formoptions: {rowpos: 8, label: 'Látható 5:'}
                },
                {
                    name: 'lathato6', index: 'lathato6', label: 'Látható 6', width: 25, align: 'center',
                    formatter: 'checkbox',
                    editable: true,
                    edittype: 'checkbox',
                    editoptions: {value: '1:0'},
                    editrules: {},
                    formoptions: {rowpos: 9, label: 'Látható 6:'}
                },
                {
                    name: 'lathato7', index: 'lathato7', label: 'Látható 7', width: 25, align: 'center',
                    formatter: 'checkbox',
                    editable: true,
                    edittype: 'checkbox',
                    editoptions: {value: '1:0'},
                    editrules: {},
                    formoptions: {rowpos: 10, label: 'Látható 7:'}
                },
                {
                    name: 'lathato8', index: 'lathato8', label: 'Látható 8', width: 25, align: 'center',
                    formatter: 'checkbox',
                    editable: true,
                    edittype: 'checkbox',
                    editoptions: {value: '1:0'},
                    editrules: {},
                    formoptions: {rowpos: 11, label: 'Látható 8:'}
                },
                {
                    name: 'lathato9', index: 'lathato9', label: 'Látható 9', width: 25, align: 'center',
                    formatter: 'checkbox',
                    editable: true,
                    edittype: 'checkbox',
                    editoptions: {value: '1:0'},
                    editrules: {},
                    formoptions: {rowpos: 12, label: 'Látható 9:'}
                },
                {
                    name: 'lathato10', index: 'lathato10', label: 'Látható 10', width: 25, align: 'center',
                    formatter: 'checkbox',
                    editable: true,
                    edittype: 'checkbox',
                    editoptions: {value: '1:0'},
                    editrules: {},
                    formoptions: {rowpos: 13, label: 'Látható 10:'}
                },
                {
                    name: 'lathato11', index: 'lathato11', label: 'Látható 11', width: 25, align: 'center',
                    formatter: 'checkbox',
                    editable: true,
                    edittype: 'checkbox',
                    editoptions: {value: '1:0'},
                    editrules: {},
                    formoptions: {rowpos: 14, label: 'Látható 11:'}
                },
                {
                    name: 'lathato12', index: 'lathato12', label: 'Látható 12', width: 25, align: 'center',
                    formatter: 'checkbox',
                    editable: true,
                    edittype: 'checkbox',
                    editoptions: {value: '1:0'},
                    editrules: {},
                    formoptions: {rowpos: 15, label: 'Látható 12:'}
                },
                {
                    name: 'lathato13', index: 'lathato13', label: 'Látható 13', width: 25, align: 'center',
                    formatter: 'checkbox',
                    editable: true,
                    edittype: 'checkbox',
                    editoptions: {value: '1:0'},
                    editrules: {},
                    formoptions: {rowpos: 16, label: 'Látható 13:'}
                },
                {
                    name: 'lathato14', index: 'lathato14', label: 'Látható 14', width: 25, align: 'center',
                    formatter: 'checkbox',
                    editable: true,
                    edittype: 'checkbox',
                    editoptions: {value: '1:0'},
                    editrules: {},
                    formoptions: {rowpos: 17, label: 'Látható 14:'}
                },
                {
                    name: 'lathato15', index: 'lathato15', label: 'Látható 15', width: 25, align: 'center',
                    formatter: 'checkbox',
                    editable: true,
                    edittype: 'checkbox',
                    editoptions: {value: '1:0'},
                    editrules: {},
                    formoptions: {rowpos: 18, label: 'Látható 15:'}
                },
            ],
            rowNum: 100000,
            rowList: [10, 20, 30],
            pager: _orszag.pager,
            sortname: 'nev',
            sortorder: 'asc',
            viewrecords: true,
            loadonce: false,
            gridview: true,
            height: 100,
            width: 320,
            hiddengrid: true,
            caption: 'Országok'
        });
        createNav(_orszag, orszaggrid);

        // mijszoklevelkibocsajto grid
        var _mijszoklevelkibocsajto = {
            grid: '#mijszoklevelkibocsajtogrid',
            pager: '#mijszoklevelkibocsajtogridpager'
        };
        var mijszoklevelkibocsajtogrid = $(_mijszoklevelkibocsajto.grid).jqGrid({
            url: '/admin/mijszoklevelkibocsajto/jsonlist',
            editurl: '/admin/mijszoklevelkibocsajto/save',
            datatype: 'json',
            colModel: [
                {
                    name: 'nev', index: 'nev', label: 'Név', width: 160, fixed: true,
                    editable: true,
                    editoptions: {size: 25, maxlength: 255},
                    editrules: {required: true},
                    formoptions: {rowpos: 1, label: 'Név:', elmsuffix: '*'}
                }],
            rowNum: 100000,
            rowList: [10, 20, 30],
            pager: _mijszoklevelkibocsajto.pager,
            sortname: 'nev',
            sortorder: 'asc',
            viewrecords: true,
            loadonce: false,
            gridview: true,
            height: 100,
            width: 320,
            hiddengrid: true,
            caption: 'Oklevél kibocsájtók'
        });
        createNav(_mijszoklevelkibocsajto, mijszoklevelkibocsajtogrid);

        // mijszoklevelszint grid
        var _mijszoklevelszint = {
            grid: '#mijszoklevelszintgrid',
            pager: '#mijszoklevelszintgridpager'
        };
        var mijszoklevelszintgrid = $(_mijszoklevelszint.grid).jqGrid({
            url: '/admin/mijszoklevelszint/jsonlist',
            editurl: '/admin/mijszoklevelszint/save',
            datatype: 'json',
            colModel: [
                {
                    name: 'nev', index: 'nev', label: 'Név', width: 160, fixed: true,
                    editable: true,
                    editoptions: {size: 25, maxlength: 255},
                    editrules: {required: true},
                    formoptions: {rowpos: 1, label: 'Név:', elmsuffix: '*'}
                }],
            rowNum: 100000,
            rowList: [10, 20, 30],
            pager: _mijszoklevelszint.pager,
            sortname: 'nev',
            sortorder: 'asc',
            viewrecords: true,
            loadonce: false,
            gridview: true,
            height: 100,
            width: 320,
            hiddengrid: true,
            caption: 'Oklevél szintek'
        });
        createNav(_mijszoklevelszint, mijszoklevelszintgrid);

        // Szotar grid
        var _szotar = {
            grid: '#szotargrid',
            pager: '#szotargridpager'
        };
        var szotargrid = $(_szotar.grid).jqGrid({
            url: '/admin/szotar/jsonlist',
            editurl: '/admin/szotar/save',
            datatype: 'json',
            colModel: [
                {
                    name: 'mit', index: 'mit', label: 'Mit', width: 160, fixed: true,
                    editable: true,
                    editoptions: {size: 25, maxlength: 255},
                    editrules: {required: true},
                    formoptions: {rowpos: 1, label: 'Mit:', elmsuffix: '*'}
                },
                {
                    name: 'mire', index: 'mire', label: 'Mire', width: 160, fixed: true,
                    editable: true,
                    editoptions: {size: 25, maxlength: 255},
                    formoptions: {rowpos: 2, label: 'Mire:', elmsuffix: '*'}
                }],
            rowNum: 100000,
            rowList: [10, 20, 30],
            pager: _szotar.pager,
            sortname: 'mit',
            sortorder: 'asc',
            viewrecords: true,
            loadonce: false,
            gridview: true,
            height: 100,
            width: 320,
            hiddengrid: true,
            caption: 'Szótár'
        });
        createNav(_szotar, szotargrid);

        // CSK grid
        var _csk = {
            grid: '#cskgrid',
            pager: '#cskgridpager'
        };
        var cskgrid = $(_csk.grid).jqGrid({
            url: '/admin/csk/jsonlist',
            editurl: '/admin/csk/save',
            datatype: 'json',
            colModel: [
                {
                    name: 'nev', index: 'nev', label: 'Név', width: 160, fixed: true,
                    editable: true,
                    editoptions: {size: 25, maxlength: 255},
                    editrules: {required: true},
                    formoptions: {rowpos: 1, label: 'Név:', elmsuffix: '*'}
                },
                {
                    name: 'ertek', index: 'ertek', label: 'Ár', width: 25, align: 'right',
                    sorttype: 'float',
                    formatter: 'number',
                    editable: true,
                    editoptions: {size: 10},
                    editrules: {required: true},
                    formoptions: {rowpos: 2, label: 'Ár:', elmsuffix: '*'}
                }],
            rowNum: 100000,
            rowList: [10, 20, 30],
            pager: _csk.pager,
            sortname: 'nev',
            sortorder: 'asc',
            viewrecords: true,
            loadonce: false,
            gridview: true,
            height: 100,
            width: 320,
            hiddengrid: true,
            caption: 'CSK kódok'
        });
        createNav(_csk, cskgrid);

        // TermekReceptTipus grid
        var _trt = {
            grid: '#termekrecepttipusgrid',
            pager: '#termekrecepttipusgridpager'
        };
        var trtgrid = $(_trt.grid).jqGrid({
            url: '/admin/termekrecepttipus/jsonlist',
            editurl: '/admin/termekrecepttipus/save',
            datatype: 'json',
            colModel: [
                {
                    name: 'nev', index: 'nev', label: 'Név', width: 160, fixed: true,
                    editable: true,
                    editoptions: {size: 25, maxlength: 255},
                    editrules: {required: true},
                    formoptions: {rowpos: 1, label: 'Név:', elmsuffix: '*'}
                }],
            rowNum: 100000,
            rowList: [10, 20, 30],
            pager: _trt.pager,
            sortname: 'nev',
            sortorder: 'asc',
            viewrecords: true,
            loadonce: false,
            gridview: true,
            height: 100,
            width: 320,
            hiddengrid: true,
            caption: 'Termékrecept típusok'
        });
        createNav(_trt, trtgrid);

        // Penztar grid
        var _penztar = {
            grid: '#penztargrid',
            pager: '#penztargridpager'
        };
        var penztargrid = $(_penztar.grid).jqGrid({
            url: '/admin/penztar/jsonlist',
            editurl: '/admin/penztar/save',
            datatype: 'json',
            colModel: [
                {
                    name: 'nev', index: 'nev', label: 'Pénztár neve', width: 160,
                    editable: true,
                    editoptions: {size: 25, maxlength: 50},
                    formoptions: {rowpos: 1, label: 'Pénztár neve:'}
                },
                {
                    name: 'valutanem', index: 'valutanem', label: 'Valutanem', width: 60,
                    editable: true,
                    edittype: 'select',
                    editoptions: {size: 4, dataUrl: '/admin/valutanem/htmllist'},
                    formoptions: {rowpos: 2, label: 'Valutanem:'}
                }],
            rowNum: 100000,
            rowList: [10, 20, 30],
            pager: _penztar.pager,
            sortname: 'nev',
            sortorder: 'asc',
            viewrecords: true,
            loadonce: false,
            gridview: true,
            height: 100,
            width: 320,
            hiddengrid: true,
            caption: 'Pénztárak'
        });
        createNav(_penztar, penztargrid);

        // Jogaterem grid
        var _jogaterem = {
            grid: '#jogateremgrid',
            pager: '#jogateremgridpager'
        };
        var jogateremgrid = $(_jogaterem.grid).jqGrid({
            url: '/admin/jogaterem/jsonlist',
            editurl: '/admin/jogaterem/save',
            datatype: 'json',
            colModel: [
                {
                    name: 'nev', index: 'nev', label: 'Név', width: 160, fixed: true,
                    editable: true,
                    editoptions: {size: 25, maxlength: 255},
                    editrules: {required: true},
                    formoptions: {rowpos: 1, label: 'Név:', elmsuffix: '*'}
                },
                {
                    name: 'maxferohely', index: 'maxferohely', label: 'Max. férőhely', width: 25, align: 'right',
                    editable: true,
                    editoptions: {size: 2},
                    editrules: {integer: true, required: false},
                    formoptions: {rowpos: 2, label: 'Max. férőhely:'}
                },
                {
                    name: 'inaktiv', index: 'inaktiv', label: 'Inaktív', width: 25, align: 'right',
                    formatter: 'checkbox',
                    editable: true,
                    edittype: 'checkbox',
                    editoptions: {value: '1:0'},
                    formoptions: {rowpos: 3, label: 'Inaktív:'}
                },
                {
                    name: 'orarendclass', index: 'orarendclass', label: 'Órarend class', width: 160, fixed: true,
                    editable: true,
                    editoptions: {size: 25, maxlength: 255},
                    editrules: {required: true},
                    formoptions: {rowpos: 4, label: 'Órarend class:', elmsuffix: '*'}
                }],
            rowNum: 100000,
            rowList: [10, 20, 30],
            pager: _jogaterem.pager,
            sortname: 'nev',
            sortorder: 'asc',
            viewrecords: true,
            loadonce: false,
            gridview: true,
            height: 100,
            width: 320,
            hiddengrid: true,
            caption: 'Jógatermek'
        });
        createNav(_jogaterem, jogateremgrid);

        // Jogaoratipus grid
        var _jogaoratipus = {
            grid: '#jogaoratipusgrid',
            pager: '#jogaoratipusgridpager'
        };
        var jogaoratipusgrid = $(_jogaoratipus.grid).jqGrid({
            url: '/admin/jogaoratipus/jsonlist',
            editurl: '/admin/jogaoratipus/save',
            datatype: 'json',
            colModel: [
                {
                    name: 'nev', index: 'nev', label: 'Név', width: 160, fixed: true,
                    editable: true,
                    editoptions: {size: 25, maxlength: 255},
                    editrules: {required: true},
                    formoptions: {rowpos: 1, label: 'Név:', elmsuffix: '*'}
                },
                {
                    name: 'szin', index: 'szin', label: 'Szín', width: 60,
                    editable: true,
                    editoptions: {size: 7, maxlength: 7},
                    formoptions: {rowpos: 2, label: 'Szín:'}
                },
                {
                    name: 'arnovelo', index: 'arnovelo', label: 'Árnövelő', width: 60, align: 'right',
                    sorttype: 'float',
                    formatter: 'number',
                    editable: true,
                    editoptions: {size: 10},
                    formoptions: {rowpos: 3, label: 'Árnövelő:'}
                },
                {
                    name: 'inaktiv', index: 'inaktiv', label: 'Inaktív', width: 25, align: 'right',
                    formatter: 'checkbox',
                    editable: true,
                    edittype: 'checkbox',
                    editoptions: {value: '1:0'},
                    formoptions: {rowpos: 4, label: 'Inaktív:'}
                },
                {
                    name: 'url', index: 'url', label: 'URL', width: 160, fixed: true,
                    editable: true,
                    editoptions: {size: 25, maxlength: 255},
                    editrules: {required: false},
                    formoptions: {rowpos: 5, label: 'URL:'}
                }
            ],
            rowNum: 100000,
            rowList: [10, 20, 30],
            pager: _jogaoratipus.pager,
            sortname: 'nev',
            sortorder: 'asc',
            viewrecords: true,
            loadonce: false,
            gridview: true,
            height: 100,
            width: 320,
            hiddengrid: true,
            caption: 'Jógaóra tipusok'
        });
        createNav(_jogaoratipus, jogaoratipusgrid);

        // Rendezvenyallapot grid
        var _rendezvenyallapot = {
            grid: '#rendezvenyallapotgrid',
            pager: '#rendezvenyallapotgridpager'
        };
        var rendezvenyallapotgrid = $(_rendezvenyallapot.grid).jqGrid({
            url: '/admin/rendezvenyallapot/jsonlist',
            editurl: '/admin/rendezvenyallapot/save',
            datatype: 'json',
            colModel: [
                {
                    name: 'nev', index: 'nev', label: 'Név', width: 160, fixed: true,
                    editable: true,
                    editoptions: {size: 25, maxlength: 255},
                    editrules: {required: true},
                    formoptions: {rowpos: 1, label: 'Név:', elmsuffix: '*'}
                },
                {
                    name: 'sorrend', index: 'sorrend', label: 'Sorrend', width: 25, align: 'right',
                    editable: true,
                    editoptions: {},
                    editrules: {integer: true},
                    formoptions: {rowpos: 2, label: 'Sorrend:'}
                },
                {
                    name: 'orarendbenszerepel', index: 'orarendbenszerepel', label: 'Órarendben', width: 25, align: 'right',
                    formatter: 'checkbox',
                    editable: true,
                    edittype: 'checkbox',
                    editoptions: {value: '1:0'},
                    formoptions: {rowpos: 3, label: 'Órarendben:'}
                }
            ],
            rowNum: 100000,
            rowList: [10, 20, 30],
            pager: _rendezvenyallapot.pager,
            sortname: 'nev',
            sortorder: 'asc',
            viewrecords: true,
            loadonce: false,
            gridview: true,
            height: 100,
            width: 320,
            hiddengrid: true,
            caption: 'Rendezvény állapotok'
        });
        createNav(_rendezvenyallapot, rendezvenyallapotgrid);

        // ME grid
        var _me = {
            grid: '#megrid',
            pager: '#megridpager'
        };
        var megrid = $(_me.grid).jqGrid({
            url: '/admin/me/jsonlist',
            editurl: '/admin/me/save',
            datatype: 'json',
            colModel: [
                {
                    name: 'nev', index: 'nev', label: 'Név', width: 160,
                    editable: true,
                    editrules: {required: true},
                    editoptions: {size: 25, maxlength: 250},
                    formoptions: {rowpos: 1, label: 'Név:', elmsuffix: '*'}
                },
                {
                    name: 'navtipus', index: 'navtipus', label: 'NAV típus', width: 60,
                    editable: true,
                    edittype: 'select',
                    editrules: {required: true},
                    editoptions: {size: 4, dataUrl: '/admin/me/navtipuslist'},
                    formoptions: {rowpos: 6, label: 'NAV típus:', elmsuffix: '*'}
                }],
            rowNum: 100000,
            rowList: [10, 20, 30],
            pager: _me.pager,
            sortname: 'nev',
            sortorder: 'asc',
            viewrecords: true,
            loadonce: false,
            gridview: true,
            height: 100,
            width: 320,
            hiddengrid: true,
            caption: 'Mennyiségi egységek'
        });
        createNav(_me, megrid);

        // Korzetszam grid
        var _korzetszam = {
            grid: '#korzetszamgrid',
            pager: '#korzetszamgridpager'
        };
        var korzetszamgrid = $(_korzetszam.grid).jqGrid({
            url: '/admin/korzetszam/jsonlist',
            editurl: '/admin/korzetszam/save',
            datatype: 'json',
            colModel: [
                {
                    name: 'id', index: 'id', label: 'Szám', width: 25, fixed: true,
                    editable: true,
                    editoptions: {size: 4},
                    editrules: {required: true},
                    formoptions: {rowpos: 1, label: 'Szám:', elmsuffix: '*'}
                },
                {
                    name: 'hossz', index: 'hossz', label: 'Hossz', width: 25, align: 'right',
                    editable: true,
                    editoptions: {size: 2},
                    editrules: {integer: true, required: true},
                    formoptions: {rowpos: 2, label: 'Hossz:', elmsuffix: '*'}
                },
                {
                    name: 'sorrend', index: 'sorrend', label: 'Sorrend', width: 25, align: 'right',
                    editable: true,
                    editoptions: {size: 2},
                    editrules: {integer: true, required: false},
                    formoptions: {rowpos: 3, label: 'Sorrend:'}
                }],
            rowNum: 100000,
            rowList: [10, 20, 30],
            pager: _korzetszam.pager,
            sortname: 'sorrend',
            sortorder: 'asc',
            viewrecords: true,
            loadonce: false,
            gridview: true,
            height: 100,
            width: 320,
            hiddengrid: true,
            caption: 'Körzetszámok'
        });
        createNav(_korzetszam, korzetszamgrid);

        // mijszgyakorlasszint grid
        var _mijszgyakorlasszint = {
            grid: '#mijszgyakorlasszintgrid',
            pager: '#mijszgyakorlasszintgridpager'
        };
        var mijszgyakorlasszintgrid = $(_mijszgyakorlasszint.grid).jqGrid({
            url: '/admin/mijszgyakorlasszint/jsonlist',
            editurl: '/admin/mijszgyakorlasszint/save',
            datatype: 'json',
            colModel: [
                {
                    name: 'nev', index: 'nev', label: 'Név', width: 160, fixed: true,
                    editable: true,
                    editoptions: {size: 25, maxlength: 255},
                    editrules: {required: true},
                    formoptions: {rowpos: 1, label: 'Név:', elmsuffix: '*'}
                }],
            rowNum: 100000,
            rowList: [10, 20, 30],
            pager: _mijszgyakorlasszint.pager,
            sortname: 'nev',
            sortorder: 'asc',
            viewrecords: true,
            loadonce: false,
            gridview: true,
            height: 100,
            width: 320,
            hiddengrid: true,
            caption: 'Gyakorlás szintek'
        });
        createNav(_mijszgyakorlasszint, mijszgyakorlasszintgrid);

        // Unnepnap grid
        var _unn = {
            grid: '#unnepnapgrid',
            pager: '#unnepnapgridpager'
        };
        var unnepnapgrid = $(_unn.grid).jqGrid({
            url: '/admin/unnepnap/jsonlist',
            editurl: '/admin/unnepnap/save',
            datatype: 'json',
            colModel: [
                {
                    name: 'datum', index: 'datum', label: 'Dátum', width: 60,
                    sorttype: 'date',
                    formatter: 'date',
                    formatoptions: {srcformat: 'Y-m-d', newformat: 'Y.m.d', reformatAfterEdit: true},
                    editable: true,
                    editoptions: {size: 15},
                    editrules: {required: true, date: true},
                    formoptions: {rowpos: 1, label: 'Dátum:', elmsuffix: '*'}
                }],
            rowNum: 100000,
            rowList: [10, 20, 30],
            pager: _unn.pager,
            sortname: 'datum',
            sortorder: 'asc',
            viewrecords: true,
            loadonce: false,
            gridview: true,
            height: 100,
            width: 320,
            hiddengrid: true,
            caption: 'Ünnepnapok'
        });
        createNav(_unn, unnepnapgrid);

        // Altalanos
        $('.ui-search-toolbar').hide();
        $('.ui-jqgrid-titlebar').on('click', function (e) {
            e.preventDefault();
            $('.ui-jqgrid-titlebar-close', this).click();
        });
    });