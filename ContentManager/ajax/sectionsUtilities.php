<?php

function findSections($keywords)
{
    $retval=array();
    $toks=explode(" ",$keywords);
    $qb = $GLOBALS['em']->createQueryBuilder();
    $qb->select("sec")
       ->from("library\doctrine\Entities\SectionHeading","sec")
       ->join("sec.ci","ci");
    $numToks=count($toks);
    if($numToks>0)
    {
        $qb->where("sec.longDesc like :first");
        $qb->setParameter("first","%".$toks[0]."%");
    }
    for($idx=1;$idx<$numToks;$idx++)
    {
        $qb->andWhere("sec.longDesc like :token".$idx);
        $qb->setParameter("token".$idx,"%".$toks[$idx]."%");
    }
    $qb->orderBy("ci.seq","ASC");
    $qry=$qb->getQuery();
    $results=$qry->getResult();
    $parents=array();
    foreach($results as $res)
    {
        $parentUUID=$res->getCi()->getParent()->getuuid();
        if(empty($parents[$parentUUID]))
        {
            $parents[$parentUUID]=array();
        }
        $parents[$parentUUID][]=$res;
    }
    foreach($parents as $par)
    {
        foreach($par as $entry)
        {
            $retval[]=$entry;
        }
    }
    return $retval;
    
}

?>
