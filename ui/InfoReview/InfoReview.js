function updateInfoReviewSection(pid,sectionUUID)
{
    $.post("../InfoReview/InfoReview.php",
            {
                patientID:pid,
                sectionUUID: sectionUUID
            },
            function(data)
            {
                infoSection=$(".infoReview[infoReviewUUID='"+sectionUUID+"']");
                infoSection.html(data);
                registerInfoReviewEvents(infoSection);
            }
        );
}

function showInfoItem()
{
    $(this).find(".infoReviewControls").show();
}

function hideInfoItem()
{
    $(this).find(".infoReviewControls").hide();
}
function infoButtonClick()
{
    infoUUID=$(this).parent("[infoUUID]").attr("infoUUID");
    func=$(this).attr("func");
    sectionUUID=$(this).parents("[infoReviewUUID]").attr("infoReviewUUID");
    $.post("/openemr/library/doctrine/interface/info/updateInfoStatus.php",
        {
            infoUUID: infoUUID,
            parentUUID: sectionUUID,
            task:func,
            refresh: "YES"
        },
        function(data)
        {
            refreshEntry(sectionUUID,data);
        }
    );

    
}
function registerInfoReviewEvents(infoSection)
{
    infoSection.find(".infoItem").mouseover(showInfoItem);
    infoSection.find(".infoItem").mouseout(hideInfoItem);
    infoSection.find("button").click(infoButtonClick);
}