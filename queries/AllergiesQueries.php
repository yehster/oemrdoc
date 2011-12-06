<?php

function findAllergies($em,$pat)
{
        $qb=$em->createQueryBuilder()
        ->select("m")
        ->from("library\doctrine\Entities\MedicationEntry","m")
        ->where("m.patient=:pat")
        ->groupBy("m.text")
        ->orderBy("m.modified","desc");
        $qb->setParameter("pat",$pat);
        return $qb->getQuery()->getResult();
}
?>
