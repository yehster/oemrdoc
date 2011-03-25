function getAttrForElem(elem,attrName)
{

    if($(elem).attr(attrName)!=undefined)
    {
        return($(elem).attr(attrName));
    }
    else
    {
        return getAttrForElem($(elem).parent(),attrName);
    }
}


function lookupProblem() {
      inputString=this.value;
    if(inputString.length == 0) {
        // Hide the suggestion box.
        $('#popup').hide();
    } else {
        $.post("/openemr/library/doctrine/ui/dictionaryLookup.php", {searchString: ""+inputString+"",context: "code", className: "problemItem", maxRes: "5" }, function(data){
            if(data.length >0) {
                $('#popup').show();
                $('#popup').html(data);
            }
        });
    }
}
function addProblem() {
        code_text=$(this).find("td.problemItem").text();
        code = $(this).find("td.CODE").text();
        uuid = getAttrForElem($("#txtNewProblem"),"uuid");
        $.post("/openemr/library/doctrine/interface/manageEntry.php",
        {   parentEntryUUID: ""+ uuid + "",
            EntryType: "problem",
            code: ""+code+"",
            content: ""+code_text+"",
            task: 'create',
            refresh: "YES"},
        function(data){
        if(data.indexOf("error:",0)==-1)
                    {
                        // successful
                        $("#txtNewProblem").val("");
                        $('#popup').html("");
                        $('#popup').hide();
                        sectionID = "#" + uuid;
                        $(sectionID).replaceWith(data) ;
                    }
                    else
                    {
                        window.alert(data);
                    }
            });
}

function registerProblemEvents()
{
    $("#txtNewProblem").live({keyup: lookupProblem});

    $("tr.problemItem").live({mouseover: function() {$(this).addClass('Highlight');} ,mouseout: function() {$(this).removeClass('Highlight');}, click: addProblem});

}