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


var problemTimer;
var searchDelayInterval=150;
function keypressProblem(event)
{
    searchString=this.value;
    if(event.which==8)
        {
                    $('#popup').attr("inputString","");
        }
    clearTimeout(problemTimer);
    problemTimer=setTimeout("lookupProblem(searchString)",searchDelayInterval);
    return true;

}

function lookupProblem(inputString) {
    if(inputString.length == 0) {
        // Hide the suggestion box.
        $('#popup').attr("inputString","");
        $('#popup').hide();
    } else {
        requestTime= new Date().getTime();
        $.post("/openemr/library/doctrine/ui/dictionaryLookup.php", {searchString: ""+inputString+"",context: "code", className: "problemItem", maxRes: "5" }, function(data){
            if(data.length >0) {
                updateTime=$('#popup').attr("updateTime");
                if( $('#popup').attr("inputString")==undefined || ($('#popup').attr("inputString").length <inputString.length))
                {
                    $('#popup').attr("updateTime",requestTime);
                    $('#popup').attr("inputString",inputString);
                    $('#popup').html(data);
                    $('#popup').show();
                }
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
            task: "create",
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
                        $('#popup').attr("inputString","");
                    }
                    else
                    {
                        window.alert(data);
                    }
            });
}

function clickProbDetails()
{

    parentEntryUUID=$(this).attr("uuid");
    $.post("/openemr/library/doctrine/interface/manageEntry.php",
           {
                parentEntryUUID: ""+parentEntryUUID+"",
                EntryType: "narrative",
                task: "create",
                content: "",
                refresh: "YES"
            },
            function(data) {
                // TODO: update the text box attributes so that changes go to the existing entry and we don't keep creating new ones
                if(data.indexOf("error:",0)==-1)
                    {
                        pos=data.indexOf("<",0);
                        uuid=data.substr(0,pos);
                        html=data.substr(pos);
                        id = "#"+parentEntryUUID;
                       $(id).replaceWith(html);
                       textareaID= "textarea"+"[uuid='"+uuid+"']";
                       $(textareaID).focus();
                    }
                    else
                    {
                        window.alert(data);
                    }
            }
        );
}


function registerProblemEvents()
{
    $("#txtNewProblem").live({keyup: keypressProblem});

    $("tr.problemItem").live({mouseover: function() {$(this).addClass('Highlight');} ,mouseout: function() {$(this).removeClass('Highlight');}, click: addProblem});

    $("input[type='button'][entrytype='Problem'][value='details']").live({click: clickProbDetails});

}