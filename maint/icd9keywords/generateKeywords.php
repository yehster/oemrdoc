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

function select_icd9_kw_mapping($em,$kw,$code)
{
    $qb = $em->createQueryBuilder()
        ->select("kwm")
        ->from("library\doctrine\Entities\ICD9\ICD9KeywordMapping","kwm")
        ->where("kwm.code=:code and kwm.keyword=:kw");
    
    $qb->setParameter("code",$code);
    $qb->setParameter("kw",$kw);

    $query=$qb->getQuery();
    
    return $query->getResult();    
}

function my_split($string)
{
    return preg_split("/[-\s.,;:()\[\]]/",$string,-1,PREG_SPLIT_NO_EMPTY);
}
$ignore_kw=array("and","of","due","to","in","a","is","an","by","or","the","be","");



function map_code_keyword($kw,$code,$priority)
{
    $kw=library\doctrine\Entities\ICD9\ICD9Keyword::normalize_text($kw);
    if(in_array($kw,$GLOBALS['ignore_kw']))
    {
        return;
    }
    $dct_kw=$GLOBALS['em']->getRepository('library\doctrine\Entities\ICD9\ICD9Keyword')->findOneBy(array("text"=>$kw));
    if(empty($dct_kw))
    {
        $dct_kw=new library\doctrine\Entities\ICD9\ICD9Keyword($kw);
        $GLOBALS['em']->persist($dct_kw);
        $GLOBALS['em']->flush();
    }
    $dct_kwm=select_icd9_kw_mapping($GLOBALS['em'],$dct_kw,$code);
    if(empty($dct_kwm))
    {
        $dct_kwm=new library\doctrine\Entities\ICD9\ICD9KeywordMapping($dct_kw,$code,$priority);            
        $GLOBALS['em']->persist($dct_kwm);
    }
    
    $GLOBALS['em']->flush();
}
function create_keywords_for_code($code)
{
    
    $tokens=my_split($code->getShort_desc());
    $definitions=array();
    foreach ($code->getDefinitions() as $definition)
    {
        $definitions=array_merge($definitions,my_split($definition->getDefinition()));
    }
    foreach($tokens as $token)
    {
        map_code_keyword($token,$code,1);
    }
    foreach($definitions as $token)
    {
        map_code_keyword($token,$code,2);
    }

    
}
function create_keywords_for_codes($codes)
{
    foreach ($codes as $code)
    {
        echo $code->getCode()."|||".$code->getShort_desc();
        create_keywords_for_code($code);
       echo PHP_EOL;
    }
    
}


if (!empty($argc) && strstr($argv[0], basename(__FILE__))) {
    $start =0;
    $size=10;
    if($argc>1)
    {
        $start = $argv[1];
        if($argc>2)
        {
            $size=$argv[2];
        }
    }

}

echo "start:".$start.PHP_EOL;
$codes = select_icd9($em,$start,$size);
create_keywords_for_codes($codes)

?>
