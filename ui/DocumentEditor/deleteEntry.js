/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


function deleteEntry()
{
    window.alert($(this).attr("entryUUID"));
}
function registerDeleteEntryEvents()
{
    $("button[func='DELETE']").live({click: deleteEntry});
}