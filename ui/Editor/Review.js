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
    $.post("/openemr/library/doctrine/ui/Review/ReviewSection.php", params,
    function(data) {
        $("#reviewhistory").children().remove();
        $("#reviewhistory").append(data);
        
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
            $("#reviewcurrent").children().remove();
            $("#review").hide();
            sectionUUID= $("#review").attr("sectionUUID");
            refreshSection(sectionUUID);

}
function registerReviewEvents()
{
    $("input[type='button'][value='review']").live({click: reviewSection});
    $(".CloseReview").live({click: closeReview});
}
