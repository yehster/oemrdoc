<?php
function lookupKeywords($em,$searchString)
{
    $orderClause = "MATCHQUALITY('".$searchString."',keyword.text)";
    $qb = $em->createQueryBuilder()
        ->select("keyword,".$orderClause." as qual")
        ->where("keyword.text like :startsWith")
        ->from("library\doctrine\Entities\ICD9\ICD9Keyword","keyword")           
        ->orderBy("qual","DESC");
    for($charIdx=1;$charIdx<strlen($searchString) && $charIdx<3; $charIdx++)
    {
        $qb->andWhere("keyword.text like :char".$charIdx);
        $qb->setParameter("char".$charIdx,"%".$searchString[$charIdx]."%");
    }

    $qb->setParameter("startsWith",$searchString[0]."%");

    $qry=$qb->getQuery();
    $qry->setFirstResult(0);
    $qry->setMaxResults(200);

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
//        $tol=$minMatch + ($maxMatch-$minMatch)*0.01;        
        for($kwIdx=0;$kwIdx<count($keywords);$kwIdx++)
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
        $qb->addOrderBy('kwm'.$tokIdx.".priority","ASC");
        $qb->addOrderBy('kw'.$tokIdx.".text","ASC");
        
    }

    $qb->select("cd".$quals);
    $qb->from("library\doctrine\Entities\ICD9\ICD9Code", "cd");
    
    $qb->addOrderBy("cd.code","ASC");

    $qry=$qb->getQuery();
  
    $qry->setFirstResult(0);
    $qry->setMaxResults(100);


    error_log($qb->getDql());
    error_log($qry->getSql());
    $res=$qry->getResult();
    error_log("query succeeded");
    return $res;
    
    
}

function generate_table($codes)
{
    $DOM=new DOMDocument("1.0","utf-8");
    $table = $DOM->createElement("table");
    $tbody= $DOM->createElement("tbody");
    $table->appendChild($tbody);
    
    foreach($codes as $result)
    {
        $code=$result[0];
        $tr=$DOM->createElement("tr");
//        error_log($code->getShort_desc());
        $tdCodeDesc=$DOM->createElement("td",htmlentities($code->getShort_desc()));
        $tdCodeID=$DOM->createElement("td",$code->getCode());

        $tr->appendChild($tdCodeDesc);
        $tr->appendChild($tdCodeID);
        $tbody->appendChild($tr);
    }
    return $DOM->saveXML($table);
}

?>
