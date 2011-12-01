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
}
function registerUpdateSIGMedsEvents()
{
    $("#main").on({
                    focus: sigTextFocus,
                    blur: sigTextBlur
                  },
                "div[entrytype='MedicationSIG'] input[type='text']");
}