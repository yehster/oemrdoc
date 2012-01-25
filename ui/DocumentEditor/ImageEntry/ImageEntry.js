
function handleCreateImage(data)
{
    window.alert(data);
}
function clickImageButtonEvt()
{
    parentUUID=$(this).attr("entryuuid");
    $.post("../../interface/ImageEntry/manageImageEntry.php",
    {
        task: "create",
        parentUUID: parentUUID,
        refresh: "YES"
    },
    handleCreateImage
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