function registerLOINCEvents()
{
    $("tr.LOINC").live({mouseover: function() {$(this).addClass('Highlight');} ,mouseout: function() {$(this).removeClass('Highlight');} });

}