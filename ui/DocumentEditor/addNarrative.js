function addNarrative()
{
    entryUUID=$(this).attr("entryUUID");
    $.post("../../interface/manageNarrative.php",
        {parentUUID: ""+entryUUID+"",
         task: "create",
         refresh: "YES"},
         function(data)
         {
             pos=data.indexOf("<",0);
             uuid=data.substr(0,pos);
             refreshEntry(entryUUID,data.substr(pos));
             newAreaSelector="textarea[uuid="+uuid+"]";
             $(newAreaSelector).select();
         }
    );
}
function registerAddNarrativeEvents()
{
    $("button[func='DETAILS']").live({click: addNarrative});
}

