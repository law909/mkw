$(document).ready(
	function(){

	    // Bolti eladás (POS) gyorsrögzítő külön dobozban (a garancialevél-rögzítő marad a #mattkarb-ban).
	    boltieladas.init('#boltieladaskarb');

	    function getForm() {
            $.ajax({
                url: '/admin/garancialevelfej/getkarb',
                data: {
                    oper: 'add'
                },
                success: function(data) {
                    setupForm(data);
                }
            });
        }

        function setupForm(data) {
            var $mattkarb = $('#mattkarb');
            $mattkarb.append(data);
            $mattkarb.mattkarb($.extend({}, bizonylathelper.getMattKarbConfig('szamla'), {
                independent: true,
                onSubmit: function(data) {
                    var d = JSON.parse(data);
                    window.open('/admin/garancialevelfej/print?id=' + d.id, '_blank');
                    $mattkarb.empty();
                    getForm();
                }
            }));
        }

        getForm();

	}
);