var mkw = (function($) {

    function showMessage(msg) {
        var msgcenter = $('#messagecenter');
        msgcenter.html(msg);
        $.magnificPopup.open({
            modal: true,
            items: [
                {
                    src: msgcenter,
                    type: 'inline'
                }
            ]
        });
    }

    function closeMessage() {
        $.magnificPopup.close();
    }

    function showDialog(msg, options) {
        var dlgcenter = $('#dialogcenter'),
                dlgheader = $('.modal-header', dlgcenter),
                dlgbody = $('.modal-body', dlgcenter).empty(),
                dlgfooter = $('.modal-footer', dlgcenter).empty(),
                classes = 'btn';
        $('h4', dlgheader).remove();
        opts = {
            header: mkwmsg.DialogFejlec,
            buttons: [{
                    caption: mkwmsg.DialogOk,
                    _class: 'okbtn',
                    click: function(e) {
                        e.preventDefault();
                        closeDialog();
                        if (options && options.onOk) {
                            options.onOk.apply(this);
                        }
                    }
                }]
        };
        if (opts.header) {
            dlgheader.append('<h4>' + opts.header + '</h4>');
        }
        if (msg) {
            dlgbody.append('<p>' + msg + '</p>');
        }
        for (var i = 0; i < opts.buttons.length; i++) {
            if (opts.buttons[i]._class) {
                classes = classes + ' ' + opts.buttons[i]._class;
            }
            $('<button class="' + classes + '">' + opts.buttons[i].caption + '</button>')
                    .appendTo(dlgfooter)
                    .on('click', opts.buttons[i].click);
        }
        return dlgcenter.modal();
    }

    function closeDialog() {
        var dlgcenter = $('#dialogcenter');
        dlgcenter.modal('hide');
    }

    function lapozas(page) {
        var lf = $('.lapozoform'), url,
                filterstr = '';
        if (!page) {
            page = lf.data('pageno');
        }
        url = lf.data('url') + '?pageno=' + page,
                url = url + '&elemperpage=' + $('.elemperpageedit').val() + '&order=' + $('.orderedit').val();
        $('#szuroform input:checkbox:checked').each(function() {
            filterstr = filterstr + $(this).prop('name') + ',';
        });
        if (filterstr !== '') {
            url = url + '&filter=' + filterstr;
        }
        url = url + '&arfilter=' + $('#ArSlider').val();
        url = url + '&keresett=' + $('.KeresettEdit').val();
        url = url + '&vt=' + $('#ListviewEdit').val();
        if ($('#CsakakciosEdit').val()) {
            url = url + '&csakakcios=1';
        }
        document.location = url;
    }

    function bloglapozas(page) {
        var lf = $('.lapozoform'), url;
        if (!page) {
            page = lf.data('pageno');
        }
        url = lf.data('url') + '?pageno=' + page;
        document.location = url;
    }

    function overrideFormSubmit(form, msg, events) {
        var $form = form;
        if (!events) {
            events = {};
        }
        if (typeof form == 'string') {
            $form = $(form);
        }
        $form.on('submit', function(e) {
            e.preventDefault();
            var data = {jax: 1};
            $form.find('input').each(function() {
                var $this = $(this);
                switch ($this.attr('type')) {
                    case 'checkbox':
                        data[$this.attr('name')] = $this.prop('checked');
                        break;
                    default:
                        data[$this.attr('name')] = $this.val();
                        break;
                }
            });
            $form.find('select').each(function() {
                var $this = $(this);
                data[$this.attr('name')] = $this.val();
            });
            $.ajax({
                url: $form.attr('action'),
                type: 'POST',
                data: data,
                beforeSend: function(xhr, settings) {
                    var ret = true;
                    if (typeof events.beforeSend == 'function') {
                        ret = events.beforeSend.call($form, xhr, settings, data);
                    }
                    if (msg) {
                        showMessage(msg);
                    }
                    return ret;
                },
                complete: function(xhr, status) {
                    if (msg) {
                        closeMessage();
                    }
                    if (typeof events.complete == 'function') {
                        events.complete.call($form, xhr, status);
                    }
                },
                error: function(xhr, status, error) {
                    if (typeof events.error == 'function') {
                        events.error.call($form, xhr, status);
                    }
                },
                success: function(data, status, xhr) {
                    if (typeof events.success == 'function') {
                        events.success.call($form, data, status, xhr);
                    }
                }
            });
        });

    }

    function irszamTypeahead(irszaminput, varosinput) {
        if ($.fn.typeahead) {
            var map = {};
            $(irszaminput).typeahead({
                source: function(query, process) {
                    var texts = [];
                    return $.ajax({
                        url: '/irszam',
                        type: 'GET',
                        data: {
                            term: query
                        },
                        success: function(data) {
                            var d = JSON.parse(data);
                            $.each(d, function(i, irszam) {
                                map[irszam.id] = irszam;
                                texts.push(irszam.id);
                            });
                            return process(texts);
                        }
                    });
                },
                updater: function(item) {
                    var irsz = map[item];
                    item = irsz.szam;
                    $(varosinput).val(irsz.nev);
                    return item;
                },
                items: 999999,
                minLength: 2
            });
        }
    }

    function varosTypeahead(irszaminput, varosinput) {
        if ($.fn.typeahead) {
            var map = {};
            $(varosinput).typeahead({
                source: function(query, process) {
                    var texts = [];
                    return $.ajax({
                        url: '/varos',
                        type: 'GET',
                        data: {
                            term: query
                        },
                        success: function(data) {
                            var d = JSON.parse(data);
                            $.each(d, function(i, irszam) {
                                map[irszam.id] = irszam;
                                texts.push(irszam.id);
                            });
                            return process(texts);
                        }
                    });
                },
                updater: function(item) {
                    var irsz = map[item];
                    item = irsz.nev;
                    $(irszaminput).val(irsz.szam);
                    return item;
                },
                items: 999999,
                minLength: 4
            });
        }
    }

    function initTooltips() {
        $('.js-tooltipbtn').tooltip({
            html: false,
            placement: 'right',
            container: 'body'
        });
    }

    function showhideFilterClear() {
        var $arslider = $('#ArSlider'),
                arfi = $arslider.val(), arfiarr,
                maxar = $arslider.data('maxar');

        if (arfi) {
            arfiarr = arfi.split(';');
            if ((arfiarr[0] * 1 !== 0) || (arfiarr[1] * 1 <= maxar) || $('#szuroform input[type="checkbox"]:checked').length) {
                $('.js-filterclear').show();
            }
            else {
                $('.js-filterclear').hide();
            }
        }
        else {
            $('.js-filterclear').hide();
        }
    }

    function onlyNumberInput(input) {
        var $kel = $(input);
        $kel.on("input keydown keyup mousedown mouseup select contextmenu drop", function() {
            var $el = $kel[0];
            if (/^\d*$/.test($el.value)) {
                $el.oldValue = $el.value;
                $el.oldSelectionStart = $el.selectionStart;
                $el.oldSelectionEnd = $el.selectionEnd;
            }
            else if ($el.hasOwnProperty("oldValue")) {
                $el.value = $el.oldValue;
                $el.setSelectionRange($el.oldSelectionStart, $el.oldSelectionEnd);
            }
        });
    }

    return {
        showMessage: showMessage,
        closeMessage: closeMessage,
        showDialog: showDialog,
        closeDialog: closeDialog,
        lapozas: lapozas,
        bloglapozas: bloglapozas,
        overrideFormSubmit: overrideFormSubmit,
        irszamTypeahead: irszamTypeahead,
        varosTypeahead: varosTypeahead,
        initTooltips: initTooltips,
        showhideFilterClear: showhideFilterClear,
        onlyNumberInput: onlyNumberInput
    };
})(jQuery);