function deleteContextEntryUUID(uuid)
{
    $.post("ajax/manageContextEntry.php",
        {
            task: "delete",
            uuid: uuid
        },
        function(data)
        {
            handleGroupContextEntries(data);        
        },
        "json"
    );
}

function deleteContextEntryEvent(evt)
{
    var uuid=$(this).parent().parent("tr").attr("uuid");
    deleteContextEntryUUID(uuid);
}

function handleGroupContextEntries(data)
{
        var display =handleGroupContentData(data,$("#contextResults"),"context_entries");
        display.find(".delete").on({click: deleteContextEntryEvent});

}