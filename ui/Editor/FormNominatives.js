function updateNominative()
{
    nomText=$(this).attr("NomText");
    window.alert(nomText);

    parentEntryUUID = getAttrForElem(this,"sectionid");
    observationID = $(this).attr("uuid");
    vocabID=$(this).attr("code");
    window.alert(observationText);
    checkValue = $(this).find("input");
    
    window.alert(checkValue.length);

    if(radioValue!=undefined)
    {
        $.post("/openemr/library/doctrine/interface/manageEntry.php",
           {
                parentEntryUUID: ""+parentEntryUUID+"",
                vocabID: ""+vocabID+"",
                observationUUID: ""+observationID+"",
                EntryType: "nominative",
                task: "update",
                content: ""+NomText+"",
                refresh: "YES"
            },
            function(data) {
                    idText = "#" + parentEntryUUID;
                    $(idText).replaceWith(data) ;
                    $(idText).removeClass('hidden');
            }

        );
    }

}

function registerNominativeEvents()
{
    $("tr.OptionRow").live({click: updateNominative});
}