<?php

function findUnlockedDocs($em,$user)
{
        $qb = $em->createQueryBuilder()
        ->select("doc")
        ->from("library\doctrine\Entities\Document","doc")
        ->where("doc.locked is null");
        $qb->orderBy("doc.modified","desc");

    $qry=$qb->getQuery();
    return $qry->getResult();        
}

function createDocRow($DOM,$parent,$doc)
{
    $tr=$DOM->createElement("tr");
    $parent->appendChild($tr);
    $tr->setAttribute("docUUID",$doc->getUUID());
    $tdPatient=$DOM->createElement("td",$doc->getPatient()->displayName());
    $tr->appendChild($tdPatient);
    
    $tdMetadata=$DOM->createElement("td",$doc->getMetadata()->getText());
    $tr->appendChild($tdMetadata);
    
    $td=$DOM->createElement("td",$doc->getModified()->format("m-d-y"));
    $tr->appendChild($td);
}

function genDocsTable($em,$DOM,$parent,$user,$docs)
{
    foreach($docs as $doc)
    {
        createDocRow($DOM,$parent,$doc);
    }
}
?>
