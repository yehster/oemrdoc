function getAttrForElem(elem,attrName)
{

    if($(elem).attr(attrName)!=undefined)
    {
        return($(elem).attr(attrName));
    }
    else
    {
        return $(elem).parents('['+attrName+']').first().attr(attrName);
    }
}

function updateNominative()
{
    nomText=$(this).attr("NomText");
    parentEntryUUID = getAttrForElem(this,"sectionuuid");
    vocabID=$(this).attr("code");
    classification=$(this).attr("classification");
    nominativeUUID=$(this).attr("nominativeUUID");


    checkValue = $(this).parent().find("input:checkbox:checked");
    checkBox =$(this).find("input:checkbox");
    if(checkBox.attr("disabled")=="disabled")
    {
        return;
    }

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
                    if(classification=="exclusive")
                        {
                        }
            }

        );
   

}

function registerNominativeEvents()
{
    $("input[type='checkbox'].OptionRow").live({change: updateNominative});
}