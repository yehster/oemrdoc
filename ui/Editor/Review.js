function updateCurrent(uuid)
{

    $("#reviewcurrent").children().remove();
    $.post("/openemr/library/doctrine/interface/manageEntry.php",{parentEntryUUID:""+uuid+"",refresh: "YES"},
    function (data){
                $("#reviewcurrent").append(data);
                $("#reviewcurrent").find("input:button[value='review'][entrytype='Section']").first().remove();
        }
    );
}

function updateHistory(type,uuid)
{
    if(type=="history")
    {
            params = {historicalSectionUUID: ""+uuid+""};
    }
    else if(type=="current")
        {
            params = {currentSectionUUID: ""+uuid+""};
        }
    $("#reviewhistory").children().remove();
    $.post("/openemr/library/doctrine/ui/Review/ReviewSection.php", params,
    function(data) {

        $("#reviewhistory").append(data);
        $("#reviewhistory").append("<INPUT TYPE='BUTTON' value='copy'/>")
    }
    );
}

function reviewSection()
{
            $("#review").show();
            sectionUUID=$(this).attr("uuid");
            $("#review").attr("sectionUUID",sectionUUID);
            updateCurrent(sectionUUID);
            updateHistory("current",sectionUUID)
}

function closeReview()
{
    $("#reviewhistory").children().remove();
    $("#reviewcurrent").children().remove();
            $("#review").hide();
            sectionUUID= $("#review").attr("sectionUUID");
            refreshSection(sectionUUID);

}
function updateReviewCheck()
{
    if($(this).attr("checked"))
    {
        parChecks=$(this).parents("div[reviewtype]").children("input:checkbox[reviewtype]"); //.children("input[type='CHECKBOX'][reviewtype]");
        parChecks.attr("checked","checked");
    }
    else
    {
        otherChecks= $(this).siblings().find("input:checkbox[reviewtype]");
        otherChecks.removeAttr("checked");
    }
}
function clickLabel()
{
    $(this).siblings("input:checkbox[reviewtype]").click().change();
}

function clickCopy()
{
    var output = "";

    $("#reviewhistory").find("input:checkbox[reviewtype]:checked").each(function(index)
        {
            output += $(this).attr('uuid')+'|'+$(this).attr('depth')+'|'+index+"\n";
        });
    targetUUID = $("#reviewcurrent").find("div[uuid]").attr("uuid");
    params = {
        targetUUID: ""+targetUUID+"",
        copylist: ""+output+"",
        refresh: "YES"
    };
    $.post("/openemr/library/doctrine/ui/Review/copyEntries.php", params,
    function(data) {

        //window.alert(data);
        $("#"+targetUUID).replaceWith(data);
    }

    );
}

function registerReviewEvents()
{
    $("input:button[value='review']").live({click: reviewSection});
    $(".CloseReview").live({click: closeReview});
    $("input:checkbox[reviewtype]").live({change: updateReviewCheck});
    $("span[reviewtype]").live({click: clickLabel});
    $("#reviewhistory>input:button[value='copy']").live({click: clickCopy});
}
