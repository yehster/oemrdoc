var idEncountersForm="encountersForBilling";
function addEncounterInfo(patID,encID,bill,partner,payer)
{
    ef=$("#"+idEncountersForm);
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
        inputMAC=$("<input name='HiddenMarkAsCleared' value='yes'").appendTo(ef);
        inputMAC=$("<input name='mode' value='bill'").appendTo(ef);
        addEncounterInfo("1","5","0","0","-1");
        addEncounterInfo("1","4","0","0","-1");        
    }
    return ef;
}