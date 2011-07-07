function formCheckBoxClicked()
{
    tr=$(this).parents("tr[code]");
    code=tr.attr("code");
    code_type=tr.attr("code_type")
    parentuuid=$(this).parents("table[type='form']").attr("entryuuid");
    classification=tr.attr("classification");
    entryuuid=tr.attr("entryuuid");
    value=$(this).is(':checked');
    task="update";
    $.post("../../interface/manageVocabEntry.php",
        {
            task: ""+task+"",
            parentEntryUUID: ""+parentuuid+"",
            code: ""+code+"",
            codeType: ""+code_type+"",
            entryUUID: ""+entryuuid+"",
            classification: ""+classification+"",
            value: ""+value+""
        },
        function(data)
        {
            window.alert(data)
        }
    );
}
function showFormDialog()
{
    $("#sectionForm").attr("hidden",false);
    entryUUID=$(this).attr("entryUUID");
    $("#sectionForm").attr("entryUUID",entryUUID);
    label=$(this).siblings(".LABEL").text();
    $.post("../DocumentForms/generateForm.php",
        {
            entryUUID: ""+entryUUID+""
        },
        function(data)
        {
            $("#sectionFormsDisplay").html(data);
            $("#sectionFormsDisplay table[type='form'] tr[code] input[type='checkbox']").click(formCheckBoxClicked)
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

