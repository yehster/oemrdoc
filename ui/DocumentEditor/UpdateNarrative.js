/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function updateNarrative()
{
    uuid=$(this).attr("uuid");
    narText=$(this).text();
    $.post("/openemr/library/doctrine/interface/manageEntry.php",
    {
        docEntryUUID: ""+uuid+"",
        EntryType: "narrative",
        task: "update",
        content: ""+narText+""
    } );
}

function registerNarrativeEvents()
{
    $("[entrytype='Narrative']").live({blur: updateNarrative});
}