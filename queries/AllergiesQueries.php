<?php

function findSubEntries($em,$pat,$code,$code_type)
{
        $qb=$em->createQueryBuilder()
        ->select("d")
        ->from("library\doctrine\Entities\DocumentEntry","d")
        ->join("d.item","i")
        ->join("i.parent","pi")
        ->join("pi.entry","t");
        $qb->leftJoin("d.statusHistory","sh");
        $qb->leftJoin("d.copiedTo","ct");        
        $qb->where("d.patient=:pat")
        ->andWhere("t.patient=:pat")
        ->andWhere("t.code=:code")
        ->andWhere("t.code_type=:code_type");
        $qb->andWhere("(sh.status>0 and sh.modified=(select max(es.modified) from library\doctrine\Entities\EntryStatus as es where es.entry=d))"
                       ."or (sh is null)");
        $qb->andWhere("ct is null");
//        ->groupBy("a.text")
//        ->orderBy("a.modified","desc");
        
        // Specify parameters for the parent entry
        $qb->setParameter("pat",$pat);
        $qb->setParameter("code",$code);
        $qb->setParameter("code_type",$code_type);
        return $qb->getQuery()->getResult();
}
?>
