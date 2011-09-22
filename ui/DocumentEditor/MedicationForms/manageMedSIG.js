function startAddMedSIG()
{
    entryUUID=$(this).attr("entryUUID");
    medElement=$(this).siblings("[type='LABEL']");
    AddMedSIGWithUUID(medElement.text(),entryUUID);
}

function setSIGQtyFromTable()
{
     $("input.qty").val($(this).text())
     $("input.qty").blur();
}

function setSIGScheduleFromTable()
{
     $("input.schedule").val($(this).text())
     $("input.schedule").blur();  
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
            
            $("#medSIGDialog table.SIGQtyOptions td").click(setSIGQtyFromTable);
            $("#medSIGDialog table.SIGScheduleOptions td").click(setSIGScheduleFromTable);
            $("#medSIGDialog tr.SIGControls table td").mouseover(function(){$(this).addClass("highlight")});
            $("#medSIGDialog tr.SIGControls table td").mouseout(function(){$(this).removeClass("highlight")});
            
        }
    );

}

function closeMedSIG()
{
    $("#medSIGDialog").hide();
}

function saveSIGInfo()
{
    parent=$(this).parents("section.SIGInput");
    medEntry=$(this).parents("section[entrytype='MedicationEntry']");
    value=$(this).val();
    attribute=$(this).attr("class");
    medSIGUUID=$(this).parents("tr[entryuuid]").attr("entryuuid");
    parentUUID=medEntry.attr("entryuuid");

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