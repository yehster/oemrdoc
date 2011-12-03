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
    updateSigFromElement(this);
}

function updateSigFromElement(elem)
{
    parent=$(elem).parents("section.SIGInput");
    medEntry=$(elem).parents("[entrytype='MedicationEntry']");
    value=$(elem).val();
    attribute=$(elem).attr("func");
    medSIGUUID=$(elem).parents("div[entrytype='MedicationSIG']").attr("uuid");
    parentUUID=medEntry.attr("uuid");

    params=    {
        task:   "update",
        
        parentUUID: ""+parentUUID+"",
        medSIGUUID: ""+medSIGUUID+""
    };
    params[attribute]=value;
    $.post("../../interface/med/manageMedSIG.php",
        params,
         function(data)
         {
             //refreshEntry(parentUUID,data);
         }
    )
    
}


function selectorQty()
{
    value=$(this).text();
    sigDIV=$(this).parents("[entrytype='MedicationSIG']");
    qtyCtrl=sigDIV.find("input[func='qty']");
    qtyCtrl.val(value);
    updateSigFromElement(qtyCtrl);
}


function selectorSchedule()
{
    value=$(this).text();
    sigDIV=$(this).parents("[entrytype='MedicationSIG']");
    qtyCtrl=sigDIV.find("input[func='schedule']");
    qtyCtrl.val(value);
    updateSigFromElement(qtyCtrl);
}
function displayMedSigSelector()
{
    divInfo=$(this).find(".sigInfoSelector");
    divInfo.show();
    medSIGUUID=$(this).attr("uuid");
    $.post("MedicationForms/medSigOptions.php",
            {
                medSIGUUID: medSIGUUID               
            },
            function(data)
            {
              divInfo.html(data);
              divInfo.find("div[func='qty'] td").click(selectorQty);
              divInfo.find("div[func='schedule'] td").click(selectorSchedule);
            }
            );
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
        focus: displayMedSigSelector
            
    },
    "div[entrytype='MedicationSIG']"
    );            
}