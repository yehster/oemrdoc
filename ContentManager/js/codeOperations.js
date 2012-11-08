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
    handleGroupContextEntries(data);
}
mode_task_map['Context']='create_context_entry';
mode_task_callbacks['Context']=afterContextCreate;

function dispatchCode(code,code_type,desc)
{
    // Create a Content or a Context Entry depending on selections.
    var group_uuid=$("#groupChoice").attr("uuid");
    var mode=$("div[mode_choice='true'].mode_option").attr("mode");
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


function handleGroupContentData(data,display,type)
{
        display.empty();
        if(data[type].length==0)
        {
            display.html("No Entries");   
            return display;
        }
        else
        {
            var display_table=$("<table><tbody></tbody></table>");
            var tbody=display_table.find("tbody");
            for(idx=0;idx<data[type].length;idx++)
            {
                var entry=data[type][idx];
                var tr=$("<tr></tr>");
                tbody.append(tr);
                var td_display=$("<td>"+entry.display_text+"</td>");
                tr.attr("uuid",entry.uuid);
                tr.attr("code",entry.code);
                tr.attr("code_type",entry.code_type);
                tr.append(td_display);
                
                var td_delete=$("<td><button class='delete'>del</button></td>");
                tr.append(td_delete);
                var td_up=$("<td><button class='move'>up</button></td>");
                var td_down=$("<td><button class='move'>down</button></td>");
                tr.append(td_up);
                tr.append(td_down);
            }
            display.append(display_table);
        }
    return display_table;
}

function chooseMode(evt)
{
    $(".mode_option").attr("mode_choice","false");
    $(this).attr("mode_choice","true");
}
function bindCodeOperations()
{
    $(".mode_option").on({click: chooseMode});
}