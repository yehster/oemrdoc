var idEncountersForm="encountersForBilling";
function addEncounterInfo(patID,encID,bill,partner,payer)
{
    ef=$("#"+idEncountersForm).find(".encItems");
    namePrefix="claims["+patID+"-"+encID+"]";
    inputBill=$("<input name='"+namePrefix+"[bill]'>").appendTo(ef);
    inputBill.attr("value",bill);
    inputPayer=$("<input name='"+namePrefix+"[payer]'>").appendTo(ef);
    inputPayer.attr("value",payer);
    inputPartner=$("<input name='"+namePrefix+"[partner]'>").appendTo(ef);
    inputPartner.attr("value",partner);    
    
}
function setupEncountersForm(parent)
{
    ef=$("#"+idEncountersForm);
    if(ef.length==0)
    {
        strEF="<form id='"+idEncountersForm+"' method='post' action='/openemr/interface/billing/billing_process.php'></form>";
        ef=$(strEF).appendTo(parent);
        inputHCFA=$("<input type='submit' name='bn_process_hcfa' value='hcfa'>").appendTo(ef);
        inputX12=$("<input type='submit' name='bn_x12' value='x12'>").appendTo(ef);
        inputMAC=$("<input name='HiddenMarkAsCleared' value='yes'>").appendTo(ef);
        inputLeftMargin=$("<input name='left_margin' value='24'>").appendTo(ef);
        inputTopMargin=$("<input name='top_margin' value='23'>").appendTo(ef);
        inputBill=$("<input name='mode' value='bill'>").appendTo(ef);

        spanEnc=$("<span class='encItems'> </span>").appendTo(ef);
    }
    return ef;
}