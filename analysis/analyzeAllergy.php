<?php

function updateOEMRAllergy(\library\doctrine\Entities\DocumentEntry $de)
{
    $oli=null;
    if($de->getOEMRListItem()===null)
    {
        $qb=$GLOBALS["em"]->createQueryBuilder()
        ->select("m")
        ->from("library\doctrine\Entities\OEMRAllergy","m")
        ->where("m.title=:all and m.patient=:pat")
        ->orderBy("m.date","DESC");

        $qb->setParameter("all",$de->getText());
        $qb->setParameter("pat",$de->getPatient());
        
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
            $oli=new \library\doctrine\Entities\OEMRAllergy($de->getPatient(),$de->getText());
            $GLOBALS["em"]->persist($oli);
        }
        $de->setOEMRListItem($oli);
    }
    else
    {
        $oli=$de->getOEMRListItem();
    }
    return $oli;
}

?>
