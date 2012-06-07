function clickDrug()
{
    var rxcui=$(this).attr("rxcui");
    var rxaui=$(this).attr("rxaui");
    var tty=$(this).attr("tty");
    var drugStr=$(this).text();
    debugMessage(drugStr);
    var parentUUID=$(this).parents(".medication_chooser").attr("entryuuid");
    if((tty=="SCD") || (tty=="SBD"))
    {
        $.post("/openemr/library/doctrine/interface/manageMedication.php",
            {
                parentUUID: ""+ parentUUID + "",
                text: ""+drugStr+"",
                rxcui: ""+rxcui+"",
                rxaui: ""+rxaui+"",
                task: "create",
                refresh: "doc"
            },
            function(data)
            {
                refreshSection(data);
            },
            "json"
        );
        
    }
    else
    {
        if((tty=="SCDF") || (tty=="SBDF"))
        {

        }
        else
        {
            var results=$(this).parents(".medication_chooser:first").find(".medResults");
            $.post("/openemr/library/doctrine/ui/Dictionary/lookupMedications.php",
            {
                rxcui: ""+rxcui+"",
                rxaui: ""+rxaui+"",
                task: "MEDSEMANTIC"
            },
            function(data)
            {
                results.html(data);
                results.find("td").on({click: clickDrug});            
            }

            );            
        }
    }

}

function lookupMeds()
{
    debugMessage("lookupMeds:"+$(this).val());
    var results=$(this).parents(".medication_chooser:first").find(".medResults");
    searchString=$(this).val();
     $.post("/openemr/library/doctrine/ui/Dictionary/lookupMedications.php",
        {
            searchString: searchString,
            task: "MEDSEARCH"
        },
        function(data)
        {
            results.html(data);
            results.find("td").on({click: clickDrug});
        }        
    );
}

function closeMed()
{
    $(this).parents(".medication_chooser:first").hide();
}
function chooseMed(parent,uuid)
{
    var display=parent.find(".medication_chooser");
    var medInput;
    if(display.length==0)
        {
            display=$("<div></div>");
            display.attr("entryuuid",uuid);
            display.addClass("medication_chooser");
            parent.prepend(display);
            
            var controls=$("<div></div>");
            controls.addClass("controls");
            display.append(controls);

            medInput=$("<input type='text'/>");
            medInput.on({keyup: lookupMeds});
            controls.append(medInput);


            var close=$("<span>&#x2713</span>")
            close.addClass("document_iconic");
            close.on({click: closeMed });            
            controls.append(close);
            
            var results=$("<div></div>");
            results.addClass("medResults");
            display.append(results);
        }
    else
        {
            medInput=display.find("input[type='text']:first");
        }
    display.show();
    medInput.select();
     
}

function addMed()
{
    var entryuuid=$(this).attr("entryUUID");
    var parent=$(this).parent("[uuid]");
    chooseMed(parent,entryuuid);
}
function addMedControlAfterLabel(idx,elem)
{
    var control=$("<span>&#xe01e</span>");
    var di=new doctrineInfo(elem);
    control.attr("entryUUID",di.uuid);    
    control.addClass("document_iconic");
    $(elem).after(control);
    control.on({click: addMed});
}
function setupMedicationControls(parent)
{
    var problemLabels = parent.find("span.problem[uuid] > .problemLabel");
    problemLabels.each(addMedControlAfterLabel);
}