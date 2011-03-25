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

function displayForm()
{
    secUUID = $(this).attr('uuid');
    $.post("/openemr/library/doctrine/ui/FormGenerator.php",{docEntryUUID: ""+secUUID+""},
        function(data) {$("#popup").html(data)}
    );

$("#popup").show();

}

function updateObservation()
{
    parentEntryUUID = getAttrForElem(this,"sectionid");
    observationID = $(this).attr("observationid");
    vocabID=this.id;
    observationText=$(this).attr("ObsText");
    radioValue = $(this).find("input:radio:checked").val();

    if(radioValue!=undefined)
    {
        $.post("/openemr/library/doctrine/interface/manageEntry.php",
           {
                parentEntryUUID: ""+parentEntryUUID+"",
                vocabID: ""+vocabID+"",
                observationUUID: ""+observationID+"",
                EntryType: "observation",
                task: "update",
                val: ""+radioValue+"",
                content: ""+observationText+"",
                refresh: "YES"
            },
            function(data) {
                    idText = "#" + parentEntryUUID;
                    $(idText).replaceWith(data) ;
                    $(idText).removeClass('hidden');
            }

        )
    }

}


function closePopup()
{
    $("#popup").hide();
}

function registerControlEvents()
{

        $("tr.ObservationTable").live({click: updateObservation});

        $("input[type='button']").live({click: displayForm});
        $("#finishPE").live({click: closePopup});



        $("#popup").hide();
}
    window.onload= registerControlEvents;