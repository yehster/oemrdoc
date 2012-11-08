function updateGroupDescription(control)
{
    var editor = $(control).parent("td");
    var row=editor.parent("tr");
    var description = row.find(".groupDescription");
    var groupUUID=row.attr("uuid");
    var value=$(control).val();
    $.post("ajax/manageContentGroup.php",
    {   task: "rename",
        uuid: groupUUID,
        newName: value
    }
    ,function(data){
            description.text(data.newValue);
            editor.hide();
            description.show();
            
    }
    ,"json");
}

function handleRenameReturn(evt)
{
    if(evt.keyCode==13)
    {
        updateGroupDescription(this);
    }
}
function renameGroupStart(evt)
{

    var row=$(this).parent().parent("tr");
    var description=$(row).find(".groupDescription");
    description.hide();
    var editor=row.find("td.editor");
    var descriptionText=description.text();
    var input;
    if(editor.length==0)
    {
        editor=$("<td class='editor'></td>");
        input=$("<input class='renameGroup' type='text'/>");
        input.on({blur: function(evt){updateGroupDescription(this);}, keypress: handleRenameReturn});
        editor.append(input);
        description.after(editor);
    }
    else
    {
        input=editor.find("input.renameGroup");
    }
    editor.show();
    input.focus();
    input.attr("value",descriptionText);
}


function displayGroupContext(uuid)
{
    $.post("ajax/manageContentGroup.php",
    {   task: "get_context_entries",
        uuid: uuid
    }
    ,function(data){
        handleGroupContextEntries(data);
    }
    ,"json");
    
}

function displayGroupContent(uuid)
{
    $.post("ajax/manageContentGroup.php",
    {   
        task: "get_content_entries",
        uuid: uuid
    }
    ,function(data)
    {
        handleGroupContentEntries(data);
    }
    ,"json");
    
}

function chooseGroup()
{
    var row=$(this).parent("tr");
    var uuid=row.attr("uuid");
    var description=row.find("td:first").text();
    $("#groupChoice").text(description).attr("uuid",uuid);
    toggleGroupChooserVisibility();
    displayGroupContent(uuid);
    displayGroupContext(uuid);
    
//    window.alert(description);
//    window.alert(uuid);
}

function bindGroupControls(parent)
{
    parent.find("button.rename").on({click:renameGroupStart});
    parent.find("td.groupDescription").on({click:chooseGroup});
}
function showGroups(data,elem)
{
    elem.children().remove();
    var table=$("<table></table>");
    var tbody=$("<tbody></tbody>").appendTo(table);
    elem.append(table);
    for(idx=0;idx<data.uuids.length;idx++)
    {
        uuid=data.uuids[idx];
        description=data.descriptions[idx];
        var tr=$("<tr></tr>");
        tr.attr("uuid",uuid);
        var td=$("<td class='groupDescription'>"+description+"</td>");
        tr.append(td);

        var td_rename=$("<td><button class='rename'>rename</button></td>");
        tr.append(td_rename);


        var td_delete=$("<td><button class='delete'>delete</button></td>");
        tr.append(td_delete);
        
        tbody.append(tr);
    }
    bindGroupControls(tbody);
}

function clickSectionDescription(evt)
{
    var code=$(this).attr("code");
    var code_type=$(this).attr("code_type");
    
    $("#sectionChoice").text($(this).text());
    $("#sectionChoice").attr("code",code);
    $("#sectionChoice").attr("code_type",code_type);
    
    $.post("ajax/manageContentGroups.php",
    {
        task: "search",
        document_code: code,
        document_code_type: code_type
    },
    function(data)
    {
        $("#createGroup").removeAttr("disabled");
        
        showGroups(data,$("#groupResults"));
    }
    ,"json");
}

