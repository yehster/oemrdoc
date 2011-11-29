function CBPostHandler(data)
{
    window.alert(data);
}
function evtCBDiag()
{
    tr=$(this).parents("tr.billing");
    BillingID=tr.attr("billingid");
    DiagCode=$(this).val();
    
    $.post("../../interface/billing/processFeeSheet.php",
        {
            BillingID: BillingID,
            DiagCode: DiagCode,
            Justify: this.checked
        },
        CBPostHandler
    );
}

function registerFeeSheetEvents()
{
     $("section[name='Billing']").on("click", ".billingInfo input[type='checkbox']", evtCBDiag );
}