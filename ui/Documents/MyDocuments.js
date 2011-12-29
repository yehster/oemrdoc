function clickDocRow()
{
    docUUID=$(this).parent().attr("docUUID");
    window.location.href="../DocumentEditor/DocumentEditor.php?docUUID="+docUUID;

}

function handleCheckedBilling(idx,elem)
{
    row=$(elem).parents("tr[docUUID]");
    addEncounterInfo(row.attr("patid"),row.attr("encounter"),"0","6","P7");
}
function generateOEMRBillingForCheckboxes()
{
    $(".encItems input").remove();
    $("tr[docUUID] input:checked").each(handleCheckedBilling);
            
}
function clickBill()
{
    generateOEMRBillingForCheckboxes();
}
function registerMyDocsEvents(parent)
{
    parent.find("tr[docUUID] td.patient").click(clickDocRow);
    parent.find("button.bill").click(clickBill)
}