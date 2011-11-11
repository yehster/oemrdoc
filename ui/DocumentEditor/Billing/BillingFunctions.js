function processDocumentFinished(data)
{
    billing=$("section[name='Billing']");
    status=billing.children("div.billingInfo");
    if(status.length==0)
        {
            status=$("<div class='billingInfo'></div>").appendTo(billing);
        }
    status.html(data);
}
function evtButtonBillingClick()
{
    docUUID=$("body").attr("docUUID");
    $.post("../../interface/billing/processDocument.php",
        {
            docUUID: docUUID
        },
        processDocumentFinished
    );
}
function registerBillingEvents()
{
     $("button[func='PROCESS']").on("click", evtButtonBillingClick);
}