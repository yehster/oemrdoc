/* 
Scripts for manipulating Forms (ROS/Physical Exam) in the document editor
 */
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
    secUUID = this.id;
    $.post("/openemr/library/doctrine/ui/FormGenerator.php",{docEntryUUID: ""+secUUID+""},
        function(data) {$("#popupDiv").html(data)}
    );

$("#popupDiv").show();



}

function finishPhysicalExam()
{
    $("#popupDiv").hide();

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
            }
            
        )
    }

}
function createNarrative()
{
    parentEntryUUID=$(this).attr("sectionuuid");
    narText=this.value;
    if(narText!=""){
           $.post("/openemr/library/doctrine/interface/manageEntry.php",
           {
                parentEntryUUID: ""+parentEntryUUID+"",
                EntryType: "narrative",
                task: "create",
                content: ""+narText+""
            },
            function(data) {
                uuid=data;
                refreshSection(parentEntryUUID);
            }
            
        )
        $(this).attr("class","FormNarrative");
        $(this).attr("id",uuid);
    }
}

function updateNarrative()
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

function registerFormEvents()
{
    $("tr.ObservationTable").live({click: updateObservation});
    $("textarea.newNarrative").live({blur: createNarrative});
    $("textarea.FormNarrative").live({blur: updateNarrative});
}