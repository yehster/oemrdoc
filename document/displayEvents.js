function doctrineInfo(elem)
{
    var jq=$(elem);
    this.uuid=$(elem).attr("uuid");
    if(typeof(this.uuid)=='undefined')
        {
            var parent=jq.parent("[uuid]");
            this.uuid=parent.attr("uuid");
            jq=parent;
        }
    var docParent=jq.parents("[uuid]");
    if(docParent.length>0)
        {
            this.parentuuid=docParent.eq(0).attr("uuid");
        }
        else
            {
                this.parentuuid="";
            }
    return this;
}

function registerNarrative(parent)
{
    parent.find("textarea[entrytype='Narrative']").on({
        blur: function(evt)
        {
            var di=new doctrineInfo(this);
            var narText=$(this).val();
            $.post("/openemr/library/doctrine/interface/manageNarrative.php",
            {
                docEntryUUID: ""+di.uuid+"",
                EntryType: "narrative",
                task: "update",
                content: ""+narText+""
            } );
            //update anyother text areas that might also refer to this entry;
            $("[entrytype='Narrative'][uuid='"+di.uuid+"']").val(narText);
        }
    });


}
function registerEvents(parent)
{
    registerNarrative(parent);
}

registerEvents($("#main"));