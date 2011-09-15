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
            $("#medSIGDialog .closeSIG").click(closeMedSIG);
            $("#medSIGDialog").show();
        }
    );

}

function closeMedSIG()
{
    $("#medSIGDialog").hide();
}

function saveSIGInfo()
{
    parent=$(this).parent("section.SIGInput");
    medEntry=parent.parent("section[entrytype]");
    value=$(this).val();
    attribute=$(this).attr("class");
    medSIGUUID=parent.attr("entryuuid");
    parentUUID=medEntry.attr("entryuuid");
    window.alert(medSIGUUID+":"+parentUUID);
    params=    {
        task:   "update",
        
        parentUUID: ""+parentUUID+"",
        medSIGUUID: ""+medSIGUUID+"",
        refresh: "YES"
    };
    params[attribute]=value;
    $.post("../../interface/med/manageMedSIG.php",
        params,
         function(data)
         {
             refreshEntry(entryUUID,data);
         }
    )
    
}

function registerManageSIGMedsEvents()
{
    $("button[func='SIG']").live({click: startAddMedSIG});
    $("section.SIGInput input").live({blur: saveSIGInfo});

}