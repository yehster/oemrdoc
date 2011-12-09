function existingMedClicked()
{
    existingUUID=$(this).attr("entryUUID");
    parentUUID=$("#medLookupDialog").attr("entryUUID");
    addExisting(existingUUID,parentUUID);
}
function addExisting(existingUUID,parentUUID)
{
    $.post("../../interface/copyOrLink.php",
            {
                copySourceUUID: existingUUID,
                parentUUID: parentUUID,
                task: "",
                refresh: "YES"
            },
            function(data)
            {
                refreshEntry(parentUUID,data);
            }
            );
    
}
function registerExistingMedsEvents()
{
    $("#existingMeds li").click(existingMedClicked)
}

function updateExistingMedDisplay()
{
    $(".existingMeds").html("hello world!");
    sectionUUID=$(".existingMeds").attr("sectionUUID");
    $.post("../MedReview/MedicationReview.php",
        {
            patientID:patID,
            sectionUUID: sectionUUID
        },
        function(data)
        {
            $(".existingMeds").html(data);           
        }
    );
}