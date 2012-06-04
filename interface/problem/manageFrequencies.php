<?php
include('/var/www/openemr/library/doctrine/init-em.php');

function increment_frequencies($em,$code,$codeType,$keywords)
{
        $dct_icd9=$em->getRepository('library\doctrine\Entities\ICD9\ICD9Code')->findOneBy(array("code"=>$code));
        if($dct_icd9!=null)
        {
            $dct_icd9->incrementFreq();
        }
        $arKeywords=explode(",",$keywords);
        $repKeywords=$em->getRepository('library\doctrine\Entities\ICD9\ICD9Keyword');
        foreach($arKeywords as $kw)
        {
            error_log($kw);
            if($dct_kw!=null)
            {
                $dct_kw=$repKeywords->findOneBy(array("text"=>$kw));
                $dct_kw->incrementFreq();
            }
        }
        $em->flush();

}

if(isset($_REQUEST["code"]))
{
    $code = $_REQUEST["code"];
}

if(isset($_REQUEST["codeType"]))
{
    $codeType = $_REQUEST["codeType"];
    if($codeType=="2")
    {
        $codeTypeString="ICD9";
    }
}
if(isset($_REQUEST['keywords']))
{
    $keywords=$_REQUEST['keywords'];
    increment_frequencies($em,$code,$codeType,$keywords);
    
}

?>
