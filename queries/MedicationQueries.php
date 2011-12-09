<?php

function findMedications($em,$pat,$root=null)
{
        $qb=$em->createQueryBuilder()
        ->select("m")
        ->from("library\doctrine\Entities\MedicationEntry","m");
        $qb->leftJoin("m.statusHistory","sh");
        $qb->leftJoin("m.copiedTo","ct");
        $qb->where("m.patient=:pat");
        $qb->andWhere("(sh.status>0 and sh.modified=(select max(es.modified) from library\doctrine\Entities\EntryStatus as es where es.entry=m))"
                       ."or (sh is null)");
        $qb->andWhere("ct is null");
        if($root!=null)
        {
            $qb->join("m.item","it");
            $qb->andWhere("it.root != :rt");
            $qb->setParameter("rt",$root);
        }
//        $qb->groupBy("m");
        $qb->orderBy("m.modified","desc");
        $qb->setParameter("pat",$pat);
        return $qb->getQuery()->getResult();
}
?>
