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

function displayForm()
{
    secUUID = $(this).attr('uuid');
    $.post("/openemr/library/doctrine/ui/Editor/FormGenerator.php",{docEntryUUID: ""+secUUID+""},
        function(data) {
            $("#popup").html(data);
            $("#popup").show();
        }
    );


}
function refreshSection(id)
{
    $.post("/openemr/library/doctrine/interface/manageEntry.php",{parentEntryUUID:""+id+"",refresh: "YES"},function (data){
       idText = "#" + id;
       $(idText).replaceWith(data) ;
       $(idText).removeClass("hidden");
    });
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

function createNarrative()
{
    parentEntryUUID=$(this).attr("sectionuuid");
    narText=this.value;
    var textArea=$(this);
    if(narText!=""){
           $.post("/openemr/library/doctrine/interface/manageEntry.php",
           {
                parentEntryUUID: ""+parentEntryUUID+"",
                EntryType: "narrative",
                task: "create",
                content: ""+narText+""
            },
            function(data) {
                // TODO: update the text box attributes so that changes go to the existing entry and we don't keep creating new ones
                uuid=data;
                refreshSection(parentEntryUUID);
                textArea.attr("class","FormNarrative");
                textArea.attr("id",uuid);
            }

        )
    }
}


function updateFormNarrative()
{
    parentEntryUUID=$(this).attr("sectionuuid");
    narText=this.value;
    uuid=$(this).attr("id");
    $.post("/openemr/library/doctrine/interface/manageEntry.php",
    {
        docEntryUUID: ""+uuid+"",
        parentEntryUUID: ""+parentEntryUUID+"",
        EntryType: "narrative",
        task: "update",
        content: ""+narText+"",
        refresh: "YES"
    },
        function(data) {
                    idText = "#" + parentEntryUUID;
                    $(idText).replaceWith(data) ;
            }

        )

}

function updateNarrative()
{
    uuid=$(this).attr("uuid");
    narText=this.value;
    $.post("/openemr/library/doctrine/interface/manageEntry.php",
    {
        docEntryUUID: ""+uuid+"",
        EntryType: "narrative",
        task: "update",
        content: ""+narText+""
    } )
    
}

function closePopup()
{
    $("#popup").hide();
}


function deleteEntry()
{
    uuid=$(this).attr("uuid");
    $.post("/openemr/library/doctrine/interface/deleteEntry.php",
    {
        docEntryUUID: ""+uuid+"",
        refresh: "YES"
    },
    function(data) {
        pos=data.indexOf("<",0);
        uuid=data.substr(0,pos);
        id="#"+uuid;
        $(id).html(data.substr(pos));
    }
    );
}

function registerControlEvents()
{
        $("#popup").hide();

        $("tr.ObservationTable").live({click: updateObservation});
        $("textarea.newNarrative").live({blur: createNarrative});
        $("textarea.FormNarrative").live({blur: updateFormNarrative});
        $("textarea[entrytype='Narrative']").live({blur: updateNarrative});


        $("input[type='button'][value='details'][entrytype='Section']").live({click: displayForm});
        $(".ClosePopup").live({click: closePopup});


        $("input[type='button'][value='del']").live({click: deleteEntry});


        registerProblemEvents();
        registerMedManagementEvents();
        registerNominativeEvents();
        registerReviewEvents();
        registerFormQuantEvents();
        $("#popup").hide();
        $("#review").hide();
}
    window.onload= registerControlEvents;