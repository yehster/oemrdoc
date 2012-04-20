function clickDocRow()
{
    var docUUID=$(this).parent().attr("docUUID");
    var pid = $(this).parent().attr("patid");
    var new_url="/openemr/interface/patient_file/summary/demographics.php?set_pid="+pid;
    window.location.href=new_url;
    
    if(top.createNewTab!=null)
        {
            top.removeCreatedTabs();
            top.createNewTab("Doctrine","../../library/doctrine/ui/DocumentEditor/DocumentEditor.php?docUUID="+docUUID);
        }

}

function handleCheckedBilling(idx,elem)
{
    row=$(elem).parents("tr[docUUID]");
    provider=row.find("select.insuranceChoice").val();
    addEncounterInfo(row.attr("patid"),row.attr("encounter"),"0","6","P"+provider);
}
function generateOEMRBillingForCheckboxes()
{
    $(".encItems input").remove();
    $("tr[docUUID] input:checked").each(handleCheckedBilling);
            
}
function clickBill()
{
    generateOEMRBillingForCheckboxes();
    $("#encountersForBilling").find("[name='bn_process_hcfa']").click();
}
function registerMyDocsEvents(parent)
{
    parent.find("tr[docUUID] td.patient").click(clickDocRow);
    parent.find("button.bill").click(clickBill);
}