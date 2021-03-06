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

function loadExistingMeds()
{
    
    $.post("../Widgets/MedicationsWidget.php",
        {
            patientID:patID
        }
        ,function(data)
        {
            $("#existingMeds").html(data);
            registerExistingMedsEvents();
        }
    );
    
    
}

function startAddMed()
{
    showMedDialog();
    entryUUID=$(this).attr("entryUUID");
    $("#medLookupDialog").attr("entryUUID",entryUUID);
    $("#medProblemLabel").text($(this).siblings("span").text());
    loadExistingMeds();
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
    $("#medLookupDialog").attr("prevTime",currentTime);
    $("#medLookupDialog .back").attr("lastSearch",searchString);
    $.post("../Dictionary/lookupMedications.php",
        {
            searchString: ""+searchString+"",
            task: "MEDSEARCH"
        },
        function(data)
        {
            prevTime=$("#medLookupDialog").attr("prevTime");
            if((prevTime==null) || prevTime <= currentTime)
                {
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


function medBack()
{
    lookupMed($(this).attr("lastSearch"));
}

function registerManageMedsEvents()
{
    $("button[func='MED']").live({click: startAddMed});
    $("#cancelMed").click(clearAndHideDialogMed);
    $("#useMedText").click(useMedText);
    $("#txtMedication").keypress(txtMedKeyPress);
    $("#medLookupDialog .back").click(medBack);
        
}