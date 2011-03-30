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
function keypressProblem()
{
    searchString=this.value;
    clearTimeout(problemTimer);
        problemTimer = setTimeout(
        function(){lookupProblem(searchString)},
        searchDelayInterval
    );

    return true;
}

function lookupProblem(inputString) {
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

function setupMedSearch(uuid)
{
    info="<INPUT TYPE='BUTTON' value='close' class='closePopup'/><DIV><INPUT TYPE='TEXT' ID='txtMedSearch' uuid='"+uuid+"'/></DIV><DIV ID='popupResults'> </DIV>";
    $("#popup").html(info);
}
function clickMed()
{
    uuid=$(this).attr('uuid');
    setupMedSearch(uuid);
    $("#popup").show();
    $("#txtMedSearch").focus();

}
function keypressMed()
{
    inputString=this.value;
    sectionID=$(this).attr("uuid");
    if(inputString.length==0)
    {
     $('#popupDiv').hide();
    }
    else
    {
        $.post("/openemr/library/doctrine/ui/dictionaryLookup.php", {searchString: ""+inputString+"",context: "med", className: "medList", maxRes: "20" }, function(data){
            if(data.length >0) {
                $('#popupResults').html(data);
                $('#popupResults').attr("ProblemSection",sectionID);
                }
        })
    }
}
function registerProblemEvents()
{
    $("#txtNewProblem").live({keyup: keypressProblem});

    $("tr.problemItem").live({mouseover: function() {$(this).addClass('Highlight');} ,mouseout: function() {$(this).removeClass('Highlight');}, click: addProblem});

    $("input[type='button'][entrytype='Problem'][value='details']").live({click: clickProbDetails});

    $("input[type='button'][entrytype='Problem'][value='med']").live({click: clickMed});
    $("#txtMedSearch").live({keyup: keypressMed});
}