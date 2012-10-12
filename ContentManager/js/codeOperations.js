var mode_task_map=new  Array();

var mode_task_callbacks=new Array();
function afterContentCreate(data)
{
    handleGroupContentEntries(data);
}
mode_task_map['Content']='create_content_entry';
mode_task_callbacks['Content']=afterContentCreate;
function dispatchCode(code,code_type,desc)
{
    // Create a Content or a Context Entry depending on selections.
    var group_uuid=$("#groupChoice").attr("uuid");
    var mode="Content"; // Hard coding this for now.
    var task=mode_task_map[mode];
    var callBack=mode_task_callbacks[mode];
    $.post
    (
        "ajax/manageContentGroup.php",
        {
            task: task,
            uuid: group_uuid,
            content_code: code,
            content_code_type:code_type,
            content_display_text: desc
        },
        callBack,
       "json"
    );
    
}