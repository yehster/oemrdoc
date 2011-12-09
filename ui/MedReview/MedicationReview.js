function updateMedStatus()
{
    func=$(this).attr("func");
    row=$(this).parents("tr[medUUID]").eq(0);
    medUUID=row.attr("medUUID");
    parentUUID=row.find(".existingMeds").attr("sectionuuid");
//    window.alert(row.attr("medUUID")+":"+func);
    $.post("/openemr/library/doctrine/interface/med/updateStatus.php",
        {
            medUUID: medUUID,
            parentUUID: parentUUID,
            task: func
        },
        function(data)
        {
            window.alert(data);
        }
    );
}

function setupReviewEvents()
{
 $(".existingMeds tr button").click(updateMedStatus);
}

setupReviewEvents();