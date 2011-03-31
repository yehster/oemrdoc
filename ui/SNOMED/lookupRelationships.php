<?php
include_once('/var/www/openemr/library/doctrine/init-em.php');

function addRowRelationship($DOM,$TABLE,$rel)
{
    $TR=$DOM->createElement("TR");
    $TABLE->appendChild($TR);

    $TDCon=$DOM->createElement("TD",$rel->getConcept2()->getSTR());
    $TR->appendChild($TDCon);


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
    addRowRelationship($DOM,$TABLE,$rel);
}
echo $DOM->saveXML($TABLE);
?>
