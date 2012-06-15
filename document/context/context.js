function closeContext(evt)
{
    var display = $(this).parents(".context_display");
    display.hide();
}
function displayContextInfo(section,data)
{
    var display=section.find(".context_display");
    var results;
    if(display.length==0)
        {
            display=$("<span></span>");
            display.addClass("context_display");
            section.append(display);
            
            var controls=$("<div></div>");
            controls.addClass("context_controls");
            display.append(controls);

            var close=$("<span>&#x2713</span>")
            close.addClass("document_iconic");
            close.on({click: closeContext });
            controls.append(close);

            results=$("<div></div>")
            results.addClass("results");
            display.append(results);
            
        }
        results=display.find(".results");
        results.html(data.html);
        display.show();
}
function retrieveContextInfo(section,query,entryuuid)
{
    $.post("/openemr/library/doctrine/context/lookupContextOptions.php",
    {
        searchString: query,
        entryUUID: entryuuid
    },
    function(data)
    {
        displayContextInfo(section,data);
    }
    ,"json");
}

function showContextOptions(evt)
{
    var section=$(this).parent("[func='Context']");
    retrieveContextInfo(section,$(this).val(),$(this).attr("entryuuid"));
}
function addContextMenu(jqElem,uuid)
{
    var contextSelector=$("<span></span>");
    var contextInput=$("<input type='text'/>");
    contextInput.attr('entryuuid',uuid);
    contextInput.on({focus: showContextOptions, keyup:showContextOptions});
    contextInput.attr("func","Context");
    contextSelector.attr("func","Context");
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

