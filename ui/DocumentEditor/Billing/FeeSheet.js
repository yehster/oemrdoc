function CBPostHandler(data)
{
    ShowStatusMessage(data);
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
function evtBlurFee()
{
    tr=$(this).parents("tr.billing");
    BillingID=tr.attr("billingid");
    fee=$(this).val();
    $.post("../../interface/billing/processFeeSheet.php",
        {
            BillingID: BillingID,
            Fee: fee
        },
        CBPostHandler
    );
    
}

function evtBlurMod()
{
    tr=$(this).parents("tr.billing");
    BillingID=tr.attr("billingid");
    mod=$(this).val();
    $.post("../../interface/billing/processFeeSheet.php",
        {
            BillingID: BillingID,
            Modifier: mod
        },
        CBPostHandler
    );
    
}


function registerFeeSheetEvents()
{

    $("section[name='Billing']").on("click", ".billingInfo input[type='checkbox']", evtCBDiag );
    $("section[name='Billing']").on("blur", "input.fee[type='text']", evtBlurFee );
    $("section[name='Billing']").on("blur", "input.mod[type='text']", evtBlurMod );

}