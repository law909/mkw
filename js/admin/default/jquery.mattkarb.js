(function ($) {
    $.fn.mattkarb = function (options) {
        if (!this.length) {
            return this;
        }

        var baseOptions = {
            name: 'egyed',
            animationSpeed: 50,
            independent: true,
            header: '#mattkarb-header',
            footer: '#mattkarb-footer',
            form: '#mattkarb-form',
            tab: '#mattkarb-tabs',
            page: '.mattkarb-page',
            titlebar: '.mattkarb-titlebar',
            cancel: '#mattkarb-cancelbutton',
            ok: '#mattkarb-okbutton',
            viewUrl: '/admin/xx/viewkarb',
            saveUrl: '/admin/xx/save',
            beforeShow: function () {
                ;
            },
            beforeHide: function () {
                ;
            },
            onSubmit: function () {
                ;
            },
            onCancel: function () {
                ;
            }
        };

        var setup = $.extend({}, baseOptions, options);

        var _dataattr = {
            recordid: 'data-' + setup.name + 'id',
            oper: 'data-oper',
            pagevisible: 'data-visible',
            pagerefcontrol: 'data-refcontrol',
            titlecaption: 'data-caption'
        };

        var karbContainer = $(this),
            header = $(setup.header),
            footer = $(setup.footer),
            cancelbtn = $(setup.cancel),
            titlebar = $(setup.titlebar);

        // Önálló (saját URL-es) karb oldal-e? Ezek a /viewkarb útvonalon töltődnek be,
        // a listából idenavigálva. Csak ezeken térünk vissza mentés után az előző URL-re –
        // az inline-fragmentként betöltött karbokon (pl. fa-szerkesztő, munkafolyamat) nem.
        var isStandaloneKarbPage = function () {
            return setup.independent && window.location.pathname.indexOf('/viewkarb') !== -1;
        };

        // Visszatérés az előző URL-re (a lista nézetre) a mentés után.
        var returnToPreviousUrl = function () {
            var origin = window.location.origin,
                ref = document.referrer,
                internalRef = !!ref && ref.indexOf(origin + '/') === 0,
                // a /viewkarb levágásával a lista nézet URL-je
                listUrl = origin + window.location.pathname.replace(/\/viewkarb.*$/, '/viewlist');
            if (window.history.length > 1 && internalRef) {
                // belső oldalról (a listából) jöttünk ugyanabban a fülben – pontosan az előző URL-re lépünk vissza
                window.history.back();
            } else if (internalRef) {
                // másik bizonylatról ÚJ FÜLÖN nyílt meg (pl. "Számla"/"Bevét" gomb vagy stornó) –
                // a sikeres mentésről visszajelzünk, majd az OK után csukjuk be a fület
                // (ha a böngésző nem engedi, essünk vissza a lista nézetre)
                if ($.unblockUI) {
                    $.unblockUI();
                }
                $('<div>A mentés sikerült.</div>').dialog({
                    title: 'Mentés',
                    resizable: false,
                    modal: true,
                    buttons: {
                        'OK': function () {
                            $(this).dialog('close');
                        }
                    },
                    close: function () {
                        window.close();
                        setTimeout(function () {
                            window.location.href = listUrl;
                        }, 200);
                    }
                });
            } else {
                // közvetlen megnyitás (könyvjelző/megosztott link): a lista nézetre lépünk
                window.location.href = listUrl;
            }
        };

        var showKarb = function () {
            $(setup.form).ajaxForm({
                type: 'POST',
                beforeSerialize: function (form, opt) {
                    var ret = true;
                    if ($.isFunction(setup.beforeSerialize)) {
                        ret = setup.beforeSerialize.call(this, form, opt, setup.quick);
                    }
                    if (ret) {
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
                    }
                    return ret;
                },
                success: function (data) {
                    if ($.isFunction(setup.beforeHide)) {
                        setup.beforeHide.call(this);
                    }
                    if ($.isFunction(setup.onSubmit)) {
                        setup.onSubmit.call(this, data);
                    }
                    // Önálló (saját URL-es / viewkarb) karb oldalon a mentés után
                    // visszatérünk az előző URL-re (a lista nézetre).
                    if (isStandaloneKarbPage()) {
                        returnToPreviousUrl();
                    }
                }
            });
            karbContainer.addClass('ui-widget ui-widget-content ui-corner-all mattkarb');
            header.addClass('mattable-titlebar ui-widget-header ui-corner-top ui-helper-clearfix');
            titlebar.addClass('mattedit-titlebar ui-widget-header ui-helper-clearfix');
            titlebar.each(function () {
                $this = $(this);
                var ref = $($this.attr(_dataattr.pagerefcontrol));
                if (ref.attr(_dataattr.pagevisible) == 'hidden') {
                    $this.append('<a href="#" class="mattedit-titlebar-close">' +
                        '<span class="ui-icon ui-icon-circle-triangle-s"></span></a>' +
                        '<span class="ui-jqgrid-title">' + $this.attr(_dataattr.titlecaption) + '</span>');
                } else {
                    $this.append('<a href="#" class="mattedit-titlebar-close">' +
                        '<span class="ui-icon ui-icon-circle-triangle-n"></span></a>' +
                        '<span class="ui-jqgrid-title">' + $this.attr(_dataattr.titlecaption) + '</span>');
                }
            });
            $(setup.ok).button();
            cancelbtn.on('click', function (e) {
                e.preventDefault();
                if ($.isFunction(setup.beforeHide)) {
                    setup.beforeHide.call(this);
                }
                if ($.isFunction(setup.onCancel)) {
                    setup.onCancel.call(this);
                }
                if (isStandaloneKarbPage()) {
                    returnToPreviousUrl();
                }
            }).button();
            $(setup.tab).tabs();
            titlebar.on('click', function (e) {
                e.preventDefault();
                var ref = $($(this).attr(_dataattr.pagerefcontrol));
                if (ref.attr(_dataattr.pagevisible) == 'hidden') {
                    ref.attr(_dataattr.pagevisible, 'visible');
                    ref.slideDown(setup.animationSpeed);
                    $('> a > span', this).removeClass('ui-icon-circle-triangle-s').addClass('ui-icon-circle-triangle-n');
                } else {
                    ref.attr(_dataattr.pagevisible, 'hidden');
                    ref.slideUp(setup.animationSpeed);
                    $('> a > span', this).removeClass('ui-icon-circle-triangle-n').addClass('ui-icon-circle-triangle-s');
                }
            });
            $(setup.page).each(function (index) {
                var $this = $(this).addClass('mattedit-page');
                if ($this.attr(_dataattr.pagevisible) == 'hidden') {
                    $this.hide();
                }
            });
            if ($.isFunction(setup.beforeShow)) {
                setup.beforeShow.call(this);
            }
            karbContainer.show();
            $(document).scrollTop(0);
        };

        var initialize = function () {
            if ($.meta) {
                setup = $.extend({}, setup, this.data());
            }
            showKarb();
        };

        initialize();
        return this;
    }
})(jQuery);