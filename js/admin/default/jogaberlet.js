$(document).ready(function(){

    function isPartnerAutocomplete() {
        return $('#mattkarb-header').data('partnerautocomplete') == '1';
    }

    function partnerAutocompleteRenderer(ul, item) {
        return $('<li>')
            .append('<a>' + item.value + '</a>')
            .appendTo( ul );
    }

    function partnerAutocompleteConfig() {
        return {
            minLength: 4,
            autoFocus: true,
            source: '/admin/bizonylatfej/getpartnerlist',
            select: function(event, ui) {
                var partner = ui.item,
                    pi = $('input[name="partner"]');
                if (partner) {
                    pi.val(partner.id);
                    $('.js-ujpartnercb').prop('checked', false);
                    pi.change();
                }
            }
        };
    }

    function termekAutocompleteRenderer(ul, item) {
        if (item.nemlathato) {
            return $('<li>')
                .append('<a class="nemelerhetovaltozat">' + item.label + '</a>')
                .appendTo( ul );
        }
        else {
            return $('<li>')
                .append('<a>' + item.label + '</a>')
                .appendTo( ul );
        }
    }

    function termekAutocompleteConfig() {
        return {
            minLength: 4,
            autoFocus: true,
            source: '/admin/bizonylattetel/gettermeklist',
            select: function(event, ui) {
                var termek = ui.item;
                if (termek) {
                    var $this = $(this),
                        sorid = $this.attr('name').split('_')[1];
                    $this.siblings().val(termek.id);
                }
            }
        };
    }

    var mattkarbconfig={
			container:'#mattkarb',
			viewUrl:'/admin/jogaberlet/getkarb',
			newWindowUrl:'/admin/jogaberlet/viewkarb',
			saveUrl:'/admin/jogaberlet/save',
            beforeShow: function() {
                $('.js-partnerautocomplete').autocomplete(partnerAutocompleteConfig())
                    .autocomplete( "instance" )._renderItem = partnerAutocompleteRenderer;
                mkwcomp.datumEdit.init('#VasarlasDatumEdit');
            },
			onSubmit:function() {
				$('#messagecenter')
					.html('A mentés sikerült.')
					.hide()
					.addClass('matt-messagecenter ui-widget ui-state-highlight')
					.one('click',messagecenterclick)
					.slideToggle('slow');
			}
	}

	if ($.fn.mattable) {
	    mkwcomp.datumEdit.init('#datumtolfilter');
        mkwcomp.datumEdit.init('#datumigfilter');
        mkwcomp.datumEdit.init('#lejarattolfilter');
        mkwcomp.datumEdit.init('#lejaratigfilter');
		$('#mattable-select').mattable({
			filter:{
				fields:[
				    '#vevonevfilter',
                    '#TermekEdit',
                    '#datumtolfilter',
                    '#datumigfilter',
                    '#lejarattolfilter',
                    '#lejaratigfilter',
                    '#lejartfilter'
                ]
			},
			tablebody:{
				url: '/admin/jogaberlet/getlistbody'
			},
			karb: mattkarbconfig
		});
		$('#maincheckbox').change(function(){
			$('.egyedcheckbox').attr('checked',$(this).attr('checked'));
		});
	}
	else {
		if ($.fn.mattkarb) {
			$('#mattkarb').mattkarb($.extend({},mattkarbconfig,{independent:true}));
		}
	}
});