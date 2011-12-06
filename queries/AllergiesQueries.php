<?php

function findSubEntries($em,$pat,$code,$code_type)
{
        $qb=$em->createQueryBuilder()
        ->select("d")
        ->from("library\doctrine\Entities\DocumentEntry","d")
        ->join("d.item","i")
        ->join("i.parent","pi")
        ->join("pi.entry","t")
        ->where("d.patient=:pat")
        ->andWhere("t.patient=:pat")
        ->andWhere("t.code=:code")
        ->andWhere("t.code_type=:code_type");
//        ->groupBy("a.text")
//        ->orderBy("a.modified","desc");
        
        // Specify parameters for the parent entry
        $qb->setParameter("pat",$pat);
        $qb->setParameter("code",$code);
        $qb->setParameter("code_type",$code_type);
        return $qb->getQuery()->getResult();
}
?>
