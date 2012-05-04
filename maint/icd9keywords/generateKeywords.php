<?php
include('/var/www/openemr/library/doctrine/init-em.php');


function select_icd9($em,$start,$size)
{
    $qb = $em->createQueryBuilder()
        ->select("icd9")
        ->from("library\doctrine\Entities\ICD9\ICD9Code","icd9");
    $qb->orderBy("icd9.code","ASC");

    $query=$qb->getQuery();
    $query->setFirstResult($start);
    $query->setMaxResults($size);
    
    return $query->getResult();
}

function create_keywords_for_code($code)
{
    echo $code->getShort_desc() . PHP_EOL;
}

function create_keywords_for_codes($codes)
{
    foreach ($codes as $code)
    {
        create_keywords_for_code($code);
    }
}

$codes = select_icd9($em,0,100);
create_keywords_for_codes($codes)

?>
