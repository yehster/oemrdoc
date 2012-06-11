function swapCall()
{
    var task=$(this).attr("func");
    var entryUUID=$(this).attr("entryuuid");
    $.post("/openemr/library/doctrine/interface/sorting.php",
    {
        entryUUID: entryUUID,
        task: task,
        refresh: "doc"
    },
        function(data)
        {
            refreshSection(data);
        }
        ,"json"
    );
}

function addMoveControlsToList(list,childSelector)
{
    var numItems=list.length;
    if(numItems>1)
        {
            list.each(function(idx,elem)
            {
                var child=$(elem).find(childSelector);
                var di=new doctrineInfo(child);
                if(idx>0)
                    {
                        // up
                        var up=$("<span>&#x2191</span>");
                        up.addClass("document_iconic");
                        up.attr("func","UP");
                        up.attr("entryuuid",di.uuid);
                        up.on({click: swapCall});
                        child.after(up);
                    }
                if(idx<(numItems-1))
                    {
                        //down
                        var down=$("<span>&#x2193</span>");
                        child.after(down);
                        down.attr("func","DOWN");
                        down.attr("entryuuid",di.uuid);
                        down.on({click: swapCall});
                        down.addClass("document_iconic");
                    }
            });
        }
    
}
function addMoveControls(parent)
{
    var problems = parent.parent().find("li.problem");
    problems.find("span.problem > [func='UP']").remove();
    problems.find("span.problem > [func='DOWN']").remove();
    addMoveControlsToList(problems,"span.problem > .document_iconic:last");
}