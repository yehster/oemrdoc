/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


function deleteEntry()
{
    entryUUID=$(this).attr("entryUUID");
    $.post("../../interface/deleteEntry.php",
        {entryUUID: ""+entryUUID},
         function(data)
         {
             $(this).parent.remove();
         }
    );
        
}
function registerDeleteEntryEvents()
{
    $("button[func='DELETE']").live({click: deleteEntry});
}