<?php
function lookupKeywords($em,$searchString)
{
    $orderClause = "MATCHQUALITY('".$searchString."',keyword.text)";
    $qb = $em->createQueryBuilder()
        ->select("keyword,".$orderClause." as qual")
        ->where("keyword.text like :startsWith")
        ->from("library\doctrine\Entities\ICD9\ICD9Keyword","keyword")
        ->orderBy("qual","DESC");

    $qb->setParameter("startsWith",$searchString[0]."%");

    $qry=$qb->getQuery();
    return $qry->getResult();
    
}

function findKeywords($em,$toks)
{
    $retval=array();
    for($idx=0;$idx<count($toks);$idx++)
    {
        $retval[$idx]=lookupKeywords($em,$toks[$idx]);
    }
    return $retval;
}

function findCodes($em,$kwArr,$toks)
{
    $kwInList=array();
    $numTok=count($kwArr);
    $qb = $em->createQueryBuilder();

    $quals="";
    $orderByString="";
    
    for($tokIdx=0;$tokIdx<$numTok;$tokIdx++)
    {
        $keywords=$kwArr[$tokIdx];
        $kwInList[$tokIdx]="";
        $maxMatch=$keywords[0]['qual'];
        $minMatch=$keywords[count($keywords)-1]['qual'];
        $tol=$minMatch + ($maxMatch-$minMatch)*0.1 ;        
        for($kwIdx=0;$kwIdx<count($keywords)&& ($keywords[$kwIdx]['qual']>=$tol);$kwIdx++)
        {
            $kwInList[$tokIdx].=",".$keywords[$kwIdx][0]->getID();
        }
        $kwInList[$tokIdx]="(". substr($kwInList[$tokIdx],1).")";
//        error_log($kwInList[$tokIdx]);
        
        $qualStr="MATCHQUALITY('".$toks[$tokIdx]."',kw".$tokIdx.".text) as qual".$tokIdx;
        $quals=$quals.",".$qualStr.",kw".$tokIdx.".text as content".$tokIdx;

        $qb->from("library\doctrine\Entities\ICD9\ICD9KeywordMapping","kwm".$tokIdx);
        $qb->from("library\doctrine\Entities\ICD9\ICD9Keyword","kw".$tokIdx);
        $qb->andWhere("kwm".$tokIdx.".keyword in ".$kwInList[$tokIdx]);
        $qb->andWhere("kwm".$tokIdx.".code = cd");
        $qb->andWhere("kwm".$tokIdx.".keyword = kw".$tokIdx);

        $qb->addOrderBy('qual'.$tokIdx,"DESC");
        
    }

    $qb->select("cd".$quals);
    $qb->from("library\doctrine\Entities\ICD9\ICD9Code", "cd");
    
    $qb->addOrderBy("cd.code","ASC");

    error_log($qb->getDql());
    $qry=$qb->getQuery();
    $res=$qry->getResult();
    return $res;
    
    
}


?>
