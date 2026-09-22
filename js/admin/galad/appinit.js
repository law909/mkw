$(document).ready(
    function () {
        // Bolti eladás (POS) gyorsrögzítő a főoldali #mattkarb dobozban.
        boltieladas.init('#mattkarb', {printQuestion: 'Nyomtatja a garancialevelet?', email: false});
    }
);