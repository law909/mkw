(function($) {
    $.fn.mattable = function(options) {
        if (!this.length) {
            return this;
        }

        var baseOptions = {
            name: 'egyed',
            animationSpeed: 50,
            numberOfRows: 30,
            header: '#mattable-header',
            table: '#mattable-table',
            tableRow: '#mattable-row_',
            tableRefresh: '.mattable-tablerefresh',
            addLink: '.mattable-addlink',
            addVisible: true,
            editLink: '.mattable-editlink',
            editVisible: true,
            delLink: '.mattable-dellink',
            delVisible: true,
            quickAddLink: '.mattable-quickaddlink',
            quickAddVisible: false,
            filter: {
                selector: '#mattable-filterwrapper',
                fields: undefined,
                clearButton: '.mattable-filterclear',
                refreshButton: '.mattable-filterrefresh',
                onClear: false,
                onFilter: false
            },
            pager: {
                hide: false,
                selector: '.mattable-pagerwrapper',
                orderwrapper: '.mattable-order',
                order: '.mattable-orderselect',
                orderdir: '.mattable-orderdirselect'
            },
            batch: {
                selector: '.mattable-batch'
            },
            tablebody: {
                selector: '#mattable-body',
                url: '/admin/xx/getlistbody',
                onStyle: false,
                onDoEditLink: false
            },
            karb: {
                name: 'egyed',
                container: 'mattable-karb',
                independent: false,
                viewUrl: '/admin/xx/getkarb',
                newWindowUrl: '/admin/xx/viewkarb',
                saveUrl: '/admin/xx/save',
                beforeShow: false,
                beforeHide: false
            },
            txt: {
                header: 'txt.header',
                headerTitle: 'Frissít',
                add: 'Új',
                addTitle: 'Új',
                quickAddTitle: 'Gyors új',
                filterRefresh: 'Szűr',
                filterRefreshTitle: 'Szűrő bekapcsolása',
                filterClear: 'Töröl',
                filterClearTitle: 'Szűrőfeltételek tölése',
                filterOpenTitle: 'Szűrőt kinyit',
                filterCloseTitle: 'Szűrőt becsuk',
                filterOpenCloseTitle: 'Szűrő kinyit/becsuk',
                orderUp: 'növekvő',
                orderDown: 'csökkenő',
                msgDel: 'Biztosan törli a tételt?'
            }
        };

        var setup = $.extend(true, {}, baseOptions, options);

        var _pagerIds = {
            first: setup.name + 'first',
            prev: setup.name + 'prev',
            next: setup.name + 'next',
            end: setup.name + 'end',
            pageno: setup.name + 'pageno',
            pagecount: setup.name + 'pagecount',
            elemperpage: setup.name + 'elemperpage',
            elemcountinfo: setup.name + 'elemcountinfo'
        };

        var _dataattr = {
            pagecount: 'data-pagecount',
            recordid: 'data-' + setup.name + 'id',
            oper: 'data-oper',
            quick: 'data-quick',
            pagevisible: 'data-visible',
            pagerefcontrol: 'data-refcontrol',
            theme: 'data-theme',
            headerTitle: 'data-title',
            headerCaption: 'data-caption'
        };

        var pagerhtml = '<div class="mattable-pager"><table><tbody>' +
                '<tr>' +
                '<td class="' + _pagerIds.first + ' ui-corner-all"><span class="ui-icon ui-icon-seek-first"></span></td>' +
                '<td class="' + _pagerIds.prev + ' ui-corner-all"><span class="ui-icon ui-icon-seek-prev"></span></td>' +
                '<td>' +
                'Oldal <input class="' + _pagerIds.pageno + '" type="text" size="3" maxlength="7"/> / <span class="' + _pagerIds.pagecount + '"></span>' +
                '</td>' +
                '<td class="' + _pagerIds.next + ' ui-corner-all"><span class="ui-icon ui-icon-seek-next"></span></td>' +
                '<td class="' + _pagerIds.end + ' ui-corner-all"><span class="ui-icon ui-icon-seek-end"></span></td>' +
                '<td><input class="' + _pagerIds.elemperpage + '" type="text" size="2" maxlength="7"/></td>' +
                '<td class="' + _pagerIds.elemcountinfo + '"></td>' +
                '</tr>' +
                '</tbody></table></div>';
        var pagerhidehtml = '<div class="mattable-pager"></div>';

        var scrollPosition;

        var selectContainer = this,
                karbContainer = $(setup.karb.container),
                header = $(setup.header),
                table = $(setup.table),
                pager = $(setup.pager.selector),
                batch = $(setup.batch.selector),
                filterwrapper = $(setup.filter.selector),
                orderwrapper = $(setup.pager.orderwrapper),
                orderselect = $(setup.pager.order),
                orderdirselect,
                theme;

        // private functions
        var createHeaderFooter = function() {
            if (pager.length) {
                if (setup.pager.hide) {
                    pager.addClass('mattable-footerbar ui-widget-header ui-helper-clearfix')
                            .append(pagerhidehtml);
                }
                else {
                    pager.addClass('mattable-footerbar ui-widget-header ui-helper-clearfix');
                    if (setup.quickAddVisible) {
                        pager.prepend('<a class="mattable-quickaddlink mattable-left" href="#" data-oper="add" data-quick="1" title="' + setup.txt.quickAddTitle + '"><span class="ui-icon ui-icon-circle-triangle-e"></span></a>');
                    }
                    if (setup.addVisible) {
                        pager.prepend('<a class="mattable-addlink mattable-left" href="#" data-oper="add" title="' + setup.txt.addTitle + '"><span class="ui-icon ui-icon-circle-plus"></span></a>');
                    }
                    pager.append(pagerhtml);
                }
            }
            $(setup.addLink + ',' + setup.quickAddLink).button();
            if (batch.length) {
                batch.addClass('mattable-footerbar ui-widget-header ui-helper-clearfix');
            }
            if (filterwrapper.length) {
                header.append('<a id="_filtercloseupbutton" class="mattedit-titlebar-close" title="' + setup.txt.filterOpenTitle + '" href="#"><span class="ui-icon ui-icon-circle-triangle-s"></span></a>');
                var _filtercloseupbutton = $('#_filtercloseupbutton');
                filterwrapper.prepend('<a class="mattable-filterrefresh" href="#" title="' + setup.txt.filterRefreshTitle + '">' + setup.txt.filterRefresh + '</a>' +
                        '<a class="mattable-filterclear" href="#" title="' + setup.txt.filterClearTitle + '">' + setup.txt.filterClear + '</a>');
                filterwrapper.addClass('ui-widget ui-widget-content mattable-filterwrapper').hide();
                _filtercloseupbutton.on('click', function(e) {
                    e.preventDefault();
                    if (!filterwrapper.is(':visible')) {
                        filterwrapper.slideDown(setup.animationSpeed);
                        $(this).children('span').removeClass('ui-icon-circle-triangle-s').addClass('ui-icon-circle-triangle-n');
                        _filtercloseupbutton.attr('title', setup.txt.filterCloseTitle);
                    }
                    else {
                        filterwrapper.slideUp(setup.animationSpeed);
                        $(this).children('span').removeClass('ui-icon-circle-triangle-n').addClass('ui-icon-circle-triangle-s');
                        _filtercloseupbutton.attr('title', setup.txt.filterOpenTitle);
                    }
                });
                header.append('<a href="#" class="js-mattablefiltercloseup">' + setup.txt.filterOpenCloseTitle + '</a>');
                var _filtercloseup = $('.js-mattablefiltercloseup');
                _filtercloseup.on('click', function(e) {
                    e.preventDefault();
                    if (!filterwrapper.is(':visible')) {
                        filterwrapper.slideDown(setup.animationSpeed);
                        $(this).children('span').removeClass('ui-icon-circle-triangle-s').addClass('ui-icon-circle-triangle-n');
                        _filtercloseupbutton.attr('title', setup.txt.filterCloseTitle);
                    }
                    else {
                        filterwrapper.slideUp(setup.animationSpeed);
                        $(this).children('span').removeClass('ui-icon-circle-triangle-n').addClass('ui-icon-circle-triangle-s');
                        _filtercloseupbutton.attr('title', setup.txt.filterOpenTitle);
                    }
                });

                $(setup.filter.refreshButton)
                        .on('click', function(e) {
                            e.preventDefault();
                            reloadTbody();
                            _filtercloseupbutton.click();
                        })
                        .button();
                $(setup.filter.clearButton)
                        .on('click', function(e) {
                            e.preventDefault();
                            if ($.isArray(setup.filter.fields)) {
                                for (i in setup.filter.fields) {
                                    var elem = $(setup.filter.fields[i])[0];
                                    if (elem) {
                                        var t = elem.type,
                                                tag = elem.tagName.toLowerCase();
                                        if (t == 'text' || t == 'password' || tag == 'textarea') {
                                            elem.value = '';
                                        }
                                        else if (t == 'checkbox' || t == 'radio') {
                                            elem.checked = false;
                                        }
                                        else if (tag == 'select') {
                                            elem.selectedIndex = 0;
                                        }
                                    }
                                }
                            }
                            if ($.isFunction(setup.filter.onClear)) {
                                setup.filter.onClear.call(this);
                            }
                            reloadTbody();
                        })
                        .button();
                if ($.isArray(setup.filter.fields)) {
                    for (i in setup.filter.fields) {
                        $(setup.filter.fields[i]).keypress(function(e) {
                            if (e.keyCode == 13) {
                                e.preventDefault();
                                $(setup.filter.refreshButton).click();
                            }
                        });
                    }
                }
            }
            if (orderwrapper.length) {
/*                orderwrapper.append('<select class="mattable-orderdirselect">' +
                        '<option value="asc">' + setup.txt.orderUp + '</option>' +
                        '<option value="desc">' + setup.txt.orderDown + '</option>' +
                        '</select>');
                        */
                orderdirselect = $(setup.pager.orderdir);
            }
            if (pager.length) {
                $('.' + _pagerIds.first + ',.' + _pagerIds.prev + ',.' + _pagerIds.next + ',.' + _pagerIds.end).hover(
                        function() {
                            var $this = $(this);
                            if (!$this.hasClass('ui-state-disabled')) {
                                $this.addClass('ui-state-hover').css('cursor', 'pointer');
                            }
                        },
                        function() {
                            var $this = $(this);
                            if (!$this.hasClass('ui-state-disabled')) {
                                $this.removeClass('ui-state-hover').css('cursor', 'default');
                            }
                        });
                $('.' + _pagerIds.first).on('click', function(event) {
                    if (!$(this).hasClass('ui-state-disabled')) {
                        gettbody(1, $('.' + _pagerIds.elemperpage).val());
                    }
                    event.preventDefault();
                });
                $('.' + _pagerIds.prev).on('click', function(event) {
                    if (!$(this).hasClass('ui-state-disabled')) {
                        gettbody($('.' + _pagerIds.pageno).val() * 1 - 1, $('.' + _pagerIds.elemperpage).val());
                    }
                    event.preventDefault();
                });
                $('.' + _pagerIds.next).on('click', function(event) {
                    if (!$(this).hasClass('ui-state-disabled')) {
                        gettbody($('.' + _pagerIds.pageno).val() * 1 + 1, $('.' + _pagerIds.elemperpage).val());
                    }
                    event.preventDefault();
                });
                $('.' + _pagerIds.end).on('click', function(event) {
                    if (!$(this).hasClass('ui-state-disabled')) {
                        gettbody($('.' + _pagerIds.pagecount).attr(_dataattr.pagecount), $('.' + _pagerIds.elemperpage).val());
                    }
                    event.preventDefault();
                });
                $('.' + _pagerIds.pageno).keypress(function(event) {
                    if (event.keyCode == '13') {
                        $('.' + _pagerIds.pageno).val($(this).val());
                        reloadTbody();
                        event.preventDefault();
                    }
                });
                $('.' + _pagerIds.elemperpage).keypress(function(event) {
                    if (event.keyCode == '13') {
                        $('.' + _pagerIds.elemperpage).val($(this).val());
                        reloadTbody();
                        event.preventDefault();
                    }
                });
            }
        };

        var reloadTbody = function() {
            gettbody($('.' + _pagerIds.pageno).val(), $('.' + _pagerIds.elemperpage).val());
        };

        var createFilterObject = function(obj) {
            const queryString = window.location.search;
            const urlParams = new URLSearchParams(queryString);
            for (i in setup.filter.fields) {
                o = $(setup.filter.fields[i]);
                if (o.val()) {
                    obj[o.attr('name')] = o.val();
                }
            }
            for (const [key, value] of urlParams) {
                obj[key] = value;
            }
        };

        var gettbody = function(_pageno, _elemperpage) {
            var obj = {
                pageno: _pageno,
                elemperpage: _elemperpage
            };
            if (orderselect && orderselect[0]) {
                obj.order = orderselect[0].options[orderselect[0].selectedIndex].value;
            }
            if (orderdirselect && orderdirselect[0]) {
                obj.orderdir = orderdirselect[0].options[orderdirselect[0].selectedIndex].value;
            }
            createFilterObject(obj);
            if ($.isFunction(setup.filter.onFilter)) {
                setup.filter.onFilter.call(this, obj);
            }
            $.ajax({url: setup.tablebody.url, type: 'GET',
                data: obj,
                success: function(data) {
                    var resp = JSON.parse(data);
                    var tbody = $(setup.tablebody.selector);
                    try {
                        tbody[0].innerHTML = resp.html;
                    }
                    catch (e) {
                        tbody.empty().append(resp.html);
                    }
                    styleTbody();
                    doEditLink(tbody);
                    if ($.isFunction(setup.onGetTBody)) {
                        setup.onGetTBody.call(this, resp);
                    }
                    $('.' + _pagerIds.pageno).val(resp.pageno);
                    $('.' + _pagerIds.pagecount).text(resp.pagecount).attr(_dataattr.pagecount, resp.pagecount);
                    $('.' + _pagerIds.elemperpage).val(resp.elemperpage);
                    $('.' + _pagerIds.elemcountinfo).text('Tétel ' + resp.firstelemno + ' - ' + resp.lastelemno + ' / ' + resp.elemcount);
                    if (resp.pagecount == 1) {
                        $('.' + _pagerIds.first + ',.' + _pagerIds.prev).addClass('ui-state-disabled');
                        $('.' + _pagerIds.next + ',.' + _pagerIds.end).addClass('ui-state-disabled');
                        $('.' + _pagerIds.pageno).addClass('ui-state-disabled');
                    }
                    else {
                        $('.' + _pagerIds.pageno).removeClass('ui-state-disabled');
                        if (resp.pageno < resp.pagecount) {
                            $('.' + _pagerIds.next + ',.' + _pagerIds.end).removeClass('ui-state-disabled');
                            $('.' + _pagerIds.first + ',.' + _pagerIds.prev).removeClass('ui-state-disabled');
                        }
                        if (resp.pageno == 1) {
                            $('.' + _pagerIds.first + ',.' + _pagerIds.prev).addClass('ui-state-disabled');
                        }
                        if (resp.pageno == resp.pagecount) {
                            $('.' + _pagerIds.first + ',.' + _pagerIds.prev).removeClass('ui-state-disabled');
                            $('.' + _pagerIds.next + ',.' + _pagerIds.end).addClass('ui-state-disabled');
                        }
                    }
                }
            });
        };

        var styleTbody = function() {
            $('td.cell', table).addClass('mattable-cell mattable-tborder mattable-rborder');
            $('tbody tr td.cell:last-child', table).removeClass('mattable-rborder');
            $(setup.table + ' > tbody > tr').addClass('ui-widget-content');
            $('.mattable-editlink').button();
            $('.mattable-dellink').button();
            if ($.isFunction(setup.tablebody.onStyle)) {
                setup.tablebody.onStyle.call(this);
            }
        };

        var doEditLink = function(obj) {
            $(setup.editLink, obj).each(function(i) {
                $(this).attr('href', setup.karb.newWindowUrl + '?id=' + $(this).attr(_dataattr.recordid) + '&oper=' + $(this).attr(_dataattr.oper));
            });
            if ($.isFunction(setup.tablebody.onDoEditLink)) {
                setup.tablebody.onDoEditLink.call(this);
            }
        };

        var showKarb = function(elem) {
            var $elem = $(elem),
                    isquick = $elem.attr(_dataattr.quick);

            scrollPosition = $(document).scrollTop();
            $.blockUI({
                message: 'Kérem várjon...',
                css: {
                    border: 'none',
                    padding: '15px',
                    backgroundColor: '#000',
                    '-webkit-border-radius': '10px',
                    '-moz-border-radius': '10px',
                    opacity: .5,
                    color: '#fff'
                }
            });
            $.ajax({url: setup.karb.viewUrl,
                data: {
                    id: $elem.attr(_dataattr.recordid),
                    oper: $elem.attr(_dataattr.oper),
                    quick: isquick
                },
                success: function(data) {
                    selectContainer.hide();
                    karbContainer.append(data);

                    var karbsetup = setup.karb;
                    karbsetup.name = setup.name;
                    karbsetup.independent = false;
                    karbsetup.quick = isquick;
                    karbsetup.onSubmit = function(data) {
                        var resp = JSON.parse(data);
                        switch (resp.oper) {
                            case 'edit':
                                if (isNaN(resp.id)) {
                                    var x = setup.tableRow + resp.id.replace(/([ #;&,.+*~\':"!^$[\]()=>|\/])/g, '\\$1');
                                }
                                else {
                                    var x = setup.tableRow + resp.id;
                                }
                                $(x).replaceWith(resp.html);
                                doEditLink($(x));
                                styleTbody();
                                break;
                            case 'addreopen':
                                $(setup.tablebody.selector).prepend(resp.html);
                                doEditLink($(setup.tablebody.selector));
                                styleTbody();
                                $('.mattable-editlink[data-termekid="' + resp.id + '"]').click();
                                break;
                            case 'add':
                                $(setup.tablebody.selector).prepend(resp.html);
                                doEditLink($(setup.tablebody.selector));
                                styleTbody();
                                break;
                        }
                        showSelect();
                    };
                    karbsetup.onCancel = function() {
                        showSelect();
                    };
                    $(document).scrollTop(0);
                    karbContainer.mattkarb(karbsetup);
                }
            });
        };

        var showSelect = function() {
            karbContainer.empty().hide();
            selectContainer.show();
            $(document).scrollTop(scrollPosition);
            return false;
        };

        var initialize = function() {
            if ($.meta) {
                setup = $.extend({}, setup, this.data());
            }
            karbContainer.hide();
            karbContainer.addClass('ui-widget ui-widget-content ui-corner-all mattkarb');

            theme = selectContainer.attr(_dataattr.theme);
            selectContainer.addClass('ui-widget ui-widget-content ui-corner-all mattable');
            header.addClass('mattable-titlebar ui-widget-header ui-corner-top ui-helper-clearfix');
            header.append('<h3><a class="mattable-tablerefresh" href="#" title="' + header.attr(_dataattr.headerTitle) + '">' + header.attr(_dataattr.headerCaption) + '</a></h3>');
            createHeaderFooter();
            table.addClass('mattable-table');
            $('th', table).addClass('ui-state-default mattable-thcolumn');

            $('#maincheckbox').on('change', function(e) {
                var ch = $(this).prop('checked');
                $('.maincheckbox').prop('checked', ch);
            });
            $('.mattable-batchbtn').button();
            $(setup.tableRefresh).on('click', function(e) {
                e.preventDefault();
                reloadTbody();
            });
            $(table).on('click', setup.editLink, function(e) {
                e.preventDefault();
                showKarb(this);
            });
            $(table).on('click', setup.delLink, function(e) {
                e.preventDefault();
                var termeksor = $(this);
                $("#dialogcenter").html(setup.txt.msgDel).dialog({
                    resizable: false,
                    height: 140,
                    modal: true,
                    buttons: {
                        'Igen': function() {
                            $.ajax({url: setup.karb.saveUrl,
                                type: 'POST',
                                data: {
                                    id: termeksor.attr(_dataattr.recordid),
                                    oper: 'del'
                                },
                                success: function(data) {
                                    if (isNaN(data)) {
                                        $(setup.tableRow + data.replace(/([ #;&,.+*~\':"!^$[\]()=>|\/])/g, '\\$1')).remove();
                                    }
                                    else {
                                        $(setup.tableRow + data).remove();
                                    }
                                }
                            });
                            $(this).dialog('close');
                        },
                        'Nem': function() {
                            $(this).dialog('close');
                        }
                    }
                });
            });
            $(setup.addLink).attr('href', setup.karb.newWindowUrl + '?id=0&oper=add')
                    .on('click', function(e) {
                        e.preventDefault();
                        showKarb(this);
                    });
            $(setup.quickAddLink).attr('href', setup.karb.newWindowUrl + '?id=0&oper=add&quick=1')
                    .on('click', function(e) {
                        e.preventDefault();
                        showKarb(this);
                    });
            orderselect.change(function(e) {
                orderselect.val(this.value);
                reloadTbody();
            });
            if (orderdirselect) {
                orderdirselect.change(function(e) {
                    orderdirselect.val(this.value);
                    reloadTbody();
                });
            }
            gettbody(1, setup.numberOfRows);
        };

        initialize();
        return this;
    };
})(jQuery);