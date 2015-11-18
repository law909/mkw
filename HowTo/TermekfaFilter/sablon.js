

// filter
$('#mattable-select').mattable({
    filter: {
        fields: [],
        onClear: function() {
            mkwcomp.termekfaFilter.clearChecks('#termekfa');
        },
        onFilter: function(obj) {
            var fak;
            fak = mkwcomp.termekfaFilter.getFilter('#termekfa');
            if (fak.length > 0) {
                obj['fafilter'] = fak;
            }
        }
    },
});



//init:
mkwcomp.termekfaFilter.init('#termekfa');
