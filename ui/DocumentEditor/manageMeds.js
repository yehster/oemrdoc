function clearAndHideDialogMed()
{
    $("#medLookupDialog").attr("hidden",true);
    $("#txtMedication").val("");
    $("#medSearch").html("");
}    

function showMedDialog()
{
    $("#medLookupDialog").attr("hidden",false);
    $("#txtMedication").select();
    
}
    
function startAddMed()
{
    showMedDialog();
    entryUUID=$(this).attr("entryUUID");
    $("#medLookupDialog").attr("entryUUID",entryUUID);

}

function registerManageMedsEvents()
{
    $("button[func='MED']").live({click: startAddMed});
    $("#cancelMed").click(clearAndHideDialogMed);
}