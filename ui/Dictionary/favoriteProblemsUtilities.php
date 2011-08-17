<?php
require_once('../../util/tokenize.php');

function findFavoriteProblems($searchStr)
{
    $toks = tokenize($searchStr);
    // to do use keyword/match quality matching instead of partial text
    $qb=$GLOBALS['em']->createQueryBuilder()
        ->select("code")
        ->from("library\doctrine\Entities\Code","code")
        ->from("library\doctrine\Entities\Problem", "prob")
        ->where("prob.code=code.code");
    
    for($idx=0;$idx<count($toks);$idx++)
    {
        $qb->andWhere("prob.text like :srch".$idx);
        $qb->setParameter("srch".$idx,"%".$toks[$idx]."%");
        
    }
    
    
    $qry=$qb->getQuery();
    return $qry->getResult();
}

?>
