/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function deleteEntry()
{
    entryUUID=$(this).attr("entryUUID");
    $.post("../../interface/deleteEntry.php",
        {
            entryUUID: ""+entryUUID+"",
            refresh: "YES"
        },
        function(data)
        {
            uuid=data.uuid;
            refreshEntry(uuid,data.html);
        },"json"
        );
        
}
function registerDeleteEntryEvents()
{
    $("button[func='DELETE']").live({click: deleteEntry});
}