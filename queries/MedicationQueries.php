<?php

function findMedications($em,$pat)
{
        $qb=$em->createQueryBuilder()
        ->select("m")
        ->from("library\doctrine\Entities\MedicationEntry","m");
        $qb->leftJoin("m.statusHistory","sh");
        $qb->where("m.patient=:pat");
        $qb->andWhere("(sh.status>0 and sh.modified=(select max(es.modified) from library\doctrine\Entities\EntryStatus as es where es.entry=m))"
                       ."or (sh is null)");
//        $qb->groupBy("m");
        $qb->orderBy("m.modified","desc");
        $qb->setParameter("pat",$pat);
        return $qb->getQuery()->getResult();
}
?>
