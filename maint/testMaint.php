<?php
include('/var/www/openemr/library/doctrine/init-session.php');

function generateKeywords($em,$code)
{
	global $kwm;
	echo $code->getCodeText().'<br>';
	$search = array ("(",")",",");
	$replace = array (" "," "," ");
	$text = str_replace($search, $replace,$code->getCodeText());
	$text = strtoupper($text);
	echo $text;
	$tok=strtok($text,' ');
	while($tok!==false)
	{
		$tok=strtoupper($tok);
		$kw=$kwm[$tok];
		if($kw==null)
		{
			$kw=$em->getRepository('library\doctrine\Entities\Keyword')->findOneBy(array('content' => $tok));
			if($kw==null)
			{
				echo 'generating:';
				echo $tok.'<br>';
				$kw= new library\doctrine\Entities\Keyword($tok);
				$em->persist($kw);
				$em->flush();
			}
			else
			{
//				echo $kw->getId();
				echo 'Queried:';
			}
			$kwm[$tok]=$kw;
			echo 'adding'.$tok.count($kwm).';';
		}
		// need to create associations between keywords and code
		$q = $em->createQuery("select kwa from library\doctrine\Entities\KeywordCodeAssociation kwa where kwa.keyword=".$kw->getId()." AND kwa.code=".$code->getId());
		$kwas = $q->getResult(); 
//		$kwas = $em->getRepository('library\doctrine\Entities\KeywordCodeAssociation')->findOneBy(array('keyword' => $kw,'code' =>$code));
		if(count($kwas)==0)
		{
			$kwa = new library\doctrine\Entities\KeywordCodeAssociation($kw,$code);
			$em->persist($kwa);
                }
		else
		{
			echo $kwas[0]->getId();
		}
		$tok=strtok(' ');
	}
        $em->flush();

}
echo date('h:i:s');
echo '<br>';
echo $config->getProxyDir();
echo '<br>';
echo $connectionParams[dbname];
echo '<br>';

//phpinfo();
//echo $cache;

echo get_include_path();
	$kwm = array();

$code = $em->getRepository('library\doctrine\Entities\Code')->find(1);
for($idx=1;$idx<13000;$idx+= 1)
{
	if($code!==null)
	{
		generateKeywords($em,$code);
	}
	echo $idx. ':';
$code = $em->getRepository('library\doctrine\Entities\Code')->find($idx);
}
$em->flush();
echo '<br>';
echo 'Success!';
?>



