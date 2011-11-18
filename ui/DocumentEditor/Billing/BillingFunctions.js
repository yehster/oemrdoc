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
function evtSelectCode()
{
    docUUID=$("body").attr("docUUID");
    params=$(this).val().split("|");
    codeType=params[0];
    codeVal=params[1];
    $.post("../../interface/billing/processDocument.php",
    {
        docUUID: docUUID,
        codeType: codeType,
        codeVal: codeVal
    },
        processDocumentFinished
    );
}

function addBillingControls()
{
    label=$("span.LABEL:contains('Billing')");
    controlGroup=label.siblings("span[func='controlGroup']");
    cptOptions=controlGroup.find("span.cptOptions");
    if(cptOptions.length==0)
    {
        cptOptions=$("<span>").appendTo(controlGroup);
        cptOptions.addClass("cptOptions");
    }
    $.post("Billing/BillingOptions.php",
        {patientID: patID},
        function(data)
        {
            cptOptions.html(data);
            $("span.cptOptions").on({click: evtSelectCode},"option");

        }
    )
}

function registerBillingEvents()
{
     $("button[func='PROCESS']").on("click", evtButtonBillingClick);
     addBillingControls();
}