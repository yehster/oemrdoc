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

function updateNominative()
{
    nomText=$(this).attr("NomText");

    parentEntryUUID = getAttrForElem(this,"sectionuuid");
    vocabID=$(this).attr("code");
    checkValue = $(this).find("input:checkbox:checked");
    nominativeUUID="";

    if(checkValue.length==1)
    {
        task="update";
    }
    else
    {
        task="delete";
    }
        $.post("/openemr/library/doctrine/interface/manageEntry.php",
           {
                parentEntryUUID: ""+parentEntryUUID+"",
                vocabID: ""+vocabID+"",
                nominativeUUID: ""+nominativeUUID+"",
                EntryType: "nominative",
                task: ""+task+"",
                content: ""+nomText+"",
                refresh: "YES"
            },
            function(data) {
                    idText = "#" + parentEntryUUID;
                    $(idText).replaceWith(data) ;
                    $(idText).removeClass('hidden');
            }

        );
   

}

function registerNominativeEvents()
{
    $("tr.OptionRow").live({click: updateNominative});
}