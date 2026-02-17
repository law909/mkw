
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
                    url: '/search',
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

            console.log('nincs valtozat1');
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
    // valaszthato valtozat van
    $('.js-kosarbaszinvaltozat').on('click', function(e) {
        var $this = $(this),
            termekid = $this.attr('data-termek'),
            price = $this.attr('data-price'),
            caption = $this.attr('data-caption'),
            currency = $this.attr('data-currency'),
            valtozatid = $('.js-meretvaltozatedit[data-termek="' + termekid + '"] option:selected').val();

        e.preventDefault();

        if (!valtozatid) {
            console.log('nincs valtozat2');
            mkw.showDialog(mkwmsg.TermekValtozatotValassz);
        }
        else {
            $.ajax({
                type: 'POST',
                url: $this.attr('href'),
                data: {
                    jax: 2,
                    vid: valtozatid
                },
                beforeSend: function(x) {
                    mkw.showMessage(mkwmsg.TermekKosarba);
                }
            })
                .done(function(data) {
                    $('.js-meretvaltozatedit[data-termek="' + termekid + '"]').selectedIndex = 0;
                    var d = JSON.parse(data);
                    $('#minikosar').html(d.minikosar);
                    $('#minikosaringyenes').html(d.minikosaringyenes);

                    fbq('track', 'AddToCart', {
                        content_ids: [termekid],
                        content_name: caption,
                        content_type: 'product',
                        value: price,
                        currency: currency
                    });
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
    $('.js-szinvaltozatedit').on('change', function() {
        var $szinedit = $(this),
                termek = $szinedit.data('termek');
        $('#meret' + termek).remove();
        $.ajax({
            url: '/getmeretszinhez',
            data: {
                t: termek,
                sz: $szinedit.val(),
            }
        })
        .done(function(data) {
            if (data) {
                var box = $szinedit.parents('.js-valtozatbox');
                box.append(data);
            }
        });
    });

    $('.color-selector .select-option').on('click', function() {
        var $option = $(this);
        var value = $option.data('value');
        var termek = $option.parent('.color-selector').data('termek');

        // 1) vizuális kijelölés
        $option
            .addClass('active')
            .siblings().removeClass('active');

        // 2) háttérben a rejtett select értékének beállítása
        var $select = $('.js-szinvaltozatedit[data-termek="' + termek + '"]');
        $select.val(value);

        // 3) manuális change trigger → lefut a meglévő AJAX /getmeretszinhez
        $select.trigger('change');
    });

    $(document).on('click', '.size-selector .select-option', function() {
        var $option = $(this);
        var value = $option.data('value');
        var termek = $option.parent('.size-selector').data('termek');
        // 1) vizuális kijelölés
        $option
            .addClass('active')
            .siblings().removeClass('active');

        // 2) háttérben a rejtett select értékének beállítása
        var $select = $('.js-meretvaltozatedit[data-termek="' + termek + '"]');
        $select.val(value);

        // 3) manuális change trigger → lefut a meglévő AJAX /getmeretszinhez
        $select.trigger('change');
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
    $('select[name="headerorszag"]').on('change', function(e) {
        $.ajax({
            url: '/setorszag',
            type: 'POST',
            data: {
                orszag: $('select[name="headerorszag"] option:selected').val()
            },
            success: function() {
                window.location.reload();
            }
        });

    });

    if ($.fn.magnify) {
        $zoom = $('.zoom').magnify();
    }

    mkw.initTooltips();
    mkw.showhideFilterClear();

    cart.initUI();
    checkout.initUI();
    fiok.initUI();

    $('.header .icon.search').on('click', function(e) {
        e.preventDefault();
        $('#searchform').toggleClass('header__searchform__open');
    });

    $('.header__searchform-close').on('click', function(e) {
        e.preventDefault();
        $('#searchform').toggleClass('header__searchform__open');
    });

    $('.menu-toggle').on('click', function(e) {
        e.preventDefault();
        $('.main-menu').toggleClass('main-menu__open');
    });

    function isMobileMenu() {
        return window.matchMedia('(max-width: 1024px)').matches;
    }

    $('.main-menu-item>.main-menu__arrow, .main-menu-item>a').on('click', function (e) {
        if (!isMobileMenu()) return;

        e.preventDefault();

        const $item = $(this).parent();
        const isOpen = $item.hasClass('main-menu__menu-item-open');

        $('.main-menu-item').removeClass('main-menu__menu-item-open');

        if (!isOpen) {
            $item.addClass('main-menu__menu-item-open');
        }
    });

    

    

    $('.main-menu__close').on('click', function(e) {
        e.preventDefault();
        $('.main-menu').toggleClass('main-menu__open');
    });
    
    $('.product-filter__toggle').on('click', function(e) {
        e.preventDefault();
        $('.product-filter').toggleClass('product-filter__open');
    });

    $('.product-filter__close').on('click', function(e) {
        e.preventDefault();
        $('.product-filter').removeClass('product-filter__open');
    });

    // Accordion
    
    $(".accordion .accordion-item:first .accordion-content").show();

    // --- Betöltéskor az első sor legyen nyitva ---
  $(".accordion .accordion-item:first .accordion-content").show();
  $(".accordion .accordion-item:first .accordion-header").addClass("active");

  $(".accordion-header").click(function() {
    const content = $(this).next(".accordion-content");

    // Csak egy legyen nyitva
    // $(".accordion-content").not(content).slideUp();
    // $(".accordion-header").not(this).removeClass("active");

    // Nyit/zár + aktív osztály
    content.slideToggle();
    $(this).toggleClass("active");
  });
});



class Carousel {
    constructor(sectionEl, options = {}) {
        this.section = sectionEl;
        this.wrapper = this.section.querySelector('.carousel-wrapper');
        this.items = this.wrapper.querySelectorAll('.carousel-item');

        this.prevBtn = this.section.querySelector('.carousel-prev');
        this.nextBtn = this.section.querySelector('.carousel-next');

        this.currentIndex = 0;
        this.totalItems = this.items.length;
        this.itemsPerView = 1;

        this.autoplayInterval = options.autoplayInterval || 5000;
        this.autoplayTimer = null;

        this.init();
    }

    init() {
        this.updateItemsPerView();
        this.attachEventListeners();
        this.updateCarousel();
        this.startAutoplay();
    }

    updateItemsPerView() {
        const width = window.innerWidth;

        if (width >= 1024) {
            this.itemsPerView = 5;
        } else if (width >= 768) {
            this.itemsPerView = 3;
        } else {
            this.itemsPerView = 2;
        }

        this.items.forEach(item => {
            item.style.minWidth = `${100 / this.itemsPerView}%`;
        });
    }

    getMaxIndex() {
        return Math.max(0, this.totalItems - this.itemsPerView);
    }

    attachEventListeners() {
        if (this.prevBtn) {
            this.prevBtn.addEventListener('click', () => this.prev());
        }

        if (this.nextBtn) {
            this.nextBtn.addEventListener('click', () => this.next());
        }

        // resize (globális, de instance-enként frissít)
        let resizeTimer;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(() => {
                const old = this.itemsPerView;
                this.updateItemsPerView();

                if (old !== this.itemsPerView) {
                    this.currentIndex = 0;
                    this.updateCarousel();
                }
            }, 250);
        });

        // hover pause
        const container = this.section.querySelector('.carousel-container');
        if (container) {
            container.addEventListener('mouseenter', () => this.stopAutoplay());
            container.addEventListener('mouseleave', () => this.startAutoplay());
        }

        // touch
        let startX = 0;
        this.wrapper.addEventListener('touchstart', e => {
            startX = e.changedTouches[0].screenX;
        });

        this.wrapper.addEventListener('touchend', e => {
            const endX = e.changedTouches[0].screenX;
            if (endX < startX - 50) this.next();
            if (endX > startX + 50) this.prev();
        });
    }

    next() {
        const max = this.getMaxIndex();
        this.currentIndex = this.currentIndex < max ? this.currentIndex + 1 : 0;
        this.updateCarousel();
        this.resetAutoplay();
    }

    prev() {
        const max = this.getMaxIndex();
        this.currentIndex = this.currentIndex > 0 ? this.currentIndex - 1 : max;
        this.updateCarousel();
        this.resetAutoplay();
    }

    updateCarousel() {
        const offset = -(this.currentIndex * (100 / this.itemsPerView));
        this.wrapper.style.transform = `translateX(${offset}%)`;
    }

    startAutoplay() {
        if (!this.autoplayInterval) return;
        this.autoplayTimer = setInterval(() => this.next(), this.autoplayInterval);
    }

    stopAutoplay() {
        clearInterval(this.autoplayTimer);
        this.autoplayTimer = null;
    }

    resetAutoplay() {
        this.stopAutoplay();
        this.startAutoplay();
    }
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.carousel-section').forEach(section => {
        new Carousel(section, {
            autoplayInterval: 5000
        });
    });
});


// Header Country selector
document.addEventListener('click', function (e) {

  // Header szöveg kattintás → modal nyitás
  if (e.target.closest('#countryTrigger')) {
    const modal = document.getElementById('countryModal');
    if (modal) modal.classList.add('active');
  }

  // Bezárás
  if (e.target.closest('.country-modal__close')) {
    const modal = document.getElementById('countryModal');
    if (modal) modal.classList.remove('active');
  }

  // Ország kiválasztása
  const countryBtn = e.target.closest('.country-list button');
  if (countryBtn) {
    const value = countryBtn.dataset.value;
    const select = document.querySelector('.headerorszag');

    if (!select) return;

    select.value = value;
    select.dispatchEvent(new Event('change', { bubbles: true }));

    // Szöveg frissítése
    // azonnal (ha már jó)
    syncCountryTrigger();


    const modal = document.getElementById('countryModal');
    if (modal) modal.classList.remove('active');
  }

});

syncCountryTrigger();
setTimeout(syncCountryTrigger, 10);

// ÉS minden későbbi változásra is
document.addEventListener('change', function (e) {
if (e.target.matches('.headerorszag')) {
    syncCountryTrigger();
}
});

function syncCountryTrigger() {
  const select = document.querySelector('.headerorszag');
  const trigger = document.querySelector('.country-trigger');

  if (!select || !trigger) return;

  const selectedOption = select.options[select.selectedIndex];
  if (!selectedOption) return;

  trigger.textContent = selectedOption.text;
}

// Header Country selector

$( document ).ready(function() {

  $(document).on("click", ".side-cart__open", function() {
    const content = $(".side-cart");
    console.log('side-cart open');
    content.toggleClass("active");
  });

//   $(".side-cart__open").click(function() {
//     const content = $(".side-cart");
//     console.log('side-cart open');
//     content.toggleClass("active");
//   });

  $(document).on("click", ".side-cart__close", function() {
    const content = $(".side-cart");
    content.toggleClass("active");
  });
});





// #################
// Product datasheet
// #################


// ########################
// Product profile carousel
// ########################       


$( document ).ready(function() {
    
    const thumbsContainer = document.getElementById("thumbs");
    const mainImage = document.getElementById("mainImage");
    const carouselImages = Array.from(document.querySelectorAll("#thumbs img")).map(img => img.src);
    let currentIndex = 0;
    if (mainImage) {
       mainImage.addEventListener("click", () => {
            openLightboxByIndex(getCarouselImages(), currentIndex);
        });
    }

    function getCarouselImages() {
    return Array.from(document.querySelectorAll("#thumbs img")).map(img => img.src);
}

    document.querySelectorAll("#thumbs img").forEach((thumb, i) => {
        thumb.addEventListener("click", () => {
            openLightboxByIndex(carouselImages, i);
        });
    });

    

    if (typeof images !== "undefined" && images) {

        images.forEach((src, index) => {
            const img = document.createElement("img");
            img.src = src;
            img.dataset.index = index;
            if (index === 0) img.classList.add("active");
            img.onclick = () => changeImage(index, true);
            thumbsContainer.appendChild(img);
        });
    
        const preloaded = images.map(src => {
            const img = new Image();
            img.src = src;
            return img;
        });
    }

    function setActiveThumb(index) {
        const all = thumbsContainer.querySelectorAll("img");
        all.forEach(t => t.classList.remove("active"));

        const activeThumb = all[index];
        activeThumb.classList.add("active");

        const containerTop = thumbsContainer.scrollTop;
        const containerBottom = containerTop + thumbsContainer.clientHeight;

        const thumbTop = activeThumb.offsetTop;
        const thumbBottom = thumbTop + activeThumb.offsetHeight;

        if (thumbTop < containerTop) {
            // kilóg felül
            thumbsContainer.scrollTo({
            top: thumbTop - 20, //  
            behavior: "smooth"
            });
        } else if (thumbBottom > containerBottom) {
            // kilóg alul
            thumbsContainer.scrollTo({
            top: thumbBottom - thumbsContainer.clientHeight - 10, //  
            behavior: "smooth"
            });
        }
    }

    let isAnimating = false;

    function changeImage(newIndex) {
        if (newIndex === currentIndex) return;
        if (isAnimating) return;

        isAnimating = true;

        const wrapper = document.querySelector(".main-image-wrapper");
        const oldImage = wrapper.querySelector(".main-image");

        const direction = newIndex > currentIndex ? 1 : -1;
        const wrapperWidth = wrapper.clientWidth;

        const newImg = document.createElement("img");
        newImg.src = images[newIndex];
        newImg.className = "main-image";
        newImg.style.position = "absolute";
        newImg.style.transform =
            "translateX(" + (direction > 0 ? wrapperWidth : -wrapperWidth) + "px)";
        newImg.style.transition = "transform 0.15s ease";

        wrapper.appendChild(newImg);

        // 👉 lightbox click
        newImg.addEventListener("click", () => {
            openLightboxByIndex(getCarouselImages(), newIndex);
        });

        newImg.onload = () => {
            newImg.getBoundingClientRect();

            oldImage.style.transition = "transform 0.15s ease";
            oldImage.style.transform =
            "translateX(" + (direction > 0 ? -wrapperWidth : wrapperWidth) + "px)";

            newImg.style.transform = "translateX(0)";

            setTimeout(() => {
                oldImage.remove();
                newImg.style.position = "";
                newImg.style.transform = "";
                newImg.style.transition = "";
                newImg.style.top = "";

                isAnimating = false; // 🔓 unlock
            }, 170);
        };

        currentIndex = newIndex;
        setActiveThumb(newIndex);
    }


    const prevBtn = document.getElementById("prevBtn");
    if(prevBtn) {
        prevBtn.onclick = () => {
            const newIndex = (currentIndex - 1 + images.length) % images.length;
            changeImage(newIndex);
        };
    }


    const nextBtn = document.getElementById("nextBtn");
    if(nextBtn) {
        nextBtn.onclick = () => {
            const newIndex = (currentIndex + 1) % images.length;
            changeImage(newIndex);
        };
    }

    // Init
    if (typeof images !== "undefined" && images) {
        mainImage.src = images[0];
    }
    
    // ########
    // Lightbox
    // ########
    const lightbox = document.getElementById("lightbox");
    const lightboxImage = document.getElementById("lightboxImage");
    const lightboxClose = document.querySelector(".lightbox-close");
    const lightboxBackdrop = document.querySelector(".lightbox-backdrop");
    const prevBtnLightbox = document.querySelector(".lightbox-prev");
    const nextBtnLightbox = document.querySelector(".lightbox-next");
    let activeLightboxImages = [];
    let currentIndexLightbox = 0;

    // galéria képek tömbje
    const galleryImages = $('.gallery-image').map(function () {
        return $(this).data('image-large');
    }).get();

    $('.gallery-image').on('click', function () {
        const index = $('.gallery-image').index(this);
        openLightboxByIndex(galleryImages, index);
    });

    function openLightboxByIndex(galleryImages, index) {
        if (!galleryImages || !galleryImages.length) return;

        activeLightboxImages = galleryImages;
        currentIndexLightbox = index;

        lightboxImage.classList.add("is-fading");
        lightboxImage.src = activeLightboxImages[currentIndexLightbox];

        lightbox.classList.remove("hidden");
        document.body.style.overflow = "hidden";

        requestAnimationFrame(() => {
            lightboxImage.classList.remove("is-fading");
        });
    }

    function changeLightboxImage(newIndex) {
        lightboxImage.classList.add("is-fading");

        setTimeout(() => {
            currentIndexLightbox = newIndex;
            lightboxImage.src = activeLightboxImages[currentIndexLightbox];
        }, 150);

        setTimeout(() => {
            lightboxImage.classList.remove("is-fading");
        }, 300);
    }

    function showPrev() {
        const newIndex =
            (currentIndexLightbox - 1 + activeLightboxImages.length) %
            activeLightboxImages.length;
        changeLightboxImage(newIndex);
    }

    function showNext() {
        const newIndex =
            (currentIndexLightbox + 1) % activeLightboxImages.length;
        changeLightboxImage(newIndex);
    }

    function closeLightbox() {
        lightbox.classList.add("hidden");
        lightboxImage.src = "";
        document.body.style.overflow = "";
    }

    // function changeImageWithFade(newIndex) {
    //     lightboxImage.classList.add('is-fading');

    //     setTimeout(() => {
    //         currentIndexLightbox = newIndex;
    //         lightboxImage.src = lightboxImages[currentIndexLightbox];
    //     }, 150); // félidőn váltjuk a képet

    //     setTimeout(() => {
    //         lightboxImage.classList.remove('is-fading');
    //     }, 300); // teljes fade idő
    // }

    // function showPrev() {
    //     const newIndex = (currentIndexLightbox - 1 + lightboxImages.length) % lightboxImages.length;
    //     changeImageWithFade(newIndex);
    // }

    // function showNext() {
    //     const newIndex = (currentIndexLightbox + 1) % lightboxImages.length;
    //     changeImageWithFade(newIndex);
    // }

    

    if (lightboxClose) lightboxClose.addEventListener("click", closeLightbox);
    if (lightboxBackdrop) lightboxBackdrop.addEventListener("click", closeLightbox);
    // if (prevBtnLightbox) prevBtnLightbox.addEventListener("click", showPrev);
    // if (nextBtnLightbox) nextBtnLightbox.addEventListener("click", showNext);
    if (prevBtnLightbox) prevBtnLightbox.onclick = showPrev;
    if (nextBtnLightbox) nextBtnLightbox.onclick = showNext;
  
    document.addEventListener("keydown", (e) => {
        if (!lightbox) {
            return;
        }

        if (lightbox.classList.contains("hidden")) {
            return;
        }

        if (e.key === "Escape") closeLightbox();
        if (e.key === "ArrowLeft") showPrev();
        if (e.key === "ArrowRight") showNext();
    });

    $('.gallery-image').on('click', function() {
        const index = $('.gallery-image').index(this);
        openLightboxByIndex(index);
    });

});
