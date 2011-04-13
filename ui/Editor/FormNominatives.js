
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
        sibs=$(this).parents("tr").siblings("tr.OptionRow");
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

                    if(task=="update")
                    {
                        if(classification=="exclusive")
                        {
                            sibs.find("input:checkbox:checked").removeAttr("checked").change();
                            
                        }
                        else
                        {
                            sibs.find("input[classification='exclusive']:checked").removeAttr("checked").change()
                        }
                    }
            }

        );
   

}

function registerNominativeEvents()
{
    $("input[type='checkbox'].OptionRow").live({change: updateNominative});
}