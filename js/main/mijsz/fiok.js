var fiok = (function($) {

	function initUI() {
		var $fiokadataimform = $('#FiokAdataim');

		if ($fiokadataimform.length > 0) {

			H5F.setup($fiokadataimform);

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
				}).done(function(data) {
					var d = JSON.parse(data);
					$this.data('hiba', d);
					mkwcheck.regEmailCheck();
				});
			})
			.each(function(i, ez) {
				mkwcheck.regEmailCheck();
			});

			$('.js-copyszamlaadat').on('click', function() {
				$('input[name="szallnev"]').val($('input[name="nev"]').val());
				$('input[name="szallirszam"]').val($('input[name="irszam"]').val());
				$('input[name="szallvaros"]').val($('input[name="varos"]').val());
				$('input[name="szallutca"]').val($('input[name="utca"]').val());
				return false;
			});

			$('.js-tooltipbtn').tooltip({
				html: false,
				placement: 'right',
				container: 'body'
			});

			mkw.overrideFormSubmit($fiokadataimform, mkwmsg.FiokAdataitModositjuk);

		}

		var $fiokszamlaadatok = $('#FiokSzamlaAdatok');
		if ($fiokszamlaadatok.length > 0) {
			mkw.irszamTypeahead('input[name="irszam"]', 'input[name="varos"]');
			mkw.varosTypeahead('input[name="irszam"]', 'input[name="varos"]');
			mkw.overrideFormSubmit($fiokszamlaadatok, mkwmsg.FiokAdataitModositjuk);
		}

		var $fiokszallitasiadatok = $('#FiokSzallitasiAdatok');
		if ($fiokszallitasiadatok.length > 0) {
			mkw.irszamTypeahead('input[name="szallirszam"]', 'input[name="szallvaros"]');
			mkw.varosTypeahead('input[name="szallirszam"]', 'input[name="szallvaros"]');
			mkw.overrideFormSubmit($fiokszallitasiadatok, mkwmsg.FiokAdataitModositjuk);
		}

		var mijszokleveltab = $('#FiokOklevelek');
		if (mijszokleveltab.length >0) {
            mijszokleveltab.on('click', '.js-mijszoklevelnewbutton', function(e) {
                var $this = $(this);
                e.preventDefault();
                $.ajax({
                    url: '/partnermijszoklevel/getemptyrow',
                    type: 'GET',
                    success: function(data) {
                        var tbody = $('#FiokOklevelek fieldset div.form-actions');
                        tbody.before(data);
                    }
                });
            })
            .on('click', '.js-mijszokleveldelbutton', function(e) {
                e.preventDefault();
                var gomb = $(this),
                    id = gomb.attr('data-id');
                if (gomb.attr('data-source') === 'client') {
                    gomb.parent().remove();
                }
                else {
                    mkw.showDialog('Biztos, hogy törli az oklevelet?',{
                            'onOk': function() {
                                $.ajax({
                                    url: '/partnermijszoklevel/save',
                                    type: 'POST',
                                    data: {
                                        id: id,
                                        oper: 'del'
                                    },
                                    success: function(data) {
                                        gomb.parent().remove();
                                    }
                                });
                            }
                    });
                }
            });
        }
        var mijszpunetab = $('#FiokPune');
        if (mijszpunetab.length >0) {
            mijszpunetab.on('click', '.js-mijszpunenewbutton', function(e) {
                var $this = $(this);
                e.preventDefault();
                $.ajax({
                    url: '/partnermijszpune/getemptyrow',
                    type: 'GET',
                    success: function(data) {
                        var tbody = $('#FiokPune fieldset div.form-actions');
                        tbody.before(data);
                    }
                });
            })
                .on('click', '.js-mijszpunedelbutton', function(e) {
                    e.preventDefault();
                    var gomb = $(this),
                        id = gomb.attr('data-id');
                    if (gomb.attr('data-source') === 'client') {
                        gomb.parent().remove();
                    }
                    else {
                        mkw.showDialog('Biztos, hogy törli a látogatást?',{
                            'onOk': function() {
                                $.ajax({
                                    url: '/partnermijszpune/save',
                                    type: 'POST',
                                    data: {
                                        id: id,
                                        oper: 'del'
                                    },
                                    success: function(data) {
                                        gomb.parent().remove();
                                    }
                                });
                            }
                        });
                    }
                });
        }
        var mijszoralatogatastab = $('#FiokOralatogatasok');
        if (mijszoralatogatastab.length >0) {
            mijszoralatogatastab.on('click', '.js-mijszoralatogatasnewbutton', function(e) {
                var $this = $(this);
                e.preventDefault();
                $.ajax({
                    url: '/partnermijszoralatogatas/getemptyrow',
                    type: 'GET',
                    success: function(data) {
                        var tbody = $('#FiokOralatogatasok fieldset div.form-actions');
                        tbody.before(data);
                    }
                });
            })
                .on('click', '.js-mijszoralatogatasdelbutton', function(e) {
                    e.preventDefault();
                    var gomb = $(this),
                        id = gomb.attr('data-id');
                    if (gomb.attr('data-source') === 'client') {
                        gomb.parent().parent().parent().remove();
                    }
                    else {
                        mkw.showDialog('Biztos, hogy törli a látogatást?',{
                            'onOk': function() {
                                $.ajax({
                                    url: '/partnermijszoralatogatas/save',
                                    type: 'POST',
                                    data: {
                                        id: id,
                                        oper: 'del'
                                    },
                                    success: function(data) {
                                        gomb.parent().parent().parent().remove();
                                    }
                                });
                            }
                        });
                    }
                });
        }
        var mijsztanitastab = $('#FiokTanitas');
        if (mijsztanitastab.length >0) {
            mijsztanitastab.on('click', '.js-mijsztanitasnewbutton', function(e) {
                var $this = $(this);
                e.preventDefault();
                $.ajax({
                    url: '/partnermijsztanitas/getemptyrow',
                    type: 'GET',
                    success: function(data) {
                        var tbody = $('#FiokTanitas fieldset div.form-actions');
                        tbody.before(data);
                    }
                });
            })
                .on('click', '.js-mijsztanitasdelbutton', function(e) {
                    e.preventDefault();
                    var gomb = $(this),
                        id = gomb.attr('data-id');
                    if (gomb.attr('data-source') === 'client') {
                        gomb.parent().parent().parent().remove();
                    }
                    else {
                        mkw.showDialog('Biztos, hogy törli a tanítást?',{
                            'onOk': function() {
                                $.ajax({
                                    url: '/partnermijsztanitas/save',
                                    type: 'POST',
                                    data: {
                                        id: id,
                                        oper: 'del'
                                    },
                                    success: function(data) {
                                        gomb.parent().parent().parent().remove();
                                    }
                                });
                            }
                        });
                    }
                });
        }
    }

	return {
		initUI: initUI
	};
})(jQuery);