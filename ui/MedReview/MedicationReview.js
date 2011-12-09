function updateMedStatus()
{
    func=$(this).attr("func");
    row=$(this).parents("tr[medUUID]").eq(0);
    medUUID=row.attr("medUUID");
    parentUUID=row.parents(".existingMeds").attr("sectionuuid");
    window.alert(parentUUID+":"+func);
    $.post("/openemr/library/doctrine/interface/med/updateStatus.php",
        {
            medUUID: medUUID,
            parentUUID: parentUUID,
            task: func,
            refresh: "YES"
        },
        function(data)
        {
//            window.alert(parentUUID);
            refreshEntry(parentUUID,data);
        }
    );
}

function setupReviewEvents()
{
 $(".existingMeds tr button").click(updateMedStatus);
}

setupReviewEvents();