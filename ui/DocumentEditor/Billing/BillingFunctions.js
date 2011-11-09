function processDocumentFinished()
{
    window.alert("finished");
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
     $("button[func='PROCESS']").live(
        {
            click: evtButtonBillingClick
        }
    )   
}