function clearAndHideDialogMed()
{
    $("#medLookupDialog").hide();
    $("#txtMedication").val("");
    $("#medSearch").html("");
}    

function showMedDialog()
{
    $("#medLookupDialog").show();
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
    $("#medLoading").hide();

}
function useMedText()
{
    rxcui="";
    rxaui="";
    tty="";
    drugStr=$("#txtMedication").val();
        parentUUID=$("#medLookupDialog").attr("entryUUID");
        $("#medLoading").show();
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
                $("#medLoading").hide();
                clearAndHideDialogMed()
            });
    clearAndHideDialogMed();
}
function clickDrug()
{
    rxcui=$(this).attr("rxcui");
    rxaui=$(this).attr("rxaui");
    tty=$(this).attr("tty");
    drugStr=$(this).text();
    $("#txtMedication").val(drugStr);
    if((tty=="SCD") || (tty=="SBD"))
    {
        parentUUID=$("#medLookupDialog").attr("entryUUID");
        $("#medLoading").show();
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
                $("#medLoading").hide();
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
        $("#medLoading").show();
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
    $("#medLoading").show();
    currentTime=(new Date()).getTime();
    $.post("../Dictionary/lookupMedications.php",
        {
            searchString: ""+searchString+"",
            task: "MEDSEARCH"
        },
        function(data)
        {
            prevTime=$("#medLookupDialog").attr("prevTime");
            if((prevTime==null) || prevTime < currentTime)
                {
                    $("#medLookupDialog").attr("prevTime",currentTime);
                    processResults(data);
                }
        }
        
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
    $("#useMedText").click(useMedText);
    $("#txtMedication").keypress(txtMedKeyPress);
}