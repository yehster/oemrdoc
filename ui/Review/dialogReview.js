function closeReview()
{
    $("#dialogReview").attr("hidden",true);
}

function updateReviewCheckBoxes()
{
    div=$(this).parent("[uuid]");
    if(div.length==0)
        {
            div=$(this).parent().parent("[uuid]");
        }
    otherCB=div.find("[uuid]").find("input:checkbox[func='review']");
    otherCB.attr("checked",$(this).is(":checked"));
    if($(this).is(":checked"))
        {
            div.parents("[uuid]").children("input:checkbox[func='review']").attr("checked",true);
            div.parents("[uuid]").children().children("input:checkbox[func='review']").attr("checked",true);
        }
    
}
function navigateReview()
{
    sections=$("#dialogReview").attr("sections")
    relDoc=$(this).attr("relDoc");
    direction=$(this).attr("direction");
    updateReviewHistory(relDoc,sections,direction);
}
function addCheckBoxReview()
{
    if($(this).children().length)
        {
           elem=$(this).children(":first-child");
        }
        else
        {
           elem=$(this);     
        }
    elem.prepend("<INPUT TYPE='CHECKBOX' func='review'>");
    cb=elem.find("[func='review']").attr("reviewuuid",$(this).attr("uuid"));
    cb.attr("depth",$(this).parents("[uuid]").length+1);
    
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
            $("button[reldoc]").click(navigateReview)
            uuidEntries=
            $("#reviewHistory").find("[uuid]").each(
                addCheckBoxReview
            );
            $("input:checkbox[func='review']").change(updateReviewCheckBoxes);

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

function copyEntries()
{
    var copylist="";
    $("#reviewHistory").find("input:checkbox[func='review']:checked").each(function(index)
        {
            copylist += $(this).attr('reviewuuid')+'|'+$(this).attr('depth')+'|'+index+"\n";
        });
    entryUUID=$('#dialogReview').attr("entryuuid");
    $.post("../../interface/copyEntries.php",
        {
            parentUUID: ""+entryUUID+"",
            task: "copy",
            copylist: ""+copylist+"",
            refresh: "YES"
        },
        function(data)
        {
            refreshEntry(entryUUID,data);
        }
    );
}

function registerDialogReviewEvents()
{
    $("#closeReview").click(closeReview);
    $("#copyEntries").click(copyEntries);
    $("button[func='REVIEW']").live({click: showReviewDialog});
}