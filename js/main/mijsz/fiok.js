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
                        var tbody = $('#MIJSZOklevelTab');
                        tbody.append(data);
                        $('.js-mijszoklevelnewbutton,.js-mijszokleveldelbutton').button();
                        $this.remove();
                    }
                });
            })
            .on('click', '.js-mijszokleveldelbutton', function(e) {
                e.preventDefault();
                var argomb = $(this),
                    arid = argomb.attr('data-id');
                if (argomb.attr('data-source') === 'client') {
                    $('#mijszokleveltable_' + arid).remove();
                }
                else {
                    dialogcenter.html('Biztos, hogy törli az oklevelet?').dialog({
                        resizable: false,
                        height: 140,
                        modal: true,
                        buttons: {
                            'Igen': function() {
                                $.ajax({
                                    url: '/admin/partnermijszoklevel/save',
                                    type: 'POST',
                                    data: {
                                        id: arid,
                                        oper: 'del'
                                    },
                                    success: function(data) {
                                        $('#mijszokleveltable_' + data).remove();
                                    }
                                });
                                $(this).dialog('close');
                            },
                            'Nem': function() {
                                $(this).dialog('close');
                            }
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