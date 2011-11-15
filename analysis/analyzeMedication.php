<?php

function updateOEMRMedication(\library\doctrine\Entities\MedicationEntry $me)
{
    $oli=null;
    if($me->getOEMRListItem()===null)
    {
        $qb=$GLOBALS["em"]->createQueryBuilder()
        ->select("m")
        ->from("library\doctrine\Entities\OEMRMedication","m")
        ->where("m.title=:med and m.patient=:pat")
        ->orderBy("m.date","DESC");

        $qb->setParameter("med",$me->getText());
        $qb->setParameter("pat",$me->getPatient());
        
        $res=$qb->getQuery()->getResult();
        
        for($idx=0;($idx<count($res)&& (!isset($oli)));$idx++)
        {
            $cur=$res[$idx];
            if($cur->getEndDate()===null)
            {
                $oli=$cur;
            }
        }
        if($oli==null)
        {
            $oli=new \library\doctrine\Entities\OEMRMedication($me->getPatient(),$me->getText());
            $GLOBALS["em"]->persist($oli);
        }
        $me->setOEMRListItem($oli);
    }
    else
    {
        $oli=$me->getOEMRListItem();
    }
    return $oli;
}

?>
