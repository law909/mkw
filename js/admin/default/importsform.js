$(document).ready(function() {
	var dialogcenter=$('#dialogcenter');

    $('#mattkarb').mattkarb({
		independent:true,
		viewUrl:'/admin/getkarb',
		newWindowUrl:'/admin/viewkarb',
		saveUrl:'/admin/save',
		beforeShow:function() {

            $('.js-kreativimport,.js-deltonimport,.js-nomadimport,.js-reinteximport,.js-legavenueimport,.js-tutisportimport').on('click', function(e) {
                e.preventDefault();
                if (!$('#TermekKategoria1').attr('data-value')) {
                    alert('Válasszon kategóriát.');
                }
                else {
                    var data = new FormData($('#mattkarb-form')[0]);
                    data.append('katid', $('#TermekKategoria1').attr('data-value'));
                    $.ajax({
                        type: 'POST',
                        url: $(this).attr('href'),
                        processData: false,
                        contentType: false,
                        data: data,
                        success: function() {
                            alert('Kész.');
                        }
                    });
                }
            }).button();

            $('.js-vateraimport').on('click', function(e) {
                e.preventDefault();
                var data = new FormData($('#mattkarb-form')[0]);
                $.ajax({
                    type: 'POST',
                    url: $(this).attr('href'),
                    processData: false,
                    contentType: false,
                    data: data,
                    success: function() {
                        alert('Kész.');
                    }
                });
            }).button();

            $('.js-termekfabutton').on('click', function(e) {
                var edit = $(this);
                e.preventDefault();
                dialogcenter.jstree({
                    core: {animation: 100},
                    plugins: ['themeroller', 'json_data', 'ui'],
                    themeroller: {item: ''},
                    json_data: {
                        ajax: {url: '/admin/termekfa/jsonlist'}
                    },
                    ui: {select_limit: 1}
                })
                        .bind('loaded.jstree', function(event, data) {
                            dialogcenter.jstree('open_node', $('#termekfa_1', dialogcenter).parent());
                        });
                dialogcenter.dialog({
                    resizable: true,
                    height: 340,
                    modal: true,
                    buttons: {
                        'OK': function() {
                            dialogcenter.jstree('get_selected').each(function() {
                                var treenode = $(this).children('a');
                                edit.attr('data-value', treenode.attr('id').split('_')[1]);
                                $('span', edit).text(treenode.text());
                            });
                            $(this).dialog('close');
                        },
                        'Bezár': function() {
                            $(this).dialog('close');
                        }
                    }
                });
            }).button();
		},
		onSubmit:function() {
			$('#messagecenter')
				.html('A mentés sikerült.')
				.hide()
				.addClass('matt-messagecenter ui-widget ui-state-highlight')
				.one('click',messagecenterclick)
				.slideToggle('slow');
		}
	});
});