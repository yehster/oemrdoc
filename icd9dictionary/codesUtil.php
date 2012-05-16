<?php

function lookupByCode($em,$searchString)
{
        $qb = $em->createQueryBuilder()
        ->select("code, code.frequency")
        ->where("code.code like :startsWith")
        ->from("library\doctrine\Entities\ICD9\ICD9Code","code")           
        ->orderBy("code.frequency","DESC")
        ->addOrderBy("code.code","ASC");
    $qb->addOrderBy("code.frequency","DESC");

    $qb->setParameter("startsWith",$searchString."%");

    $qry=$qb->getQuery();
    $qry->setFirstResult(0);
    $qry->setMaxResults(100);

    return $qry->getResult();    
}

function generate_codes($codeList)
{
    
}
?>
