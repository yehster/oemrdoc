function showFormDialog()
{
    $("#sectionForm").attr("hidden",false);
    $("#sectionForm").attr("entryUUID",$(this).attr("entryUUID"));
    label=$(this).siblings(".LABEL").text();
    $("#sectionFormsHeader").html(label)
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

