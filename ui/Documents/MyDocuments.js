function clickDocRow()
{
    docUUID=$(this).attr("docUUID");
    window.location.href="../DocumentEditor/DocumentEditor.php?docUUID="+docUUID;
}
function registerMyDocsEvents(parent)
{
    parent.find("tr[docUUID]").click(clickDocRow);
}