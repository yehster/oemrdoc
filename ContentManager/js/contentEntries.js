

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

function handleGroupContentEntries(data)
{
        var display =handleGroupContentData(data,$("#contentResults"),"content_entries");
        display.find(".delete").on({click: deleteContentEntryEvent});
        display.find("button.move").on({click: moveContentEntryEvent});
        display.find("tr:first button.move:contains('up')").attr("disabled","disabled");
        display.find("tr:last button.move:contains('down')").attr("disabled","disabled");
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
