function closeReview()
{
    $("#dialogReview").attr("hidden",true);
}

function updateReviewCheckBoxes()
{
    div=$(this).parent("div[reviewtype]");
    otherCB=div.find("div").find("input:checkbox[func='review']");
    otherCB.attr("checked",$(this).is(":checked"));
    if($(this).is(":checked"))
        {
            div.parents("div[reviewtype]").children("input:checkbox[func='review']").attr("checked",true);
        }
    
}
function navigateReview()
{
    sections=$("#dialogReview").attr("sections")
    relDoc=$(this).attr("relDoc");
    direction=$(this).attr("direction");
    updateReviewHistory(relDoc,sections,direction);
}

function updateReviewHistory(docUUID,sections,direction)
{
    curdocUUID= $("body").attr("docuuid");

    $.post("../Review/reviewHistorySections.php",
        {
            relDocUUID: ""+docUUID+"",
            direction:  ""+direction+"",
            sectionUUIDs: ""+sections+"",
            curDocUUID: ""+curdocUUID+""
        },
        function(data)
        {
            $("#reviewHistory").html(data);
            $("input:checkbox[func='review']").change(updateReviewCheckBoxes);
            $("button[reldoc]").click(navigateReview)
        }
    );
    
}
function showReviewDialog()
{
    $("#dialogReview").attr("hidden",false);
    sectionuuid=$(this).attr("entryuuid");
    $("#dialogReview").attr("sections",sectionuuid);
    $("#dialogReview").attr("entryuuid",sectionuuid);
    // setup History section of the dialog
    updateReviewHistory($("body").attr("docuuid"),sectionuuid,-1);
    
    
    // setup the editor section of the 
    $.post("../../interface/reviewSection.php",
    {
      task: "refresh",
      parentUUID: ""+sectionuuid+""  
    },
    function(data)
    {
        $("#reviewCurrent").html(data);
    }
    );
        
}
function registerDialogReviewEvents()
{
    $("#closeReview").click(closeReview);
    $("button[func='REVIEW']").live({click: showReviewDialog});
}