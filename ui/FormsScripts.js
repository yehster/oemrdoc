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

function displayPhysicalExam()
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
    observationMetaDataUUID=this.id;
    observationText=$(this).attr("ObsText");
    radioValue = $(this).find("input:radio:checked").val();

    if(radioValue!=undefined)
    {
        $.post("/openemr/library/doctrine/interface/manageEntry.php",
           {
                parentEntryUUID: ""+parentEntryUUID+"",
                metadataUUID: ""+observationMetaDataUUID+"",
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

function registerFormEvents()
{
    $("tr.ObservationTable").live({click: updateObservation});
}