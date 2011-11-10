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

            pos=data.indexOf("|");
            uuid=data.substr(0,pos);
            uuid=$.trim(uuid);
            newHTML=data.substr(pos+1);
            selector="[uuid='"+uuid+"']";         
            $(selector).replaceWith(newHTML);
        }
        );
        
}
function registerDeleteEntryEvents()
{
    $("button[func='DELETE']").live({click: deleteEntry});
}