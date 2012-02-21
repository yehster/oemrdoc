<?php
if (!empty($argc) && strstr($argv[0], basename(__FILE__))) {
    $start =0;
    $end = 15000;
    if($argc>1)
    {
        $start = $argv[1];
        if($argc>2)
        {
            $end=intval($start)+intval($argv[2]);
        }
    }

}

echo $start.":".$end.PHP_EOL;

include('/var/www/openemr/library/doctrine/init-session.php');

function generateKeywords($em,$code)
{
        global $flushCount;
	echo $code->getCodeText().':'.PHP_EOL;
	$search = array ("(",")",",");
	$replace = array (" "," "," ");
	$text = str_replace($search, $replace,$code->getCodeText());
	$text = strtoupper($text);
	$tok=strtok($text,' ');
        $count=0;

        $retKWM=array();
        $kwme = array(); // a list of the keyword ID's for this code
        $kws=array();
	while($tok!==false)
	{
		$tok=strtoupper($tok);
                $kws[$tok]=$tok;
           	$tok=strtok(' ');
        }

        
        $qb = $em->createQueryBuilder();
            $qb->select("kw")
            ->from("library\doctrine\Entities\Keyword","kw")
            ->where($qb->expr()->in("kw.content",$kws));
        $qry=$qb->getQuery();
    
        $res=$qry->getResult();
        foreach($res as $kwc)
        {
                $kws[$kwc->getContent()]=$kwc;
        }
        foreach($kws as $kwc)
        {
            if(gettype($kwc)==="string")
            {
                $kw= new library\doctrine\Entities\Keyword($kwc);
                $em->persist($kw);
                $kws[$kwc]=$kw;
            }
        }
        $em->flush();
        foreach($kws as $kwo)
        {
            $kwme[$kwo->getId()]=$kwo;
        }
        // at this point $kwme contains the list of keywords that need to be mapped to this code
        $queryStr="select kwa from library\doctrine\Entities\KeywordCodeAssociation kwa where kwa.code=".$code->getId();

	$q = $em->createQuery($queryStr);
	$kwas = $q->getResult(); 
        foreach($kwas as $value)
        {
            $keyword_id=$value->getKeyword()->getId();
            $kwme[$keyword_id]=null;
        }
        foreach($kwme as $value)
        {
            if($value!=null)
            {
            	$kwa = new library\doctrine\Entities\KeywordCodeAssociation($value,$code);
		$em->persist($kwa);
                echo "Creating Entry:".$value->getContent().":".$code->getId().":".$value->getId().PHP_EOL;
            }
        }
        $em->flush();
        
        return $retKWM;
}       echo PHP_EOL;


echo date('h:i:s');

echo get_include_path();
$flushCount=0;
$code = $em->getRepository('library\doctrine\Entities\Code')->find(1);

for($idx=$start;$idx<$end;$idx+= 1)
{
	if($code!==null)
	{
		$kwm=generateKeywords($em,$code);
	}
	echo 'CodeIndex:'.$idx .PHP_EOL;
        $code = $em->getRepository('library\doctrine\Entities\Code')->find($idx);
}
$em->flush();
echo PHP_EOL;
echo 'Success!';
?>



