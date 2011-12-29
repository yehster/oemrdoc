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
    $tr->setAttribute("patID",$doc->getPatient()->getPID());
    
}

function genDocsTable($em,$DOM,$parent,$user,$docs)
{
    foreach($docs as $doc)
    {
        createDocRow($DOM,$parent,$doc);
    }
}
?>
