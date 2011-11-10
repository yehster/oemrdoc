<?php
require_once("../init-em.php");
require_once("analysisTools.php");


function updateOEMRProblem(\library\doctrine\Entities\Problem $dep)
{
    $oli=null;
    if($dep->getOEMRListItem()===null)
    {
        $qb=$GLOBALS["em"]->createQueryBuilder()
        ->select("p")
        ->from("library\doctrine\Entities\OEMRProblem","p")
        ->where("p.diagnosis=:diag")
        ->orderBy("p.date","DESC");

        $qb->setParameter("diag",$dep->getCode_type().":".$dep->getCode());
        
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
            $oli=new \library\doctrine\Entities\OEMRProblem($dep->getPatient(),$dep->getText(),$dep->getCode());
            $GLOBALS["em"]->persist($oli);
        }
        $dep->setOEMRListItem($oli);
    }
    else
    {
        $oli=$dep->getOEMRListItem();
    }
    return $oli;
}

function updateDocumentProblems(\library\doctrine\Entities\Document $doc)
{
    $problems=scanDocument($doc, "Problem");
    foreach($problems as $prob)
    {
        updateOEMRProblem($prob);
    }
}
?>
