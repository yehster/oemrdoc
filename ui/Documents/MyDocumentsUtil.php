<?php

function findUnlockedDocs($em,$user)
{
        $qb = $em->createQueryBuilder()
        ->select("doc")
        ->from("library\doctrine\Entities\Document","doc")
        ->where("doc.locked is null")
        ->andWhere("doc.removed is null");
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
    
    $OEMREnc=$doc->getOEMREncounter();
    if($OEMREnc!=null)
    {
        $tr->setAttribute("encounter",$OEMREnc->getEncounter());    
        $tdToBill=$DOM->createElement("td");
        $tr->appendChild($tdToBill);
        $cbToBill=$DOM->createElement("input");
        $cbToBill->setAttribute("type","checkbox");
        $cbToBill->setAttribute("class","billEnc");
        $cbToBill->setAttribute("checked","true");
        $tdToBill->appendChild($cbToBill);
    }
    $pat=$doc->getPatient();
    $tr->setAttribute("patID",$pat->getPID());
    
    $tdInsurance=$DOM->createElement("td");
    $tr->appendChild($tdInsurance);
    
    createInsuranceInfo($DOM,$tr,$pat);
    
}

function genDocsTable($em,$DOM,$parent,$user,$docs)
{
    foreach($docs as $doc)
    {
        createDocRow($DOM,$parent,$doc);
    }
}
?>
