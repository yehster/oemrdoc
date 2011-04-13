function setupMedSearch(uuid)
{
    info="<INPUT TYPE='BUTTON' value='close' class='closePopup'/><DIV><INPUT TYPE='TEXT' ID='txtMedSearch' uuid='"+uuid+"'/></DIV><DIV ID='popupResults'></DIV>";
    $("#popup").html(info);
}
function clickMedButton()
{
    uuid=$(this).attr('uuid');
    setupMedSearch(uuid);
    $("#popup").show();
    $("#txtMedSearch").focus();

}

function searchMed(inputString)
{
    if(inputString.length==0)
    {
//     $('#popupDiv').hide();
    }
    else
    {
            $.post("/openemr/library/doctrine/ui/dictionaryLookup.php",
            {searchString: ""+inputString+"",
                context: "med",
                className: "medList",
                maxRes: "20" }, function(data){
                if(data.length >0) {
                    $('#popupResults').html(data);
                    }
            })
        }

}

var medTimer;
function keypressMed()
{
    
    inputString=this.value;
    clearTimeout(medTimer);
        problemTimer = setTimeout(
        function(){searchMed(inputString)},
        150
    );
}

function clickDrug()
{
    rxcui=$(this).attr("rxcui");
    rxaui=$(this).attr("rxaui");
    tty=$(this).attr("tty");

    parentEntryUUID= $("#txtMedSearch").attr('uuid'); // get section UUID
    textArea=$("#txtMedSearch");
    drugStr=$(this).text();
 
    if((tty=="SCD") || (tty=="SBD"))
    {
        $.post("/openemr/library/doctrine/interface/manageEntry.php",
        {   parentEntryUUID: ""+ parentEntryUUID + "",
            rxcui: ""+rxcui+"",
            rxaui: ""+rxaui+"",
            content: ""+drugStr+"",
            task: 'create',
            EntryType: 'med',
            refresh: 'YES'},
        function(data){
        if(data.indexOf("error:",0)==-1)
                    {
                        $('#popup').hide();
                        $('#popup').html("");
                        // refresh the section
                        divID="#"+parentEntryUUID;
                        $(divID).html(data);

                    }
                    else
                    {
                        window.alert(data);
                    }
      });

    }
    else if((tty=="SCDF") || (tty=="SBDF"))
    {
        textArea.val(drugStr);
    }
    else
    {
        textArea.val(drugStr);
        $.post("/openemr/library/doctrine/ui/dictionaryLookup.php", {rxcui: ""+rxcui+"",rxaui: ""+rxaui+"",context: "medSemantic", className: "medList", maxRes: "20" }, function(data){
            if(data.length >0) {
                $('#popupResults').html(data);
                }});
    }
}



function registerMedManagementEvents()
{
    $("input[type='button'][entrytype='Problem'][value='med']").live({click: clickMedButton});
    $("input[type='button'][entrytype='Section'][value='med']").live({click: clickMedButton});
    $("#txtMedSearch").live({keyup: keypressMed});

    $("td.medName").live({mouseover: function() {$(this).addClass('Highlight');} ,mouseout: function() {$(this).removeClass('Highlight');}, click: clickDrug});
}