<?php

function findSubEntries($em,$pat,$code,$code_type)
{
        $qb=$em->createQueryBuilder()
        ->select("i.items")
        ->from("library\doctrine\Entities\DocumentEntry","d")
        ->join("d.item","i")
        ->where("d.patient=:pat")
        ->andWhere("d.code=:code")
        ->andWhere("d.code_type:=code_type")
        ->groupBy("a.text")
        ->orderBy("a.modified","desc");
        
        // Specify parameters for the parent entry
        $qb->setParameter("pat",$pat);
        $qb->setParameter("code",$code);
        $qb->setParameter("code_type",$code_type);
        return $qb->getQuery()->getResult();
}
?>
