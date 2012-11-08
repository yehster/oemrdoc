

function deleteContentEntryUUID(uuid)
{
    $.post("ajax/manageContentEntry.php",
        {
            task: "delete",
            uuid: uuid
        },
        function(data)
        {
            handleGroupContentEntries(data);        
        },
        "json"
    );
}
function deleteContentEntryEvent(evt)
{
    var uuid=$(this).parent().parent("tr").attr("uuid");
    deleteContentEntryUUID(uuid);
}


function setClassification(uuid,classification)
{
    $.post("ajax/manageContentEntry.php",
        {
            task: "set_classification",
            uuid: uuid,
            classification: classification
        },
        function(data)
        {
            handleGroupContentEntries(data);        
        },
        "json"
    );
    
}

function handleClassificationChoice(evt)
{
    var row=$(this).parents("tr[uuid]");
    var uuid=row.attr("uuid");
    var newVal=$(this).val();
    setClassification(uuid,newVal);
}
var content_entry_classification_html="<select>"
    +"<option></option>"
    +"<option value='NORMAL'>Normal</option>"
    +"<option value='ABNORMAL'>Abnormal</option>"
    +"<option value='TRAIT'>Trait</option>"
    +"<option value='MEASUREMENT'>Measurement</option>"

    +"</select>";
function addClassificationControls(display)
{
    var rows=display.find("tr");
    rows.each(function(idx,elem)
        {
            var jqElem=$(elem);
            var td = $("<td></td>");
            var select=$(content_entry_classification_html);
            var classification=jqElem.attr("classification");
            if(classification!="")
                {
                    var choice=select.find("option[value='"+classification+"']");
                    choice.attr("selected","selected");
                }
            td.append(select);
            select.on({change: handleClassificationChoice});
            jqElem.append(td);
        }
    );
}

function handleGroupContentEntries(data)
{
        var display =handleGroupContentData(data,$("#contentResults"),"content_entries");
        display.find(".delete").on({click: deleteContentEntryEvent});
        display.find("button.move").on({click: moveContentEntryEvent});
        display.find("tr:first button.move:contains('up')").attr("disabled","disabled");
        display.find("tr:last button.move:contains('down')").attr("disabled","disabled");
        addClassificationControls(display);
}

function moveContentEntryEvent(evt)
{
    var uuid=$(this).parent().parent("tr").attr("uuid");
    var direction=$(this).text();
    moveContentEntryUUID(uuid,direction);
}

function moveContentEntryUUID(uuid,direction)
{
    $.post("ajax/manageContentEntry.php",
        {
            task: "move",
            uuid: uuid,
            direction: direction
        },
        function(data)
        {
            handleGroupContentEntries(data);        
        },
        "json"
    );
}
