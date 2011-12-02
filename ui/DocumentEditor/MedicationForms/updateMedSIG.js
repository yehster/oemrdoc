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
    medEntry=$(this).parents("[entrytype='MedicationEntry']");
    value=$(this).val();
    attribute=$(this).attr("func");
    medSIGUUID=$(this).parents("div[entrytype='MedicationSIG']").attr("uuid");
    parentUUID=medEntry.attr("uuid");

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
function displayMedSigSelector()
{
    divInfo=$(this).find(".sigInfoSelector");
    divInfo.show();
    divInfo.html("Hello World!");
}
function hideMedSigSelector()
{
    divInfo=$(this).find(".sigInfoSelector");
    divInfo.hide();
    
}
function registerUpdateSIGMedsEvents()
{
    $("#main").on({
                    focus: sigTextFocus,
                    blur: sigTextBlur
                  },
                "div[entrytype='MedicationSIG'] input[type='text']");
    $("#main").on({
        mouseover: displayMedSigSelector,
        mouseout: hideMedSigSelector
            
    },
    "div[entrytype='MedicationSIG']"
    );            
}