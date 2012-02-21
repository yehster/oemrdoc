<?php
include_once('/var/www/openemr/library/doctrine/init-session.php');
include_once('DictionaryUtilities.php');
require_once('problemsLayout.php');
require_once('lookupProblemsUtil.php');



if(isset($_REQUEST["searchString"]))
{
    $searchString= trim($_REQUEST["searchString"]);    
}
if(isset($_REQUEST["codeSet"]))
{
    $codeSet=$_REQUEST["codeSet"]; 
}
else
{
    $codeSet="";
}
if(isset($_REQUEST["requestTime"]))
{
    $requestTime=intval($_REQUEST["requestTime"]);
    echo $requestTime."|";
   
}

    $DOM= new DOMDocument("1.0","utf-8");
    if(strlen($searchString)==0)
    {
        return;
    }
    $table=$DOM->createElement("TABLE");
    $tbody=$DOM->createElement("TBODY");
    $table->appendChild($tbody);
    
    $toks = explode(" ",trim($searchString));
    if(count($toks)==1)
    {
        // is the a 
        $searchReq=$toks[0];
        if( preg_match("/^[veVE0-9][0-9]{0,2}[.]?/",$searchReq))
        {
            findProblemsByCodeNum($em,$DOM,$tbody,$searchReq);
        }
        else
        {
            findProblemsForKeyword($em,$DOM,$tbody,$searchReq);
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
