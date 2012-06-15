function showContextOptions(evt)
{
    debugMessage($(this).attr("entryuuid"));
}
function addContextMenu(jqElem,uuid)
{
    var contextSelector=$("<span></span>");
    var contextInput=$("<input type='text'/>");
    contextInput.attr('entryuuid',uuid);
    contextInput.on({focus: showContextOptions, keyup:showContextOptions});
    contextSelector.attr("entryuuid",uuid);
    contextSelector.append(contextInput);
jqElem.append(contextSelector);
}

function setupContextMenu(parent)
{
    var contextEntries=parent.find("[entrytype='Section'][code='A3114534']");
    contextEntries.each(function(idx,elem)
        {
            var di=new doctrineInfo(elem);
            addContextMenu($(elem).find(".controls"),di.uuid);
        });
}

