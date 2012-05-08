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


?>
