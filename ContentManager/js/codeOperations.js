var mode_task_map=new  Array();

var mode_task_callbacks=new Array();
function afterContentCreate(data)
{
    handleGroupContentEntries(data);
}


mode_task_map['Content']='create_content_entry';
mode_task_callbacks['Content']=afterContentCreate;
function afterContextCreate(data)
{
    window.alert("Context Created!");
}
mode_task_map['Context']='create_context_entry';
mode_task_callbacks['Context']=afterContextCreate;

function dispatchCode(code,code_type,desc)
{
    // Create a Content or a Context Entry depending on selections.
    var group_uuid=$("#groupChoice").attr("uuid");
    var mode="Context"; // Hard coding this for now.
    var task=mode_task_map[mode];
    var callBack=mode_task_callbacks[mode];
    $.post
    (
        "ajax/manageContentGroup.php",
        {
            task: task,
            uuid: group_uuid,
            code: code,
            code_type:code_type,
            display_text: desc
        },
        callBack,
       "json"
    );
    
}