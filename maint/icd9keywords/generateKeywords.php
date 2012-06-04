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
$ignore_kw=array("and","of","due","to","in","a","is","an","by","or","the","be","as","on","at","are"
        ,"from"
        ,"because","become"
        ,"2000","2001","2002","2003","2004","2005","2006","2007","2008","2009",
        "");



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

function select_icd9_keywords($em,$list)
{
    $inClause="'".implode("','",$list)."'";

    $qb = $em->createQueryBuilder()
        ->select("kw")
        ->from("library\doctrine\Entities\ICD9\ICD9Keyword","kw")
        ->where("kw.text in (".$inClause.")");
    $query=$qb->getQuery();
    return $query->getResult();    

}

function map_code_keywords($em,$code,$keywords,$priority,&$cache)
{
    $toLookup = array();
    foreach($keywords as $keyword)
    {
           if(!isset($cache[$keyword]))
           {
               $toLookup[]=$keyword;
           }
    }
    if(count($toLookup)>0)
    {
       $lookup=select_icd9_keywords($em,$toLookup);
       foreach($lookup as $found_kw)
       {
           $cache[$found_kw->getText()]=$found_kw;
       }
    }

    foreach($keywords as $keyword)
    {
        if(isset($cache[$keyword]))
        {
            $dct_kw=$cache[$keyword];
        }
        else {
            $dct_kw=new library\doctrine\Entities\ICD9\ICD9Keyword($keyword);
            $em->persist($dct_kw);
            $cache[$keyword]=$dct_kw;
            $em->flush();
        }
        $dct_kwm=select_icd9_kw_mapping($em,$dct_kw,$code);
        if(empty($dct_kwm))
        {
            $dct_kwm=new library\doctrine\Entities\ICD9\ICD9KeywordMapping($dct_kw,$code,$priority);            
            $em->persist($dct_kwm);
            $em->flush();
        }
        
    }
    
    
}
function create_keywords_for_code($code)
{
    
    global $kwCache;
    $tokens=my_split($code->getShort_desc());
    $keywords=array();
    foreach($tokens as $token)
    {
        $token=library\doctrine\Entities\ICD9\ICD9Keyword::normalize_text($token);
        if(!in_array($token,$GLOBALS['ignore_kw']))
        {
            $keywords[]=$token;
        }
    }
    $definitions=array();
    foreach ($code->getDefinitions() as $definition)
    {
        $definitions=array_merge($definitions,my_split($definition->getDefinition()));
    }
    $def_kw=array();
    foreach($definitions as $token)
    {
        $token=library\doctrine\Entities\ICD9\ICD9Keyword::normalize_text($token);
        if(!in_array($token,$GLOBALS['ignore_kw']))
        {
            $def_kw[]=$token;
        }
    }

    map_code_keywords($GLOBALS['em'],$code,$keywords,1,$kwCache);
    map_code_keywords($GLOBALS['em'],$code,$def_kw,2,$kwCache);
    
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
$kwCache = array();
$codes = select_icd9($em,$start,$size);
create_keywords_for_codes($codes)

?>
