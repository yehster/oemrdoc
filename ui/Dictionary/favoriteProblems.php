<?php

include_once('/var/www/openemr/library/doctrine/init-em.php');
include_once('DictionaryUtilities.php');
require_once('problemsLayout.php');
require_once('favoriteProblemsUtilities.php');

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
    
    $codes = findFavoriteProblems($searchString);
    
    foreach($codes as $code)
    {
        addCodeResult($DOM,$tbody,$code);
    }
    
    echo $DOM->saveXML($table);
?>
