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
    sectionUUID = getAttrForElem(this,"sectionid");
    observationUUID=this.id;
    radioValue = $(this).find("input:radio:checked").val();
}

function registerFormEvents()
{
    $("tr.ObservationTable").live({click: updateObservation});
}