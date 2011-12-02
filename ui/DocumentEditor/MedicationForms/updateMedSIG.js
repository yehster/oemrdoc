/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
function sigTextFocus()
{
    ShowStatusMessage($(this).attr("FUNC"));
    
}

function sigTextBlur()
{
    ShowStatusMessage("BLUR:"+$(this).attr("FUNC"));
    parent=$(this).parents("section.SIGInput");
    medEntry=$(this).parents("section[entrytype='MedicationEntry']");
    value=$(this).val();
    attribute=$(this).attr("func");
    medSIGUUID=$(this).parents("div[entrytype='MedicationSIG']").attr("uuid");
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
            ShowStatusMessage(data);
         }
    )
}
function registerUpdateSIGMedsEvents()
{
    $("#main").on({
                    focus: sigTextFocus,
                    blur: sigTextBlur
                  },
                "div[entrytype='MedicationSIG'] input[type='text']");
}