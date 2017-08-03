
$(document).ready(function() {

    var $termekertesitomodal = $('#termekertesitoModal'),
            $termekertesitoform = $('#termekertesitoform'),
        $zoom;

    function changeTermekAdat(id, d) {
        var imgpath = '';
        if ('imagepath' in d) {
            imgpath = d['imagepath'];
        }

        $zoom.destroy();

        $('#termekprice' + id).text(d['price']);
        $('#termekszallitasiido' + id).text(d['szallitasiido']);
        if ('kepurllarge' in d) {
            $('#termekkeplink' + id).attr('href', imgpath + d['kepurllarge']);
            $('#termekkep' + id).attr('src', imgpath + d['kepurllarge']);
        }
        if ('kepurlorig' in d) {
            $('#termekkep' + id).attr('data-magnify-src', imgpath + d['kepurlorig']);
        }
        if ('kepurlsmall' in d) {
            $('#termekkiskep' + id).attr('src', imgpath + d['kepurlsmall']);
        }
        if ('kepek' in d) {
            $('.js-termekimageslider .js-lightbox').each(function(index, elem) {
                if (index in d['kepek']) {
                    var $this = $(elem),
                        $img = $('img', $this);
                    $this.attr('href', imgpath + d['kepek'][index]['kepurl']);
                    $this.attr('title', d['kepek'][index]['leiras']);
                    $img.attr('src', imgpath + d['kepek'][index]['minikepurl']);
                    $img.attr('alt', d['kepek'][index]['leiras']);
                    $img.attr('title', d['kepek'][index]['leiras']);
                }
            });
        }

        $zoom = $('.zoom').magnify();
    }

    if ($.fn.mattaccord) {
        $(document).mattaccord();
    }
    if ($.fn.tab) {
        $('#termekTabbable').tab('show');
        $('#adamodositasTabbable').tab('show');
    }
    if ($.fn.slider) {
        var $arslider = $('#ArSlider');
        if ($arslider.length > 0) {
            var maxar = $arslider.data('maxar') * 1,
                    ti = $arslider.data('value'),
                    step = $arslider.data('step') * 1;
            $arslider.slider({
                from: 0,
                to: maxar,
                step: step,
                dimension: '&nbsp;Ft',
                skin: 'mkwcansas',
                callback: function(value) {
                    mkw.lapozas();
                }
            });
/*            var from = ti.split(';')[0] * 1,
            to = ti.split(';')[1] * 1;
            $arslider.slider('value', 1000, 3000);
*/
        }
    }
    if ($.fn.typeahead) {
        $('#searchinput').typeahead({
            source: function(query, process) {
                return $.ajax({
                    url: '/kereses',
                    type: 'GET',
                    data: {
                        term: query
                    },
                    success: function(data) {
                        var d = JSON.parse(data);
                        return process(d);
                    }
                });
            },
            onselect: function() {
                $('#searchform').submit();
            },
            items: 999999,
            minLength: 4
        });
    }
    if ($.fn.royalSlider) {
        $('#korhinta').royalSlider({
            autoScaleSlider: true,
            loopRewind: true,
            keyboardNavEnabled: true,
            controlNavigation: 'bullets',
            imageScalePadding: 0,
            autoPlay: {
                enabled: true,
                delay: 4000
            }
        });
        $('#akciostermekslider').royalSlider({
            loopRewind: true,
            keyboardNavEnabled: true,
            autoHeight: true,
            controlNavigation: 'bullets'
        });
        $('#legnepszerubbtermekslider').royalSlider({
            loopRewind: true,
            keyboardNavEnabled: true,
            autoHeight: true,
            controlNavigation: 'bullets'
        });
        $('#ajanlotttermekslider').royalSlider({
            autoHeight: true,
            loopRewind: true,
            keyboardNavEnabled: true,
            controlNavigation: 'bullets'
        });
        $('.js-termekimageslider').royalSlider({
            loopRewind: true,
            keyboardNavEnabled: true,
            controlNavigation: 'bullets',
            autoHeight: true
        });
        $('#hozzavasarolttermekslider').royalSlider({
            autoHeight: true,
            loopRewind: true,
            keyboardNavEnabled: true,
            controlNavigation: 'bullets'
        });
    }
    $termekertesitomodal.modal({
        show: false
    });
    $('.js-termekertesitobtn').on('click', function() {
        $termekertesitoform.find('input[name="termekid"]').val($(this).data('termek'));
        $termekertesitomodal.modal('show');
        return false;
    });
    mkw.overrideFormSubmit($termekertesitoform, false, {
        beforeSend: function(xhr, settings, data) {
            if (!data['email']) {
                alert('Adjon meg email címet.');
                return false;
            }
            return true;
        },
        success: function() {
            mkw.showMessage(mkwmsg.TermekErtesitoKoszonjuk);
            window.setTimeout(function() {
                mkw.closeMessage();
            }, 2500);
        },
        complete: function() {
            $termekertesitomodal.modal('hide');
            $termekertesitoform.find('input[name="termekid"]').val('');
        }
    });
    $('.js-termekertesitomodalok').on('click', function(e) {
        e.preventDefault();
        $termekertesitoform.submit();
    });
    $('.js-termekertesitodel').on('click', function(e) {
        var $this = $(this);
        e.preventDefault();
        $.ajax({
            url: '/termekertesito/save',
            type: 'POST',
            data: {
                oper: 'del',
                id: $this.data('id')
            },
            beforeSend: function() {
                mkw.showMessage(mkwmsg.TermekErtesitoLeiratkozas);
            },
            success: function() {
                $this.parents('div.js-termekertesito').remove();
            },
            complete: function() {
                mkw.closeMessage();
            }
        });
    });
    if ($.fn.magnificPopup) {
        $('.js-lightbox').magnificPopup({
            gallery: {
                enabled: true
            },
            image: {
                cursor: null
            },
            type: 'image'
        });
    }
    // nincs valtozat
    $('.js-kosarba').on('click', function(e) {
        var $this = $(this);
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: $this.attr('href'),
            data: {
                jax: 1
            },
            beforeSend: function(x) {
                mkw.showMessage(mkwmsg.TermekKosarba);
            }
        })
                .done(function(data) {
                    var d = JSON.parse(data);
                    $('#minikosar').html(d.minikosar);
                    $('#minikosaringyenes').html(d.minikosaringyenes);
                })
                .always(function() {
                    mkw.closeMessage();
                });
    });
    // lathato valtozat van
    $('.js-kosarbavaltozat').on('click', function(e) {
        var $this = $(this),
                id = $this.attr('data-id');

        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: $this.attr('href'),
            data: {
                jax: 2,
                vid: $this.attr('data-vid')
            },
            beforeSend: function() {
                mkw.showMessage(mkwmsg.TermekKosarba);
            }
        })
                .done(function(data) {
                    $('.js-valtozatedit[data-id="' + id + '"]').selectedIndex = 0;
                    var d = JSON.parse(data);
                    $('#minikosar').html(d.minikosar);
                    $('#minikosaringyenes').html(d.minikosaringyenes);
                })
                .always(function() {
                    mkw.closeMessage();
                });
    });
    // valaszthato valtozat van
    $('.js-kosarbamindenvaltozat').on('click', function(e) {
        var $this = $(this),
                termekid = $this.attr('data-termek'),
                tipusok = new Array(), ertekek = new Array(),
                valtozatselect = $('.js-mindenvaltozatedit[data-termek="' + termekid + '"]');

        e.preventDefault();

        valtozatselect.each(function() {
            var $this = $(this);
            if ($this.val()) {
                tipusok.push($this.data('tipusid'));
                ertekek.push($this.val());
            }
        });

        if (valtozatselect.length !== ertekek.length) {
            mkw.showDialog(mkwmsg.TermekValtozatotValassz);
        }
        else {
            $.ajax({
                type: 'POST',
                url: $this.attr('href'),
                data: {
                    jax: 3,
                    tip: tipusok,
                    val: ertekek
                },
                beforeSend: function(x) {
                    mkw.showMessage(mkwmsg.TermekKosarba);
                }
            })
                    .done(function(data) {
                        $('.js-mindenvaltozatedit[data-termek="' + termekid + '"]').selectedIndex = 0;
                        var d = JSON.parse(data);
                        $('#minikosar').html(d.minikosar);
                        $('#minikosaringyenes').html(d.minikosaringyenes);
                    })
                    .always(function() {
                        mkw.closeMessage();
                    });
        }
    });

    // valtozat
    $('.js-valtozatedit').on('change', function() {
        var $this = $(this),
                termek = $this.data('termek'),
                id = $this.data('id');

        $.ajax({
            url: '/valtozatar',
            data: {
                t: termek,
                vid: $this.val()
            }
        })
                .done(function(data) {
                    var d = JSON.parse(data);

                    changeTermekAdat(id, d);

                })
                .always(function() {
                    $('.js-kosarbavaltozat[data-id="' + id + '"]').attr('data-vid', $this.val());
                });
    });
    $('.js-mindenvaltozatedit').on('change', function() {
        var $valtedit = $(this),
                tipusid = $valtedit.data('tipusid'),
                termek = $valtedit.data('termek'),
                id = $valtedit.data('id'),
                $masikedit = $('.js-mindenvaltozatedit[data-termek="' + termek + '"][data-tipusid!="' + tipusid + '"]');

        $.ajax({
            url: '/valtozat',
            data: {
                t: termek,
                ti: tipusid,
                v: $valtedit.val(),
                sel: $masikedit.val(),
                mti: $masikedit.data('tipusid')
            }
        })
                .done(function(data) {
                    var d = JSON.parse(data),
                            adat = d['adat'];
                    sel = '';

                    changeTermekAdat(id, d);

                    $('option[value!=""]', $masikedit).remove();
                    $.each(adat, function(i, v) {
                        if (v['sel']) {
                            sel = ' selected="selected"';
                        }
                        else {
                            sel = '';
                        }
                        $masikedit.append('<option value="' + v['value'] + '"' + sel + '>' + v['value'] + '</option>');
                    });
                });
    });
    var $regform = $('#Regform');
    if ($regform.length > 0) {
        H5F.setup($regform);
        $('#VezeteknevEdit,#KeresztnevEdit')
                .on('input', function(e) {
                    mkwcheck.regNevCheck();
                    $(this).off('keydown');
                })
                .on('keydown blur', function(e) {
                    mkwcheck.wasinteraction.doublenev = true;
                    mkwcheck.regNevCheck();
                })
                .each(function(i, ez) {
                    mkwcheck.regNevCheck();
                });
        $('#EmailEdit')
                .on('input', function(e) {
                    mkwcheck.regEmailCheck();
                    $(this).off('keydown');
                })
                .on('keydown blur', function(e) {
                    mkwcheck.wasinteraction.email = true;
                    mkwcheck.regEmailCheck();
                })
                .on('change', function(e) {
                    var $this = $(this);
                    $.ajax({
                        type: 'POST',
                        url: '/checkemail',
                        data: {email: $this.val()}
                    })
                            .done(function(data) {
                                var d = JSON.parse(data);
                                $this.data('hiba', d);
                                mkwcheck.regEmailCheck();
                            });
                })
                .each(function(i, ez) {
                    mkwcheck.regEmailCheck();
                });
        $('#Jelszo1Edit,#Jelszo2Edit')
                .on('input', function(e) {
                    mkwcheck.regJelszoCheck();
                    $(this).off('keydown');
                })
                .on('keydown blur', function(e) {
                    mkwcheck.wasinteraction.pw = true;
                    mkwcheck.regJelszoCheck();
                })
                .each(function(i, ez) {
                    mkwcheck.regJelszoCheck();
                });
    }
    var $passreminderform = $('#passreminderform');
    if ($passreminderform.length >0) {
        $('#Jelszo1Edit,#Jelszo2Edit')
                .on('input', function(e) {
                    mkwcheck.regJelszoCheck();
                    $(this).off('keydown');
                })
                .on('keydown blur', function(e) {
                    mkwcheck.wasinteraction.pw = true;
                    mkwcheck.regJelszoCheck();
                })
                .each(function(i, ez) {
                    mkwcheck.regJelszoCheck();
                });
    }
    var $kapcsolatform = $('#Kapcsolatform');
    if ($kapcsolatform.length > 0) {
        H5F.setup($kapcsolatform);
        $('#NevEdit')
                .on('input', function(e) {
                    mkwcheck.kapcsolatNevCheck();
                    $(this).off('keydown');
                })
                .on('keydown blur', function(e) {
                    mkwcheck.wasinteraction.nev = true;
                    mkwcheck.kapcsolatNevCheck();
                })
                .each(function(i, ez) {
                    mkwcheck.kapcsolatNevCheck();
                });
        $('#Email1Edit,#Email2Edit')
                .on('input', function(e) {
                    mkwcheck.kapcsolatEmailCheck();
                    $(this).off('keydown');
                })
                .on('change', function(e) {
                    var $this = $(this);
                    $.ajax({
                        type: 'POST',
                        url: '/checkemail',
                        data: {
                            email: $this.val(),
                            dce: 1
                        }
                    })
                            .done(function(data) {
                                var d = JSON.parse(data);
                                $this.data('hiba', d);
                                mkwcheck.kapcsolatEmailCheck();
                            });
                })
                .on('keydown blur', function(e) {
                    mkwcheck.wasinteraction.email = true;
                    mkwcheck.kapcsolatEmailCheck();
                })
                .each(function(i, ez) {
                    mkwcheck.kapcsolatEmailCheck();
                });
        $('#TemaEdit')
                .on('input', function(e) {
                    mkwcheck.kapcsolatTemaCheck();
                    $(this).off('keydown');
                })
                .on('keydown blur', function(e) {
                    mkwcheck.wasinteraction.tema = true;
                    mkwcheck.kapcsolatTemaCheck();
                })
                .each(function(i, ez) {
                    mkwcheck.kapcsolatTemaCheck();
                });
    }
    var $loginform = $('#Loginform');
    if ($loginform.length > 0) {
        H5F.setup($loginform);
        $('#EmailEdit')
                .on('input', function(e) {
                    mkwcheck.loginEmailCheck();
                    $(this).off('keydown');
                })
                .on('keydown blur', function(e) {
                    mkwcheck.wasinteraction.email = true;
                    mkwcheck.loginEmailCheck();
                })
                .each(function(i, ez) {
                    mkwcheck.loginEmailCheck();
                });
        $('.js-passreminder').on('click', function() {
            var email = $('input[name="email"]', $loginform).val();
            if (!email) {
                mkw.showDialog(mkwmsg.PassReminderRequiredEmail);
                return false;
            }
            $.ajax({
                type: 'POST',
                url: '/getpassreminder',
                data: {
                    email: email
                },
                success: function() {
                    mkw.showDialog(mkwmsg.PassReminderCreated);
                }
            })
            return false;
        })
    }
    var $passwordchangeform = $('#JelszoChangeForm');
    if ($passwordchangeform.length > 0) {
        mkw.overrideFormSubmit($passwordchangeform, false, {
           beforeSend: function(xhr, settings, data) {
               if (!data['jelszo1'] || !data['jelszo2']) {
                   mkw.showDialog(mkwmsg.PassChange[3]);
                   return false;
               }
               if (data['jelszo1'] !== data['jelszo2']) {
                   mkw.showDialog(mkwmsg.PassChange[1]);
                   return false;
               }
               return true;
           },
           success: function(data) {
               var d = JSON.parse(data);
               if (!d.hibas) {
                   mkw.showMessage(mkwmsg.PassChange[d.hibas]);
                   window.setTimeout(function() {
                       mkw.closeMessage();
                   }, 2500);
               }
               else {
                   mkw.showDialog(mkwmsg.PassChange[d.hibas]);
               }
           }
        });
    }
    // kategoria navigalas
    var a = $('#navmain li a'),
            b = $('#navmain li .sub');
    $('#navmain li').on('click', function(e) {
        var $this = $(this),
            gy = $this.children('a'),
            v = gy.hasClass('active');
        e.preventDefault();
        if (gy.attr('data-cnt') > 0) {
            a.removeClass('active');
            b.hide();
            if (!v) {
                gy.addClass('active');
                $this.children('.sub').toggle();
            }
        }
        else {
            if (gy.length > 0) {
                document.location = gy.attr('href');
            }
        }
    });
    b.mouseup(function() {
        return false;
    });
    $(document).on('mouseup', function(c) {
        if ($(c.target).parent("#navmain li").length == 0) {
            a.removeClass("active");
            b.hide();
        }
    });
    $('div.kat').on('click', function(e) {
        e.preventDefault();
        document.location = $(this).attr('data-href');
    });
    // lapozo es szuroform
    $('.elemperpageedit').on('change', function() {
        $('.elemperpageedit').val($(this).val());
        mkw.lapozas();
    });
    $('.orderedit').on('change', function() {
        $('.orderedit').val($(this).val());
        mkw.lapozas();
    });
    $('.pageedit').on('click', function() {
        $('.lapozoform').attr('data-pageno', $(this).attr('data-pageno'));
        mkw.lapozas();
    });
    $('.termeklistview').on('click', function() {
        $('#ListviewEdit').val($(this).attr('data-vt'));
        mkw.lapozas();
    });
    $('#szuroform input').on('change', function() {
        $('.lapozoform input[name="cimkekatid"]').val($(this).attr('name').split('_')[1]);
        mkw.lapozas();
    });
    $('.js-filterclear').on('click', function(e) {
        e.preventDefault();
        $('#szuroform input[type="checkbox"]').prop('checked',false);
        $('#ArSlider').val('0;0');
        mkw.lapozas(1);
    });

    $zoom = $('.zoom').magnify();

    mkw.initTooltips();
    mkw.showhideFilterClear();

    cart.initUI();
    checkout.initUI();
    fiok.initUI();

});