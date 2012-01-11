<?php

function findUnlockedDocs($em,$user)
{
        $qb = $em->createQueryBuilder()
        ->select("doc")
        ->from("library\doctrine\Entities\Document","doc")
//        ->where("doc.locked is null")
        ->where("doc.removed is null");
        $qb->orderBy("doc.dateofservice","desc");

    $qry=$qb->getQuery();
    return $qry->getResult();        
}

function createInsuranceInfo($DOM,$parent,$pat)
{
    $id=$pat->getInsurance_data();
    $info=$DOM->createElement("SPAN",count($id));
    $sel=$DOM->createElement("SELECT");
    $sel->setAttribute("class","insuranceChoice");
    foreach ($id as $insurance)
    {
        if($insurance->getProvider()!=null)
        {
            $opt=$DOM->createElement("option",$insurance->getPlan_name());
            $opt->setAttribute("value",$insurance->getProvider());
            $sel->appendChild($opt);
        }
    }
    $parent->appendChild($sel);
}
function createDocRow($DOM,$parent,$doc)
{
    $tr=$DOM->createElement("tr");
    $parent->appendChild($tr);
    $tr->setAttribute("docUUID",$doc->getUUID());
    $tdPatient=$DOM->createElement("td",$doc->getPatient()->displayName());
    $tdPatient->setAttribute("class","patient");
    $tr->appendChild($tdPatient);
    
    $tdMetadata=$DOM->createElement("td",$doc->getMetadata()->getText());
    $tr->appendChild($tdMetadata);
    
    $td=$DOM->createElement("td",$doc->getDateOfService()->format("m-d-y"));
    $tr->appendChild($td);
    
    $pat=$doc->getPatient();
    $tr->setAttribute("patID",$pat->getPID());

    $OEMREnc=$doc->getOEMREncounter();

    
    
    if($OEMREnc!=null)
    {
    
        if($OEMREnc->isBilled())
        {
        
            $tdStatus=$DOM->createElement("TD","billed");
            $tr->appendChild($tdStatus);
        }
        else
        {
            $tdStatus=$DOM->createElement("TD","unbilled");
            $tr->appendChild($tdStatus);

        }
        
        $tdInsurance=$DOM->createElement("td");
        $tr->appendChild($tdInsurance);
        createInsuranceInfo($DOM,$tr,$pat);

        $tr->setAttribute("encounter",$OEMREnc->getEncounter());    
        $tdToBill=$DOM->createElement("td");
        $tr->appendChild($tdToBill);
        $cbToBill=$DOM->createElement("input");
        $cbToBill->setAttribute("type","checkbox");
        $cbToBill->setAttribute("class","billEnc");
        if(!$OEMREnc->isBilled())
        {
            $cbToBill->setAttribute("checked","true");        
        }
        $tdToBill->appendChild($cbToBill);
    }
    else 
    {
        $tdStatus=$DOM->createElement("TD","unbilled");
        $tr->appendChild($tdStatus);
    }
    
}
function genDocsHeader($DOM,$parent)
{
    $tr=$DOM->createElement("TR");
    $parent->appendChild($tr);
    
    $thPat=$DOM->createElement("TH","Patient");
    $tr->appendChild($thPat);

    $thDocType=$DOM->createElement("TH","Document Type");
    $tr->appendChild($thDocType);

    $thDOS=$DOM->createElement("TH","DOS");
    $tr->appendChild($thDOS);    
    
    $thDOS=$DOM->createElement("TH","Status");
    $tr->appendChild($thDOS);
}

function genDocsTable($em,$DOM,$parent,$user,$docs)
{
    genDocsHeader($DOM,$parent);
    foreach($docs as $doc)
    {
        createDocRow($DOM,$parent,$doc);
    }
}
?>
