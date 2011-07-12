function formCheckBoxClicked()
{
    tr=$(this).parents("tr[code]");
    code=tr.attr("code");
    code_type=tr.attr("code_type")
    parentuuid=$(this).parents("table[type='form']").attr("entryuuid");
    classification=tr.attr("classification");
    entryuuid=tr.attr("entryuuid");
    value=$(this).is(':checked');
    if(value)
    {
        task="update";        
    }
    else
    {
        task="clear";
    }
    label=tr.children("td[type='label']").text();

    seq=tr.attr("seq");
    $.post("../../interface/manageVocabEntry.php",
        {
            task: ""+task+"",
            parentUUID: ""+parentuuid+"",
            code: ""+code+"",
            codeType: ""+code_type+"",
            entryUUID: ""+entryuuid+"",
            classification: ""+classification+"",
            value: ""+value+"",
            seq: ""+seq+"",
            text: ""+label+"",
            refresh: "YES"
        },
        function(data)
        {
             pos=data.indexOf("<",0);
             uuid=data.substr(0,pos);
             refreshEntry(parentuuid,data.substr(pos));
             tr.attr("entryuuid",uuid);
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

