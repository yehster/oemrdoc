<?php
if (!empty($argc) && strstr($argv[0], basename(__FILE__))) {
    $start =0;
    $end = 15000;
    if($argc>1)
    {
        $start = $argv[1];
        if($argc>2)
        {
         $end = $argv[2];
        }
    }

}
include('/var/www/openemr/library/doctrine/init-em.php');

function generateKeywords($em,$code)
{
        global $flushCount;
	echo $code->getCodeText().':';
	$search = array ("(",")",",");
	$replace = array (" "," "," ");
	$text = str_replace($search, $replace,$code->getCodeText());
	$text = strtoupper($text);
	echo $text.PHP_EOL;
	$tok=strtok($text,' ');
        $count=0;

        $retKWM=array();
        $kwme = array(); // a list of the keyword ID's for this code
	while($tok!==false)
	{

		$tok=strtoupper($tok);
                $kw=$em->getRepository('library\doctrine\Entities\Keyword')->findOneBy(array('content' => $tok));
                if($kw==null)
		{
                    $kw= new library\doctrine\Entities\Keyword($tok);
                    $em->persist($kw);
		    $em->flush();
                    $flushCount=0;
		}
                $retKWM[$tok]=$kw;
                $kwme[$kw->getId()]=$kw;
           	$tok=strtok(' ');
        }
        // at this point $kwme contains the list of keywords that need to be mapped to this code
        $queryStr="select kwa from library\doctrine\Entities\KeywordCodeAssociation kwa where kwa.code=".$code->getId();

	$q = $em->createQuery($queryStr);
	$kwas = $q->getResult(); 
//		$kwas = $em->getRepository('library\doctrine\Entities\KeywordCodeAssociation')->findOneBy(array('keyword' => $kw,'code' =>$code));
        foreach($kwas as $value)
        {
            $keyword_id=$value->getKeyword()->getId();
//            echo $keyword_id.PHP_EOL;
            // null out keywords entries which already exist
            $kwme[$keyword_id]=null;
        }
        foreach($kwme as $value)
        {
            if($value!=null)
            {
            	$kwa = new library\doctrine\Entities\KeywordCodeAssociation($value,$code);
		$em->persist($kwa);
                echo "Creating Entry:".$value->getContent().":".$code->getId().PHP_EOL;
                $flushCount +=1;
            }
        }
        //$em->flush();
        return $retKWM;
}       echo PHP_EOL;

echo date('h:i:s');

//phpinfo();
//echo $cache;

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
        if($flushCount>100)
        {
            $flushCount=0;
            $em->flush();
        }
}
$em->flush();
echo PHP_EOL;
echo 'Success!';
?>



