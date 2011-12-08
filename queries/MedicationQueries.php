<?php

function findMedications($em,$pat)
{
        $qb=$em->createQueryBuilder()
        ->select("m")
        ->from("library\doctrine\Entities\MedicationEntry","m");
        $qb->join("m.statusHistory","sh");
//        ->where("m.patient=:pat")
//        ->groupBy("m.text")
//        ->orderBy("m.modified","desc");
//        $qb->setParameter("pat",$pat);
        return $qb->getQuery()->getResult();
}
?>
