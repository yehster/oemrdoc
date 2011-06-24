<?php
function findCodesForKeyword($em,$kw)
{


   $qb = $em->createQueryBuilder()
        ->select("code")
        ->from("library\doctrine\Entities\Code","code")
        ->from("library\doctrine\Entities\KeywordCodeAssociation", "kwa")
        ->where("kwa.code = code AND kwa.keyword=:kw");
//    $qb->setParameter("kw",$arrKeywords[0]);

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
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
