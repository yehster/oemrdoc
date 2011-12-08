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
                task: ""
            },
            function(data)
            {
                ShowStatusMessage(data)
            }
            );
    
}
function registerExistingMedsEvents()
{
    $("#existingMeds li").click(existingMedClicked)
}