var mkwcomp = (function ($) {

    function termekfaFilter() {

        function clearChecks(sel) {
            $(sel).jstree('uncheck_all');
        }

        function getFilter(sel) {
            var fak = [];
            $(sel).jstree('get_checked').each(function () {
                var x = $('a', this).attr('id');
                if (x) {
                    fak.push(x.split('_')[1]);
                }
            });
            return fak;
        }

        function init(sel) {
            $(sel).jstree({
                core: {animation: 100},
                plugins: ['themeroller', 'json_data', 'contextmenu', 'ui', 'checkbox'],
                themeroller: {item: ''},
                json_data: {
                    ajax: {url: '/admin/termekfa/jsonlist'}
                },
                ui: {select_limit: 1},
                contextmenu: {
                    select_node: true,
                    items: {
                        create: false, rename: false, remove: false, ccp: false
                    }
                }
            })
                .bind('change_state.jstree', function (e, data) {
                    $termekfa = $(this);
                    $('li', $termekfa).each(function (i) {
                        $this = $(this);
                        if ($this.hasClass('jstree-unchecked')) {
                            $('ins.jstree-checkbox', $this).removeClass('ui-icon ui-icon-circle-check ui-icon-check');
                        } else if ($this.hasClass('jstree-checked')) {
                            $('ins.jstree-checkbox', $this).removeClass('ui-icon ui-icon-circle-check ui-icon-check').addClass('ui-icon ui-icon-circle-check');
                        } else if ($this.hasClass('jstree-undetermined')) {
                            $('ins.jstree-checkbox', $this).removeClass('ui-icon ui-icon-circle-check ui-icon-check').addClass('ui-icon ui-icon-check');
                        }
                    });
                });
        }

        return {
            init: init,
            clearChecks: clearChecks,
            getFilter: getFilter
        }
    }

    function termekmenuFilter() {

        function clearChecks(sel) {
            $(sel).jstree('uncheck_all');
        }

        function getFilter(sel) {
            var fak = [];
            $(sel).jstree('get_checked').each(function () {
                var x = $('a', this).attr('id');
                if (x) {
                    fak.push(x.split('_')[1]);
                }
            });
            return fak;
        }

        function init(sel) {
            $(sel).jstree({
                core: {animation: 100},
                plugins: ['themeroller', 'json_data', 'contextmenu', 'ui', 'checkbox'],
                themeroller: {item: ''},
                json_data: {
                    ajax: {url: '/admin/termekmenu/jsonlist'}
                },
                ui: {select_limit: 1},
                contextmenu: {
                    select_node: true,
                    items: {
                        create: false, rename: false, remove: false, ccp: false
                    }
                }
            })
                .bind('change_state.jstree', function (e, data) {
                    $termekfa = $(this);
                    $('li', $termekfa).each(function (i) {
                        $this = $(this);
                        if ($this.hasClass('jstree-unchecked')) {
                            $('ins.jstree-checkbox', $this).removeClass('ui-icon ui-icon-circle-check ui-icon-check');
                        } else if ($this.hasClass('jstree-checked')) {
                            $('ins.jstree-checkbox', $this).removeClass('ui-icon ui-icon-circle-check ui-icon-check').addClass('ui-icon ui-icon-circle-check');
                        } else if ($this.hasClass('jstree-undetermined')) {
                            $('ins.jstree-checkbox', $this).removeClass('ui-icon ui-icon-circle-check ui-icon-check').addClass('ui-icon ui-icon-check');
                        }
                    });
                });
        }

        return {
            init: init,
            clearChecks: clearChecks,
            getFilter: getFilter
        }
    }

    function datumEdit() {

        function init(sel) {
            var $datumedit;
            if (typeof sel === 'string') {
                $datumedit = $(sel);
            } else {
                $datumedit = sel;
            }
            if ($datumedit) {
                $datumedit.datepicker($.datepicker.regional['hu']);
                $datumedit.datepicker('option', 'dateFormat', 'yy.mm.dd');
                $datumedit.datepicker('setDate', $datumedit.attr('data-datum'));
            }
        }

        function clear(sel) {
            var $datumedit;
            if (typeof sel === 'string') {
                $datumedit = $(sel);
            } else {
                $datumedit = sel;
            }
            if ($datumedit) {
                $datumedit.datepicker('setDate', $datumedit.attr('data-datum'));
            }
        }

        function getDate(sel) {
            var d = $(sel).datepicker('getDate');
            if (d) {
                return d.getFullYear() + '.' + (d.getMonth() + 1) + '.' + d.getDate();
            }
            return '';
        }

        return {
            init: init,
            clear: clear,
            getDate: getDate
        }
    }

    function bizonylattipusFilter() {

        function getFilter(sel) {
            var btk = [];
            $(sel + ':checked').each(function () {
                btk.push($(this).val());
            });
            return btk;
        }

        return {
            getFilter: getFilter
        }
    }

    function partnercimkeFilter() {

        function getFilter(sel) {
            var cimkek = [];
            $(sel).filter('.ui-state-hover').each(function () {
                cimkek.push($(this).attr('data-id'));
            });
            return cimkek;
        }

        return {
            getFilter: getFilter
        }
    }

    return {
        termekfaFilter: termekfaFilter(),
        termekmenuFilter: termekmenuFilter(),
        datumEdit: datumEdit(),
        bizonylattipusFilter: bizonylattipusFilter(),
        partnercimkeFilter: partnercimkeFilter()
    }

})(jQuery);