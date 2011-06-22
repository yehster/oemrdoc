/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


function deleteEntry()
{
    entryUUID=$(this).attr("entryUUID");
    $.post("../../interface/deleteEntry.php",
        {entryUUID: ""+entryUUID,
         refresh: "YES"},
         function(data)
         {
            pos=data.indexOf("<",0);
            uuid=data.substr(0,pos);
            selector="[uuid='"+uuid+"']";
             $(selector).html(data.substr(pos));
         }
    );
        
}
function registerDeleteEntryEvents()
{
    $("button[func='DELETE']").live({click: deleteEntry});
}