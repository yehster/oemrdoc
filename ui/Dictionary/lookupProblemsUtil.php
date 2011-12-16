<?php

function findCodesForCodeString($em,$cs)
{
   $qb = $em->createQueryBuilder()
        ->select("code")
        ->from("library\doctrine\Entities\Code","code")
        ->where("code.code like :codeStr");
        $qb->andWhere("code.code_type=2");
   $qb->setParameter("codeStr","$cs%");
   return $qb->getQuery()->getResult();
}
function findProblemsForKeyword($em,$DOM,$tbody,$searchToken)
{
        $keywords = findKeywords($em,$searchToken);
        $maxMatch=$keywords[0]['qual'];
        $minMatch=$keywords[count($keywords)-1]['qual'];
        $tol=($maxMatch-$minMatch) / 2;
        if(strlen($searchToken)==1)
        {
            $maxRes=20;
        }
        else {
            $maxRes=9999;
        }
        for($idx=0;$idx<count($keywords) and ((($keywords[$idx]['qual']) - $maxMatch + $tol) >= 0) and $idx<$maxRes;$idx++)
        {
            $curKW = $keywords[$idx][0];
            $qual = $keywords[$idx]['qual'];
            $codes = findCodesForKeyword($em,$curKW,$codeSet);
            if(count($codes)>0)
            {
                addKeyword($DOM,$tbody,$curKW,$qual);

            }
            for($cidx=0;$cidx<count($codes);$cidx++)
            {
                addCodeResult($DOM,$tbody,$codes[$cidx]);
            }
        }
}
function findProblemsByCodeNum($em,$DOM,$tbody,$searchToken)
{
    $codes=findCodesForCodeString($em,$searchToken);
    foreach($codes as $code)
    {
        addCodeResult($DOM,$tbody,$code);
    }
}
?>
