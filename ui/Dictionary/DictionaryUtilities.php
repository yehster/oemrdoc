<?php

require_once('../../util/tokenize.php');

function findCodesForKeyword($em,$kw,$codeSet="")
{


    $csArray=array('2','3','9','16','V');
   $qb = $em->createQueryBuilder()
        ->select("code")
        ->from("library\doctrine\Entities\Code","code")
        ->from("library\doctrine\Entities\KeywordCodeAssociation", "kwa")
        ->where("kwa.code = code AND kwa.keyword=:kw");
   if($codeSet!="")
   {
        $qb->andWhere("code.code_text_short in ".$codeSet);
   }
//        $qb->setParameter("cs","('16')");
    $qb->orderBy("code.code","ASC");
   $qb->setParameter("kw",$kw->getId());
    $qry=$qb->getQuery();
    return $qry->getResult();
}

function findKeywords($em,$searchString)
{
    $orderClause = "MATCHQUALITY('".$searchString."',keyword.content)";
    $qb = $em->createQueryBuilder()
        ->select("keyword,".$orderClause." as qual")
        ->where("keyword.content like :startsWith")
        ->from("library\doctrine\Entities\Keyword","keyword")
        ->orderBy("qual","DESC");

    $qb->setParameter("startsWith",$searchString[0]."%");
    $qry=$qb->getQuery();
    return $qry->getResult();
}


function findCodesForKwArr($em,$kwarr,$tok,$codeSet)
{
    // need to fix scoring order
    $kwinList=array();
    $numTok=count($kwarr);
    for($tokIdx=0;$tokIdx<$numTok;$tokIdx++)
    {
        $kwinList[$tokIdx]="";
        $keywords=$kwarr[$tokIdx];
        $maxMatch=$keywords[0]['qual'];
        $minMatch=$keywords[count($keywords)-1]['qual'];
        $tol=$minMatch + ($maxMatch-$minMatch)*0.05 ;
        for($kwIdx=0;$kwIdx<count($keywords) && ($keywords[$kwIdx]['qual']>=$tol) ;$kwIdx++)
        {
            $kwID=$keywords[$kwIdx][0]->getID();
            $kwinList[$tokIdx]=$kwinList[$tokIdx].",".$kwID;
        }
        $kwinList[$tokIdx]="(". substr($kwinList[$tokIdx],1).")";
    }
    $quals = "";
    for($tokIdx=0;$tokIdx<$numTok;$tokIdx++)
    {
        $qualStr="MATCHQUALITY('".$tok[$tokIdx]."',kw".$tokIdx.".content) as qual".$tokIdx;
        $quals=$quals.",".$qualStr.",kw".$tokIdx.".content as content".$tokIdx;
    }
    $qb = $em->createQueryBuilder()
        ->select("cd".$quals);
     $qb->from("library\doctrine\Entities\Code", "cd");
     $orderByString="";
    for($tokIdx=0;$tokIdx<$numTok;$tokIdx++)
    {
        $qb->from("library\doctrine\Entities\KeywordCodeAssociation","kwc".$tokIdx);
        $qb->from("library\doctrine\Entities\Keyword","kw".$tokIdx);
        $qb->andWhere("kwc".$tokIdx.".keyword in ".$kwinList[$tokIdx]);
        $qb->andWhere("kwc".$tokIdx.".code = cd");
        $qb->andWhere("kwc".$tokIdx.".keyword = kw".$tokIdx);
        if($tokIdx<$numTok-1)
        {
            $orderByString.='qual'.$tokIdx.' DESC,'. "kw".$tokIdx.".content DESC,";
        }
        else
        {
            $orderByString.="kw".$tokIdx.".content";
        }

//        $qb->orderBy("qual".$tokIdx,"DESC");
// TODO: tweak sort ordering issues
     }

   if($codeSet!="")
   {
        $qb->andWhere("cd.code_text_short in ".$codeSet);
   }
     $qb->orderBy($orderByString,"DESC");
     $qb->addOrderBy("cd.code","ASC");
    $qry=$qb->getQuery();
    $res=$qry->getResult();
    return $res;
}

?>
