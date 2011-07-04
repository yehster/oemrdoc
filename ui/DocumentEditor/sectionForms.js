function showFormDialog()
{
    $("#sectionForm").attr("hidden",false);
    entryUUID=$(this).attr("entryUUID");
    $("#sectionForm").attr("entryUUID",entryUUID);
    label=$(this).siblings(".LABEL").text();
    $("#sectionFormsHeader").html(label)
    $.post("../DocumentForms/generateForm.php",
        {
            entryUUID: ""+entryUUID+""
        },
        function(data)
        {
            $("#sectionFormsDisplay").html(data);
        }
    );
}
function hideFormDialog()
{
    $("#sectionForm").attr("hidden",true);    
}

function registerSectionFormsEvents()
{
    $("button[func='SHOWFORM']").live({click: showFormDialog});
    $("#closeSectionForm").click(hideFormDialog);
    
}

