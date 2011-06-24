<?php
include_once('/var/www/openemr/library/doctrine/init-em.php');
include_once('DictionaryUtilities.php');

if(isset($_REQUEST["searchStr"]))
{
    $searchString= $_REQUEST["searchStr"];
    
    
}

    $toks = tokenize($searchString);
    if(count($toks)==1)
    {
        $keywords = findKeywords($em,$toks[0]);
        $maxMatch=$keywords[0]['qual'];
        $minMatch=$keywords[count($keywords)-1]['qual'];
        $tol=($maxMatch-$minMatch) / 2;
        if(strlen($toks[0])==1)
        {
            $maxRes=20;
        }
        else {
            $maxRes=9999;
        }
        for($idx=0;$idx<count($keywords) and ((($keywords[$idx]['qual']) - $maxMatch + $tol) >= 0) and $idx<$maxRes;$idx++)
        {
            $curKW = $keywords[$idx][0];
            addKeyword($DOM,$table,$curKW,"");
            $codes = findCodesForKeyword($em,$curKW);
            for($cidx=0;$cidx<count($codes);$cidx++)
            {
                addCodeResult($DOM,$table,$codes[$cidx],$className);
            }
        }
        echo $DOM->saveXML();
    }
    else
    {
        if(count($toks)>1)
        {
            $kwarr=array();
            for($tokIdx=0;$tokIdx<count($toks);$tokIdx++)
            {
                $kwarr[$tokIdx] = findKeywords($em,$toks[$tokIdx]);
            }
            $codes=findCodesForKwArr($em,$kwarr,$toks);
            $lastKW = array();
            for($cidx=0;$cidx<count($codes);$cidx++)
            {
                for($tokIdx=0;$tokIdx<count($toks);$tokIdx++)
                {
                    if($lastKW[$tokIdx]!==$codes[$cidx]['content'.$tokIdx])
                    {
                        $lastKW[$tokIdx]=$codes[$cidx]['content'.$tokIdx];
                        addKeywordContent($DOM,$table,$lastKW[$tokIdx],"");
                    }
                }
                $curCode=$codes[$cidx][0];
                addCodeResult($DOM,$table,$curCode,$className);
            }

        }
        echo $DOM->saveXML();
    }
?>
