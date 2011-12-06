<?php
require_once("$doctrineroot/queries/AllergiesQueries.php");

function echoDoctrineAllergies()
{
    $DOM=new \DOMDocument("1.0","utf-8");
    $patID=$_SESSION['pid'];
    $pat=$GLOBALS['em']->getRepository('library\doctrine\Entities\Patient')->find($patID);    
    $allList = listAllergies($DOM,$GLOBALS['em'],$pat,"A8380263","SNOMED");
    $foodAll = listAllergies($DOM,$GLOBALS['em'],$pat,"A7873398","SNOMED");
    echo $DOM->saveXML($allList);    
    echo $DOM->saveXML($foodAll);
}

function listAllergies($DOM,$em,$pat,$code,$code_type)
{
    $all=findSubEntries($em,$pat,$code,$code_type);
    $list=$DOM->createElement("ul");
    for($idx=0;$idx<count($all);$idx++)
    {
            $cur=$all[$idx];
            $allLI=$DOM->createElement("li",$cur->getText());
            $list->appendChild($allLI);
    }
    return $list;    
}
?>
