
function handleCreateImage(data)
{
    refreshEntry(data.uuid,data.html);
}
function clickImageButtonEvt()
{
    parentUUID=$(this).attr("entryuuid");
    $.post("../../interface/ImageEntry/manageImageEntry.php",
    {
        task: "CREATE",
        parentUUID: parentUUID,
        refresh: "YES"
    },
    handleCreateImage,
    "json"
    );
}
function registerImageEntryEvents(parent)
{
    if(parent==null)
        {
            parent=$(document);
        }
    parent.find("button[func='IMAGE']").click(clickImageButtonEvt);
}