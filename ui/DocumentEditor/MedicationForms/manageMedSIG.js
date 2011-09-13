function startAddMedSIG()
{
    entryUUID=$(this).attr("entryUUID");
    medElement=$(this).siblings("[type='LABEL']");
    AddMedSIGWithUUID(medElement.text(),entryUUID);
}

function AddMedSIGWithUUID(medName,MedUUID)
{
    $("#medSIGLabel").html(medName);
    $("#medSIGDialog").attr("parentEntryUUID",MedUUID);
    $("#medSIGDialog").show();
}

function cancelMedSIG()
{
    $("#medSIGDialog").hide();
}


function registerManageSIGMedsEvents()
{
    $("button[func='SIG']").live({click: startAddMedSIG});
    $("#cancelMedSIG").click(cancelMedSIG);
}