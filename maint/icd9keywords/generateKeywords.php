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

function my_split($string)
{
    return preg_split("/[-\s.,()\[\]]/",$string,-1,PREG_SPLIT_NO_EMPTY);
}
$ignore_kw=array("and","of","due","to","in","a","is");


function map_code_keyword($kw,$code)
{
    $kw=library\doctrine\Entities\ICD9\ICD9Keyword::normalize_text($kw);
    $dct_kw=$GLOBALS['em']->getRepository('library\doctrine\Entities\ICD9\ICD9Keyword')->find($kw);
    if($dct_kw==null)
    {
        $dct_kw=new library\doctrine\Entities\ICD9\ICD9Keyword($kw);
        $GLOBALS['em']->persist($dct_kw);
    }
    $GLOBALS['em']->flush();
    $dct_kwm=$GLOBALS['em']->getRepository('library\doctrine\Entities\ICD9\ICD9KeywordMapping')->findBy(array("keyword"=>$kw,"code"=>$code->getCode()));
    if(empty($dct_kwm))
    {
        $dct_kwm=new library\doctrine\Entities\ICD9\ICD9KeywordMapping($kw,$code);
        $GLOBALS['em']->persist($dct_kwm);
    }
    
    $GLOBALS['em']->flush();
}
function create_keywords_for_code($code)
{
    
    echo $code->getCode().PHP_EOL;
    $tokens=my_split($code->getShort_desc());
    $definitions=array();
    foreach ($code->getDefinitions() as $definition)
    {
        $definitions=array_merge($definitions,my_split($definition->getDefinition()));
    }
    foreach($tokens as $token)
    {
        map_code_keyword($token,$code);
    }
    echo $code->getCode().PHP_EOL;
}
function create_keywords_for_codes($codes)
{
    foreach ($codes as $code)
    {
        create_keywords_for_code($code);
    }
}

$codes = select_icd9($em,10000,10);
create_keywords_for_codes($codes)

?>
