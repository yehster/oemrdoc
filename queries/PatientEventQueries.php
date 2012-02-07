<?php
function PatientEvent($em,$pat,$date=null)
{
    if($date==null)
    {
        $date= new \DateTime;
    }
    $beg=clone $date;
    $beg->setTime(0,0,0);
    $end=clone $beg;
    $end=$end->add(new DateInterval("P1D"));

    $qb = $em->createQueryBuilder()
    ->select("evt")
    ->from("library\doctrine\Entities\PatientEvent","evt");
    $qb->Where("evt.time=(select max(esub.time) from library\doctrine\Entities\PatientEvent as esub where esub.patient=evt.patient and esub.patient=:pat and esub.time>:beg and esub.time<:end)");
    $qb->orderBy("evt.time","asc");
        
    $qb->setParameter("pat",$pat);
    $qb->setParameter("beg",$beg);
    $qb->setParameter("end",$end);
    
    $qry=$qb->getQuery();
    $res=$qry->getResult(); 
    return $res;
}
?>
