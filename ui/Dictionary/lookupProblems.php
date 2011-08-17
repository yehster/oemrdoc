<?php
include_once('/var/www/openemr/library/doctrine/init-em.php');
include_once('DictionaryUtilities.php');
require_once('problemsLayout.php');

function addKeyword($DOM,$tbody,$keyword,$qual=0)
{
    $row=$DOM->createElement("tr");
    $tbody->appendChild($row);
    
    $tdKeyword=$DOM->createElement("TD",$keyword->getContent());
    $tdQual=$DOM->createElement("TD",$qual);
    
    $row->appendChild($tdKeyword);
    $row->appendChild($tdQual);
}
function addKeywordContent($DOM,$tbody,$keyword)
{
    $row=$DOM->createElement("tr");
    $tbody->appendChild($row);
    
    $tdKeyword=$DOM->createElement("TD",$keyword);
    
    $row->appendChild($tdKeyword);

}


if(isset($_REQUEST["searchString"]))
{
    $searchString= $_REQUEST["searchString"];    
}
if(isset($_REQUEST["codeSet"]))
{
    $codeSet=$_REQUEST["codeSet"];
}
else
{
    $codeSet="";
}

    $DOM= new DOMDocument("1.0","utf-8");
    
    $table=$DOM->createElement("TABLE");
    $tbody=$DOM->createElement("TBODY");
    $table->appendChild($tbody);
    
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
            $qual = $keywords[$idx]['qual'];
            $codes = findCodesForKeyword($em,$curKW,$codeSet);
            if(count($codes)>0)
            {
                addKeyword($DOM,$tbody,$curKW,$qual);

            }
            for($cidx=0;$cidx<count($codes);$cidx++)
            {
                addCodeResult($DOM,$tbody,$codes[$cidx]);
            }
        }
        echo $DOM->saveXML($table);
    }
    else
    {
        if(count($toks)>1)
        {
            $kwarr=array();
            $lastKW = array();
            for($tokIdx=0;$tokIdx<count($toks);$tokIdx++)
            {
                $kwarr[$tokIdx] = findKeywords($em,$toks[$tokIdx]);
                $lastKW[$tokIdx]=null;
            }
            $codes=findCodesForKwArr($em,$kwarr,$toks,$codeSet);
            for($cidx=0;$cidx<count($codes);$cidx++)
            {
                for($tokIdx=0;$tokIdx<count($toks);$tokIdx++)
                {
                    if($lastKW[$tokIdx]!==$codes[$cidx]['content'.$tokIdx])
                    {
                        $lastKW[$tokIdx]=$codes[$cidx]['content'.$tokIdx];
                        addKeywordContent($DOM,$tbody,$lastKW[$tokIdx]);
                    }
                }
                $curCode=$codes[$cidx][0];
                addCodeResult($DOM,$tbody,$curCode);
            }

        }
        echo $DOM->saveXML($table);
    }
?>
