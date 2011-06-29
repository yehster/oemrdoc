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
    $("#medSearch tr").mouseover(function(){$(this).addClass("highlight")});
    $("#medSearch tr").mouseout(function(){$(this).removeClass("highlight")});
    $("#medSearch tr td").click(clickDrug);
    $("#medSearch tr td[tty='SCDF']").click(clickDrug);
    $("#medLoading").attr("hidden",true);

}

function clickDrug()
{
    rxcui=$(this).attr("rxcui");
    rxaui=$(this).attr("rxaui");
    tty=$(this).attr("tty");
    drugStr=$(this).text();
    if((tty=="SCD") || (tty=="SBD"))
    {
        parentUUID=$("#medLookupDialog").attr("entryUUID");
        $("#medLoading").attr("hidden",false);
        $.post("../../interface/manageMedication.php",
            {
                parentUUID: ""+ parentUUID + "",
                text: ""+drugStr+"",
                rxcui: ""+rxcui+"",
                rxaui: ""+rxaui+"",
                task: "create",
                refresh: "YES"
            },
            function(data)
            {
                refreshEntry(parentUUID,data);
                $("#medLoading").attr("hidden",true);
                clearAndHideDialogMed()
            }
        );
    }
    else
        {
    if((tty=="SCDF") || (tty=="SBDF"))
    {
            
    }
    else
    {
        $("#medLoading").attr("hidden",false);
        $.post("../Dictionary/lookupMedications.php",
        {
            rxcui: ""+rxcui+"",
            rxaui: ""+rxaui+"",
            task: "MEDSEMANTIC"
        },
        processResults
        
        );            
    }
            
        }
    
}
function lookupMed(searchString)
{
    $("#medLoading").attr("hidden",false);
    $.post("../Dictionary/lookupMedications.php",
        {
            searchString: ""+searchString+"",
            task: "MEDSEARCH"
        },
        processResults
        
    );
}

var t;
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