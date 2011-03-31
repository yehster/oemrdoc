<?php
include_once('/var/www/openemr/library/doctrine/init-em.php');

function addRowRelationship($DOM,$TABLE,$rel,$dir)
{
    $TR=$DOM->createElement("TR");
    $TABLE->appendChild($TR);
    $TR->setAttribute("AUI",$rel->getAUI2());
    $TR->setAttribute("class","relationship");

    if($dir==true)
    {
        $con=$rel->getConcept2();
    }
    else
    {
        $con=$rel->getConcept1();
    }
    if($con!=null)
    {
        $TDCon=$DOM->createElement("TD",$con->getSTR());
        $TDCon->setAttribute("aui",$con->getAUI());
        $TR->appendChild($TDCon);
    }
     else {
    }

    $TDRELA=$DOM->createElement("TD",$rel->getRELA());
    $TR->appendChild($TDRELA);



}

if(isset($_REQUEST['aui']))
{
    $aui = $_REQUEST['aui'];
    $sourceCode=$aui;
    $sourceType="SNOMED";
    $concept=$em->getRepository('library\doctrine\Entities\SNOMED\Concept')->find($aui);
}

echo $concept->getSTR();
$relationships=$concept->getRelationships1();
$DOM= new DOMDocument("1.0","utf-8");


$TABLE = $DOM->createElement("TABLE");
$DOM->appendChild($TABLE);
foreach($relationships as $rel)
{
    addRowRelationship($DOM,$TABLE,$rel,true);
}


echo $DOM->saveXML($TABLE);
?>
