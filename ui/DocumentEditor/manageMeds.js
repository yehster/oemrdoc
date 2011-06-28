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
    $("#medProblemLabel").text($(this).siblings("span").text());
}
function processResults(data)
{
    $("#medSearch").html(data);
}
function lookupMed(searchString)
{
    $.post("../Dictionary/lookupMedications.php",
        {
            searchString: ""+searchString+""
        },
        processResults
        
    );
}

function txtMedKeyPress()
{
clearTimeout(t);
t=setTimeout(function() {
        searchString=$("#txtMedication").val();
        length=searchString.length;
        lookupMed(searchString);
    },200)
}


function registerManageMedsEvents()
{
    $("button[func='MED']").live({click: startAddMed});
    $("#cancelMed").click(clearAndHideDialogMed);
    $("#txtMedication").keypress(txtMedKeyPress);
}