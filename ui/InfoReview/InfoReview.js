function updateInfoReviewSection(pid,sectionUUID)
{
    $.post("../InfoReview/InfoReview.php",
            {
                patientID:pid,
                sectionUUID: sectionUUID
            },
            function(data)
            {
                $(".infoReview[infoUUID='"+sectionUUID+"']").html(data);
            }
        );
}