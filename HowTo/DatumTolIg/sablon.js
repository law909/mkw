/**
 * Init
 */
var $toledit = $('#TolEdit');
if ($toledit) {
    $toledit.datepicker($.datepicker.regional['hu']);
    $toledit.datepicker('option', 'dateFormat', 'yy.mm.dd');
    $toledit.datepicker('setDate', $toledit.attr('data-datum'));
}

var $igedit = $('#IgEdit');
if ($igedit) {
    $igedit.datepicker($.datepicker.regional['hu']);
    $igedit.datepicker('option', 'dateFormat', 'yy.mm.dd');
    $igedit.datepicker('setDate', $igedit.attr('data-datum'));
}



/**
 * POST elott stringge alakit
 */
tol = $('#TolEdit').datepicker('getDate');
tol = tol.getFullYear() + '.' + (tol.getMonth() + 1) + '.' + tol.getDate();
ig = $('#IgEdit').datepicker('getDate');
ig = ig.getFullYear() + '.' + (ig.getMonth() + 1) + '.' + ig.getDate();
