function updateDateOfService()
{
    dateStr = $(this).val();
    docUUID=$("body").attr("docUUID");
    $.post("../../interface/updateDateOfService.php",
        {
            docUUID: ""+docUUID+"",
            dateofservice: ""+dateStr+""
        }
    )
}

function registerDocumentInfoEvents()
{
    $("#txtDateOfService").blur(updateDateOfService);
}