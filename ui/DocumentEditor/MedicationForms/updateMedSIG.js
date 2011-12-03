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
    info=$(this).siblings(".sigInfoSelector");
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
    Ctrl=sigDIV.find("input[func='qty']");
    Ctrl.val(value);
    updateSigFromElement(Ctrl);
    Ctrl.focus();
}


function selectorSchedule()
{
    value=$(this).text();
    sigDIV=$(this).parents("[entrytype='MedicationSIG']");
    Ctrl=sigDIV.find("input[func='schedule']");
    Ctrl.val(value);
    updateSigFromElement(Ctrl);
    Ctrl.focus();
}
function displayMedSigSelector()
{
    divInfo=$(this).find(".sigInfoSelector");
    if(divInfo.html().length==0)
        {
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
                divInfo.find("td").on(
                    {
                        mouseover: function() {$(this).addClass("highlight");},
                        mouseout: function() {$(this).removeClass("highlight");}
                    });
                }
            );
        }
divInfo.show();
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
        focus: displayMedSigSelector,
        mouseout: hideMedSigSelector

    },
    "div[entrytype='MedicationSIG']"
    );            
}