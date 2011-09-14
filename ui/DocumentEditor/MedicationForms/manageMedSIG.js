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
    $.post("MedicationForms/displayMedSIGdialog.php",
        {
            medEntryUUID: ""+MedUUID+""
        },
        function(data)
        {
            $("#medSIGDialog").html(data);
        }
    );
    $("#medSIGDialog").show();
}

function cancelMedSIG()
{
    $("#medSIGDialog").hide();
}


function registerManageSIGMedsEvents()
{
    $("button[func='SIG']").live({click: startAddMedSIG});

}